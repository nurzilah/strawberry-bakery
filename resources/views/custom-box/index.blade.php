@extends('layouts.app')

@section('title', 'Buat Paket Custom')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        [x-cloak] { display: none !important; }
        .box-card {
            background-color: #fff;
            border: 2px dashed #fbcfe8;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        .box-card:hover {
            border-color: #f472b6;
        }
        .product-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
        }
        #customBoxDeliveryMap {
            height: 280px;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
<div class="py-5 bg-pink-50 min-h-screen" x-data="customBox()">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Buat Paket Roti Anda Sendiri</h1>
            <p class="text-gray-600">Pilih isi roti untuk masing-masing box. Beli banyak box bisa dapat harga khusus atau gratis!</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                
                <template x-for="(box, boxIndex) in boxes" :key="box.id">
                    <div class="box-card p-5 relative shadow-sm">
                        <div class="flex justify-between items-center mb-4 pb-3 border-b border-pink-100">
                            <h2 class="text-xl font-bold text-pink-600">
                                Box <span x-text="boxIndex + 1"></span>
                            </h2>
                            <button @click="removeBox(boxIndex)" x-show="boxes.length > 1" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-trash me-1"></i> Hapus Box
                            </button>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">
                            Jenis roti dipilih: <strong x-text="countSelectedTypes(box)"></strong> dari minimal 3 jenis
                        </p>
                        <p x-show="countSelectedTypes(box) < minimumProductTypes" class="text-sm font-semibold text-red-600 mb-3" role="alert">
                            Box <span x-text="boxIndex + 1"></span> baru berisi <span x-text="countSelectedTypes(box)"></span> jenis roti, minimal 3 jenis.
                        </p>

                        <div class="space-y-3 mb-4">
                            <template x-for="(item, itemIndex) in box.items" :key="item.id">
                                <div class="product-item p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="text-xs font-bold text-gray-700">
                                            Pilihan Roti <span x-text="itemIndex + 1"></span>:
                                            <span x-show="itemIndex < minimumProductTypes" class="text-pink-600 font-semibold">(Wajib)</span>
                                            <span x-show="itemIndex >= minimumProductTypes" class="text-gray-500 font-normal">(Tambahan)</span>
                                        </span>
                                        <button type="button" 
                                                x-show="box.items.length > minimumProductTypes" 
                                                @click="removeItem(boxIndex, itemIndex)" 
                                                class="text-red-400 hover:text-red-600 text-xs flex items-center gap-1"
                                                title="Hapus roti tambahan ini">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="flex justify-between items-center gap-2">
                                        <div class="flex-1">
                                            <select x-model="item.product_id" @change="selectProduct(boxIndex, itemIndex)" class="form-select border-gray-300 rounded-md shadow-sm w-full text-sm">
                                                <option value="">-- Pilih Jenis Roti --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" :disabled="isProductSelectedInOtherItem(boxIndex, itemIndex, {{ (int) $product->id }})">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                            <p x-show="item.error" x-text="item.error" class="mt-1 mb-0 text-xs text-red-600" role="alert"></p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" @click="if(item.quantity > 1) item.quantity--" class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 hover:bg-pink-200 flex items-center justify-center font-bold">-</button>
                                            <input type="number" x-model.number="item.quantity" class="form-control text-center w-14 h-8 text-sm p-1" min="1" readonly>
                                            <button type="button" @click="item.quantity++" class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 hover:bg-pink-200 flex items-center justify-center font-bold">+</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button @click="addItem(boxIndex)" class="w-full py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-lg hover:border-pink-400 hover:text-pink-500 transition-colors text-sm font-medium">
                            <i class="fas fa-plus me-1"></i> Tambah Roti ke Box Ini
                        </button>
                        
                        <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Subtotal Box:</span>
                            <span class="text-lg font-bold text-gray-800">Rp <span x-text="formatMoney(calculateBoxTotal(boxIndex))"></span></span>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Jumlah Box:</span>
                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        x-show="canDecreaseBox(box)" 
                                        x-cloak 
                                        @click="if(canDecreaseBox(box)) box.quantity--" 
                                        aria-label="Kurangi jumlah box" 
                                        class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 hover:bg-pink-200 flex items-center justify-center transition-all">
                                    -
                                </button>
                                <input type="number" x-model.number="box.quantity" :min="boxes.length === 1 ? minimumBoxes : 1" readonly aria-label="Jumlah box" class="form-control text-center w-16 h-8 text-sm p-1">
                                <button type="button" @click="box.quantity++" aria-label="Tambah jumlah box" class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 hover:bg-pink-200 flex items-center justify-center">+</button>
                            </div>
                        </div>
                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <p class="text-gray-700 font-medium text-sm mb-2">Tambahan Air Mineral per Box (opsional)</p>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click="box.beverage_type = ''"
                                        :class="!box.beverage_type ? 'bg-pink-600 text-white border-pink-600 shadow-sm ring-2 ring-pink-300 font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300'"
                                        class="rounded-lg border px-2 py-2 text-xs font-semibold transition-all flex items-center justify-center">
                                    <i class="fas fa-check me-1 text-xs" x-show="!box.beverage_type"></i>
                                    Tanpa
                                </button>
                                <button type="button" @click="box.beverage_type = 'gelas'"
                                        :class="box.beverage_type === 'gelas' ? 'bg-pink-600 text-white border-pink-600 shadow-sm ring-2 ring-pink-300 font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300'"
                                        class="rounded-lg border px-2 py-2 text-xs font-semibold transition-all flex items-center justify-center">
                                    <i class="fas fa-check me-1 text-xs" x-show="box.beverage_type === 'gelas'"></i>
                                    Gelas - Rp500
                                </button>
                                <button type="button" @click="box.beverage_type = 'botol'"
                                        :class="box.beverage_type === 'botol' ? 'bg-pink-600 text-white border-pink-600 shadow-sm ring-2 ring-pink-300 font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300'"
                                        class="rounded-lg border px-2 py-2 text-xs font-semibold transition-all flex items-center justify-center">
                                    <i class="fas fa-check me-1 text-xs" x-show="box.beverage_type === 'botol'"></i>
                                    Botol - Rp2.000
                                </button>
                            </div>
                            <p class="mt-2 mb-0 text-xs text-gray-500">
                                <span x-show="!box.beverage_type">
                                    <i class="fas fa-info-circle me-1 text-gray-400"></i> Tanpa tambahan air mineral untuk box ini.
                                </span>
                                <span x-show="box.beverage_type">
                                    <i class="fas fa-check-circle me-1 text-pink-600"></i>
                                    <span x-text="box.beverage_type === 'gelas' ? 'Air mineral gelas' : 'Air mineral botol'"></span>
                                    sebanyak <span x-text="box.quantity"></span> mengikuti jumlah box.
                                </span>
                            </p>
                        </div>
                    </div>
                </template>

                <button @click="addBox()" class="w-full py-4 bg-white border-2 border-dashed border-pink-300 text-pink-600 rounded-xl hover:bg-pink-50 transition-colors font-bold text-lg shadow-sm">
                    <i class="fas fa-box-open me-2"></i> Tambah Box Baru
                </button>

                {{-- === PILIH VOUCHER SECTION === --}}
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-pink-100">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-sm">
                                <i class="fas fa-ticket-alt"></i>
                            </span>
                            <h2 class="text-xl font-bold text-gray-800">Pilih Voucher</h2>
                        </div>
                        <button type="button" @click="removeVoucher()" x-show="selectedVoucherCode" x-cloak class="text-xs text-red-500 hover:text-red-700 font-semibold">
                            <i class="fas fa-times me-1"></i> Batal Pakai
                        </button>
                    </div>

                    {{-- Manual Input Voucher --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Punya kode voucher khusus?</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="manualVoucherInput" placeholder="Contoh: WELCOME15, HEMAT25" class="form-control text-sm uppercase flex-1 rounded-lg">
                            <button type="button" @click="applyManualVoucher()" class="btn btn-outline-strawberry px-4 py-2 text-sm font-semibold rounded-lg">
                                Terapkan
                            </button>
                        </div>
                        <p x-show="voucherFeedback.message" x-cloak class="text-xs mt-1.5 font-medium"
                           :class="{
                               'text-green-600': voucherFeedback.type === 'success',
                               'text-red-600': voucherFeedback.type === 'error',
                               'text-orange-600': voucherFeedback.type === 'warning',
                               'text-gray-600': voucherFeedback.type === 'info'
                           }"
                           x-text="voucherFeedback.message"></p>
                    </div>

                    {{-- List Kartu Pilihan Voucher --}}
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Voucher Tersedia:</label>
                    <div class="space-y-3">
                        <template x-for="v in vouchers" :key="v.code">
                            <div class="p-3 rounded-xl border transition-all cursor-pointer relative"
                                 :class="{
                                     'border-pink-600 bg-pink-50 ring-1 ring-pink-500': selectedVoucherCode === v.code,
                                     'border-gray-200 bg-white hover:border-pink-300': selectedVoucherCode !== v.code && isVoucherEligible(v),
                                     'border-gray-200 bg-gray-50 opacity-75': !isVoucherEligible(v)
                                 }"
                                 @click="selectVoucher(v.code)">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 flex-1">
                                        <input type="radio" name="package_voucher_radio" :value="v.code" :checked="selectedVoucherCode === v.code" class="mt-1 accent-pink-600 pointer-events-none">
                                        <div>
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-sm text-gray-800" x-text="v.name"></span>
                                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-pink-100 text-pink-700 uppercase" x-text="v.code"></span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 mb-0" x-text="v.description"></p>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span x-show="isVoucherEligible(v)" class="px-2.5 py-1 rounded-full text-xs font-semibold inline-block bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle me-1"></i> Memenuhi Syarat
                                        </span>
                                        <span x-show="!isVoucherEligible(v)" class="px-2.5 py-1 rounded-full text-xs font-semibold inline-block bg-gray-100 text-gray-500">
                                            <i class="fas fa-info-circle me-1"></i> <span x-text="voucherIneligibilityReason(v)"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-2 mb-4 text-gray-600 text-sm">
                        <div class="flex justify-between">
                            <span>Total Box:</span>
                            <span class="font-semibold text-gray-800"><span x-text="totalBoxCount"></span> Box</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span class="font-semibold text-gray-800">Rp <span x-text="formatMoney(calculateGrandTotal())"></span></span>
                        </div>
                        <div x-show="totalBeverageCount('gelas') > 0" class="flex justify-between text-xs text-gray-500">
                            <span>Air Mineral Gelas:</span>
                            <span><span x-text="totalBeverageCount('gelas')"></span> x Rp500</span>
                        </div>
                        <div x-show="totalBeverageCount('botol') > 0" class="flex justify-between text-xs text-gray-500">
                            <span>Air Mineral Botol:</span>
                            <span><span x-text="totalBeverageCount('botol')"></span> x Rp2.000</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkir:</span>
                            <span class="font-semibold text-gray-800">
                                <span x-show="deliveryMethod === 'pickup'">Rp 0 (Ambil di Toko)</span>
                                <span x-show="deliveryMethod === 'delivery'">
                                    <span x-text="'Rp ' + formatMoney(shippingCost)"></span>
                                    <span x-show="distanceKm !== null" class="text-xs text-gray-500" x-text="' (' + distanceKm + ' km)'"></span>
                                </span>
                            </span>
                        </div>
                        <div x-show="promoDiscount > 0" x-cloak class="flex justify-between text-green-700 font-semibold">
                            <span>Diskon Promo Box:</span>
                            <span>- Rp <span x-text="formatMoney(promoDiscount)"></span></span>
                        </div>
                        <div x-show="voucherDiscount > 0" x-cloak class="flex justify-between text-pink-700 bg-pink-50 p-2 rounded-lg border border-pink-200">
                            <div>
                                <span class="font-semibold block text-xs">Diskon Voucher:</span>
                                <span class="text-xs font-mono font-bold" x-text="selectedVoucherCode"></span>
                            </div>
                            <span class="font-bold self-center">- Rp <span x-text="formatMoney(voucherDiscount)"></span></span>
                        </div>
                        <div class="text-xs text-pink-500 italic mt-2" x-show="promoDiscount === 0 && voucherDiscount === 0">* Diskon/Gratis Box akan dihitung otomatis jika memenuhi syarat promo/voucher.</div>
                        <div x-show="totalBoxCount < minimumBoxes" class="mt-3 rounded-lg border border-red-200 bg-red-50 p-3 text-sm font-semibold text-red-700" role="status">
                            Minimal pembelian 10 box, saat ini baru <span x-text="totalBoxCount"></span> box.
                        </div>
                        <div x-show="eligibleBonus" x-cloak class="mt-3 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-800" role="status">
                            <strong>Bonus promo:</strong> <span x-text="eligibleBonus ? eligibleBonus.bonus_item_qty : 0"></span>
                            <span x-text="eligibleBonus ? eligibleBonus.bonus_item_name : ''"></span>
                            <span x-text="eligibleBonus ? '(syarat ' + eligibleBonus.min_qty + ' box terpenuhi)' : ''"></span>
                        </div>
                        <hr class="my-3 border-gray-200">
                        <div class="flex justify-between text-base font-bold text-gray-800">
                            <span>Total Akhir:</span>
                            <span class="text-pink-600 text-lg font-extrabold">Rp <span x-text="formatMoney(finalPayableAmount)"></span></span>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200">

                    <form id="packageCheckoutForm" @submit.prevent="submitCheckout">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Metode Pengiriman:</label>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" x-model="deliveryMethod" value="pickup" class="accent-pink-500" required>
                                    <span class="text-sm">Ambil di Toko (Pickup)</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" x-model="deliveryMethod" value="delivery" class="accent-pink-500" required>
                                    <span class="text-sm">Kirim ke Alamat (Delivery)</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="deliveryMethod === 'delivery'" class="mb-4" style="display: none;">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Alamat Pengiriman:</label>
                            <textarea x-model="deliveryAddress" @input="onAddressInput()" class="form-control w-full text-sm" rows="3" placeholder="Masukkan alamat lengkap (misal: Jl. Kartini No. 12, Kel. Mangkukusuman, Tegal)"></textarea>

                            {{-- Dropdown Rekomendasi/Saran Alamat Nominatim --}}
                            <div x-show="suggestions.length > 0" x-cloak class="my-2 bg-white rounded-xl border border-pink-200 shadow-md overflow-hidden z-20">
                                <div class="py-1.5 px-3 bg-pink-50 border-b border-pink-100 flex items-center justify-between text-xs text-pink-700 font-semibold">
                                    <span><i class="fas fa-map-marker-alt me-1"></i> Saran Lokasi Ditemukan (Klik untuk ubah titik):</span>
                                    <button type="button" @click="suggestions = []" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="max-h-48 overflow-y-auto divide-y divide-gray-100 text-xs text-gray-700">
                                    <template x-for="(sug, sIdx) in suggestions" :key="sug.place_id || sIdx">
                                        <button type="button" @click="selectCustomBoxSuggestion(sug)" class="w-full text-left p-2.5 hover:bg-pink-50 transition flex items-start gap-2" :class="sIdx === 0 ? 'bg-pink-50/40 font-medium' : ''">
                                            <i class="fas fa-map-pin text-pink-500 mt-1 flex-shrink-0 text-xs"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-800 text-xs mb-0.5">
                                                    <span x-text="sug.name || (sug.display_name ? sug.display_name.split(',')[0] : '')"></span>
                                                    <span x-show="sIdx === 0" class="text-[10px] text-pink-600 bg-pink-100 px-1.5 py-0.5 rounded ml-1 font-normal">Pilihan Teratas</span>
                                                </p>
                                                <p class="text-[11px] text-gray-500 truncate mb-0" x-text="sug.display_name"></p>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <button type="button" @click="searchCustomBoxNominatim(false)" :disabled="isSearchingLocation" class="btn btn-outline-strawberry text-xs py-1.5 px-3 rounded-lg font-semibold flex items-center gap-1.5">
                                    <i class="fas fa-search-location"></i> Cari Titik dari Alamat
                                </button>
                                <button type="button" @click="useCurrentLocation()" class="btn btn-outline-secondary text-xs py-1.5 px-3 rounded-lg font-semibold flex items-center gap-1.5">
                                    <i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya
                                </button>
                                <span x-show="isSearchingLocation" x-cloak class="text-xs text-pink-600 font-medium">
                                    <i class="fas fa-spinner fa-spin me-1"></i> <span x-text="searchingLocationText || 'Mendeteksi lokasi otomatis...'"></span>
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 mb-2">
                                <i class="fas fa-info-circle me-1 text-pink-500"></i> Ketik alamat di atas (otomatis mendeteksi titik setelah selesai mengetik), atau geser pin biru langsung pada peta.
                            </p>

                            {{-- Leaflet Map Container --}}
                            <div id="customBoxDeliveryMap" class="w-full my-3"></div>

                            {{-- Delivery Distance & Cost Info Badge --}}
                            <div x-show="deliveryLat && deliveryLng" x-cloak class="p-3 bg-pink-50 border border-pink-200 rounded-xl text-xs space-y-1.5">
                                <div class="flex items-center justify-between font-semibold">
                                    <span class="text-gray-700"><i class="fas fa-route text-pink-600 me-1"></i> Jarak Pengiriman:</span>
                                    <span class="text-pink-700 text-sm font-bold" x-text="distanceKm !== null ? (distanceKm + ' km') : '-'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Tarif Ongkos Kirim:</span>
                                    <span class="font-bold text-gray-800" x-text="'Rp ' + formatMoney(shippingCost)"></span>
                                </div>
                                <div class="text-[11px] text-gray-500 flex items-center justify-between">
                                    <span>Titik Koordinat Antar:</span>
                                    <span class="font-mono text-gray-600" x-text="deliveryLat ? (deliveryLat.toFixed(5) + ', ' + deliveryLng.toFixed(5)) : '-'"></span>
                                </div>
                                <div x-show="isOutsideRadius" x-cloak class="mt-2 p-2 rounded-lg bg-red-100 border border-red-300 text-red-700 font-semibold text-xs">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Lokasi pengiriman (<span x-text="distanceKm"></span> km) di luar batas jangkauan pengantaran toko (maksimal 25 km). Silakan pilih metode <strong>Ambil di Toko (Pickup)</strong> atau tentukan titik antar lain.
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal & Jam Pengambilan --}}
                        @php
                            $minPickup   = \Carbon\Carbon::today()->format('Y-m-d');
                            $maxPickup   = \Carbon\Carbon::today()->addDays(2)->format('Y-m-d');
                            $timeSlots   = \App\Helpers\StoreHoursHelper::generateTimeSlots();
                            $deadlineHrs = config('store.pickup_deadline_hours', 2);
                        @endphp
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">Tanggal &amp; Jam Pengambilan/Pengantaran:</label>

                            {{-- Quick Option Buttons untuk Pemilihan Tanggal --}}
                            <div class="mb-2">
                                <label class="block text-xs text-gray-500 mb-1 font-medium">Pilihan Cepat Tanggal:</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" 
                                            @click="requestedDate = '{{ \Carbon\Carbon::today()->format('Y-m-d') }}'; updateAvailableTimeSlots()"
                                            :class="requestedDate === '{{ \Carbon\Carbon::today()->format('Y-m-d') }}' ? 'bg-pink-600 text-white border-pink-600 shadow-sm font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300 font-semibold'"
                                            class="py-1.5 px-2 text-xs rounded-lg border transition-all text-center">
                                        Hari Ini
                                    </button>
                                    <button type="button" 
                                            @click="requestedDate = '{{ \Carbon\Carbon::today()->addDays(1)->format('Y-m-d') }}'; updateAvailableTimeSlots()"
                                            :class="requestedDate === '{{ \Carbon\Carbon::today()->addDays(1)->format('Y-m-d') }}' ? 'bg-pink-600 text-white border-pink-600 shadow-sm font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300 font-semibold'"
                                            class="py-1.5 px-2 text-xs rounded-lg border transition-all text-center">
                                        Besok
                                    </button>
                                    <button type="button" 
                                            @click="requestedDate = '{{ \Carbon\Carbon::today()->addDays(2)->format('Y-m-d') }}'; updateAvailableTimeSlots()"
                                            :class="requestedDate === '{{ \Carbon\Carbon::today()->addDays(2)->format('Y-m-d') }}' ? 'bg-pink-600 text-white border-pink-600 shadow-sm font-bold' : 'bg-white text-gray-700 border-gray-300 hover:bg-pink-50 hover:border-pink-300 font-semibold'"
                                            class="py-1.5 px-2 text-xs rounded-lg border transition-all text-center">
                                        Lusa (H+2)
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Tanggal (Maks H+2)</label>
                                    <input type="date" x-model="requestedDate" id="pickupDateInput" required
                                           min="{{ $minPickup }}"
                                           max="{{ $maxPickup }}"
                                           data-min="{{ $minPickup }}"
                                           data-max="{{ $maxPickup }}"
                                           @change="updateAvailableTimeSlots()"
                                           class="form-control text-sm w-full">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Jam</label>
                                    <select x-model="requestedTime" id="requestedTimeSelect" required
                                            @change="updateDeadlineExample()"
                                            class="form-select text-sm w-full">
                                        <option value="" disabled selected>-- Pilih Jam --</option>
                                        @foreach($timeSlots as $timeSlot)
                                            <option value="{{ $timeSlot }}">{{ $timeSlot }} WIB</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p id="noTimeSlotWarning" class="text-xs text-red-600 mt-2 hidden">
                                ⚠ Tidak ada slot jam yang tersedia untuk hari ini (sudah melebihi jam operasional / batas waktu persiapan toko). Silakan pilih tanggal besok.
                            </p>
                        </div>

                        {{-- Disclaimer Pickup --}}
                        <div x-show="deliveryMethod === 'pickup'" class="mb-4">
                            <div class="rounded-xl border border-orange-300 bg-orange-50 p-3">
                                <p class="font-bold text-orange-700 text-xs mb-1">⚠ Ketentuan Pengambilan (Pickup)</p>
                                <p class="text-xs text-orange-700 leading-relaxed mb-2">
                                    Pesanan pickup <strong>wajib diambil paling lambat {{ $deadlineHrs }} jam</strong> setelah jam yang dipilih.
                                    Lewat dari batas tersebut, toko tidak bertanggung jawab atas pesanan.
                                </p>
                                <p class="text-xs text-orange-600 mb-2">
                                    Contoh: jam <span id="exampleTime" class="font-semibold">--:--</span> → deadline sebelum <span id="exampleDeadline" class="font-semibold">--:--</span>.
                                </p>
                                <label class="flex items-start gap-2 cursor-pointer mt-2 pt-2 border-t border-orange-200">
                                    <input type="checkbox" x-model="pickupDisclaimerAgreed" class="mt-0.5 rounded border-orange-400 accent-orange-500">
                                    <span class="text-xs text-orange-800 font-medium">
                                        Saya menyetujui ketentuan pengambilan pesanan di atas.
                                    </span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" :disabled="isSubmitting || !isValid()" class="w-full py-3 bg-pink-600 hover:bg-pink-700 text-white rounded-xl font-bold transition-colors disabled:opacity-50 flex justify-center items-center">
                            <span x-show="!isSubmitting">Checkout Sekarang</span>
                            <span x-show="isSubmitting"><i class="fas fa-spinner fa-spin me-2"></i> Memproses...</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Data for Alpine -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    const productsData = @json($products->keyBy('id')->map(function($p) { return ['id' => $p->id, 'name' => $p->name, 'price' => $p->price]; }));
    const packagePromos = @json($packagePromos->values());
    const vouchersData = @json($vouchers->values());
    const isFirstOrderUser = @json($isFirstOrder);
    const PICKUP_DEADLINE_HOURS = {{ config('store.pickup_deadline_hours', 2) }};
    const ORDER_LEAD_TIME_HOURS = {{ config('store.order_lead_time_hours', 1) }};

    const STORE_CONFIG = {
        lat: {{ config('store.lat', -6.86985) }},
        lng: {{ config('store.lng', 109.13615) }},
        name: @json(config('store.store_name', 'Strawberry Bakery')),
        baseShipping: {{ config('store.base_shipping_cost', 10000) }},
        costPerKm: {{ config('store.cost_per_km', 2500) }},
        maxRadius: {{ config('store.max_delivery_radius_km', 25.0) }},
        roadCorrection: {{ config('store.road_correction_factor', 1.3) }}
    };
</script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    function initCustomBoxComponent() {
        if (!window.Alpine) return;
        Alpine.data('customBox', () => ({
            boxes: [
                { id: 1, quantity: 10, items: [
                    { id: 1, product_id: '', quantity: 1, price: 0 },
                    { id: 2, product_id: '', quantity: 1, price: 0 },
                    { id: 3, product_id: '', quantity: 1, price: 0 }
                ], beverage_type: '' }
            ],
            nextBoxId: 2,
            nextItemId: 4,
            deliveryMethod: 'pickup',
            deliveryAddress: '',
            requestedDate: '{{ \Carbon\Carbon::today()->format('Y-m-d') }}',
            requestedTime: '',
            pickupDisclaimerAgreed: false,
            isSubmitting: false,
            minimumBoxes: 10,
            minimumProductTypes: 3,

            // Delivery Map & Location State
            deliveryLat: null,
            deliveryLng: null,
            distanceKm: null,
            isOutsideRadius: false,
            isSearchingLocation: false,
            searchingLocationText: '',
            suggestions: [],
            addressDebounceTimer: null,
            map: null,
            storeMarker: null,
            customerMarker: null,
            circle10km: null,
            circle25km: null,

            // Voucher State
            vouchers: vouchersData,
            isFirstOrder: isFirstOrderUser,
            selectedVoucherCode: '',
            manualVoucherInput: '',
            voucherFeedback: { type: '', message: '' },

            get currentVoucher() {
                if (!this.selectedVoucherCode) return null;
                return this.vouchers.find(v => v.code === this.selectedVoucherCode) || null;
            },

            calculateDistance(lat, lng) {
                const R = 6371.0;
                const toRad = deg => (deg * Math.PI) / 180;
                const lat1 = toRad(STORE_CONFIG.lat);
                const lon1 = toRad(STORE_CONFIG.lng);
                const lat2 = toRad(lat);
                const lon2 = toRad(lng);

                const dLat = lat2 - lat1;
                const dLon = lon2 - lon1;

                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                          Math.cos(lat1) * Math.cos(lat2) *
                          Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                const straight = R * c;
                const road = straight * STORE_CONFIG.roadCorrection;
                return Math.round(road * 10) / 10;
            },

            initMap() {
                if (this.map) return;
                const mapEl = document.getElementById('customBoxDeliveryMap');
                if (!mapEl) return;

                this.map = L.map('customBoxDeliveryMap').setView([STORE_CONFIG.lat, STORE_CONFIG.lng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map);

                const storePinIcon = L.divIcon({
                    className: 'custom-store-pin',
                    html: '<div style="background-color: #db2777; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="fas fa-store" style="transform: rotate(45deg); color: #fff; font-size: 14px;"></i></div>',
                    iconSize: [34, 34],
                    iconAnchor: [17, 34],
                    popupAnchor: [0, -34]
                });

                this.storeMarker = L.marker([STORE_CONFIG.lat, STORE_CONFIG.lng], { icon: storePinIcon })
                    .addTo(this.map)
                    .bindPopup(`<b>${STORE_CONFIG.name}</b><br>Jl. AR. Hakim No. 149, Mangkukusuman, Tegal`);

                this.circle10km = L.circle([STORE_CONFIG.lat, STORE_CONFIG.lng], {
                    radius: 10000,
                    color: '#db2777',
                    fillColor: '#f472b6',
                    fillOpacity: 0.1,
                    weight: 2,
                    dashArray: '5, 5'
                }).addTo(this.map).bindPopup('<b>Zona Gratis Ongkir (Maks 10 KM)</b><br>Khusus voucher ONGKIR10KM');

                this.circle25km = L.circle([STORE_CONFIG.lat, STORE_CONFIG.lng], {
                    radius: 25000,
                    color: '#ef4444',
                    fillColor: '#f87171',
                    fillOpacity: 0.05,
                    weight: 1.5
                }).addTo(this.map).bindPopup('<b>Batas Jangkauan Toko (Maks 25 KM)</b><br>Meliputi Kota Tegal, Slawi, dan Brebes');

                this.map.on('click', (e) => {
                    this.setDestination(e.latlng.lat, e.latlng.lng, false);
                });

                this.setDestination(STORE_CONFIG.lat, STORE_CONFIG.lng, false);
            },

            setDestination(lat, lng, panTo = true) {
                this.deliveryLat = parseFloat(lat);
                this.deliveryLng = parseFloat(lng);
                this.distanceKm = this.calculateDistance(lat, lng);
                this.isOutsideRadius = this.distanceKm > STORE_CONFIG.maxRadius;

                if (!this.customerMarker && this.map) {
                    const customerPinIcon = L.divIcon({
                        className: 'custom-customer-pin',
                        html: '<div style="background-color: #2563eb; width: 34px; height: 34px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="fas fa-map-marker-alt" style="transform: rotate(45deg); color: #fff; font-size: 16px;"></i></div>',
                        iconSize: [34, 34],
                        iconAnchor: [17, 34],
                        popupAnchor: [0, -34]
                    });
                    this.customerMarker = L.marker([lat, lng], { icon: customerPinIcon, draggable: true }).addTo(this.map);
                    this.customerMarker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.setDestination(pos.lat, pos.lng, false);
                    });
                } else if (this.customerMarker) {
                    this.customerMarker.setLatLng([lat, lng]);
                }

                if (this.customerMarker) {
                    this.customerMarker.bindPopup(`<b>Titik Pengantaran Anda</b><br>Jarak: ${this.distanceKm} km<br><small class="text-gray-500">(Bisa digeser ke titik yang pas)</small>`).openPopup();
                }

                if (panTo && this.map) {
                    this.map.panTo([lat, lng]);
                }

                // Auto deselect ONGKIR10KM if distance > 10 km
                if (this.selectedVoucherCode === 'ONGKIR10KM' && this.distanceKm > 10.0) {
                    this.removeVoucher();
                    this.voucherFeedback = {
                        type: 'warning',
                        message: `Voucher ONGKIR10KM dilepas karena jarak pengiriman (${this.distanceKm} km) melebihi 10 km.`
                    };
                }
            },

            cleanAddressForGeocoding(rawAddress) {
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
            },

            async queryNominatimWithFallbacks(rawAddress) {
                const cleaned = this.cleanAddressForGeocoding(rawAddress);
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
            },

            onAddressInput() {
                clearTimeout(this.addressDebounceTimer);
                const val = (this.deliveryAddress || '').trim();
                if (val.length < 10) {
                    this.suggestions = [];
                    this.isSearchingLocation = false;
                    this.searchingLocationText = '';
                    return;
                }
                this.isSearchingLocation = true;
                this.searchingLocationText = 'Mengetik...';

                this.addressDebounceTimer = setTimeout(() => {
                    this.searchCustomBoxNominatim(true);
                }, 1500);
            },

            async searchCustomBoxNominatim(isAuto = false) {
                const query = (this.deliveryAddress || '').trim();
                if (!query || query.length < 10) {
                    if (!isAuto) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alamat Terlalu Singkat',
                            text: 'Silakan ketik alamat pengiriman lebih lengkap (minimal 10 karakter).'
                        });
                    }
                    this.suggestions = [];
                    this.isSearchingLocation = false;
                    this.searchingLocationText = '';
                    return;
                }

                this.isSearchingLocation = true;
                this.searchingLocationText = isAuto ? 'Mendeteksi lokasi otomatis...' : 'Mencari koordinat...';

                try {
                    const { results, queryUsed } = await this.queryNominatimWithFallbacks(query);

                    if (results && results.length > 0) {
                        const first = results[0];
                        this.setDestination(parseFloat(first.lat), parseFloat(first.lon), true);
                        this.suggestions = results;

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
                        this.suggestions = [];
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
                        Swal.fire({ icon: 'error', title: 'Gagal Mencari Alamat', text: 'Terjadi gangguan saat mencari alamat. Silakan klik langsung titik lokasi pada peta.' });
                    }
                } finally {
                    this.isSearchingLocation = false;
                    this.searchingLocationText = '';
                }
            },

            selectCustomBoxSuggestion(sug) {
                if (!sug) return;
                this.setDestination(parseFloat(sug.lat), parseFloat(sug.lon), true);
                this.suggestions = [];
                const shortName = (sug.display_name || '').split(',')[0];
                this.voucherFeedback = {
                    type: 'info',
                    message: `Titik lokasi diatur ke: ${shortName}`
                };
            },

            useCurrentLocation() {
                if (!navigator.geolocation) {
                    Swal.fire({ icon: 'warning', title: 'Tidak Didukung', text: 'Perangkat Anda tidak mendukung geolokasi.' });
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        this.setDestination(pos.coords.latitude, pos.coords.longitude, true);
                        this.suggestions = [];
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
            },

            isVoucherEligible(voucher) {
                if (!voucher || !voucher.is_active) return false;
                if (voucher.first_order_only && !this.isFirstOrder) return false;
                if (voucher.type === 'free_shipping') {
                    if (this.deliveryMethod !== 'delivery') return false;
                    if (this.distanceKm === null) return false;
                    const maxDist = voucher.max_distance_km || 10.0;
                    if (this.distanceKm > maxDist) return false;
                }
                if (voucher.min_subtotal > 0 && this.calculateGrandTotal() < voucher.min_subtotal) return false;
                return true;
            },

            voucherIneligibilityReason(voucher) {
                if (!voucher) return '';
                if (!voucher.is_active) return 'Voucher tidak aktif';
                if (voucher.first_order_only && !this.isFirstOrder) return 'Khusus pesanan pertama Anda';
                if (voucher.type === 'free_shipping') {
                    if (this.deliveryMethod !== 'delivery') return 'Hanya berlaku untuk metode Diantar (Delivery)';
                    if (this.distanceKm === null) return 'Tentukan titik lokasi pada peta';
                    const maxDist = voucher.max_distance_km || 10.0;
                    if (this.distanceKm > maxDist) return `Jarak ${this.distanceKm} km > maks ${maxDist} km`;
                }
                if (voucher.min_subtotal > 0 && this.calculateGrandTotal() < voucher.min_subtotal) {
                    return 'Min. belanja Rp ' + this.formatMoney(voucher.min_subtotal);
                }
                return 'Memenuhi Syarat';
            },

            get promoDiscount() {
                const subtotal = this.calculateGrandTotal();
                const totalBoxes = this.totalBoxCount;
                const promo = packagePromos.find(r => totalBoxes >= r.min_qty && r.discount_percent > 0);
                if (promo && promo.discount_percent) {
                    return Math.round(subtotal * (Number(promo.discount_percent) / 100));
                }
                return 0;
            },

            get shippingCost() {
                if (this.deliveryMethod !== 'delivery') return 0;
                if (this.distanceKm !== null && this.distanceKm !== undefined) {
                    if (this.distanceKm <= 5.0) return STORE_CONFIG.baseShipping;
                    return STORE_CONFIG.baseShipping + (Math.ceil(this.distanceKm - 5.0) * STORE_CONFIG.costPerKm);
                }
                return STORE_CONFIG.baseShipping;
            },

            get voucherDiscount() {
                const voucher = this.currentVoucher;
                if (!voucher || !this.isVoucherEligible(voucher)) return 0;
                const subtotal = this.calculateGrandTotal();

                if (voucher.type === 'percentage') {
                    let disc = Math.round((Number(voucher.discount_value) / 100) * subtotal);
                    if (voucher.max_discount && disc > voucher.max_discount) {
                        disc = voucher.max_discount;
                    }
                    return Math.min(disc, subtotal);
                }
                if (voucher.type === 'fixed') {
                    return Math.min(Number(voucher.discount_value), subtotal);
                }
                if (voucher.type === 'free_shipping') {
                    return this.shippingCost;
                }
                return 0;
            },

            get finalPayableAmount() {
                const subtotal = this.calculateGrandTotal();
                return Math.max(0, subtotal - this.promoDiscount + this.shippingCost - this.voucherDiscount);
            },

            selectVoucher(code) {
                if (this.selectedVoucherCode === code) {
                    this.removeVoucher();
                    return;
                }
                const v = this.vouchers.find(x => x.code === code);
                if (!v) return;

                if (!this.isVoucherEligible(v)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Syarat Belum Terpenuhi',
                        text: `Voucher ${v.code} (${v.name}): ${this.voucherIneligibilityReason(v)}`
                    });
                    return;
                }

                this.selectedVoucherCode = code;
                this.manualVoucherInput = code;
                this.voucherFeedback = {
                    type: 'success',
                    message: `Voucher ${code} berhasil digunakan!`
                };
            },

            applyManualVoucher() {
                const code = (this.manualVoucherInput || '').trim().toUpperCase();
                if (!code) {
                    this.voucherFeedback = { type: 'warning', message: 'Ketikkan kode voucher terlebih dahulu.' };
                    return;
                }

                const v = this.vouchers.find(x => x.code === code);
                if (!v) {
                    this.voucherFeedback = { type: 'error', message: `Kode voucher "${code}" tidak ditemukan.` };
                    return;
                }

                if (!this.isVoucherEligible(v)) {
                    this.voucherFeedback = { type: 'error', message: `Voucher ${code}: ${this.voucherIneligibilityReason(v)}` };
                    return;
                }

                this.selectedVoucherCode = code;
                this.voucherFeedback = { type: 'success', message: `Voucher ${code} berhasil digunakan!` };
            },

            removeVoucher() {
                this.selectedVoucherCode = '';
                this.manualVoucherInput = '';
                this.voucherFeedback = { type: 'info', message: 'Voucher berhasil dibatalkan.' };
            },

            get eligibleBonus() {
                const promo = packagePromos.find(rule => this.totalBoxCount >= rule.min_qty);
                return promo && promo.bonus_item_name && promo.bonus_item_qty ? promo : null;
            },
            get totalBoxCount() {
                return this.boxes.reduce((sum, box) => sum + (Number(box.quantity) || 0), 0);
            },
            totalBeverageCount(type) {
                return this.boxes.reduce((sum, box) => sum + (box.beverage_type === type ? (Number(box.quantity) || 0) : 0), 0);
            },
            beveragePrice(type) {
                return type === 'gelas' ? 500 : (type === 'botol' ? 2000 : 0);
            },
            canDecreaseBox(box) {
                if (this.totalBoxCount <= this.minimumBoxes) return false;
                const minQty = this.boxes.length === 1 ? this.minimumBoxes : 1;
                return (Number(box.quantity) || 0) > minQty;
            },

            init() {
                this.boxes.forEach(box => {
                    while (box.items.length < this.minimumProductTypes) {
                        box.items.push({ id: this.nextItemId++, product_id: '', quantity: 1, price: 0 });
                    }
                });
                this.$nextTick(() => {
                    this.updateAvailableTimeSlots();
                    if (this.deliveryMethod === 'delivery') {
                        this.initMap();
                        setTimeout(() => {
                            if (this.map) this.map.invalidateSize();
                        }, 150);
                    }
                });

                this.$watch('deliveryMethod', (value) => {
                    if (value === 'delivery') {
                        this.$nextTick(() => {
                            this.initMap();
                            setTimeout(() => {
                                if (this.map) this.map.invalidateSize();
                            }, 150);
                        });
                    } else {
                        if (this.selectedVoucherCode === 'ONGKIR10KM') {
                            this.removeVoucher();
                        }
                    }
                });
            },

            addBox() {
                this.boxes.push({ id: this.nextBoxId++, quantity: 1, items: this.createEmptyItems(), beverage_type: '' });
            },
            createEmptyItems() {
                return Array.from({ length: this.minimumProductTypes }, () => ({
                    id: this.nextItemId++, product_id: '', quantity: 1, price: 0
                }));
            },
            removeBox(index) {
                if(this.boxes.length > 1) {
                    this.boxes.splice(index, 1);
                    if(this.boxes.length === 1 && this.boxes[0].quantity < this.minimumBoxes) {
                        this.boxes[0].quantity = this.minimumBoxes;
                    }
                }
            },
            addItem(boxIndex) {
                this.boxes[boxIndex].items.push({ id: this.nextItemId++, product_id: '', quantity: 1, price: 0 });
            },
            countSelectedTypes(box) {
                return new Set(box.items.map(item => String(item.product_id)).filter(Boolean)).size;
            },
            removeItem(boxIndex, itemIndex) {
                if(this.boxes[boxIndex].items.length > this.minimumProductTypes) {
                    this.boxes[boxIndex].items.splice(itemIndex, 1);
                }
            },
            isProductSelectedInOtherItem(boxIndex, itemIndex, productId) {
                return this.boxes[boxIndex].items.some((item, index) =>
                    index !== itemIndex && String(item.product_id) === String(productId)
                );
            },
            selectProduct(boxIndex, itemIndex) {
                const item = this.boxes[boxIndex].items[itemIndex];
                const pid = item.product_id;

                if (pid && this.isProductSelectedInOtherItem(boxIndex, itemIndex, pid)) {
                    const productName = productsData[pid]?.name || 'Produk tersebut';
                    item.product_id = '';
                    item.price = 0;
                    item.error = `${productName} sudah ada di box ini, silakan ubah jumlahnya di baris yang sudah ada.`;
                    return;
                }

                item.error = '';
                if(pid && productsData[pid]) {
                    item.price = productsData[pid].price;
                } else {
                    item.price = 0;
                }
            },
            calculateBoxTotal(boxIndex) {
                const box = this.boxes[boxIndex];
                const itemsTotal = box.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const perBoxBeveragePrice = this.beveragePrice(box.beverage_type);
                return (itemsTotal + perBoxBeveragePrice) * (Number(box.quantity) || 0);
            },
            calculateGrandTotal() {
                return this.boxes.reduce((sum, box, index) => sum + this.calculateBoxTotal(index), 0);
            },
            formatMoney(amount) {
                return new Intl.NumberFormat('id-ID').format(amount);
            },
            updateAvailableTimeSlots() {
                const dateInput = document.getElementById('pickupDateInput');
                const timeSelect = document.getElementById('requestedTimeSelect');
                if (!dateInput || !timeSelect) return;

                const minDate = dateInput.dataset.min;
                const maxDate = dateInput.dataset.max;

                if (this.requestedDate && minDate && this.requestedDate < minDate) {
                    this.requestedDate = minDate;
                } else if (this.requestedDate && maxDate && this.requestedDate > maxDate) {
                    this.requestedDate = maxDate;
                }

                const selectedDate = this.requestedDate;

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
                    if (!opt.value) return;

                    if (minTimeStr && opt.value < minTimeStr) {
                        opt.disabled = true;
                        opt.hidden = true;
                        if (opt.value === this.requestedTime) {
                            selectedOptionDisabled = true;
                        }
                    } else {
                        opt.disabled = false;
                        opt.hidden = false;
                        availableCount++;
                    }
                });

                if (selectedOptionDisabled) {
                    this.requestedTime = '';
                    this.updateDeadlineExample();
                }

                const noSlotWarning = document.getElementById('noTimeSlotWarning');
                if (noSlotWarning) {
                    if (selectedDate && selectedDate === minDate && availableCount === 0) {
                        noSlotWarning.classList.remove('hidden');
                    } else {
                        noSlotWarning.classList.add('hidden');
                    }
                }
            },
            updateDeadlineExample() {
                const exampleTime = document.getElementById('exampleTime');
                const exampleDeadline = document.getElementById('exampleDeadline');
                if (!exampleTime || !exampleDeadline) return;

                if (!this.requestedTime) {
                    exampleTime.textContent = '--:-- WIB';
                    exampleDeadline.textContent = '--:-- WIB';
                    return;
                }

                const [h, m] = this.requestedTime.split(':').map(Number);
                const totalMins = h * 60 + m + PICKUP_DEADLINE_HOURS * 60;
                const dh = Math.floor(totalMins / 60) % 24;
                const dm = totalMins % 60;
                exampleTime.textContent = this.requestedTime + ' WIB';
                exampleDeadline.textContent = String(dh).padStart(2,'0') + ':' + String(dm).padStart(2,'0') + ' WIB';
            },
            isValid() {
                if(this.totalBoxCount < this.minimumBoxes) return false;
                
                let valid = true;
                this.boxes.forEach(box => {
                    if (!Number.isInteger(Number(box.quantity)) || Number(box.quantity) < 1) valid = false;
                    if (this.countSelectedTypes(box) < this.minimumProductTypes) valid = false;
                    if(box.items.length === 0) valid = false;
                    box.items.forEach(item => {
                        if(!item.product_id || item.quantity < 1) valid = false;
                    });
                });
                
                if (this.deliveryMethod === 'delivery') {
                    if (!this.deliveryAddress.trim()) valid = false;
                    if (!this.deliveryLat || !this.deliveryLng) valid = false;
                    if (this.isOutsideRadius || (this.distanceKm !== null && this.distanceKm > STORE_CONFIG.maxRadius)) valid = false;
                }
                if (!this.requestedDate || !this.requestedTime) {
                    valid = false;
                }
                if (this.deliveryMethod === 'pickup' && !this.pickupDisclaimerAgreed) {
                    valid = false;
                }
                
                return valid;
            },
            
            async submitCheckout() {
                if (this.totalBoxCount < this.minimumBoxes) {
                    Swal.fire({icon: 'warning', title: 'Minimal Pembelian', text: `Minimal pembelian 10 box, saat ini baru ${this.totalBoxCount} box.`});
                    return;
                }
                if(!this.isValid()) {
                    if (this.deliveryMethod === 'delivery' && (!this.deliveryLat || !this.deliveryLng)) {
                        Swal.fire({icon: 'warning', title: 'Titik Lokasi Diperlukan', text: 'Silakan tentukan titik lokasi pengiriman pada peta terlebih dahulu.'});
                        return;
                    }
                    if (this.deliveryMethod === 'delivery' && (this.isOutsideRadius || this.distanceKm > STORE_CONFIG.maxRadius)) {
                        Swal.fire({icon: 'error', title: 'Di Luar Jangkauan Toko', text: `Lokasi pengiriman (${this.distanceKm} km) di luar batas jangkauan pengantaran toko (maksimal ${STORE_CONFIG.maxRadius} km). Silakan pilih metode Ambil di Toko.`});
                        return;
                    }
                    Swal.fire({icon: 'warning', title: 'Perhatian', text: 'Pastikan seluruh data alamat, tanggal, jam, dan persetujuan telah diisi dengan benar.'});
                    return;
                }
                
                this.isSubmitting = true;
                
                try {
                    const response = await fetch('{{ route('custom-box.checkout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'ngrok-skip-browser-warning': 'true'
                        },
                        body: JSON.stringify({
                            boxes: this.boxes,
                            delivery_method: this.deliveryMethod,
                            delivery_address: this.deliveryAddress,
                            delivery_lat: this.deliveryLat,
                            delivery_lng: this.deliveryLng,
                            requested_date: this.requestedDate,
                            requested_time: this.requestedTime,
                            pickup_disclaimer_agreed: this.pickupDisclaimerAgreed,
                            voucher_code: this.selectedVoucherCode || null
                        })
                    });
                    
                    const result = await response.json();
                    
                    if(response.ok && result.status === 'success') {
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
                        Swal.fire({icon: 'error', title: 'Gagal', text: result.message || 'Terjadi kesalahan.'});
                    }
                } catch(e) {
                    console.error(e);
                    Swal.fire({icon: 'error', title: 'Error Server', text: 'Terjadi kesalahan pada server.'});
                } finally {
                    this.isSubmitting = false;
                }
            }
        }));
    }

    if (window.Alpine) {
        initCustomBoxComponent();
    } else {
        document.addEventListener('alpine:init', initCustomBoxComponent);
    }
</script>

@endsection
