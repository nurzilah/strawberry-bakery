import sys
import re

with open('app/Http/Controllers/CheckoutController.php', 'r', encoding='utf-8') as f:
    content = f.read()

target = r'''                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'waiting_verification',
                        'paid_at' => now(),
                        'payment_type' => $status->payment_type ?? $order->payment_type,
                        'midtrans_transaction_id' => $status->transaction_id ?? $order->midtrans_transaction_id
                    ]);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {'''

replacement = r'''                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    if ($order->payment_status !== 'paid') {
                        // Kurangi stok reguler
                        foreach ($order->items as $item) {
                            if ($item->product_id) {
                                $product = \App\Models\Product::find($item->product_id);
                                if ($product) {
                                    $product->decrement('stock', (int) $item->quantity);
                                    $product->increment('purchase_count', (int) $item->quantity);
                                }
                            }
                        }
                        
                        // Kurangi stok paket
                        app(\App\Services\PackageOrderService::class)->decrementStock($order);
                    }

                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'waiting_verification',
                        'paid_at' => now(),
                        'payment_type' => $status->payment_type ?? $order->payment_type,
                        'midtrans_transaction_id' => $status->transaction_id ?? $order->midtrans_transaction_id
                    ]);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {'''

content = content.replace(target, replacement)

with open('app/Http/Controllers/CheckoutController.php', 'w', encoding='utf-8') as f:
    f.write(content)
