import sys

with open('resources/views/checkout/index.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

target1 = '''                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tanggal Pengambilan (H-2)</h2>
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
                    <p class="text-sm text-gray-500">Minimal pengambilan H-2 dari hari ini (contoh: hari ini tgl 1, paling cepat tgl 3).</p>'''

replacement1 = '''                    @php
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
                        <div class="flex space-x-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="delivery_method" value="pickup" form="paymentForm" class="checkout-radio" checked onchange="toggleDeliveryAddress(false)">
                                <span>Ambil di Toko (Pickup)</span>
                            </label>
                            <label class="flex items-center space-x-2 {{ !$canDelivery ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <input type="radio" name="delivery_method" value="delivery" form="paymentForm" class="checkout-radio" {{ !$canDelivery ? 'disabled' : '' }} onchange="toggleDeliveryAddress(true)">
                                <span>Kirim ke Alamat (Delivery)</span>
                            </label>
                        </div>
                        @if(!$canDelivery)
                            <p class="text-sm text-orange-500 mt-1">Delivery hanya tersedia untuk pemesanan minimal {{ $minDeliveryQty }} item (Item saat ini: {{ $totalQty }}).</p>
                        @endif
                    </div>

                    <div id="deliveryAddressContainer" class="mb-4 hidden">
                        <label class="block text-gray-700 font-semibold mb-2">Alamat Pengiriman:</label>
                        <textarea name="delivery_address" form="paymentForm" class="form-control w-full" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal Pengambilan / Pengantaran:</label>
                        @php
                            $minPickup = \Carbon\Carbon::today()->format('Y-m-d');
                        @endphp
                        <input type="date" name="requested_date" required form="paymentForm"
                               id="pickupDateInput"
                               min="{{ $minPickup }}"
                               data-min="{{ $minPickup }}"
                               onkeydown="return false"
                               onpaste="return false"
                               class="form-control">
                        @error('requested_date')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <p id="pickupDateWarning" class="text-sm text-red-600 mt-2 hidden">
                        Tanggal tidak boleh di masa lalu.
                    </p>'''

target2 = '''<script>
const pickupInput = document.getElementById('pickupDateInput');'''

replacement2 = '''<script>
function toggleDeliveryAddress(show) {
    const container = document.getElementById('deliveryAddressContainer');
    const textarea = container.querySelector('textarea');
    if (show) {
        container.classList.remove('hidden');
        textarea.setAttribute('required', 'required');
    } else {
        container.classList.add('hidden');
        textarea.removeAttribute('required');
    }
}

const pickupInput = document.getElementById('pickupDateInput');'''

target3 = '''            // Tambahkan pickup date dari input di luar form
            if (pickupInput) {
                formData.append('pickup_date', pickupInput.value);
            }'''

replacement3 = '''            // Tambahkan pickup date dari input di luar form
            if (pickupInput) {
                formData.append('requested_date', pickupInput.value);
            }'''

content = content.replace(target1, replacement1)
content = content.replace(target2, replacement2)
content = content.replace(target3, replacement3)

with open('resources/views/checkout/index.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
