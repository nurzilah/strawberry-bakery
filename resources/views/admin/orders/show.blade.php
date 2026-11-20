@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Rincian pesanan, informasi pengiriman, dan item yang dibeli.</p>
        </div>
        <div>
            <a href="{{ route('admin.orders.receipt', $order->id) }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-print me-2"></i> Cetak Struk
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light rounded-pill px-4">Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    @if($order->packages && $order->packages->isNotEmpty())
                        <div class="mb-3">
                            <span class="badge bg-pink-500 text-white px-3 py-2 rounded-pill fw-semibold">
                                <i class="fas fa-box-open me-1"></i> Pesanan Custom Box / Paket
                            </span>
                        </div>
                        <div class="space-y-4">
                            @foreach($order->packages as $index => $pkg)
                                <div class="border rounded-3 p-3 mb-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            Box #{{ $index + 1 }}
                                            @if($pkg->is_free)
                                                <span class="badge bg-success ms-2">GRATIS (PROMO)</span>
                                            @endif
                                        </h6>
                                        <span class="fw-bold">
                                            Rp {{ number_format($pkg->box_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($pkg->items as $pItem)
                                            <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                                                <span>
                                                    <i class="fas fa-bread-slice me-2 text-muted"></i>
                                                    {{ $pItem->product->name ?? 'Produk' }} x{{ $pItem->quantity }}
                                                </span>
                                                <span class="text-muted small">
                                                    @ Rp {{ number_format($pItem->unit_price, 0, ',', '.') }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Total Pembayaran</span>
                            <span class="fw-bold text-dark fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="border-bottom">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $item->product->image_default ?? asset('images/logo.png') }}" alt="{{ $item->product_name }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}'">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $item->product_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-top">
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Total Pembayaran</td>
                                        <td class="text-end fw-bold text-dark fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Information Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Informasi Pesanan</h5>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Status Utama Pesanan</label>
                        <div>
                            @if($order->status === 'waiting_verification')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($order->status === 'processing')
                                <span class="badge bg-primary">Siap Diambil / Diproses</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Metode Pengiriman</label>
                        <div class="fw-semibold">
                            @if($order->delivery_method === 'delivery')
                                <span class="badge bg-primary text-white"><i class="fas fa-truck me-1"></i> Delivery Kurir</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-store me-1"></i> Pickup (Ambil Sendiri)</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Tanggal Pesanan</label>
                        <div class="fw-semibold">{{ $order->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Tanggal Pengambilan/Pengantaran</label>
                        <div class="fw-semibold">
                            {{ $order->requested_date ? \Carbon\Carbon::parse($order->requested_date)->format('d M Y') : '-' }}
                            @if($order->requested_time)
                                &nbsp;pukul <strong>{{ \Carbon\Carbon::parse($order->requested_time)->format('H:i') }} WIB</strong>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Pelanggan</label>
                        <div class="fw-semibold">{{ $order->user->name ?? '-' }}</div>
                        <div class="small text-muted">{{ $order->user->email ?? '-' }}</div>
                        @if(optional($order->user)->phone)
                            <div class="small text-success mt-1">
                                <i class="fab fa-whatsapp me-1"></i> {{ $order->user->phone }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small mb-1">Metode Checkout & Pembayaran</label>
                        <div class="fw-semibold">{{ strtoupper($order->payment_method) }}</div>
                        <div>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($order->payment_status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                            @endif
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">Update Status Utama</h5>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="waiting_verification" {{ $order->status == 'waiting_verification' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Siap Diambil / Diproses</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan Status</button>
                    </form>
                </div>
            </div>

            <!-- Courier & Delivery Card -->
            @if($order->delivery_method === 'delivery')
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-primary">
                        <i class="fas fa-truck-fast me-2"></i> Informasi Pengiriman & Penugasan Kurir
                    </h5>
                    
                    {{-- Detail Alamat & Jarak Pelanggan --}}
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Tujuan Pengantaran</label>
                        <div class="fw-semibold text-dark small mb-2">
                            <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $order->delivery_address ?? '-' }}
                        </div>
                        @if($order->distance_km)
                            <div class="small text-muted mb-2">
                                <i class="fas fa-route text-primary me-1"></i> Jarak Tempuh: <strong>{{ $order->distance_km }} km</strong> dari toko
                            </div>
                        @endif
                        @if($order->delivery_lat && $order->delivery_lng)
                            <a href="https://www.google.com/maps?q={{ $order->delivery_lat }},{{ $order->delivery_lng }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-location-arrow me-1"></i> Buka Titik di Google Maps
                            </a>
                        @endif
                    </div>

                    {{-- Status Pengantaran Saat Ini --}}
                    <div class="mb-3">
                        <label class="text-muted small mb-1 d-block">Status Pengantaran Saat Ini:</label>
                        <div>
                            @if($order->delivery_status === 'dalam_pengantaran' || $order->delivery_status === 'dalam_perjalanan')
                                <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill">
                                    <i class="fas fa-motorcycle me-1"></i> Dalam Pengantaran
                                </span>
                            @elseif($order->delivery_status === 'terkirim')
                                <span class="badge bg-success text-white fs-6 px-3 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-1"></i> Terkirim / Selesai
                                </span>
                            @elseif($order->delivery_status === 'gagal_kirim')
                                <span class="badge bg-danger text-white fs-6 px-3 py-2 rounded-pill">
                                    <i class="fas fa-times-circle me-1"></i> Gagal Kirim
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i> Menunggu Kurir
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($order->delivered_at)
                    <div class="mb-3">
                        <label class="text-muted small mb-1">Waktu Sampai / Terkirim:</label>
                        <div class="fw-semibold text-success small">
                            <i class="fas fa-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($order->delivered_at)->format('d M Y H:i') }} WIB
                        </div>
                    </div>
                    @endif

                    {{-- Kurir yang Sedang Bertugas --}}
                    @if($order->courier || $order->courier_name)
                    <div class="p-3 bg-white rounded-3 border border-primary-subtle mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small fw-bold text-muted text-uppercase">Kurir Ditugaskan:</span>
                            <span class="badge bg-info-subtle text-info rounded-pill" style="font-size: 0.7rem;">
                                {{ $order->courier_id ? 'Kurir Toko' : 'Kurir Eksternal' }}
                            </span>
                        </div>
                        <div class="fw-bold text-dark mb-1">
                            <i class="fas fa-user-check text-primary me-1"></i> {{ $order->courier_display_name }}
                        </div>
                        @if($order->courier_display_phone && $order->courier_display_phone !== '-')
                            <div class="small mb-2">
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $order->courier_display_phone) }}" target="_blank" class="text-success text-decoration-none fw-semibold">
                                    <i class="fab fa-whatsapp me-1"></i> {{ $order->courier_display_phone }}
                                </a>
                            </div>
                        @endif
                        @if(optional($order->courier)->vehicle_type || optional($order->courier)->plate_number)
                            <div class="text-muted small">
                                <i class="fas fa-id-card me-1"></i> Kendaraan: {{ $order->courier->vehicle_type ?? 'Motor' }} ({{ $order->courier->plate_number ?? '-' }})
                            </div>
                        @endif
                    </div>
                    @endif

                    <hr class="my-3">

                    {{-- Form Penugasan Kurir & Update Status --}}
                    <h6 class="fw-bold mb-2">Form Penugasan Kurir</h6>
                    <form action="{{ route('admin.orders.assign-courier', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Pilih Kurir:</label>
                            <select name="courier_id" id="courierSelect" class="form-select rounded-3">
                                <option value="">-- Belum Ditugaskan / Lepas Kurir --</option>
                                <optgroup label="Kurir Toko Internal">
                                    @foreach($couriers as $courier)
                                        <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                            {{ $courier->name }} ({{ $courier->phone ?? '-' }})
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Kurir Eksternal">
                                    <option value="external" {{ (!$order->courier_id && $order->courier_name) ? 'selected' : '' }}>
                                        -- Input Kurir Eksternal / Ojek Online --
                                    </option>
                                </optgroup>
                            </select>
                        </div>

                        {{-- Input Manual Kurir Eksternal --}}
                        <div id="externalCourierFields" class="{{ (!$order->courier_id && $order->courier_name) ? '' : 'd-none' }} mb-3 p-3 bg-light rounded-3 border">
                            <p class="small fw-semibold text-dark mb-2">Data Kurir Eksternal / Ojol:</p>
                            <div class="mb-2">
                                <label class="small text-muted mb-1">Nama Kurir:</label>
                                <input type="text" name="courier_name" value="{{ $order->courier_name }}" class="form-control form-control-sm rounded-2" placeholder="Nama driver / kurir">
                            </div>
                            <div>
                                <label class="small text-muted mb-1">No. HP / WA Kurir:</label>
                                <input type="text" name="courier_phone" value="{{ $order->courier_phone }}" class="form-control form-control-sm rounded-2" placeholder="Contoh: 081234567890">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted mb-1">Status Pengantaran:</label>
                            <select name="delivery_status" class="form-select rounded-3" required>
                                <option value="menunggu_kurir" {{ ($order->delivery_status === 'menunggu_kurir' || empty($order->delivery_status) || $order->delivery_status === 'belum_ditugaskan') ? 'selected' : '' }}>
                                    Menunggu Kurir
                                </option>
                                <option value="dalam_pengantaran" {{ ($order->delivery_status === 'dalam_pengantaran' || $order->delivery_status === 'dalam_perjalanan' || $order->delivery_status === 'diambil_kurir' || $order->delivery_status === 'ditugaskan') ? 'selected' : '' }}>
                                    Dalam Pengantaran
                                </option>
                                <option value="terkirim" {{ $order->delivery_status === 'terkirim' ? 'selected' : '' }}>
                                    Terkirim / Selesai Diantar
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            <i class="fas fa-paper-plane me-1"></i> Simpan Penugasan & Status
                        </button>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const courierSelect = document.getElementById('courierSelect');
                    const externalFields = document.getElementById('externalCourierFields');
                    if (courierSelect && externalFields) {
                        courierSelect.addEventListener('change', function() {
                            if (this.value === 'external') {
                                externalFields.classList.remove('d-none');
                            } else {
                                externalFields.classList.add('d-none');
                            }
                        });
                    }
                });
            </script>
            @endif
        </div>
    </div>
</div>
@endsection
