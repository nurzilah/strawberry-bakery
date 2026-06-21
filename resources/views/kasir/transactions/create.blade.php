@extends('layouts.app')

@section('title', 'Input Transaksi')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Input Transaksi</h1>
        <a href="{{ route('kasir.orders.index') }}" class="text-pink-500 hover:underline">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <form action="{{ route('kasir.transactions.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pelanggan</label>
                    <select name="user_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengambilan (H-2)</label>
                    <input type="date" name="pickup_date" required min="{{ $minPickup }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-4 py-2.5">
                        <option value="bca">BCA</option>
                        <option value="dana">DANA</option>
                        <option value="shopeepay">ShopeePay</option>
                        <option value="gopay">GoPay</option>
                    </select>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Item Pesanan</h2>
                <div id="items-wrapper" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 item-row">
                        <select name="items[0][product_id]" class="border border-gray-300 rounded-lg px-4 py-2.5">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="items[0][quantity]" min="1" value="1"
                               class="border border-gray-300 rounded-lg px-4 py-2.5">
                        <button type="button" class="remove-item px-4 py-2 bg-gray-100 rounded-lg text-sm">
                            Hapus
                        </button>
                    </div>
                </div>

                <button type="button" id="add-item" class="mt-4 px-4 py-2 bg-black text-white rounded-lg text-sm">
                    Tambah Item
                </button>
            </div>

            <div class="mt-8">
                <button type="submit" class="px-6 py-3 bg-pink-500 text-white rounded-lg font-semibold hover:bg-pink-600 transition">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const wrapper = document.getElementById('items-wrapper');
const addButton = document.getElementById('add-item');

if (wrapper && addButton) {
    addButton.addEventListener('click', () => {
        const index = wrapper.querySelectorAll('.item-row').length;
        const template = wrapper.querySelector('.item-row').cloneNode(true);
        template.querySelectorAll('select, input').forEach((input) => {
            if (input.name.includes('product_id')) {
                input.name = `items[${index}][product_id]`;
            }
            if (input.name.includes('quantity')) {
                input.name = `items[${index}][quantity]`;
                input.value = 1;
            }
        });
        wrapper.appendChild(template);
    });

    wrapper.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            const rows = wrapper.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
            }
        }
    });
}
</script>
@endsection
