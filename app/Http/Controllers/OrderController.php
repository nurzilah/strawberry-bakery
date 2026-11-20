<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PackageReceiptGrouper;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['items.product', 'packages.items.product', 'courier']);
        $packageGroups = app(PackageReceiptGrouper::class)->group($order->packages);
        $packageSubtotal = $order->packages->sum(fn ($package) => (int) $package->box_price);

        return view('order.show', compact('order', 'packageGroups', 'packageSubtotal'));
    }

    public function receipt(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return $this->receiptPdf($order);
    }

    public function sharedReceipt(Order $order)
    {
        return $this->receiptPdf($order);
    }

    private function receiptPdf(Order $order)
    {
        $order->loadMissing(['user', 'items', 'packages.items.product']);
        $packageGroups = app(PackageReceiptGrouper::class)->group($order->packages);
        $pdf = Pdf::loadView('admin.orders.receipt_pdf', compact('order', 'packageGroups'));
        $pdf->setPaper([0, 0, 226.77, 800], 'portrait');

        return $pdf->stream('Struk_Pesanan_' . $order->id . '.pdf');
    }
}
