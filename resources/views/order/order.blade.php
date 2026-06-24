@extends('layouts.app')

@section('title', 'Lacak Pesanan - Strawberry Bakery')

@section('content')
<!-- Hero Section -->
<section class="min-vh-100 d-flex align-items-center justify-content-center py-5 px-3">
    <div class="container" style="max-width: 800px;">

        <!-- Logo and Title -->
        <div class="text-center mb-5">
            <div class="d-flex align-items-center justify-content-center mb-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-gradient shadow-sm" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-box-open text-white" style="font-size: 2rem;"></i>
                </div>
            </div>
            <h1 class="display-5 fw-bold text-dark mb-3">
                Lacak Pesanan Anda
            </h1>
            <p class="text-muted fs-5">
                Masukkan nomor pesanan untuk melacak status pesanan Anda
            </p>
        </div>

        <!-- Search Card -->
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">
                <form id="trackingForm" class="d-flex flex-column gap-4">
                    <div>
                        <label for="orderNumber" class="form-label fw-bold text-dark">
                            Nomor Pesanan
                        </label>
                        <div class="input-group input-group-lg">
                            <input
                                type="text"
                                id="orderNumber"
                                name="orderNumber"
                                placeholder="Contoh: ORD-20251109-001"
                                class="form-control bg-light border-0 px-4 py-3"
                                required
                            >
                            <span class="input-group-text bg-light border-0 text-muted px-4">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                        </div>
                        <div class="form-text mt-2">
                            Format: ORD-YYYYMMDD-XXX
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-strawberry rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                    >
                        <i class="fa-solid fa-location-crosshairs"></i>
                        Lacak Pesanan
                    </button>
                </form>
            </div>
        </div>

        <!-- Result Card (Hidden by default) -->
        <div id="resultCard" class="card border-0 shadow-lg rounded-4 d-none mb-4">
            <div class="card-body p-4 p-md-5">
                <!-- Success State -->
                <div id="successState" class="d-none">
                    <!-- Order Header -->
                    <div class="border-bottom pb-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h4 fw-bold text-dark mb-0">Detail Pesanan</h2>
                            <span id="orderStatus" class="badge rounded-pill px-3 py-2 fs-6"></span>
                        </div>
                        <p class="text-muted mb-1">Nomor Pesanan: <span id="displayOrderNumber" class="fw-bold text-dark"></span></p>
                        <p class="text-muted mb-1">Tanggal Pemesanan: <span id="orderDate" class="fw-bold text-dark"></span></p>
                        <p class="text-muted mb-0">Tanggal Pengambilan: <span id="pickupDate" class="fw-bold text-dark"></span></p>
                    </div>

                    <!-- Status Timeline -->
                    <div class="mb-5">
                        <h3 class="h5 fw-bold text-dark mb-4">Status Pesanan</h3>
                        <div class="d-flex flex-column gap-0 position-relative ps-3">
                            <!-- Step 1: Order Placed -->
                            <div class="d-flex align-items-start timeline-step mb-4 position-relative" data-step="placed">
                                <div class="rounded-circle d-flex align-items-center justify-content-center step-icon bg-secondary" style="width: 40px; height: 40px; z-index: 2;">
                                    <i class="fa-solid fa-check text-white"></i>
                                </div>
                                <div class="ms-3 flex-grow-1 pt-1">
                                    <p class="fw-bold text-dark mb-0">Pesanan Diterima</p>
                                    <p class="small text-muted mb-0">Pesanan Anda telah diterima dan sedang diproses</p>
                                </div>
                            </div>

                            <!-- Step 2: Payment Confirmed -->
                            <div class="d-flex align-items-start timeline-step mb-4 position-relative" data-step="paid">
                                <div class="timeline-connector bg-secondary position-absolute" style="width: 2px; height: 100%; top: -100%; left: 19px; z-index: 1;"></div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center step-icon bg-secondary" style="width: 40px; height: 40px; z-index: 2;">
                                    <i class="fa-solid fa-money-bill-wave text-white"></i>
                                </div>
                                <div class="ms-3 flex-grow-1 pt-1">
                                    <p class="fw-bold text-dark mb-0">Pembayaran Dikonfirmasi</p>
                                    <p class="small text-muted mb-0">Pembayaran Anda telah diterima dan diverifikasi</p>
                                </div>
                            </div>

                            <!-- Step 3: Preparing -->
                            <div class="d-flex align-items-start timeline-step mb-4 position-relative" data-step="preparing">
                                <div class="timeline-connector bg-secondary position-absolute" style="width: 2px; height: 100%; top: -100%; left: 19px; z-index: 1;"></div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center step-icon bg-secondary" style="width: 40px; height: 40px; z-index: 2;">
                                    <i class="fa-solid fa-fire-burner text-white"></i>
                                </div>
                                <div class="ms-3 flex-grow-1 pt-1">
                                    <p class="fw-bold text-dark mb-0">Sedang Diproses</p>
                                    <p class="small text-muted mb-0">Pesanan Anda sedang disiapkan dengan cermat</p>
                                </div>
                            </div>

                            <!-- Step 4: Ready -->
                            <div class="d-flex align-items-start timeline-step position-relative" data-step="ready">
                                <div class="timeline-connector bg-secondary position-absolute" style="width: 2px; height: 100%; top: -100%; left: 19px; z-index: 1;"></div>
                                <div class="rounded-circle d-flex align-items-center justify-content-center step-icon bg-secondary" style="width: 40px; height: 40px; z-index: 2;">
                                    <i class="fa-solid fa-bag-shopping text-white"></i>
                                </div>
                                <div class="ms-3 flex-grow-1 pt-1">
                                    <p class="fw-bold text-dark mb-0">Siap Diambil</p>
                                    <p class="small text-muted mb-0">Pesanan siap untuk diambil di toko</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-4">
                        <h3 class="h5 fw-bold text-dark mb-3">Item Pesanan</h3>
                        <div id="orderItems" class="d-flex flex-column gap-2">
                            <!-- Items will be populated here -->
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
                            <span id="totalAmount" class="fw-bold text-primary fs-4"></span>
                        </div>
                    </div>

                    <!-- Pickup Info -->
                    <div class="alert bg-primary bg-opacity-10 border-0 rounded-3 p-4 mb-4">
                        <h4 class="h6 fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                            Informasi Pengambilan
                        </h4>
                        <p class="small text-dark mb-0">
                            <strong>Lokasi:</strong> Strawberry Bakery<br>
                            Jl. Makmur Sejahtera No. 123, Ngawi, East Java 63251<br>
                            <strong>Jam Operasional:</strong> Senin-Sabtu (09.00-19.00), Minggu (10.00-17.00)
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                        <button onclick="window.print()" class="btn btn-light rounded-pill py-2 px-4 fw-bold flex-grow-1">
                            <i class="fa-solid fa-print me-2"></i> Print
                        </button>
                        <button onclick="location.reload()" class="btn btn-strawberry rounded-pill py-2 px-4 fw-bold flex-grow-1">
                            <i class="fa-solid fa-rotate-right me-2"></i> Lacak Pesanan Lain
                        </button>
                    </div>
                </div>

                <!-- Error State -->
                <div id="errorState" class="d-none text-center py-5">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold text-dark mb-2">Pesanan Tidak Ditemukan</h3>
                    <p class="text-muted mb-4">
                        Nomor pesanan yang Anda masukkan tidak ditemukan dalam sistem kami.<br>
                        Pastikan Anda memasukkan nomor yang benar.
                    </p>
                    <button onclick="location.reload()" class="btn btn-strawberry rounded-pill px-5 py-2 fw-bold">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fa-brands fa-whatsapp text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h6 fw-bold text-dark mb-1">WhatsApp</h3>
                        <p class="small text-muted mb-0">+62 812-3456-7890</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-envelope text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h6 fw-bold text-dark mb-1">Email</h3>
                        <p class="small text-muted mb-0">info@strawberrybakery.com</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-circle-question text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h6 fw-bold text-dark mb-1">FAQ</h3>
                        <a href="{{ route('faq') }}" class="small text-primary text-decoration-none hover-primary">Lihat Pertanyaan</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #resultCard, #resultCard * {
        visibility: visible;
    }
    #resultCard {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
    }
    .btn, .badge {
        display: none !important;
    }
}
</style>
@endpush

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
    resultCard.classList.remove('d-none');

    // Check if order exists
    if (demoOrders[orderNumber]) {
        const order = demoOrders[orderNumber];

        // Hide error, show success
        errorState.classList.add('d-none');
        successState.classList.remove('d-none');

        // Populate order details
        document.getElementById('displayOrderNumber').textContent = order.orderNumber;
        document.getElementById('orderDate').textContent = order.orderDate;
        document.getElementById('pickupDate').textContent = order.pickupDate;
        document.getElementById('totalAmount').textContent = order.total;

        // Set status badge
        const statusBadge = document.getElementById('orderStatus');
        const statusConfig = {
            'placed': { text: 'Pesanan Diterima', class: 'bg-primary bg-opacity-10 text-primary' },
            'paid': { text: 'Pembayaran Dikonfirmasi', class: 'bg-success bg-opacity-10 text-success' },
            'preparing': { text: 'Sedang Diproses', class: 'bg-warning bg-opacity-10 text-warning' },
            'ready': { text: 'Siap Diambil', class: 'bg-info bg-opacity-10 text-info' },
            'completed': { text: 'Selesai', class: 'bg-secondary bg-opacity-10 text-secondary' },
            'cancelled': { text: 'Dibatalkan', class: 'bg-danger bg-opacity-10 text-danger' }
        };

        const currentStatus = statusConfig[order.status];
        statusBadge.textContent = currentStatus.text;
        statusBadge.className = `badge rounded-pill px-3 py-2 fs-6 ${currentStatus.class}`;

        // Update timeline
        updateTimeline(order.status);

        // Populate items
        const itemsContainer = document.getElementById('orderItems');
        itemsContainer.innerHTML = '';
        order.items.forEach(item => {
            itemsContainer.innerHTML += `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <p class="fw-medium text-dark mb-0">${item.name}</p>
                        <p class="small text-muted mb-0">Jumlah: ${item.quantity}</p>
                    </div>
                    <span class="fw-bold text-dark">${item.price}</span>
                </div>
            `;
        });

        // Smooth scroll to result
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        // Show error state
        successState.classList.add('d-none');
        errorState.classList.remove('d-none');

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
        const connector = stepElement.querySelector('.timeline-connector');

        if (index <= currentStepIndex) {
            // Completed step
            icon.classList.remove('bg-secondary');
            icon.classList.add('bg-primary');

            if (connector) {
                connector.classList.remove('bg-secondary');
                connector.classList.add('bg-primary');
            }
        } else {
            // Upcoming step
            icon.classList.remove('bg-primary');
            icon.classList.add('bg-secondary');

            if (connector) {
                connector.classList.remove('bg-primary');
                connector.classList.add('bg-secondary');
            }
        }
    });
}
</script>

@endsection
