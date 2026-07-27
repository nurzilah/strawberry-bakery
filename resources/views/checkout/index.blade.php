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

        @if(isset($carts) && count($carts) > 0)
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pesanan</h2>
                    <form id="paymentForm" action="/checkout" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="midtrans">
                        <button type="submit" class="btn btn-strawberry w-100 rounded-pill py-3">
                            Lanjutkan Pembayaran
                        </button>
                        <p class="text-sm text-gray-500 mt-3 text-center">
                            Anda akan diarahkan ke halaman detail pesanan untuk melakukan pembayaran via Midtrans.
                        </p>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="checkout-panel p-4 p-md-5 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                    <div class="space-y-4 mb-4">
                        @forelse($carts as $cart)
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
        @else
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h2>
            <p class="text-gray-600 mb-8">Silakan tambahkan produk ke keranjang Anda sebelum melakukan pembayaran.</p>
            <a href="{{ route('products.index') }}" class="inline-block py-3 px-8 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-medium transition-colors">
                Belanja Sekarang
            </a>
        </div>
        @endif
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
const pickupInput = document.getElementById('pickupDateInput');
const pickupWarning = document.getElementById('pickupDateWarning');
const paymentForm = document.getElementById('paymentForm');

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

if (paymentForm) {
    paymentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(this);
            // Tambahkan pickup date dari input di luar form
            if (pickupInput) {
                formData.append('pickup_date', pickupInput.value);
            }

            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'ngrok-skip-browser-warning': 'true'
                },
                body: formData
            });

            // Baca raw text dulu untuk debugging
            const rawText = await response.text();
            let result;
            try {
                result = JSON.parse(rawText);
            } catch (e) {
                console.error("Bukan JSON! Response dari server:", rawText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error Server',
                    text: 'Server mengembalikan error (cek console untuk detail).'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }

            if (result.status === 'success' && result.snap_token) {
                // Trigger Snap popup
                snap.pay(result.snap_token, {
                    onSuccess: function(snapResult) {
                        window.location.href = `/checkout/status/${result.order_id}`;
                    },
                    onPending: function(snapResult) {
                        window.location.href = `/checkout/status/${result.order_id}`;
                    },
                    onError: function(snapResult) {
                        window.location.href = `/checkout/status/${result.order_id}`;
                    },
                    onClose: function() {
                        window.location.href = `/checkout/status/${result.order_id}`;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: result.message || 'Terjadi kesalahan saat memproses pembayaran.'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error Sistem',
                text: 'Error Javascript: ' + error.message
            });
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            console.error(error);
        }
    });
}
</script>

@endsection
