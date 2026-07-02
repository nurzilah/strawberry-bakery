<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));

        if ($notification->signature_key != $validSignatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $transactionId = $notification->transaction_id;

        // Parse order id to get the original order ID (e.g. TRX-12-1698372632)
        // Note: the checkout controller saves `midtrans_order_id` in orders table exactly as sent to Midtrans
        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            return response(['message' => 'Order not found'], 404);
        }

        $order->midtrans_transaction_id = $transactionId;
        $order->payment_type = $type;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->paid_at = now();
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->payment_status = $transactionStatus === 'expire' ? 'expired' : ($transactionStatus === 'cancel' ? 'cancelled' : 'failed');
            $order->status = 'cancelled';
        } else if ($transactionStatus == 'pending') {
            $order->payment_status = 'pending';
        }

        $order->save();

        return response(['message' => 'Success'], 200);
    }
}
