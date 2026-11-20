<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PackageOrderService
{
    /**
     * @param Order $order
     * @param array $boxes Format: [['quantity' => 2, 'items' => [['product_id' => 1, 'quantity' => 2], ...]], ...]
     * @param float|null $distanceKm
     * @param string|null $voucherCode
     */
    public function createPackagesForOrder(Order $order, array $boxes, ?float $distanceKm = null, ?string $voucherCode = null): void
    {
        DB::transaction(function () use ($order, $boxes, $distanceKm, $voucherCode) {
            // Each card describes one box design. Expand its requested quantity
            // into physical boxes so packages, stock usage, and free-box promos
            // continue to operate on one record per box.
            $physicalBoxes = [];
            foreach ($boxes as $boxData) {
                $quantity = (int) ($boxData['quantity'] ?? 1);
                if ($quantity < 1) {
                    throw new \InvalidArgumentException('Jumlah box minimal harus 1.');
                }

                $beverageType = $boxData['beverage_type'] ?? null;
                $beveragePrice = match ($beverageType) {
                    'gelas' => 500,
                    'botol' => 2000,
                    null, '' => 0,
                    default => throw new \InvalidArgumentException('Pilihan air mineral tidak valid.'),
                };

                for ($i = 0; $i < $quantity; $i++) {
                    $physicalBoxes[] = [
                        'items' => $boxData['items'] ?? [],
                        'beverage_type' => $beverageType ?: null,
                        'beverage_price' => $beveragePrice,
                    ];
                }
            }

            $boxes = $physicalBoxes;
            $totalBoxQty = count($boxes);
            if ($totalBoxQty === 0) return;

            $subtotalSemuaBox = 0;
            $createdPackages = [];

            // 1. Buat records untuk tiap box
            foreach ($boxes as $boxData) {
                $boxPrice = (int) ($boxData['beverage_price'] ?? 0);
                $items = $boxData['items'] ?? [];
                
                $package = $order->packages()->create([
                    'box_price' => 0, // Akan diupdate
                    'is_free' => false,
                    'beverage_type' => $boxData['beverage_type'] ?? null,
                    'beverage_price' => (int) ($boxData['beverage_price'] ?? 0),
                ]);

                foreach ($items as $itemData) {
                    $product = Product::find($itemData['product_id']);
                    if (!$product) continue;

                    $qty = (int) $itemData['quantity'];
                    $price = $product->price;

                    $package->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $price
                    ]);

                    $boxPrice += ($price * $qty);
                }

                $package->update(['box_price' => $boxPrice]);
                $subtotalSemuaBox += $boxPrice;
                
                // Tambahkan property sementara untuk sorting nanti
                $package->temp_price = $boxPrice;
                $createdPackages[] = $package;
            }

            // 2. Hitung Promo
            $promoService = app(PromoCalculatorService::class);
            $promoResult = $promoService->calculate('package', $totalBoxQty, $subtotalSemuaBox, $distanceKm);

            $freeQty = $promoResult['free_qty'];
            $freeOngkir = $promoResult['free_ongkir'];
            $discountAmount = $promoResult['discount_amount']; // Dari percentage
            $freeBoxValue = 0;

            // 3. Terapkan free_qty (gratiskan box termurah)
            if ($freeQty > 0 && count($createdPackages) > 0) {
                // Urutkan ascending berdasarkan harga
                usort($createdPackages, function($a, $b) {
                    return $a->temp_price <=> $b->temp_price;
                });

                // Ambil N box termurah
                for ($i = 0; $i < min($freeQty, count($createdPackages)); $i++) {
                    $createdPackages[$i]->update(['is_free' => true]);
                    $freeBoxValue += $createdPackages[$i]->temp_price;
                }
            }

            // 4. Hitung Ongkir berbasis Jarak (Distance)
            $shippingCost = 0;
            if ($order->delivery_method === 'delivery') {
                $shippingService = app(\App\Services\ShippingCalculatorService::class);
                $shippingCost = $distanceKm !== null
                    ? $shippingService->calculateCost((float) $distanceKm)
                    : (int) config('store.base_shipping_cost', 10000);
            }

            // 5. Hitung Voucher
            $voucherDiscount = 0;
            $appliedVoucherCode = null;

            if (!empty($voucherCode)) {
                $voucher = \App\Models\Voucher::where('code', $voucherCode)
                    ->where('is_active', true)
                    ->first();

                if (!$voucher) {
                    throw new \InvalidArgumentException("Kode voucher {$voucherCode} tidak valid atau sudah tidak aktif.");
                }

                $eligibility = $voucher->checkEligibility($order->user, $subtotalSemuaBox, $order->delivery_method, $distanceKm, $order->id);
                if (!$eligibility['eligible']) {
                    throw new \InvalidArgumentException($eligibility['message']);
                }

                $voucherDiscount = $voucher->calculateDiscount($subtotalSemuaBox, $shippingCost, $order->user, $order->delivery_method, $distanceKm);
                $appliedVoucherCode = $voucher->code;
            }

            // 6. Update Order
            $totalDiscount = $discountAmount + $freeBoxValue;
            $finalTotal = max(0, $subtotalSemuaBox - $totalDiscount + $shippingCost - $voucherDiscount);

            $order->update([
                'total_amount'       => $finalTotal,
                'discount_amount'    => $totalDiscount,
                'distance_km'        => $distanceKm,
                'voucher_code'       => $appliedVoucherCode,
                'voucher_discount'   => $voucherDiscount,
                'free_qty_applied'   => $freeQty,
                'shipping_cost'      => $shippingCost,
                'bonus_item_name'    => $promoResult['bonus_item_name'],
                'bonus_item_qty'     => $promoResult['bonus_item_qty'],
            ]);
        });
    }

    public function decrementStock(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // Load relation jika belum
            $order->loadMissing('packages.items.product');

            foreach ($order->packages as $package) {
                foreach ($package->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                        $item->product->increment('purchase_count', $item->quantity);
                        
                        // Notifikasi stok habis
                        if ($item->product->stock < 5) {
                            \App\Models\Notification::create([
                                'type' => 'system',
                                'title' => 'Stok Hampir Habis',
                                'message' => "Stok produk {$item->product->name} tersisa {$item->product->stock}.",
                                'data' => ['product_id' => $item->product->id]
                            ]);
                        }
                    }
                }
            }
        });
    }
}
