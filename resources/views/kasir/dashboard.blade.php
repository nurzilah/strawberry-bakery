@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Beranda Kasir')

@push('styles')
    <style>
        .kasir-hero {
            background: linear-gradient(135deg, #ffffff 0%, #fff1f4 100%);
            border: 1px solid rgba(233, 79, 112, 0.18);
            border-radius: 1.25rem;
            padding: 1.5rem;
        }

        .quick-action {
            align-items: stretch;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 1.1rem;
            color: #0f172a;
            display: flex;
            gap: 1rem;
            min-height: 180px;
            padding: 1.4rem;
            text-decoration: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .quick-action:hover {
            border-color: rgba(233, 79, 112, 0.35);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
            color: #0f172a;
            transform: translateY(-2px);
        }

        .quick-action__icon {
            align-items: center;
            background: #fff1f4;
            border-radius: 1rem;
            color: #e94f70;
            display: inline-flex;
            flex: 0 0 64px;
            font-size: 1.65rem;
            height: 64px;
            justify-content: center;
            width: 64px;
        }

        .quick-action__title {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.25;
            margin-bottom: 0.45rem;
        }

        .quick-action__text {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .quick-action__cta {
            color: #e94f70;
            font-weight: 800;
        }

        .shift-note {
            background: #fcf8f9;
            border: 1px solid #e8edf3;
            border-radius: 1rem;
            padding: 1rem;
        }
    </style>
@endpush

@section('content')
    <section class="kasir-hero mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <span class="badge bg-pink-100 text-pink-600 rounded-pill px-3 py-2 mb-3">Workspace Kasir Toko</span>
                <h1 class="h3 fw-bold mb-2">Fokus transaksi dan antrean pesanan.</h1>
                <p class="text-muted mb-0">
                    Gunakan halaman ini untuk input order offline, memantau antrean pickup, dan mencetak struk pelanggan.
                </p>
            </div>
            <div class="shift-note">
                <div class="small text-muted">Tanggal operasional</div>
                <div class="fw-bold">{{ now()->format('d M Y') }}</div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <a href="{{ route('kasir.transactions.create') }}" class="quick-action">
                <span class="quick-action__icon">
                    <i class="fa-solid fa-plus"></i>
                </span>
                <span class="d-flex flex-column">
                    <span class="quick-action__title">Input Transaksi Baru (POS)</span>
                    <span class="quick-action__text">
                        Buat pesanan langsung dari toko, pilih pelanggan, tanggal pickup, metode pembayaran, dan item produk.
                    </span>
                    <span class="quick-action__cta mt-auto">Mulai transaksi <i class="fa-solid fa-arrow-right ms-1"></i></span>
                </span>
            </a>
        </div>

        <div class="col-12 col-lg-6">
            <a href="{{ route('kasir.orders.index') }}" class="quick-action">
                <span class="quick-action__icon">
                    <i class="fa-solid fa-list-check"></i>
                </span>
                <span class="d-flex flex-column">
                    <span class="quick-action__title">Lihat Antrean Pesanan Hari Ini</span>
                    <span class="quick-action__text">
                        Pantau pesanan online dan offline, konfirmasi pembayaran, ubah status pickup, lalu cetak struk.
                    </span>
                    <span class="quick-action__cta mt-auto">Buka antrean <i class="fa-solid fa-arrow-right ms-1"></i></span>
                </span>
            </a>
        </div>
    </div>
@endsection
