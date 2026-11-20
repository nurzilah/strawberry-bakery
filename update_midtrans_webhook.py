import sys
import re

with open('app/Http/Controllers/MidtransNotificationController.php', 'r', encoding='utf-8') as f:
    content = f.read()

target = r'''        // Logika pengubahan status di database kamu
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = 'waiting_verification'; // Otomatis masuk ke status Menunggu Konfirmasi
            $order->paid_at = now();
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        \App\Models\Product::whereKey($item->product_id)->update([
                            'stock' => \Illuminate\Support\Facades\DB::raw('stock + ' . (int) $item->quantity),
                            'purchase_count' => \Illuminate\Support\Facades\DB::raw('GREATEST(0, purchase_count - ' . (int) $item->quantity . ')')
                        ]);
                    }
                }
            }
            
            $order->payment_status = $transactionStatus === 'expire' ? 'expired' : ($transactionStatus === 'cancel' ? 'cancelled' : 'failed');
            $order->status = 'cancelled';
        }'''

replacement = r'''        // Logika pengubahan status di database kamu
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($order->payment_status !== 'paid') {
                // Kurangi stok reguler
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        $product = \App\Models\Product::find($item->product_id);
                        if ($product) {
                            $product->decrement('stock', (int) $item->quantity);
                            $product->increment('purchase_count', (int) $item->quantity);
                            
                            if ($product->stock < 5) {
                                \App\Models\Notification::create([
                                    'type' => 'system',
                                    'title' => 'Stok Hampir Habis',
                                    'message' => "Stok produk {$product->name} tersisa {$product->stock}.",
                                    'data' => ['product_id' => $product->id]
                                ]);
                            }
                        }
                    }
                }
                
                // Kurangi stok paket
                app(\App\Services\PackageOrderService::class)->decrementStock($order);
            }
            
            $order->payment_status = 'paid';
            $order->status = 'waiting_verification'; // Otomatis masuk ke status Menunggu Konfirmasi
            $order->paid_at = now();
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // Note: Stok tidak perlu di-restore karena stok tidak dikurangi saat checkout dibuat
            
            $order->payment_status = $transactionStatus === 'expire' ? 'expired' : ($transactionStatus === 'cancel' ? 'cancelled' : 'failed');
            $order->status = 'cancelled';
        }'''

content = content.replace(target, replacement)

with open('app/Http/Controllers/MidtransNotificationController.php', 'w', encoding='utf-8') as f:
    f.write(content)
