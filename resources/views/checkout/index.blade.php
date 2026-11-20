@extends('layouts.app')

@section('title', 'Checkout - Pembayaran')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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

        .voucher-card {
            transition: all 0.2s ease;
            background-color: #fff;
        }
        .voucher-card:hover {
            border-color: #f472b6;
        }
        .voucher-card.selected {
            border-color: #db2777 !important;
            background-color: #fdf2f8 !important;
            box-shadow: 0 0 0 1px #db2777;
        }
        .voucher-card.ineligible {
            opacity: 0.8;
            background-color: #fafafa;
        }

        #checkoutDeliveryMap {
            height: 320px;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            z-index: 1;
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
                    @php
                        $totalQty = 0;
                        foreach($carts as $cart) {
                            $totalQty += \Illuminate\Support\Facades\Schema::hasColumn('carts', 'quantity') 
                                ? $cart->quantity 
                                : $cart->detailCart->quantity;
                        }
                        $minDeliveryQty = config('store.delivery_min_qty', 12);
                        $canDelivery = $totalQty >= $minDeliveryQty;
                    @endphp

                    <h2 class="text-xl font-bold text-gray-800 mb-4">Metode & Tanggal Pengiriman</h2>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Pilih Metode:</label>
                        @if($canDelivery)
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="delivery_method" value="pickup" form="paymentForm" class="checkout-radio" checked onchange="toggleDeliveryAddress(false)">
                                    <span>Ambil di Toko (Pickup)</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="delivery_method" value="delivery" form="paymentForm" class="checkout-radio" onchange="toggleDeliveryAddress(true)">
                                    <span>Kirim ke Alamat (Delivery)</span>
                                </label>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="delivery_method" value="pickup" form="paymentForm" class="checkout-radio" checked onchange="toggleDeliveryAddress(false)">
                                    <span class="font-medium text-gray-800">Ambil di Toko (Pickup)</span>
                                </label>
                            </div>
                            <p class="text-xs text-orange-600 mt-2 bg-orange-50 p-2.5 rounded-lg border border-orange-200">
                                <i class="fas fa-info-circle me-1"></i> Layanan antar (Delivery) hanya tersedia untuk pemesanan minimal {{ $minDeliveryQty }} item (Pesanan Anda saat ini: {{ $totalQty }} item).
                            </p>
                        @endif
                    </div>

                    <div id="deliveryAddressContainer" class="mb-4 hidden">
                        <label class="block text-gray-700 font-semibold mb-2">Alamat Pengiriman:</label>
                        <textarea name="delivery_address" form="paymentForm" class="form-control w-full" rows="3" placeholder="Masukkan alamat lengkap (misal: desa klampis rt 09 rw 03, kecamatan jatibarang, kabupaten brebes)"></textarea>
                        
                        {{-- Dropdown Rekomendasi Lokasi Nominatim --}}
                        <div id="addressSuggestionsDropdown" class="hidden my-2 bg-white rounded-xl border border-pink-200 shadow-md overflow-hidden z-20">
                            <div class="py-1.5 px-3 bg-pink-50 border-b border-pink-100 flex items-center justify-between text-xs text-pink-700 font-semibold">
                                <span><i class="fas fa-map-marker-alt me-1"></i> Saran Lokasi Ditemukan (Klik untuk ubah titik):</span>
                                <button type="button" onclick="hideAddressSuggestions()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                            </div>
                            <div id="addressSuggestionsList" class="max-h-48 overflow-y-auto divide-y divide-gray-100 text-xs text-gray-700"></div>
                        </div>

                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <button type="button" onclick="searchAddressNominatim(false)" id="btnSearchNominatim" class="btn btn-outline-strawberry text-xs py-1.5 px-3 rounded-lg font-semibold flex items-center gap-1.5">
                                <i class="fas fa-search-location"></i> Cari Titik dari Alamat
                            </button>
                            <button type="button" onclick="useCurrentLocation()" class="btn btn-outline-secondary text-xs py-1.5 px-3 rounded-lg font-semibold flex items-center gap-1.5">
                                <i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya
                            </button>
                            <span id="nominatimLoading" class="text-xs text-pink-600 font-medium hidden"><i class="fas fa-spinner fa-spin me-1"></i> Mendeteksi lokasi otomatis...</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 mb-2">
                            <i class="fas fa-info-circle me-1 text-pink-500"></i> Ketik alamat lengkap di atas (desa, kecamatan, kabupaten). Titik lokasi dan tarif ongkir akan <strong>terdeteksi otomatis</strong> setelah selesai mengetik.
                        </p>

                        {{-- Leaflet Map Container --}}
                        <div id="checkoutDeliveryMap" class="w-full my-3"></div>

                        {{-- Delivery Distance & Cost Info Badge --}}
                        <div id="deliveryDistanceInfo" class="p-3 bg-pink-50 border border-pink-200 rounded-xl text-xs space-y-1.5 hidden">
                            <div class="flex items-center justify-between font-semibold">
                                <span class="text-gray-700"><i class="fas fa-route text-pink-600 me-1"></i> Jarak Pengiriman:</span>
                                <span class="text-pink-700 text-sm font-bold" id="infoDistanceKm">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Tarif Ongkos Kirim:</span>
                                <span class="font-bold text-gray-800" id="infoShippingCost">-</span>
                            </div>
                            <div class="text-[11px] text-gray-500 flex items-center justify-between">
                                <span>Titik Koordinat Antar:</span>
                                <span class="font-mono text-gray-600" id="infoCoords">-</span>
                            </div>
                            <div id="deliveryRadiusAlert" class="mt-2 p-2 rounded-lg bg-red-100 border border-red-300 text-red-700 font-semibold text-xs hidden">
                                <i class="fas fa-exclamation-triangle me-1"></i> Lokasi pengiriman di luar batas jangkauan pengantaran toko (maksimal 25 km). Silakan pilih metode <strong>Ambil di Toko (Pickup)</strong> atau tentukan titik antar lain.
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal &amp; Jam Pengambilan / Pengantaran:</label>
                        @php
                            $minPickup   = \Carbon\Carbon::today()->format('Y-m-d');
                            $maxPickup   = \Carbon\Carbon::today()->addDays(2)->format('Y-m-d');
                            $timeSlots   = \App\Helpers\StoreHoursHelper::generateTimeSlots();
                            $deadlineHrs = config('store.pickup_deadline_hours', 2);
                        @endphp

                        {{-- Quick Option Buttons untuk Pemilihan Tanggal --}}
                        <div class="mb-3">
                            <label class="block text-xs text-gray-500 mb-1.5 font-medium">Pilihan Cepat Tanggal:</label>
                            <div class="grid grid-cols-3 gap-2" id="quickDateButtons">
                                <button type="button" onclick="setQuickDate('{{ \Carbon\Carbon::today()->format('Y-m-d') }}')"
                                        class="quick-date-btn py-1.5 px-2 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-pink-50 hover:border-pink-300 transition-all text-center">
                                    Hari Ini
                                </button>
                                <button type="button" onclick="setQuickDate('{{ \Carbon\Carbon::today()->addDays(1)->format('Y-m-d') }}')"
                                        class="quick-date-btn py-1.5 px-2 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-pink-50 hover:border-pink-300 transition-all text-center">
                                    Besok
                                </button>
                                <button type="button" onclick="setQuickDate('{{ \Carbon\Carbon::today()->addDays(2)->format('Y-m-d') }}')"
                                        class="quick-date-btn py-1.5 px-2 text-xs font-semibold rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-pink-50 hover:border-pink-300 transition-all text-center">
                                    Lusa (H+2)
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            {{-- Pilih Tanggal --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Pilih Tanggal (Maks H+2)</label>
                                <input type="date" name="requested_date" required form="paymentForm"
                                       id="pickupDateInput"
                                       min="{{ $minPickup }}"
                                       max="{{ $maxPickup }}"
                                       data-min="{{ $minPickup }}"
                                       data-max="{{ $maxPickup }}"
                                       onkeydown="return false"
                                       onpaste="return false"
                                       class="form-control w-full">
                            </div>
                            {{-- Pilih Jam --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jam</label>
                                <select name="requested_time" id="requestedTimeSelect" required form="paymentForm"
                                        class="form-control w-full">
                                    <option value="" disabled selected>-- Pilih Jam --</option>
                                    @foreach($timeSlots as $timeSlot)
                                        <option value="{{ $timeSlot }}">{{ $timeSlot }} WIB</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('requested_date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        @error('requested_time')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <p id="pickupDateWarning" class="text-sm text-red-600 mt-2 hidden">
                        Tanggal tidak boleh di masa lalu atau melebihi H+2.
                    </p>
                    <p id="noTimeSlotWarning" class="text-sm text-red-600 mt-2 hidden">
                        ⚠ Tidak ada slot jam yang tersedia untuk hari ini (sudah melebihi jam operasional / batas persiapan toko). Silakan pilih tanggal besok atau hari berikutnya.
                    </p>


                    {{-- === DISCLAIMER PICKUP === --}}
                    <div id="pickupDisclaimerBlock" class="mt-4 hidden">
                        <div class="rounded-xl border border-orange-300 bg-orange-50 p-4">
                            <div class="flex items-start gap-3 mb-3">
                                <svg class="w-6 h-6 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <div>
                                    <p class="font-bold text-orange-700 mb-1">⚠ Ketentuan Pengambilan Pesanan (Pickup)</p>
                                    <p class="text-sm text-orange-700 leading-relaxed">
                                        Pesanan pickup <strong>wajib diambil paling lambat {{ $deadlineHrs }} jam</strong>
                                        setelah jam yang Anda pilih. Lewat batas waktu tersebut, toko
                                        <strong>tidak bertanggung jawab</strong> atas kehilangan atau kerusakan pesanan.
                                    </p>
                                    <p class="text-sm text-orange-600 mt-2">
                                        Contoh: jika Anda memilih jam <span id="exampleTime" class="font-semibold">--:--</span>,
                                        pesanan harus sudah diambil sebelum <span id="exampleDeadline" class="font-semibold">--:--</span>.
                                    </p>
                                </div>
                            </div>
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" id="pickupDisclaimerCheck" name="pickup_disclaimer_agreed"
                                       value="1" form="paymentForm"
                                       class="mt-1 rounded border-orange-400 accent-orange-500 flex-shrink-0">
                                <span class="text-sm text-orange-800 font-medium">
                                    Saya mengerti dan menyetujui ketentuan di atas. Saya akan mengambil pesanan
                                    tepat waktu sesuai batas yang ditentukan.
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- === PILIH VOUCHER SECTION === --}}
                <div class="checkout-panel p-4 p-md-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-ticket-alt"></i>
                            </span>
                            <h2 class="text-xl font-bold text-gray-800">Pilih Voucher</h2>
                        </div>
                        <button type="button" id="removeVoucherBtn" onclick="removeVoucher()" class="text-xs text-red-500 hover:text-red-700 font-semibold hidden">
                            <i class="fas fa-times me-1"></i> Batal Pakai
                        </button>
                    </div>

                    {{-- Manual Input Voucher --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Punya kode voucher khusus?</label>
                        <div class="flex gap-2">
                            <input type="text" id="manualVoucherInput" placeholder="Contoh: WELCOME15, HEMAT25" class="form-control text-sm uppercase flex-1 rounded-lg">
                            <button type="button" onclick="applyManualVoucher()" class="btn btn-outline-strawberry px-4 py-2 text-sm font-semibold rounded-lg">
                                Terapkan
                            </button>
                        </div>
                        <p id="voucherFeedbackMsg" class="text-xs mt-1.5 hidden"></p>
                    </div>

                    {{-- List Kartu Pilihan Voucher --}}
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Voucher Tersedia:</label>
                    <div class="space-y-3" id="voucherCardsContainer">
                        @forelse($vouchers as $v)
                            <div class="voucher-card p-3 rounded-xl border border-gray-200 transition-all cursor-pointer relative"
                                 id="voucher-card-{{ $v->code }}"
                                 onclick="handleVoucherCardClick('{{ $v->code }}')">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1">
                                        <input type="radio" name="selected_voucher_radio" value="{{ $v->code }}" id="radio-{{ $v->code }}" class="mt-1 accent-pink-600 voucher-radio pointer-events-none">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-sm text-gray-800">{{ $v->name }}</span>
                                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-pink-100 text-pink-700 uppercase">{{ $v->code }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 mb-0">{{ $v->description }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="voucher-badge px-2.5 py-1 rounded-full text-xs font-semibold inline-block" id="badge-{{ $v->code }}">
                                            Memeriksa...
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">Belum ada voucher aktif saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="checkout-panel p-4 p-md-5">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Pesanan</h2>
                    <form id="paymentForm" action="/checkout" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="midtrans">
                        <input type="hidden" name="voucher_code" id="hiddenVoucherCode" value="">
                        <input type="hidden" name="delivery_lat" id="hiddenDeliveryLat" value="">
                        <input type="hidden" name="delivery_lng" id="hiddenDeliveryLng" value="">
                        <input type="hidden" name="distance_km" id="hiddenDistanceKm" value="">
                        <button type="submit" class="btn btn-strawberry w-100 rounded-pill py-3 font-bold text-base shadow-sm">
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
                    <div class="space-y-3 mb-4 text-sm">
                        @forelse($carts as $cart)
                            <div class="flex justify-between text-gray-600">
                                <span>{{ $cart->detailCart->product->name ?? 'Produk' }} x {{ $cart->detailCart->quantity ?? 0 }}</span>
                                <span class="font-semibold text-gray-800">
                                    Rp {{ number_format(($cart->detailCart->product->price ?? 0) * ($cart->detailCart->quantity ?? 0), 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-600">Keranjang kosong.</p>
                        @endforelse
                        <hr class="my-3 border-gray-200">
                        
                        {{-- Rincian Biaya --}}
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Belanja</span>
                            <span class="font-semibold text-gray-800">Rp <span id="summarySubtotal">{{ number_format($total ?? 0, 0, ',', '.') }}</span></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Pengiriman (Ongkir)</span>
                            <span class="font-semibold text-gray-800" id="summaryShipping">Rp 0 (Ambil di Toko)</span>
                        </div>
                        <div class="flex justify-between text-green-700 hidden" id="summaryPromoRow">
                            <span id="summaryPromoLabel">Diskon Promo</span>
                            <span class="font-semibold" id="summaryPromoDiscount">- Rp 0</span>
                        </div>
                        <div class="flex justify-between text-pink-700 bg-pink-50 p-2.5 rounded-lg border border-pink-200 hidden" id="summaryVoucherRow">
                            <div>
                                <span class="font-semibold block">Diskon Voucher</span>
                                <span class="text-xs text-pink-600 font-mono" id="summaryVoucherCode"></span>
                            </div>
                            <span class="font-bold text-pink-600 self-center" id="summaryVoucherDiscount">- Rp 0</span>
                        </div>
                        <hr class="my-3 border-gray-200">
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-800">
                        <span>Total Akhir</span>
                        <span class="text-pink-600 text-xl font-extrabold" id="summaryGrandTotal">Rp {{ number_format($total ?? 0, 0, ',', '.') }}</span>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
const PICKUP_DEADLINE_HOURS = {{ config('store.pickup_deadline_hours', 2) }};
const ORDER_LEAD_TIME_HOURS = {{ config('store.order_lead_time_hours', 1) }};

const STORE_LAT = {{ config('store.lat', -6.86985) }};
const STORE_LNG = {{ config('store.lng', 109.13615) }};
const STORE_NAME = @json(config('store.store_name', 'Strawberry Bakery'));
const STORE_BASE_SHIPPING = {{ config('store.base_shipping_cost', 10000) }};
const STORE_COST_PER_KM = {{ config('store.cost_per_km', 2500) }};
const STORE_MAX_RADIUS = {{ config('store.max_delivery_radius_km', 25.0) }};
const ROAD_CORRECTION_FACTOR = {{ config('store.road_correction_factor', 1.3) }};

const baseSubtotal = {{ (int) ($total ?? 0) }};
const orderPromo = @json($orderPromo ?? null);
const vouchersData = @json($vouchers ?? []);
const isFirstOrder = @json($isFirstOrder ?? false);

let currentDeliveryMethod = 'pickup';
let selectedVoucher = null;

let deliveryLat = null;
let deliveryLng = null;
let deliveryDistanceKm = null;
let isOutsideRadius = false;
let map = null;
let storeMarker = null;
let customerMarker = null;
let circle10km = null;
let circle25km = null;

function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function calculateDistanceHaversine(lat, lng) {
    const R = 6371.0;
    const toRad = deg => (deg * Math.PI) / 180;
    const lat1 = toRad(STORE_LAT);
    const lon1 = toRad(STORE_LNG);
    const lat2 = toRad(lat);
    const lon2 = toRad(lng);

    const dLat = lat2 - lat1;
    const dLon = lon2 - lon1;

    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1) * Math.cos(lat2) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const straight = R * c;
    const road = straight * ROAD_CORRECTION_FACTOR;
    return Math.round(road * 10) / 10;
}

function calculateShippingCost(distKm) {
    if (distKm === null || distKm === undefined) return STORE_BASE_SHIPPING;
    if (distKm <= 5.0) {
        return STORE_BASE_SHIPPING;
    }
    const extraKm = Math.ceil(distKm - 5.0);
    return STORE_BASE_SHIPPING + (extraKm * STORE_COST_PER_KM);
}

function checkVoucherEligibility(v) {
    if (!v.is_active) {
        return { eligible: false, message: 'Voucher tidak aktif' };
    }
    if (v.first_order_only && !isFirstOrder) {
        return { eligible: false, message: 'Khusus pesanan pertama Anda' };
    }
    if (v.type === 'free_shipping') {
        if (currentDeliveryMethod !== 'delivery') {
            return { eligible: false, message: 'Hanya berlaku untuk metode Diantar (Delivery)' };
        }
        if (deliveryDistanceKm === null) {
            return { eligible: false, message: 'Tentukan titik lokasi pada peta' };
        }
        const maxDist = v.max_distance_km || 10.0;
        if (deliveryDistanceKm > maxDist) {
            return { eligible: false, message: `Jarak ${deliveryDistanceKm} km > maks ${maxDist} km` };
        }
    }
    if (v.min_subtotal > 0 && baseSubtotal < v.min_subtotal) {
        return { eligible: false, message: 'Min. belanja Rp ' + formatRupiah(v.min_subtotal) };
    }
    return { eligible: true, message: 'Memenuhi Syarat' };
}

function calculateVoucherDiscount(v, subtotal, shippingCost) {
    if (!v) return 0;
    const check = checkVoucherEligibility(v);
    if (!check.eligible) return 0;

    if (v.type === 'percentage') {
        let disc = Math.round((v.discount_value / 100) * subtotal);
        if (v.max_discount && disc > v.max_discount) {
            disc = v.max_discount;
        }
        return Math.min(disc, subtotal);
    }
    if (v.type === 'fixed') {
        return Math.min(Number(v.discount_value), subtotal);
    }
    if (v.type === 'free_shipping') {
        return shippingCost;
    }
    return 0;
}

function showVoucherFeedback(msg, type = 'info') {
    const el = document.getElementById('voucherFeedbackMsg');
    if (!el) return;
    el.textContent = msg;
    el.classList.remove('hidden', 'text-green-600', 'text-red-600', 'text-orange-600', 'text-gray-600');
    if (type === 'success') {
        el.classList.add('text-green-600');
    } else if (type === 'error') {
        el.classList.add('text-red-600');
    } else if (type === 'warning') {
        el.classList.add('text-orange-600');
    } else {
        el.classList.add('text-gray-600');
    }
}

function updateVouchersState() {
    vouchersData.forEach(v => {
        const card = document.getElementById(`voucher-card-${v.code}`);
        const badge = document.getElementById(`badge-${v.code}`);
        const radio = document.getElementById(`radio-${v.code}`);
        if (!card || !badge) return;

        const check = checkVoucherEligibility(v);
        if (check.eligible) {
            card.classList.remove('ineligible');
            badge.className = 'voucher-badge px-2.5 py-1 rounded-full text-xs font-semibold inline-block bg-green-100 text-green-700';
            badge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Memenuhi Syarat';
        } else {
            card.classList.add('ineligible');
            badge.className = 'voucher-badge px-2.5 py-1 rounded-full text-xs font-semibold inline-block bg-gray-100 text-gray-500';
            badge.innerHTML = '<i class="fas fa-info-circle me-1"></i> ' + check.message;

            // Jika voucher yang sedang dipilih menjadi tidak memenuhi syarat (cth: pindah ke pickup atau jarak > 10 km)
            if (selectedVoucher && selectedVoucher.code === v.code) {
                selectedVoucher = null;
                showVoucherFeedback(`Voucher ${v.code} dilepas: ${check.message}`, 'warning');
            }
        }

        if (selectedVoucher && selectedVoucher.code === v.code) {
            card.classList.add('selected');
            if (radio) radio.checked = true;
        } else {
            card.classList.remove('selected');
            if (radio) radio.checked = false;
        }
    });
}

function recalculateSummary() {
    let shippingCost = 0;
    if (currentDeliveryMethod === 'delivery') {
        if (deliveryDistanceKm !== null) {
            shippingCost = calculateShippingCost(deliveryDistanceKm);
        } else {
            shippingCost = STORE_BASE_SHIPPING;
        }
    }

    let promoDiscount = (orderPromo && orderPromo.discount_amount) ? Number(orderPromo.discount_amount) : 0;
    let voucherDiscount = 0;

    if (selectedVoucher) {
        voucherDiscount = calculateVoucherDiscount(selectedVoucher, baseSubtotal, shippingCost);
    }

    let grandTotal = Math.max(0, baseSubtotal - promoDiscount + shippingCost - voucherDiscount);

    const subtotalEl = document.getElementById('summarySubtotal');
    if (subtotalEl) subtotalEl.textContent = formatRupiah(baseSubtotal);

    const shippingEl = document.getElementById('summaryShipping');
    if (shippingEl) {
        if (currentDeliveryMethod === 'delivery') {
            const distLabel = deliveryDistanceKm !== null ? ` (${deliveryDistanceKm} km)` : '';
            shippingEl.textContent = `Rp ${formatRupiah(shippingCost)}${distLabel}`;
        } else {
            shippingEl.textContent = 'Rp 0 (Ambil di Toko)';
        }
    }

    const promoRow = document.getElementById('summaryPromoRow');
    const promoDiscEl = document.getElementById('summaryPromoDiscount');
    if (promoRow && promoDiscEl) {
        if (promoDiscount > 0) {
            promoRow.classList.remove('hidden');
            promoDiscEl.textContent = '- Rp ' + formatRupiah(promoDiscount);
        } else {
            promoRow.classList.add('hidden');
        }
    }

    const voucherRow = document.getElementById('summaryVoucherRow');
    const voucherCodeEl = document.getElementById('summaryVoucherCode');
    const voucherDiscEl = document.getElementById('summaryVoucherDiscount');
    const removeBtn = document.getElementById('removeVoucherBtn');

    if (voucherRow && voucherDiscEl && voucherCodeEl) {
        if (selectedVoucher && voucherDiscount > 0) {
            voucherRow.classList.remove('hidden');
            voucherCodeEl.textContent = selectedVoucher.code + ' (' + selectedVoucher.name + ')';
            voucherDiscEl.textContent = '- Rp ' + formatRupiah(voucherDiscount);
            if (removeBtn) removeBtn.classList.remove('hidden');
        } else {
            voucherRow.classList.add('hidden');
            if (removeBtn) removeBtn.classList.add('hidden');
        }
    }

    const grandTotalEl = document.getElementById('summaryGrandTotal');
    if (grandTotalEl) grandTotalEl.textContent = 'Rp ' + formatRupiah(grandTotal);

    const hiddenVoucher = document.getElementById('hiddenVoucherCode');
    if (hiddenVoucher) hiddenVoucher.value = selectedVoucher ? selectedVoucher.code : '';
}

function initDeliveryMap() {
    if (map) return;
    const mapElement = document.getElementById('checkoutDeliveryMap');
    if (!mapElement) return;

    map = L.map('checkoutDeliveryMap').setView([STORE_LAT, STORE_LNG], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    const storePinIcon = L.divIcon({
        className: 'custom-store-pin',
        html: '<div style="background-color: #db2777; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="fas fa-store" style="transform: rotate(45deg); color: #fff; font-size: 14px;"></i></div>',
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -34]
    });

    storeMarker = L.marker([STORE_LAT, STORE_LNG], { icon: storePinIcon })
        .addTo(map)
        .bindPopup(`<b>${STORE_NAME}</b><br>Jl. AR. Hakim No. 149, Mangkukusuman, Tegal`);

    circle10km = L.circle([STORE_LAT, STORE_LNG], {
        radius: 10000,
        color: '#db2777',
        fillColor: '#f472b6',
        fillOpacity: 0.1,
        weight: 2,
        dashArray: '5, 5'
    }).addTo(map).bindPopup('<b>Zona Gratis Ongkir (Maks 10 KM)</b><br>Khusus voucher ONGKIR10KM');

    circle25km = L.circle([STORE_LAT, STORE_LNG], {
        radius: 25000,
        color: '#ef4444',
        fillColor: '#f87171',
        fillOpacity: 0.05,
        weight: 1.5
    }).addTo(map).bindPopup('<b>Batas Jangkauan Pengantaran (Maks 25 KM)</b>');

    map.on('click', function(e) {
        setDestination(e.latlng.lat, e.latlng.lng, false);
    });

    // Pasang titik default jika belum ada titik tujuan
    if (!deliveryLat || !deliveryLng) {
        setDestination(STORE_LAT, STORE_LNG, false);
    }
}

function setDestination(lat, lng, panTo = true) {
    lat = parseFloat(lat);
    lng = parseFloat(lng);
    deliveryLat = lat;
    deliveryLng = lng;

    const hiddenLat = document.getElementById('hiddenDeliveryLat');
    const hiddenLng = document.getElementById('hiddenDeliveryLng');
    const hiddenDist = document.getElementById('hiddenDistanceKm');
    if (hiddenLat) hiddenLat.value = lat;
    if (hiddenLng) hiddenLng.value = lng;

    deliveryDistanceKm = calculateDistanceHaversine(lat, lng);
    if (hiddenDist) hiddenDist.value = deliveryDistanceKm;

    isOutsideRadius = deliveryDistanceKm > STORE_MAX_RADIUS;

    if (!customerMarker && map) {
        const customerPinIcon = L.divIcon({
            className: 'custom-customer-pin',
            html: '<div style="background-color: #2563eb; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="fas fa-map-marker-alt" style="transform: rotate(45deg); color: #fff; font-size: 16px;"></i></div>',
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -34]
        });
        customerMarker = L.marker([lat, lng], { icon: customerPinIcon, draggable: true }).addTo(map);
        customerMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            setDestination(pos.lat, pos.lng, false);
        });
    } else if (customerMarker) {
        customerMarker.setLatLng([lat, lng]);
    }

    if (customerMarker) {
        customerMarker.bindPopup(`<b>Titik Pengantaran Anda</b><br>Jarak: ${deliveryDistanceKm} km<br><small class="text-gray-500">(Bisa digeser ke titik yang pas)</small>`).openPopup();
    }

    if (panTo && map) {
        map.setView([lat, lng], 14);
    }

    const infoBox = document.getElementById('deliveryDistanceInfo');
    const distEl = document.getElementById('infoDistanceKm');
    const costEl = document.getElementById('infoShippingCost');
    const coordsEl = document.getElementById('infoCoords');
    const alertEl = document.getElementById('deliveryRadiusAlert');

    if (infoBox) infoBox.classList.remove('hidden');
    if (distEl) distEl.textContent = `${deliveryDistanceKm} km`;
    if (costEl) costEl.textContent = 'Rp ' + formatRupiah(calculateShippingCost(deliveryDistanceKm));
    if (coordsEl) coordsEl.textContent = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;

    if (alertEl) {
        if (isOutsideRadius) {
            alertEl.classList.remove('hidden');
        } else {
            alertEl.classList.add('hidden');
        }
    }

    // Auto deselect ONGKIR10KM if distance > 10 km
    if (selectedVoucher && selectedVoucher.code === 'ONGKIR10KM' && deliveryDistanceKm > 10.0) {
        selectedVoucher = null;
        showVoucherFeedback(`Voucher ONGKIR10KM dilepas karena jarak pengiriman (${deliveryDistanceKm} km) melebihi 10 km.`, 'warning');
    }

    updateVouchersState();
    recalculateSummary();
}

/**
 * 1. Helper Pembersihan Pola Detail Alamat Non-Peta untuk Geocoding
 */
function cleanAddressForGeocoding(rawAddress) {
    if (!rawAddress) return '';
    let str = rawAddress.trim();

    // Hapus nomor RT / RW
    str = str.replace(/\b(rt|rw)\s*\.?\s*\d+[\w\/-]*/gi, ' ');
    // Hapus no. rumah / kavling
    str = str.replace(/\bno\.?\s*\d+[\w\/-]*/gi, ' ');
    // Hapus blok & gang
    str = str.replace(/\b(blok|gang|gg\.?)\s+[a-z0-9]+/gi, ' ');
    // Hapus kodepos 5 digit
    str = str.replace(/\b\d{5}\b/g, ' ');
    str = str.replace(/\bkodepos\s*\:?\s*\d+/gi, ' ');

    // Hapus kata awalan administratif wilayah (pertahankan nama daerahnya)
    str = str.replace(/\b(desa|kelurahan|kel\.?)\s+/gi, ' ');
    str = str.replace(/\b(kecamatan|kec\.?)\s+/gi, ' ');
    str = str.replace(/\b(kabupaten|kab\.?)\s+/gi, ' ');
    str = str.replace(/\bkota\s+/gi, ' ');
    str = str.replace(/\b(jalan|jl\.?)\s+/gi, ' ');
    str = str.replace(/\b(provinsi|prov\.?)\s+/gi, ' ');

    // Normalisasi tanda baca
    str = str.replace(/[;\t|\\\/]+/g, ',');
    str = str.replace(/,+/g, ',');

    // Pisahkan token
    let tokens = str.split(',')
        .map(t => t.replace(/[^\w\s\.-]/gi, ' ').trim())
        .filter(t => t.length > 1 && !/^(indonesia)$/i.test(t));

    // Format Title Case tiap kata
    tokens = tokens.map(t => {
        return t.toLowerCase().split(/\s+/).filter(Boolean).map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    });

    tokens = [...new Set(tokens)];

    if (tokens.length === 0) {
        return rawAddress.trim();
    }

    // Pastikan "Jawa Tengah" ada di akhir query
    const joined = tokens.join(', ');
    if (!/jawa\s*tengah/i.test(joined)) {
        tokens.push('Jawa Tengah');
    }

    return tokens.join(', ');
}

/**
 * Fallback Bertingkat ke Nominatim OpenStreetMap
 */
async function queryNominatimWithFallbacks(rawAddress) {
    const cleaned = cleanAddressForGeocoding(rawAddress);
    if (!cleaned) return { results: [], queryUsed: '' };

    const parts = cleaned.split(',').map(p => p.trim()).filter(Boolean);
    const queries = [];

    // Prioritas 1: Full cleaned query (cth: "Klampis, Jatibarang, Brebes, Jawa Tengah")
    queries.push(cleaned);

    // Prioritas 2: Desa + Kabupaten + Provinsi (cth: "Klampis, Brebes, Jawa Tengah")
    if (parts.length >= 3) {
        queries.push([parts[0], parts[parts.length - 2], parts[parts.length - 1]].join(', '));
    }

    // Prioritas 3: 2-3 bagian terakhir setelah koma (cth: "Jatibarang, Brebes, Jawa Tengah")
    if (parts.length >= 3) {
        queries.push(parts.slice(-3).join(', '));
    }

    // Prioritas 4: Desa + Kabupaten ringkas (cth: "Klampis, Brebes")
    if (parts.length >= 2) {
        queries.push([parts[0], parts[parts.length - 2]].join(', '));
    }

    // Prioritas 5: 2 bagian terakhir (cth: "Jatibarang, Brebes")
    if (parts.length >= 2) {
        queries.push(parts.slice(-2).join(', '));
    }

    // Prioritas 6: 2 bagian pertama (cth: "Klampis, Jatibarang")
    if (parts.length >= 2) {
        queries.push(parts.slice(0, 2).join(', '));
    }

    const uniqueQueries = [...new Set(queries.filter(q => q && q.length > 2))];

    for (let q of uniqueQueries) {
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&limit=5&q=${encodeURIComponent(q)}`;
            const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const data = await resp.json();
            if (Array.isArray(data) && data.length > 0) {
                return { results: data, queryUsed: q };
            }
        } catch (err) {
            console.warn(`Query Nominatim gagal untuk "${q}":`, err);
        }
    }

    return { results: [], queryUsed: '' };
}

function renderAddressSuggestions(results) {
    const dropdown = document.getElementById('addressSuggestionsDropdown');
    const list = document.getElementById('addressSuggestionsList');
    if (!dropdown || !list) return;

    if (!results || results.length === 0) {
        dropdown.classList.add('hidden');
        list.innerHTML = '';
        return;
    }

    let html = '';
    results.forEach((item, idx) => {
        const title = item.name || item.display_name.split(',')[0];
        const isFirst = idx === 0;
        const safeName = (item.display_name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
        html += `
            <button type="button" 
                    onclick="selectAddressSuggestion(${parseFloat(item.lat)}, ${parseFloat(item.lon)}, '${safeName}')"
                    class="w-full text-left p-2.5 hover:bg-pink-50 transition flex items-start gap-2 ${isFirst ? 'bg-pink-50/40 font-medium' : ''}">
                <i class="fas fa-map-pin text-pink-500 mt-1 flex-shrink-0 text-xs"></i>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-xs mb-0.5">
                        ${title}
                        ${isFirst ? '<span class="text-[10px] text-pink-600 bg-pink-100 px-1.5 py-0.5 rounded ml-1 font-normal">Pilihan Teratas</span>' : ''}
                    </p>
                    <p class="text-[11px] text-gray-500 truncate mb-0">${item.display_name}</p>
                </div>
            </button>
        `;
    });

    list.innerHTML = html;
    dropdown.classList.remove('hidden');
}

function selectAddressSuggestion(lat, lon, displayName) {
    setDestination(lat, lon, true);
    hideAddressSuggestions();
    const shortName = displayName.split(',')[0];
    showVoucherFeedback(`Titik lokasi diatur ke: ${shortName}`, 'info');
}

function hideAddressSuggestions() {
    const dropdown = document.getElementById('addressSuggestionsDropdown');
    if (dropdown) dropdown.classList.add('hidden');
}

async function searchAddressNominatim(isAuto = false) {
    const textarea = document.querySelector('textarea[name="delivery_address"]');
    const query = (textarea ? textarea.value : '').trim();
    if (!query || query.length < 10) {
        if (!isAuto) {
            Swal.fire({
                icon: 'warning',
                title: 'Alamat Terlalu Singkat',
                text: 'Silakan ketik alamat pengiriman lebih lengkap (minimal 10 karakter).'
            });
        }
        hideAddressSuggestions();
        return;
    }

    const loadingEl = document.getElementById('nominatimLoading');
    const btn = document.getElementById('btnSearchNominatim');
    if (loadingEl) {
        loadingEl.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mendeteksi lokasi otomatis...';
        loadingEl.classList.remove('hidden');
    }
    if (btn) btn.disabled = true;

    try {
        const { results, queryUsed } = await queryNominatimWithFallbacks(query);

        if (results && results.length > 0) {
            const first = results[0];
            setDestination(parseFloat(first.lat), parseFloat(first.lon), true);
            renderAddressSuggestions(results);

            if (!isAuto) {
                Swal.fire({
                    icon: 'success',
                    title: 'Titik Ditemukan',
                    text: `Titik berhasil diset pada peta (${first.display_name}). Anda dapat memilih saran lain atau menggeser pin biru jika diperlukan.`,
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        } else {
            hideAddressSuggestions();
            if (!isAuto) {
                Swal.fire({
                    icon: 'info',
                    title: 'Alamat Tidak Ditemukan',
                    text: 'Nama alamat tidak ditemukan di OpenStreetMap. Silakan periksa kembali tulisan alamat atau klik/geser pin biru langsung di peta.'
                });
            }
        }
    } catch (e) {
        console.error(e);
        if (!isAuto) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mencari Alamat',
                text: 'Terjadi gangguan saat mencari alamat. Silakan klik langsung titik lokasi pada peta.'
            });
        }
    } finally {
        if (loadingEl) loadingEl.classList.add('hidden');
        if (btn) btn.disabled = false;
    }
}

// 2. Auto-Detect Alamat Saat Mengetik (Debounce 1.5 Detik)
let addressDebounceTimer = null;
function setupAddressAutoDetect() {
    const textarea = document.querySelector('textarea[name="delivery_address"]');
    if (!textarea || textarea.dataset.autodetectBound) return;
    textarea.dataset.autodetectBound = 'true';

    textarea.addEventListener('input', function() {
        clearTimeout(addressDebounceTimer);
        const val = this.value.trim();
        if (val.length < 10) {
            hideAddressSuggestions();
            const loadingEl = document.getElementById('nominatimLoading');
            if (loadingEl) loadingEl.classList.add('hidden');
            return;
        }

        const loadingEl = document.getElementById('nominatimLoading');
        if (loadingEl) {
            loadingEl.innerHTML = '<i class="fas fa-keyboard me-1"></i> Mengetik...';
            loadingEl.classList.remove('hidden');
        }

        addressDebounceTimer = setTimeout(() => {
            searchAddressNominatim(true);
        }, 1500);
    });
}

function useCurrentLocation() {
    if (!navigator.geolocation) {
        Swal.fire({ icon: 'warning', title: 'Tidak Didukung', text: 'Perangkat Anda tidak mendukung geolokasi.' });
        return;
    }
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            setDestination(pos.coords.latitude, pos.coords.longitude, true);
            hideAddressSuggestions();
            Swal.fire({
                icon: 'success',
                title: 'Lokasi Terdeteksi',
                text: 'Titik antar berhasil diset sesuai lokasi GPS perangkat Anda.',
                timer: 2500,
                showConfirmButton: false
            });
        },
        (err) => {
            Swal.fire({ icon: 'warning', title: 'Lokasi Gagal Didapat', text: 'Tidak dapat mengakses lokasi perangkat: ' + err.message });
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function handleVoucherCardClick(code) {
    const v = vouchersData.find(x => x.code === code);
    if (!v) return;

    if (selectedVoucher && selectedVoucher.code === code) {
        removeVoucher();
        return;
    }

    const check = checkVoucherEligibility(v);
    if (!check.eligible) {
        Swal.fire({
            icon: 'warning',
            title: 'Syarat Belum Terpenuhi',
            text: `Voucher ${v.code} (${v.name}): ${check.message}`
        });
        return;
    }

    selectedVoucher = v;
    const input = document.getElementById('manualVoucherInput');
    if (input) input.value = v.code;
    updateVouchersState();
    recalculateSummary();
    showVoucherFeedback(`Voucher ${v.code} berhasil digunakan!`, 'success');
}

function applyManualVoucher() {
    const input = document.getElementById('manualVoucherInput');
    const code = (input.value || '').trim().toUpperCase();
    if (!code) {
        showVoucherFeedback('Ketikkan kode voucher terlebih dahulu.', 'warning');
        return;
    }

    const v = vouchersData.find(x => x.code === code);
    if (!v) {
        showVoucherFeedback(`Kode voucher "${code}" tidak ditemukan.`, 'error');
        return;
    }

    const check = checkVoucherEligibility(v);
    if (!check.eligible) {
        showVoucherFeedback(`Voucher ${code}: ${check.message}`, 'error');
        return;
    }

    selectedVoucher = v;
    updateVouchersState();
    recalculateSummary();
    showVoucherFeedback(`Voucher ${v.code} berhasil digunakan!`, 'success');
}

function removeVoucher() {
    selectedVoucher = null;
    const input = document.getElementById('manualVoucherInput');
    if (input) input.value = '';
    updateVouchersState();
    recalculateSummary();
    showVoucherFeedback('Voucher berhasil dibatalkan.', 'info');
}

function toggleDeliveryAddress(show) {
    const container = document.getElementById('deliveryAddressContainer');
    const textarea = container.querySelector('textarea');
    const disclaimerBlock = document.getElementById('pickupDisclaimerBlock');
    const disclaimerCheck = document.getElementById('pickupDisclaimerCheck');
    if (show) {
        container.classList.remove('hidden');
        textarea.setAttribute('required', 'required');
        // Hide disclaimer for delivery
        disclaimerBlock.classList.add('hidden');
        if (disclaimerCheck) disclaimerCheck.removeAttribute('required');
        currentDeliveryMethod = 'delivery';
        initDeliveryMap();
        setupAddressAutoDetect();
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 150);
    } else {
        container.classList.add('hidden');
        textarea.removeAttribute('required');
        // Show disclaimer for pickup
        disclaimerBlock.classList.remove('hidden');
        if (disclaimerCheck) disclaimerCheck.setAttribute('required', 'required');
        currentDeliveryMethod = 'pickup';
    }
    updateVouchersState();
    recalculateSummary();
}

const pickupInput   = document.getElementById('pickupDateInput');
const timeSelect    = document.getElementById('requestedTimeSelect');
const pickupWarning = document.getElementById('pickupDateWarning');
const paymentForm   = document.getElementById('paymentForm');
const disclaimerBlock = document.getElementById('pickupDisclaimerBlock');
const disclaimerCheck = document.getElementById('pickupDisclaimerCheck');

// Update slot jam yang tersedia jika tanggal = hari ini
function updateAvailableTimeSlots() {
    if (!pickupInput || !timeSelect) return;

    const selectedDate = pickupInput.value;
    const minDate = pickupInput.dataset.min; // format YYYY-MM-DD (hari ini)

    let minTimeStr = null;
    if (selectedDate && selectedDate === minDate) {
        const now = new Date();
        now.setHours(now.getHours() + ORDER_LEAD_TIME_HOURS);
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        minTimeStr = `${h}:${m}`;
    }

    let availableCount = 0;
    let selectedOptionDisabled = false;

    Array.from(timeSelect.options).forEach(opt => {
        if (!opt.value) return; // skip placeholder option

        if (minTimeStr && opt.value < minTimeStr) {
            opt.disabled = true;
            opt.hidden = true;
            if (opt.selected) {
                selectedOptionDisabled = true;
            }
        } else {
            opt.disabled = false;
            opt.hidden = false;
            availableCount++;
        }
    });

    if (selectedOptionDisabled) {
        timeSelect.value = '';
        updateDeadlineExample();
    }

    const noSlotWarning = document.getElementById('noTimeSlotWarning');
    if (noSlotWarning) {
        if (selectedDate && selectedDate === minDate && availableCount === 0) {
            noSlotWarning.classList.remove('hidden');
        } else {
            noSlotWarning.classList.add('hidden');
        }
    }
}

// Show disclaimer on load & init time slots
(function initDisclaimer() {
    const methodRadios = document.querySelectorAll('input[name="delivery_method"]');
    let currentMethod = 'pickup';
    methodRadios.forEach(r => { if (r.checked) currentMethod = r.value; });
    currentDeliveryMethod = currentMethod;
    if (currentMethod === 'pickup') {
        disclaimerBlock.classList.remove('hidden');
        if (disclaimerCheck) disclaimerCheck.setAttribute('required', 'required');
    } else if (currentMethod === 'delivery') {
        toggleDeliveryAddress(true);
    }
    setupAddressAutoDetect();
    updateAvailableTimeSlots();
    updateVouchersState();
    recalculateSummary();
})();

// Update example deadline text when time changes
function updateDeadlineExample() {
    if (!timeSelect || !timeSelect.value) return;
    const exampleTime     = document.getElementById('exampleTime');
    const exampleDeadline = document.getElementById('exampleDeadline');
    if (!exampleTime || !exampleDeadline) return;

    const [h, m] = timeSelect.value.split(':').map(Number);
    const totalMins  = h * 60 + m + PICKUP_DEADLINE_HOURS * 60;
    const dh = Math.floor(totalMins / 60) % 24;
    const dm = totalMins % 60;
    exampleTime.textContent     = timeSelect.value + ' WIB';
    exampleDeadline.textContent = String(dh).padStart(2,'0') + ':' + String(dm).padStart(2,'0') + ' WIB';
}

if (timeSelect) {
    timeSelect.addEventListener('change', updateDeadlineExample);
}

function setQuickDate(dateStr) {
    if (!pickupInput) return;
    pickupInput.value = dateStr;
    validatePickup();
}

function updateQuickDateButtonsActive(currentVal) {
    document.querySelectorAll('.quick-date-btn').forEach(btn => {
        const btnDate = btn.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
        if (btnDate === currentVal) {
            btn.classList.add('bg-pink-600', 'text-white', 'border-pink-600', 'shadow-sm');
            btn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
        } else {
            btn.classList.remove('bg-pink-600', 'text-white', 'border-pink-600', 'shadow-sm');
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
        }
    });
}

if (pickupInput) {
    const minDate = pickupInput.dataset.min;
    const maxDate = pickupInput.dataset.max;

    var validatePickup = () => {
        if (pickupInput.value && minDate && pickupInput.value < minDate) {
            pickupWarning.textContent = 'Tanggal tidak boleh di masa lalu.';
            pickupWarning.classList.remove('hidden');
            pickupInput.value = minDate;
        } else if (pickupInput.value && maxDate && pickupInput.value > maxDate) {
            pickupWarning.textContent = 'Pemesanan maksimal H+2 (2 hari ke depan).';
            pickupWarning.classList.remove('hidden');
            pickupInput.value = maxDate;
        } else {
            pickupWarning.classList.add('hidden');
        }
        updateAvailableTimeSlots();
        updateQuickDateButtonsActive(pickupInput.value);
    };

    pickupInput.addEventListener('change', validatePickup);
    pickupInput.addEventListener('input', validatePickup);
    pickupInput.addEventListener('blur', validatePickup);
    pickupInput.addEventListener('focus', () => {
        if (!pickupInput.value && minDate) {
            pickupInput.value = minDate;
        }
        updateAvailableTimeSlots();
        updateQuickDateButtonsActive(pickupInput.value);
    });

    if (pickupInput.value) {
        updateQuickDateButtonsActive(pickupInput.value);
    }
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

            // Validasi disclaimer pickup secara manual (checkbox required tapi di luar form flow normal)
            const method = document.querySelector('input[name="delivery_method"]:checked')?.value;
            if (method === 'pickup' && disclaimerCheck && !disclaimerCheck.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Persetujuan Diperlukan',
                    text: 'Anda wajib menyetujui ketentuan pengambilan pesanan sebelum checkout.'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }

            if (method === 'delivery') {
                if (!deliveryLat || !deliveryLng) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Titik Lokasi Belum Dipilih',
                        text: 'Silakan tentukan titik lokasi pengantaran Anda pada peta.'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }
                if (isOutsideRadius || (deliveryDistanceKm !== null && deliveryDistanceKm > STORE_MAX_RADIUS)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Di Luar Jangkauan Toko',
                        text: `Lokasi pengiriman Anda (${deliveryDistanceKm} km) di luar batas jangkauan pengantaran toko (maksimal ${STORE_MAX_RADIUS} km). Silakan pilih metode Ambil di Toko.`
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }
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
