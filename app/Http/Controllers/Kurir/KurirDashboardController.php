<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CourierNotificationService;
use Illuminate\Http\Request;

class KurirDashboardController extends Controller
{
    /**
     * Display courier dashboard with assigned orders.
     */
    public function index()
    {
        // Fetch orders assigned to the logged-in courier
        // Ordered by: active delivery statuses first ('ditugaskan', 'diambil_kurir', 'dalam_perjalanan'), then 'gagal_kirim', then 'terkirim'
        $orders = auth()->user()->courierOrders()->with('user')
            ->orderByRaw("
                CASE 
                    WHEN delivery_status IN ('ditugaskan', 'diambil_kurir', 'dalam_perjalanan') THEN 1
                    WHEN delivery_status = 'gagal_kirim' THEN 2
                    WHEN delivery_status = 'terkirim' THEN 3
                    ELSE 4
                END ASC
            ")
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('kurir.dashboard', compact('orders'));
    }

    /**
     * Update delivery status for an assigned order.
     */
    public function updateDeliveryStatus(Request $request, Order $order, CourierNotificationService $notificationService)
    {
        // 1. Ensure courier can only update orders assigned to themselves
        if ((int) $order->courier_id !== (int) auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status pesanan ini.');
        }

        $request->validate([
            'delivery_status' => 'required|in:diambil_kurir,dalam_perjalanan,terkirim,gagal_kirim',
            'delivery_notes' => 'required_if:delivery_status,gagal_kirim|nullable|string|max:500',
        ], [
            'delivery_notes.required_if' => 'Alasan gagal kirim wajib diisi.',
        ]);

        $newStatus = $request->delivery_status;
        $currentStatus = $order->delivery_status ?? 'ditugaskan';

        // 2. Enforce strict sequential status transitions
        $allowedTransitions = [
            'belum_ditugaskan' => ['diambil_kurir'],
            'ditugaskan' => ['diambil_kurir', 'gagal_kirim'],
            'diambil_kurir' => ['dalam_perjalanan', 'gagal_kirim'],
            'dalam_perjalanan' => ['terkirim', 'gagal_kirim'],
            'terkirim' => [],
            'gagal_kirim' => ['diambil_kurir'],
        ];

        if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
            return redirect()->back()->withErrors([
                'delivery_status' => "Perubahan status dari '{$currentStatus}' ke '{$newStatus}' tidak diperbolehkan. Urutan status pengantaran tidak boleh dilompati.",
            ]);
        }

        $updateData = [
            'delivery_status' => $newStatus,
        ];

        if ($request->filled('delivery_notes')) {
            $updateData['delivery_notes'] = $request->delivery_notes;
        }

        // 3. When delivered ('terkirim'), record delivered_at and mark main order status as completed
        if ($newStatus === 'terkirim') {
            $updateData['delivered_at'] = now();
            $updateData['status'] = 'completed';
        }

        $order->update($updateData);

        // 4. Send WA notification to customer for 'dalam_perjalanan' or 'terkirim'
        if (in_array($newStatus, ['dalam_perjalanan', 'terkirim'], true)) {
            $notificationService->sendStatusUpdateNotification($order, $newStatus);
        }

        $statusMessages = [
            'diambil_kurir' => 'Pesanan telah Anda ambil dari toko.',
            'dalam_perjalanan' => 'Pesanan sedang dalam perjalanan ke lokasi pelanggan.',
            'terkirim' => 'Pesanan berhasil diserahkan ke pelanggan!',
            'gagal_kirim' => 'Status pengantaran diperbarui menjadi gagal kirim.',
        ];

        return redirect()->back()->with('success', $statusMessages[$newStatus] ?? 'Status pengantaran berhasil diperbarui.');
    }
}
