@extends('layouts.app')

@section('title', 'Checkout - Pembayaran')

@push('styles')
    <style>
        .checkout-shell {
            background-color: var(--sb-primary-soft);
        }

        .checkout-panel {
            background-color: var(--sb-surface);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1rem;
            box-shadow: var(--sb-shadow);
        }

        .checkout-soft-box {
            background-color: var(--sb-primary-soft);
            border: 1px solid rgba(233, 79, 112, 0.22);
            border-radius: 0.9rem;
        }

        .checkout-radio {
            accent-color: var(--sb-primary);
        }
    </style>
@endpush

@section('content')
<div class="checkout-shell py-5">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-3 mb-8">
            <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2a3 3 0 006 0v-2c0-1.657-1.343-3-3-3zm8 5a8 8 0 11-16 0 8 8 0 0116 0z"/>
            </svg>
            <h1 class="text-3xl font-bold text-gray-800">Pembayaran</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="checkout-panel p-4 p-md-5">
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
                               class="form-control">
                        @error('pickup_date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <p id="pickupDateWarning" class="text-sm text-red-600 mt-2 hidden">
                        Tanggal pengambilan minimal H-2 dari hari ini.
                    </p>
                    <p class="text-sm text-gray-500">Minimal pengambilan H-2 dari hari ini (contoh: hari ini tgl 1, paling cepat tgl 3).</p>
                </div>

                <div class="checkout-panel p-4 p-md-5">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Metode Pembayaran</h2>
                    <div class="space-y-3 mb-4">
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="bca" checked form="paymentForm"
                                   class="form-check-input checkout-radio">
                            <span>Transfer Bank BCA</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="dana" form="paymentForm"
                                   class="form-check-input checkout-radio">
                            <span>DANA</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="shopeepay" form="paymentForm"
                                   class="form-check-input checkout-radio">
                            <span>ShopeePay</span>
                        </label>
                        <label class="flex items-center space-x-2 text-gray-700">
                            <input type="radio" name="payment_method" value="gopay" form="paymentForm"
                                   class="form-check-input checkout-radio">
                            <span>GoPay</span>
                        </label>
                    </div>

                    <div class="checkout-soft-box p-4">
                        <div class="text-sm text-gray-600 mb-2">Nomor Pembayaran</div>
                        <div class="text-lg font-bold text-gray-800 mb-3">085712799296</div>
                        <div class="text-sm text-gray-600 mt-2">Atas Nama</div>
                        <div class="text-lg font-semibold text-gray-800">Strawberry Bakery</div>
                    </div>
                </div>

                <div class="checkout-panel p-4 p-md-5">
                    @if(isset($snapToken))
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Selesaikan Pembayaran</h2>
                        <button id="pay-button" class="btn btn-strawberry w-100 rounded-pill py-3">
                            Bayar Sekarang
                        </button>
                    @else
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pesanan</h2>
                        <form id="paymentForm" action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-strawberry w-100 rounded-pill py-3">
                                Lanjutkan Pembayaran
                            </button>
                            <p class="text-sm text-gray-500 mt-3 text-center">
                                Anda akan diarahkan ke halaman pembayaran Midtrans.
                            </p>
                        </form>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="checkout-panel p-4 p-md-5 sticky top-24">
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

@if(isset($snapToken))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = '{{ route("checkout.success") }}';
            },
            onPending: function(result){
                window.location.href = '{{ route("checkout.pending") }}';
            },
            onError: function(result){
                window.location.href = '{{ route("checkout.failed") }}';
            },
            onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>
@endif

@endsection
