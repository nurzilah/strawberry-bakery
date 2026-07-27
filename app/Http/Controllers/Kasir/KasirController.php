<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        $categories = $products->pluck('category')->unique('id')->filter()->values();

        return view('kasir.pos', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'discount' => 'numeric|min:0',
            'tax_rate' => 'numeric|min:0|max:100',
            'extra_charge' => 'numeric|min:0',
            'extra_charge_label' => 'nullable|string|max:100',
            'payment_method' => 'required|string|in:Tunai,Transfer,QRIS,Kartu',
            'amount_paid' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {
                $itemsData = [];
                $subtotal = 0;

                foreach ($request->items as $item) {
                    $product = Product::where('id', $item['id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$product) {
                        throw new \Exception("Produk ID {$item['id']} tidak ditemukan.");
                    }

                    if (!$product->is_active) {
                        throw new \Exception("Produk {$product->name} sudah tidak aktif.");
                    }

                    if ($product->hasStockTracking() && !$product->hasSufficientStock($item['qty'])) {
                        if ($product->hasNoStock()) {
                            throw new \Exception("Stok {$product->name} habis.");
                        } else {
                            throw new \Exception("Stok {$product->name} tidak mencukupi. Sisa: {$product->stock}, dibutuhkan: {$item['qty']}");
                        }
                    }

                    $lineSubtotal = (int) $product->price * (int) $item['qty'];
                    $subtotal += $lineSubtotal;

                    if ($product->hasStockTracking()) {
                        $product->decrement('stock', $item['qty']);
                    }

                    $product->increment('purchase_count', $item['qty']);

                    StockHistory::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'quantity_added' => -$item['qty'],
                        'stock_before' => $product->stock + $item['qty'],
                        'stock_after' => $product->stock,
                        'notes' => 'POS: Penjualan via kasir',
                    ]);

                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'qty' => (int) $item['qty'],
                        'subtotal' => $lineSubtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $discount = min((float) ($request->discount ?? 0), $subtotal);
                $taxRate = (float) ($request->tax_rate ?? 0);
                $extraCharge = (float) ($request->extra_charge ?? 0);
                $amountPaid = (float) ($request->amount_paid);

                $afterDiscount = $subtotal - $discount;
                $taxAmount = round($afterDiscount * $taxRate / 100, 2);
                $beforeRounding = $afterDiscount + $taxAmount + $extraCharge;
                $grandTotal = round($beforeRounding, 0);
                $rounding = $grandTotal - $beforeRounding;

                if ($amountPaid < $grandTotal) {
                    throw new \Exception("Uang pembayaran kurang dari total tagihan.");
                }

                $amountChange = $amountPaid - $grandTotal;
                $invoiceNumber = $this->generateInvoiceNumber();

                $transaction = Transaction::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => Auth::id(),
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'discount' => $discount,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'extra_charge' => $extraCharge,
                    'extra_charge_label' => $request->extra_charge_label,
                    'rounding' => $rounding,
                    'grand_total' => $grandTotal,
                    'payment_method' => $request->payment_method,
                    'amount_paid' => $amountPaid,
                    'amount_change' => $amountChange,
                    'notes' => $request->notes,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                foreach ($itemsData as &$data) {
                    $data['transaction_id'] = $transaction->id;
                }

                $transaction->items()->createMany($itemsData);

                return $transaction;
            });

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'message' => 'Transaksi berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('POS Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['items', 'user'])->findOrFail($id);
        return view('kasir.receipt', compact('transaction'));
    }

    private function generateInvoiceNumber(): string
    {
        $date = now();
        $prefix = 'INV-' . $date->format('Ymd');

        $last = Transaction::where('invoice_number', 'like', $prefix . '-%')
            ->orderBy('invoice_number', 'desc')
            ->lockForUpdate()
            ->value('invoice_number');

        if ($last) {
            $lastSeq = (int) substr($last, -5);
            $newSeq = $lastSeq + 1;
        } else {
            $newSeq = 1;
        }

        return $prefix . '-' . str_pad($newSeq, 5, '0', STR_PAD_LEFT);
    }
}
