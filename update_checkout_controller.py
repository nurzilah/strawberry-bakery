import sys
import re

with open('app/Http/Controllers/CheckoutController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Order creation and remove stock deduction
target_create = r'''        // Create Order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method ?? 'midtrans',
            'pickup_date' => $request->pickup_date ?? \Carbon\Carbon::now()->addDays(2)->format('Y-m-d')
        ]);

        // Create Order Items
        foreach ($carts as $cart) {
            $product = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id') 
                ? $cart->product 
                : $cart->detailCart->product;
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'subtotal' => $product->price * $qty
            ]);

            // Kurangi stok dan tambah purchase_count sejak pesanan dibuat
            $product->decrement('stock', (int) $qty);
            $product->increment('purchase_count', (int) $qty);

            $updatedProduct = \App\Models\Product::find($product->id);
            if ($updatedProduct && $updatedProduct->stock < 5) {
                \App\Models\Notification::create([
                    'type' => 'system',
                    'title' => 'Stok Hampir Habis',
                    'message' => "Stok produk {$updatedProduct->name} tersisa {$updatedProduct->stock}.",
                    'data' => ['product_id' => $updatedProduct->id]
                ]);
            }
        }'''

replacement_create = r'''        // Total qty for delivery validation
        $totalQty = 0;
        foreach ($carts as $cart) {
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;
            $totalQty += $qty;
        }

        $minDeliveryQty = config('store.delivery_min_qty', 12);
        $deliveryMethod = $request->delivery_method === 'delivery' ? 'delivery' : 'pickup';
        
        if ($deliveryMethod === 'delivery' && $totalQty < $minDeliveryQty) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Delivery hanya tersedia untuk pemesanan minimal ' . $minDeliveryQty . ' item.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Delivery hanya tersedia untuk pemesanan minimal ' . $minDeliveryQty . ' item.');
        }

        // Validate requested date
        $requestedDate = $request->requested_date ?? \Carbon\Carbon::today()->format('Y-m-d');
        if ($requestedDate < \Carbon\Carbon::today()->format('Y-m-d')) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tanggal pengambilan/pengantaran tidak boleh di masa lalu.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Tanggal pengambilan/pengantaran tidak boleh di masa lalu.');
        }

        // Hitung promo
        // TODO: Kita akan panggil PromoCalculatorService nanti di sini
        $discountAmount = 0;
        $freeQtyApplied = 0;
        $shippingCost = 0; // default
        
        $finalTotal = $total - $discountAmount + $shippingCost;

        // Create Order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'total_amount' => $finalTotal,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method ?? 'midtrans',
            'requested_date' => $requestedDate,
            'delivery_method' => $deliveryMethod,
            'delivery_address' => $deliveryMethod === 'delivery' ? $request->delivery_address : null,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discountAmount,
            'free_qty_applied' => $freeQtyApplied,
        ]);

        // Create Order Items
        foreach ($carts as $cart) {
            $product = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'product_id') 
                ? $cart->product 
                : $cart->detailCart->product;
            $qty = \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                ? $cart->quantity 
                : $cart->detailCart->quantity;

            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'subtotal' => $product->price * $qty
            ]);
            
            // Note: Stok TIDAK dikurangi di sini (dipindah ke MidtransNotificationController/status)
        }'''

# 2. Fix $total parameter for Midtrans
target_midtrans = r'''            'transaction_details' => array(
                'order_id' => 'TRX-' . $order->id . '-' . time(),
                'gross_amount' => $total,
            ),'''
replacement_midtrans = r'''            'transaction_details' => array(
                'order_id' => 'TRX-' . $order->id . '-' . time(),
                'gross_amount' => $finalTotal,
            ),'''

target_notification = r'''            'message' => "Pesanan #{$order->id} dari {$user->name} sebesar Rp " . number_format($total, 0, ',', '.'),'''
replacement_notification = r'''            'message' => "Pesanan #{$order->id} dari {$user->name} sebesar Rp " . number_format($finalTotal, 0, ',', '.'),'''


content = content.replace(target_create, replacement_create)
content = content.replace(target_midtrans, replacement_midtrans)
content = content.replace(target_notification, replacement_notification)

with open('app/Http/Controllers/CheckoutController.php', 'w', encoding='utf-8') as f:
    f.write(content)
