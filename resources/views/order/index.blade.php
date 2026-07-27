@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Pesanan Saya</h1>

    @if($orders->where('status', 'processing')->count() > 0)
        <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg shadow-sm">
            Ada pesanan yang sudah dikonfirmasi. Silakan cek detailnya.
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600">
                        <th class="py-3">No Pesanan</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Ringkasan Item</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Metode</th>
                        <th class="py-3">Pickup</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t">
                            <td class="py-3 text-gray-700">
                                {{ $order->order_number ?? 'ORD-' . $order->created_at->format('d M Y') . '-' . str_pad((string) $order->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3 text-gray-700">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="py-3 text-gray-700">
                                @php
                                    $items = $order->items->take(2);
                                    $more = max(0, $order->items->count() - $items->count());
                                @endphp
                                @foreach($items as $item)
                                    <div class="text-xs">
                                        {{ $item->product->name ?? $item->product_name ?? 'Produk' }} x {{ $item->quantity }}
                                    </div>
                                @endforeach
                                @if($more > 0)
                                    <div class="text-xs text-gray-400">+{{ $more }} item lainnya</div>
                                @endif
                            </td>
                            <td class="py-3 text-gray-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3 text-gray-700">{{ strtoupper($order->payment_method ?? '-') }}</td>
                            <td class="py-3 text-gray-700">
                                {{ $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3">
                                @if($order->status === 'processing')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Siap Diambil</span>
                                @elseif($order->status === 'completed')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Selesai</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Dibatalkan</span>
                                @elseif($order->status === 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Pending</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu Konfirmasi</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <a href="{{ route('orders.show', $order) }}" class="text-pink-500 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
