<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|in:bca,dana,shopeepay,gopay',
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

        $items = $request->input('items', []);
        $total = 0;
        $preparedItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                continue;
            }
            $quantity = (int) $item['quantity'];
            $price = (int) $product->price;
            $subtotal = $price * $quantity;
            $total += $subtotal;

            $preparedItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        $orderData = [
            'user_id' => $request->user_id,
            'total_amount' => $total,
            'status' => 'menunggu_konfirmasi',
            'payment_method' => $request->payment_method,
            'pickup_date' => $pickupDate->toDateString(),
            'payment_proof_path' => null,
        ];

        if (Schema::hasColumn('orders', 'order_number')) {
            $orderData['order_number'] = $this->generateOrderNumber();
        }

        $order = Order::create($orderData);

        foreach ($preparedItems as $item) {
            $itemData = [
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ];
            if (Schema::hasColumn('order_items', 'product_name')) {
                $itemData['product_name'] = $item['product']->name;
            }
            if (Schema::hasColumn('order_items', 'subtotal')) {
                $itemData['subtotal'] = $item['subtotal'];
            }
            OrderItem::create($itemData);
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
