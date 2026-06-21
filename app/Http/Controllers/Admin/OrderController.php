<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\PaymentConfirmed;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(15);

        if (request()->routeIs('kasir.*')) {
            return view('kasir.orders.index', compact('orders'));
        }

        return view('admin.orders.index', [
            'orders' => $orders,
            'confirmRoute' => 'admin.orders.confirm',
        ]);
    }

    public function confirm(Order $order)
    {
        if ($order->status !== 'menunggu_konfirmasi') {
            return redirect()->back()->with('success', 'Pesanan ini tidak bisa dikonfirmasi lagi.');
        }

        $order->update([
            'status' => 'dikonfirmasi',
        ]);

        if ($order->user) {
            $order->user->notify(new PaymentConfirmed($order));
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:menunggu_konfirmasi,dikonfirmasi,processing,completed,cancelled',
        ]);

        $targetStatus = $request->status;
        $allowedTransitions = [
            'menunggu_konfirmasi' => ['cancelled'],
            'dikonfirmasi' => ['processing', 'cancelled'],
            'processing' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        if (!in_array($targetStatus, $allowedTransitions[$order->status] ?? [], true)) {
            return redirect()->back()->withErrors([
                'status' => 'Perubahan status tidak valid untuk kondisi pesanan saat ini.',
            ]);
        }

        $order->update(['status' => $targetStatus]);

        $message = $targetStatus === 'completed'
            ? 'Pesanan selesai diambil.'
            : 'Status pesanan berhasil diperbarui.';

        return redirect()->back()->with('success', $message);
    }
}
