<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionController extends Controller
{
    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $minPickup = Carbon::now()->addDays(2)->format('Y-m-d');

        return view('kasir.transactions.create', compact('customers', 'products', 'minPickup'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'pickup_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $pickupDate = Carbon::parse($request->pickup_date)->startOfDay();
        $minPickupDate = Carbon::now()->addDays(2)->startOfDay();
        if ($pickupDate->lt($minPickupDate)) {
            return redirect()->back()->withErrors([
                'pickup_date' => 'Tanggal pengambilan minimal H-2 dari hari ini.',
            ])->withInput();
        }

        $quantities = collect($validated['items'])
            ->groupBy('product_id')
            ->map(fn ($items) => $items->sum(fn ($item) => (int) $item['quantity']));

        $order = DB::transaction(function () use ($validated, $pickupDate, $quantities) {
            $products = Product::whereIn('id', $quantities->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);

                if (!$product) {
                    return redirect()->back()->withErrors([
                        'items' => 'Produk yang dipilih tidak ditemukan.',
                    ])->withInput();
                }

                if ((int) $product->stock < (int) $quantity) {
                    return redirect()->back()->withErrors([
                        'items' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ])->withInput();
                }
            }

            $preparedItems = [];
            $total = 0;

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);
                $price = (int) $product->price;
                $subtotal = $price * (int) $quantity;
                $total += $subtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => (int) $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $orderData = [
                'user_id' => $validated['user_id'],
                'total_amount' => $total,
                'status' => 'completed',
                'payment_method' => 'cash',
                'pickup_date' => $pickupDate->toDateString(),
                'payment_proof_path' => null,
            ];

            if (Schema::hasColumn('orders', 'order_number')) {
                $orderData['order_number'] = $this->generateOrderNumber();
            }

            $order = Order::create($orderData);

            if (Schema::hasColumn('orders', 'payment_status')) {
                $order->forceFill(['payment_status' => 'success'])->save();
            }

            foreach ($preparedItems as $item) {
                $itemData = [
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ];

                if (!Schema::hasColumn('order_items', 'product_name')) {
                    unset($itemData['product_name']);
                }

                if (!Schema::hasColumn('order_items', 'subtotal')) {
                    unset($itemData['subtotal']);
                }

                OrderItem::create($itemData);

                Product::whereKey($item['product']->id)->update([
                    'stock' => DB::raw('stock - ' . (int) $item['quantity']),
                    'purchase_count' => DB::raw('purchase_count + ' . (int) $item['quantity']),
                ]);
            }

            return $order;
        });

        if (!$order instanceof Order) {
            return $order;
        }

        return redirect()->route('kasir.orders.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function receipt(Order $order)
    {
        $order->load(['items.product', 'user']);

        $data = ['order' => $order];

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kasir.receipt', $data);
            return $pdf->stream('struk-' . ($order->order_number ?? $order->id) . '.pdf');
        }

        return view('kasir.receipt', $data);
    }

    private function generateOrderNumber(): string
    {
        $datePart = now()->format('Ymd');

        do {
            $randomPart = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = "ORD-{$datePart}-{$randomPart}";
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
