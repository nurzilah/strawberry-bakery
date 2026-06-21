@extends('layouts.app')

@section('title', 'Checkout - Pembayaran')

@section('content')
<div class="bg-pink-50 py-10">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-3 mb-8">
            <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2a3 3 0 006 0v-2c0-1.657-1.343-3-3-3zm8 5a8 8 0 11-16 0 8 8 0 0116 0z"/>
            </svg>
            <h1 class="text-3xl font-bold text-gray-800">Pembayaran</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tanggal Pengambilan (H-2)</h2>
                    <div class="mb-2">
                        @php
                            $minPickup = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d');
                        @endphp
                        <input type="date" name="pickup_date" required form="paymentForm"
                               id="pickupDateInput"
                               min="{{ $minPickup }}"
                               data-min="{{ $minPickup }}"
                               onkeydown="return false"
                               onpaste="return false"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                        @error('pickup_date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <p id="pickupDateWarning" class="text-sm text-red-600 mt-2 hidden">
                        Tanggal pengambilan minimal H-2 dari hari ini.
                    </p>
                    <p class="text-sm text-gray-500">Minimal pengambilan H-2 dari hari ini (contoh: hari ini tgl 1, paling cepat tgl 3).</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Metode Pembayaran</h2>
                    <div class="space-y-3 mb-4">
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="bca" checked form="paymentForm"
                                   class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span>Transfer Bank BCA</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="dana" form="paymentForm"
                                   class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span>DANA</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="shopeepay" form="paymentForm"
                                   class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span>ShopeePay</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="gopay" form="paymentForm"
                                   class="w-4 h-4 text-pink-500 border-gray-300 focus:ring-pink-500">
                            <span>GoPay</span>
                        </label>
                    </div>

                    <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                        <div class="text-sm text-gray-600 mb-2">Nomor Pembayaran</div>
                        <div class="text-lg font-bold text-gray-800 mb-3">085712799296</div>
                        <div class="text-sm text-gray-600 mt-2">Atas Nama</div>
                        <div class="text-lg font-semibold text-gray-800">Strawberry Bakery</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Upload Bukti Pembayaran</h2>
                    <form id="paymentForm" action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran (JPG/PNG)</label>
                            <input type="file" name="payment_proof" accept="image/png,image/jpeg" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white">
                            @error('payment_proof')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full text-center bg-gradient-to-r from-pink-400 to-red-400 text-white py-3 rounded-lg font-bold text-lg hover:shadow-xl transition">
                            Konfirmasi Pembayaran
                        </button>
                        <p class="text-sm text-gray-500 mt-3">
                            Setelah upload, status pembayaran: menunggu konfirmasi admin.
                        </p>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl p-6 shadow-sm sticky top-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                    <div class="space-y-4 mb-4">
                        @forelse($carts ?? [] as $cart)
                            <div class="flex justify-between text-gray-600">
                                <span>{{ $cart->detailCart->product->name ?? 'Produk' }} x {{ $cart->detailCart->quantity ?? 0 }}</span>
                                <span class="font-semibold">
                                    Rp {{ number_format(($cart->detailCart->product->price ?? 0) * ($cart->detailCart->quantity ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-600">Keranjang kosong.</p>
                        @endforelse
                        <hr>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-800">
                        <span>Total</span>
                        <span class="text-pink-500">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const pickupInput = document.getElementById('pickupDateInput');
const pickupWarning = document.getElementById('pickupDateWarning');

if (pickupInput) {
    const minDate = pickupInput.dataset.min;

    const validatePickup = () => {
        if (pickupInput.value && minDate && pickupInput.value < minDate) {
            pickupWarning.classList.remove('hidden');
            pickupInput.value = minDate;
        } else {
            pickupWarning.classList.add('hidden');
        }
    };

    pickupInput.addEventListener('change', validatePickup);
    pickupInput.addEventListener('input', validatePickup);
    pickupInput.addEventListener('blur', validatePickup);
    pickupInput.addEventListener('focus', () => {
        if (!pickupInput.value && minDate) {
            pickupInput.value = minDate;
        }
    });
}
</script>
@endsection
