<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Midtrans SNAP Sandbox
|--------------------------------------------------------------------------
| Environment keys:
| MIDTRANS_SERVER_KEY=your Midtrans Sandbox server key from the dashboard
| MIDTRANS_CLIENT_KEY=your Midtrans Sandbox client key from the dashboard
|
| Simulator card:
| Card number 4811111111111114, CVV 123, expiry 01/30, OTP 112233
|--------------------------------------------------------------------------
*/
class MidtransCallbackController extends Controller
{
    public function __invoke(): JsonResponse
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $notif = new \Midtrans\Notification();

        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $signatureKey = hash(
            'sha512',
            $notif->order_id . $notif->status_code . $notif->gross_amount . config('services.midtrans.server_key')
        );

        abort_unless(hash_equals($signatureKey, (string) $notif->signature_key), 403);

        DB::transaction(function () use ($orderId, $transactionStatus) {
            $order = Order::query()
                ->where('order_number', $orderId)
                ->with('items')
                ->lockForUpdate()
                ->firstOrFail();

            if ($transactionStatus === 'settlement') {
                if ($order->status === 'processing') {
                    return;
                }

                foreach ($order->items as $item) {
                    $updated = Product::query()
                        ->whereKey($item->product_id)
                        ->where('stock', '>=', (int) $item->quantity)
                        ->update([
                            'stock' => DB::raw('stock - ' . (int) $item->quantity),
                            'purchase_count' => DB::raw('purchase_count + ' . (int) $item->quantity),
                        ]);

                    if ($updated !== 1) {
                        throw new \RuntimeException("Stok produk {$item->product_name} tidak mencukupi.");
                    }
                }

                $order->forceFill([
                    'status' => 'processing',
                ])->save();

                return;
            }

            if (in_array($transactionStatus, ['expire', 'cancel', 'deny'], true)) {
                $order->forceFill([
                    'status' => 'cancelled',
                ])->save();
            }
        });

        return response()->json([
            'message' => 'OK',
        ]);
    }
}
