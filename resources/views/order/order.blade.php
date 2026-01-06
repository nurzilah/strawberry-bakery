@extends('layouts.app')

@section('title', 'Lacak Pesanan - Strawberry Bakery')

@section('content')
<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="container mx-auto max-w-2xl">

        <!-- Logo and Title -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-pink-500 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-3">
                🔍 Lacak Pesanan Anda
            </h1>
            <p class="text-gray-600 text-lg">
                Masukkan nomor pesanan untuk melacak status pesanan Anda
            </p>
        </div>

        <!-- Search Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <form id="trackingForm" class="space-y-6">
                <div>
                    <label for="orderNumber" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Pesanan
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="orderNumber"
                            name="orderNumber"
                            placeholder="Masukkan nomor pesanan (contoh: ORD-20251109-001)"
                            class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-pink-500 transition text-gray-700"
                            required
                        >
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Format: ORD-YYYYMMDD-XXX (contoh: ORD-20251109-001)
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white py-4 rounded-xl font-semibold hover:from-pink-600 hover:to-pink-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center"
                >
                    <span class="mr-2">🔍</span>
                    Lacak Pesanan
                </button>
            </form>
        </div>

        <!-- Result Card (Hidden by default) -->
        <div id="resultCard" class="bg-white rounded-2xl shadow-lg p-8 hidden">
            <!-- Success State -->
            <div id="successState" class="hidden">
                <!-- Order Header -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan</h2>
                        <span id="orderStatus" class="px-4 py-2 rounded-full text-sm font-semibold"></span>
                    </div>
                    <p class="text-gray-600">Nomor Pesanan: <span id="displayOrderNumber" class="font-semibold text-gray-800"></span></p>
                    <p class="text-gray-600">Tanggal Pemesanan: <span id="orderDate" class="font-semibold text-gray-800"></span></p>
                    <p class="text-gray-600">Tanggal Pengambilan: <span id="pickupDate" class="font-semibold text-gray-800"></span></p>
                </div>

                <!-- Status Timeline -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Pesanan</h3>
                    <div class="space-y-4">
                        <!-- Step 1: Order Placed -->
                        <div class="flex items-start timeline-step" data-step="placed">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center step-icon">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-800">Pesanan Diterima</p>
                                <p class="text-sm text-gray-600">Pesanan Anda telah diterima dan sedang diproses</p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div class="ml-5 w-0.5 h-8 bg-gray-300 timeline-connector"></div>

                        <!-- Step 2: Payment Confirmed -->
                        <div class="flex items-start timeline-step" data-step="paid">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center step-icon">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-800">Pembayaran Dikonfirmasi</p>
                                <p class="text-sm text-gray-600">Pembayaran Anda telah diterima dan diverifikasi</p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div class="ml-5 w-0.5 h-8 bg-gray-300 timeline-connector"></div>

                        <!-- Step 3: Preparing -->
                        <div class="flex items-start timeline-step" data-step="preparing">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center step-icon">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-800">Sedang Diproses</p>
                                <p class="text-sm text-gray-600">Pesanan Anda sedang disiapkan dengan cermat</p>
                            </div>
                        </div>

                        <!-- Connector -->
                        <div class="ml-5 w-0.5 h-8 bg-gray-300 timeline-connector"></div>

                        <!-- Step 4: Ready -->
                        <div class="flex items-start timeline-step" data-step="ready">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center step-icon">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-800">Siap Diambil</p>
                                <p class="text-sm text-gray-600">Pesanan siap untuk diambil di toko</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Item Pesanan</h3>
                    <div id="orderItems" class="space-y-3">
                        <!-- Items will be populated here -->
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-semibold text-gray-800">Total Pembayaran</span>
                        <span id="totalAmount" class="font-bold text-pink-500 text-xl"></span>
                    </div>
                </div>

                <!-- Pickup Info -->
                <div class="bg-pink-50 rounded-xl p-4 mt-6">
                    <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Informasi Pengambilan
                    </h4>
                    <p class="text-sm text-gray-700">
                        <strong>Lokasi:</strong> Strawberry Bakery<br>
                        Jl. Makmur Sejahtera No. 123, Ngawi, East Java 63251<br>
                        <strong>Jam Operasional:</strong> Senin-Sabtu (09.00-19.00), Minggu (10.00-17.00)
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 mt-6">
                    <button onclick="window.print()" class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">
                        🖨️ Print
                    </button>
                    <button onclick="location.reload()" class="flex-1 bg-pink-500 text-white py-3 rounded-xl font-semibold hover:bg-pink-600 transition">
                        🔍 Lacak Pesanan Lain
                    </button>
                </div>
            </div>

            <!-- Error State -->
            <div id="errorState" class="hidden text-center py-8">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pesanan Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-6">
                    Nomor pesanan yang Anda masukkan tidak ditemukan dalam sistem kami.<br>
                    Pastikan Anda memasukkan nomor yang benar.
                </p>
                <button onclick="location.reload()" class="bg-pink-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-pink-600 transition">
                    Coba Lagi
                </button>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl mb-2">📱</div>
                <h3 class="font-semibold text-gray-800 mb-1">WhatsApp</h3>
                <p class="text-sm text-gray-600">+62 812-3456-7890</p>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl mb-2">📧</div>
                <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
                <p class="text-sm text-gray-600">info@strawberrybakery.com</p>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="text-3xl mb-2">❓</div>
                <h3 class="font-semibold text-gray-800 mb-1">FAQ</h3>
                <a href="{{ route('faq') }}" class="text-sm text-pink-500 hover:underline">Lihat Pertanyaan</a>
            </div>
        </div>

    </div>
</section>

<!-- Tracking Script -->
<script>
// Demo data - In production, this would come from your API
const demoOrders = {
    'ORD-20251109-001': {
        orderNumber: 'ORD-20251109-001',
        orderDate: '9 November 2024',
        pickupDate: '11 November 2024',
        status: 'preparing', // placed, paid, preparing, ready, completed, cancelled
        items: [
            { name: 'Strawberry Shortcake', quantity: 1, price: 'Rp 180.000' },
            { name: 'Chocolate Croissant', quantity: 3, price: 'Rp 45.000' }
        ],
        total: 'Rp 225.000'
    },
    'ORD-20251109-002': {
        orderNumber: 'ORD-20251109-002',
        orderDate: '9 November 2024',
        pickupDate: '10 November 2024',
        status: 'ready',
        items: [
            { name: 'Red Velvet Cake', quantity: 1, price: 'Rp 200.000' }
        ],
        total: 'Rp 200.000'
    }
};

document.getElementById('trackingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const orderNumber = document.getElementById('orderNumber').value.trim().toUpperCase();
    const resultCard = document.getElementById('resultCard');
    const successState = document.getElementById('successState');
    const errorState = document.getElementById('errorState');

    // Show result card
    resultCard.classList.remove('hidden');

    // Check if order exists
    if (demoOrders[orderNumber]) {
        const order = demoOrders[orderNumber];

        // Hide error, show success
        errorState.classList.add('hidden');
        successState.classList.remove('hidden');

        // Populate order details
        document.getElementById('displayOrderNumber').textContent = order.orderNumber;
        document.getElementById('orderDate').textContent = order.orderDate;
        document.getElementById('pickupDate').textContent = order.pickupDate;
        document.getElementById('totalAmount').textContent = order.total;

        // Set status badge
        const statusBadge = document.getElementById('orderStatus');
        const statusConfig = {
            'placed': { text: 'Pesanan Diterima', class: 'bg-blue-100 text-blue-700' },
            'paid': { text: 'Pembayaran Dikonfirmasi', class: 'bg-green-100 text-green-700' },
            'preparing': { text: 'Sedang Diproses', class: 'bg-yellow-100 text-yellow-700' },
            'ready': { text: 'Siap Diambil', class: 'bg-purple-100 text-purple-700' },
            'completed': { text: 'Selesai', class: 'bg-gray-100 text-gray-700' },
            'cancelled': { text: 'Dibatalkan', class: 'bg-red-100 text-red-700' }
        };

        const currentStatus = statusConfig[order.status];
        statusBadge.textContent = currentStatus.text;
        statusBadge.className = `px-4 py-2 rounded-full text-sm font-semibold ${currentStatus.class}`;

        // Update timeline
        updateTimeline(order.status);

        // Populate items
        const itemsContainer = document.getElementById('orderItems');
        itemsContainer.innerHTML = '';
        order.items.forEach(item => {
            itemsContainer.innerHTML += `
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <p class="font-medium text-gray-800">${item.name}</p>
                        <p class="text-sm text-gray-600">Jumlah: ${item.quantity}</p>
                    </div>
                    <span class="font-semibold text-gray-800">${item.price}</span>
                </div>
            `;
        });

        // Smooth scroll to result
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        // Show error state
        successState.classList.add('hidden');
        errorState.classList.remove('hidden');

        // Smooth scroll to result
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});

function updateTimeline(status) {
    const steps = ['placed', 'paid', 'preparing', 'ready'];
    const currentStepIndex = steps.indexOf(status);

    steps.forEach((step, index) => {
        const stepElement = document.querySelector(`[data-step="${step}"]`);
        const icon = stepElement.querySelector('.step-icon');
        const connector = stepElement.nextElementSibling;

        if (index <= currentStepIndex) {
            // Completed step
            icon.classList.remove('bg-gray-300');
            icon.classList.add('bg-pink-500');

            if (connector && connector.classList.contains('timeline-connector')) {
                connector.classList.remove('bg-gray-300');
                connector.classList.add('bg-pink-500');
            }
        } else {
            // Upcoming step
            icon.classList.remove('bg-pink-500');
            icon.classList.add('bg-gray-300');

            if (connector && connector.classList.contains('timeline-connector')) {
                connector.classList.remove('bg-pink-500');
                connector.classList.add('bg-gray-300');
            }
        }
    });
}
</script>

@endsection
