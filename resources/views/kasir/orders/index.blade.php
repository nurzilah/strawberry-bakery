@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - Kasir')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Kasir - Konfirmasi Pembayaran</h1>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600">
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Bukti</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t">
                            <td class="py-3 text-gray-700">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="py-3 text-gray-700">{{ $order->user->name ?? '-' }}</td>
                            <td class="py-3 text-gray-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-3">
                                @if($order->status === 'waiting_verification')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Menunggu Konfirmasi</span>
                                @elseif($order->status === 'processing')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Siap Diambil</span>
                                @elseif($order->status === 'completed')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Selesai Diambil</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Dibatalkan</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Pending</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($order->payment_proof_path)
                                    <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="text-pink-500 hover:underline">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="flex items-center space-x-2">
                                    @if($order->status === 'waiting_verification')
                                        <form action="{{ route('kasir.orders.confirm', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 bg-white text-black border border-black rounded-lg text-sm font-semibold hover:bg-gray-100 transition">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif

                                    @if($order->status === 'processing')
                                        <form action="{{ route('kasir.orders.status', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                                                Selesai Diambil
                                            </button>
                                        </form>
                                    @endif

                                    @if(!in_array($order->status, ['completed','cancelled'], true))
                                        <form action="{{ route('kasir.orders.status', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('kasir.orders.receipt', $order) }}" class="text-pink-500 hover:underline text-sm">
                                        Cetak Struk
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Belum ada pembayaran.</td>
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
