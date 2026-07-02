<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Tambahkan header ini di paling atas agar Midtrans bisa menembus proteksi Ngrok
        $request->headers->set('ngrok-skip-browser-warning', 'true');

        $payload = $request->getContent();
        $notification = json_decode($payload);

        // Catatan untuk gross_amount: pastikan format string sesuai dengan kiriman Midtrans (misal "34000.00")
        $grossAmount = $notification->gross_amount;
        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $grossAmount . config('midtrans.server_key'));

        if ($notification->signature_key != $validSignatureKey) {
            Log::error('Midtrans Signature Invalid', [
                'received' => $notification->signature_key,
                'expected' => $validSignatureKey
            ]);
            return response(['message' => 'Invalid signature'], 403)
                     ->header('ngrok-skip-browser-warning', 'true'); // Sertakan di setiap response
        }

        $transactionStatus = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $transactionId = $notification->transaction_id;

        // Cari data order berdasarkan order_id dari Midtrans
        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            Log::warning('Order tidak ditemukan untuk ID: ' . $orderId);
            return response(['message' => 'Order not found'], 404)
                     ->header('ngrok-skip-browser-warning', 'true');
        }

        $order->midtrans_transaction_id = $transactionId;
        $order->payment_type = $type;

        // Logika pengubahan status di database kamu
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $order->payment_status = 'paid';
            $order->status = 'waiting_verification'; // Otomatis masuk ke status Menunggu Konfirmasi
            $order->paid_at = now();
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $order->payment_status = $transactionStatus === 'expire' ? 'expired' : ($transactionStatus === 'cancel' ? 'cancelled' : 'failed');
            $order->status = 'cancelled';
        } else if ($transactionStatus == 'pending') {
            $order->payment_status = 'pending';
        }

        $order->save();

        // Kembalikan respon sukses ke Midtrans dengan menyertakan header bypass Ngrok
        return response(['message' => 'Success'], 200)
                 ->header('ngrok-skip-browser-warning', 'true');
    }
}