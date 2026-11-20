@extends('layouts.admin')

@section('title', 'Dashboard Kurir')

@push('styles')
<style>
    .order-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    .order-card.active-delivery {
        border-left: 5px solid var(--sb-primary) !important;
    }
    .order-card.completed-delivery {
        border-left: 5px solid #198754 !important;
    }
    .order-card.failed-delivery {
        border-left: 5px solid #dc3545 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="fas fa-motorcycle text-primary me-2"></i> Dashboard Kurir Pengantaran
            </h1>
            <p class="text-muted mb-0">Selamat bertugas, <strong>{{ auth()->user()->name }}</strong>. Berikut daftar pesanan yang ditugaskan ke Anda.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">
                <i class="fas fa-boxes me-1"></i> Total: {{ $orders->total() }} Pesanan
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($orders as $order)
            @php
                $status = $order->delivery_status ?? 'ditugaskan';
                $cardClass = in_array($status, ['ditugaskan', 'diambil_kurir', 'dalam_perjalanan']) 
                    ? 'active-delivery' 
                    : ($status === 'terkirim' ? 'completed-delivery' : 'failed-delivery');
                
                $phone = preg_replace('/\D+/', '', $order->user->phone ?? '');
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }
            @endphp

            <div class="col-12 col-lg-6">
                <div class="order-card p-4 h-100 d-flex flex-column justify-content-between {{ $cardClass }}">
                    <div>
                        <!-- Header Order Card -->
                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                            <div>
                                <span class="fw-bold text-dark fs-5">
                                    {{ $order->order_number ?? 'INV-' . str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <div class="small text-muted">
                                    <i class="far fa-clock me-1"></i> {{ $order->created_at->format('d M Y H:i') }} WIB
                                </div>
                            </div>
                            <div class="text-end">
                                @if($status === 'ditugaskan')
                                    <span class="badge bg-info text-white rounded-pill px-3 py-2"><i class="fas fa-clipboard-list me-1"></i> Ditugaskan</span>
                                @elseif($status === 'diambil_kurir')
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-2"><i class="fas fa-box me-1"></i> Diambil Kurir</span>
                                @elseif($status === 'dalam_perjalanan')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-route me-1"></i> Dalam Perjalanan</span>
                                @elseif($status === 'terkirim')
                                    <span class="badge bg-success text-white rounded-pill px-3 py-2"><i class="fas fa-check-circle me-1"></i> Terkirim</span>
                                @elseif($status === 'gagal_kirim')
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-2"><i class="fas fa-times-circle me-1"></i> Gagal Kirim</span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-2">Belum Ditugaskan</span>
                                @endif
                            </div>
                        </div>

                        <!-- Info Customer & Alamat -->
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-1">
                                <i class="fas fa-user text-primary me-2"></i> {{ $order->user->name ?? 'Pelanggan' }}
                            </h6>
                            @if($phone)
                                <div class="mb-2">
                                    <a href="https://wa.me/{{ $phone }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 text-nowrap">
                                        <i class="fab fa-whatsapp me-1"></i> Hubungi WA ({{ $order->user->phone }})
                                    </a>
                                </div>
                            @endif

                            <div class="p-3 bg-light rounded-3 border mb-2">
                                <div class="small text-muted mb-1 fw-bold"><i class="fas fa-map-marker-alt text-danger me-1"></i> Alamat Pengantaran:</div>
                                <div class="fw-semibold text-dark" style="font-size: 0.95rem;">
                                    {{ $order->delivery_address ?? 'Alamat tidak diisi' }}
                                </div>
                            </div>

                            @if(!is_null($order->delivery_lat) && !is_null($order->delivery_lng))
                                <a href="https://www.google.com/maps?q={{ $order->delivery_lat }},{{ $order->delivery_lng }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100">
                                    <i class="fas fa-directions me-1"></i> Buka Rute Google Maps ({{ number_format($order->delivery_lat, 4) }}, {{ number_format($order->delivery_lng, 4) }})
                                </a>
                            @elseif($order->delivery_address)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->delivery_address) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100">
                                    <i class="fas fa-search-location me-1"></i> Cari Alamat di Google Maps
                                </a>
                            @endif
                        </div>

                        <!-- Ringkasan Roti & Catatan -->
                        <div class="mb-3 bg-pink-50 p-3 rounded-3" style="background-color: #fff1f4;">
                            <div class="small fw-bold text-primary mb-1"><i class="fas fa-shopping-bag me-1"></i> Ringkasan Pesanan:</div>
                            <div class="small text-dark">
                                Total Bayar: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> 
                                ({{ strtoupper($order->payment_method) }} - {{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar' }})
                            </div>
                        </div>

                        @if($order->delivered_at)
                            <div class="alert alert-success p-2 small mb-3 rounded-3">
                                <i class="fas fa-check-circle me-1"></i> Waktu Terkirim: <strong>{{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y H:i') }} WIB</strong>
                            </div>
                        @endif

                        @if($order->delivery_notes)
                            <div class="alert alert-danger p-2 small mb-3 rounded-3">
                                <i class="fas fa-exclamation-circle me-1"></i> Catatan Kurir: <strong>{{ $order->delivery_notes }}</strong>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons for Delivery Status Updates -->
                    <div class="pt-3 border-top mt-3">
                        @if(in_array($status, ['ditugaskan', 'belum_ditugaskan']))
                            <div class="d-flex gap-2">
                                <form action="{{ route('kurir.orders.update-status', $order) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="diambil_kurir">
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill font-semibold shadow-sm">
                                        <i class="fas fa-box me-1"></i> 1. Ambil Pesanan Dari Toko
                                    </button>
                                </form>
                                <button class="btn btn-outline-danger rounded-pill flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#failNotes{{ $order->id }}">
                                    Gagal
                                </button>
                            </div>
                        @elseif($status === 'diambil_kurir')
                            <div class="d-flex gap-2">
                                <form action="{{ route('kurir.orders.update-status', $order) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="dalam_perjalanan">
                                    <button type="submit" class="btn btn-warning text-dark w-100 rounded-pill font-semibold shadow-sm">
                                        <i class="fas fa-route me-1"></i> 2. Mulai Antar (Dalam Perjalanan)
                                    </button>
                                </form>
                                <button class="btn btn-outline-danger rounded-pill flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#failNotes{{ $order->id }}">
                                    Gagal
                                </button>
                            </div>
                        @elseif($status === 'dalam_perjalanan')
                            <div class="d-flex gap-2">
                                <form action="{{ route('kurir.orders.update-status', $order) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="terkirim">
                                    <button type="submit" class="btn btn-success w-100 rounded-pill font-semibold shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i> 3. Konfirmasi Pesanan Terkirim
                                    </button>
                                </form>
                                <button class="btn btn-outline-danger rounded-pill flex-shrink-0" type="button" data-bs-toggle="collapse" data-bs-target="#failNotes{{ $order->id }}">
                                    Gagal
                                </button>
                            </div>
                        @elseif($status === 'gagal_kirim')
                            <div class="d-flex gap-2">
                                <form action="{{ route('kurir.orders.update-status', $order) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="diambil_kurir">
                                    <button type="submit" class="btn btn-outline-warning text-dark w-100 rounded-pill font-semibold">
                                        <i class="fas fa-redo me-1"></i> Mulai Ulang Pengantaran
                                    </button>
                                </form>
                            </div>
                        @elseif($status === 'terkirim')
                            <div class="text-center text-success fw-bold py-2 bg-success-subtle rounded-pill">
                                <i class="fas fa-check-double me-1"></i> Selesai Ditugaskan & Terkirim
                            </div>
                        @endif

                        <!-- Collapse form for reporting delivery failure -->
                        <div class="collapse mt-3" id="failNotes{{ $order->id }}">
                            <div class="card card-body bg-light border-danger">
                                <form action="{{ route('kurir.orders.update-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="delivery_status" value="gagal_kirim">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-danger mb-1">Alasan Gagal Pengantaran <span class="text-danger">*</span></label>
                                        <textarea name="delivery_notes" class="form-control form-control-sm" rows="2" placeholder="Misal: Penerima tidak ada di lokasi / No WA tidak aktif" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill">
                                        Simpan Status Gagal Kirim
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 shadow-sm border text-muted">
                    <i class="fas fa-motorcycle fs-1 mb-3 text-muted opacity-50 d-block"></i>
                    <h5 class="fw-bold text-dark mb-1">Belum Ada Tugas Pengantaran</h5>
                    <p class="mb-0">Saat ini belum ada pesanan yang ditugaskan kepada Anda.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
