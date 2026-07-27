@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Strawberry Bakery')

@section('content')

<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container text-center py-4">
        <h1 class="display-5 fw-bold mb-3">Syarat & Ketentuan</h1>
        <p class="lead mb-0 opacity-75">
            Harap membaca dengan seksama sebelum menggunakan layanan kami
        </p>
    </div>
</section>

<!-- Terms Content -->
<section class="container py-5">
    <div class="row g-5">

        <!-- Sidebar Navigation -->
        <aside class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold mb-4 text-primary">Daftar Isi</h3>
                    <nav class="nav flex-column gap-2 text-sm">
                        <a href="#pendahuluan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Pendahuluan</a>
                        <a href="#akun" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Akun Pengguna</a>
                        <a href="#pemesanan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Pemesanan</a>
                        <a href="#pembayaran" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Pembayaran</a>
                        <a href="#pengambilan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Pengambilan Pesanan</a>
                        <a href="#pembatalan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Pembatalan & Refund</a>
                        <a href="#kekayaan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Hak Kekayaan Intelektual</a>
                        <a href="#privasi" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Privasi</a>
                        <a href="#disclaimer" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Disclaimer</a>
                        <a href="#perubahan" class="nav-link px-0 py-1 text-muted hover-primary fw-medium">Perubahan S&K</a>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- Update Info -->
                    <div class="alert bg-primary bg-opacity-10 border-start border-primary border-4 p-4 mb-5 rounded-3">
                        <p class="mb-0 text-dark">
                            <strong>Tanggal diperbaharui:</strong> 1 November 2024<br>
                            <strong>Berlaku efektif:</strong> 1 November 2024
                        </p>
                    </div>

                    <!-- 1. Pendahuluan -->
                    <section id="pendahuluan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">1. Pendahuluan</h2>
                        <div class="text-muted">
                            <p class="mb-3">
                                Selamat datang di Strawberry Bakery. Dengan mengakses dan menggunakan website ini,
                                Anda menyetujui untuk terikat dan mematuhi syarat dan ketentuan penggunaan berikut.
                            </p>
                            <p class="mb-0">
                                Website ini dikelola oleh Strawberry Bakery yang berlokasi di Ngawi, East Java.
                                Setiap pesanan yang dilakukan melalui website ini dianggap sebagai penawaran untuk
                                membeli produk kami.
                            </p>
                        </div>
                    </section>

                    <!-- 2. Akun Pengguna -->
                    <section id="akun" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-4">2. Akun Pengguna</h2>

                        <h3 class="h6 fw-bold text-dark mb-2">2.1 Pendaftaran Akun</h3>
                        <div class="text-muted mb-4">
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">Menjaga kerahasiaan password</li>
                                <li class="mb-1">Memperbarui informasi akun</li>
                                <li class="mb-1">Tidak membagikan akun</li>
                                <li>Melaporkan penggunaan tanpa izin</li>
                            </ul>
                        </div>

                        <h3 class="h6 fw-bold text-dark mb-2">2.2 Keamanan Akun</h3>
                        <div class="text-muted">
                            <p class="mb-0">
                                Anda bertanggung jawab penuh atas aktivitas akun Anda.
                            </p>
                        </div>
                    </section>

                    <!-- 3. Pemesanan -->
                    <section id="pemesanan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">3. Pemesanan</h2>
                        <div class="text-muted">
                            <p class="mb-0">Pemesanan dilakukan minimal H-2 sebelum pengambilan.</p>
                        </div>
                    </section>

                    <!-- 4. Pembayaran -->
                    <section id="pembayaran" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">4. Pembayaran</h2>
                        <div class="text-muted">
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">Transfer Bank</li>
                                <li class="mb-1">E-Wallet</li>
                                <li>QRIS</li>
                            </ul>
                        </div>
                    </section>

                    <!-- 5. Pengambilan -->
                    <section id="pengambilan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">5. Pengambilan Pesanan</h2>
                        <div class="text-muted">
                            <p class="mb-0">Pengambilan dilakukan di toko Strawberry Bakery.</p>
                        </div>
                    </section>

                    <!-- 6. Pembatalan -->
                    <section id="pembatalan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">6. Pembatalan & Refund</h2>
                        <div class="text-muted">
                            <p class="mb-0">Pembatalan H-1 mendapat refund penuh.</p>
                        </div>
                    </section>

                    <!-- 7. Hak Kekayaan -->
                    <section id="kekayaan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">7. Hak Kekayaan Intelektual</h2>
                        <div class="text-muted">
                            <p class="mb-0">Seluruh konten dilindungi undang-undang.</p>
                        </div>
                    </section>

                    <!-- 8. Privasi -->
                    <section id="privasi" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">8. Privasi</h2>
                        <div class="text-muted">
                            <p class="mb-0">Kami menjaga keamanan data pribadi Anda.</p>
                        </div>
                    </section>

                    <!-- 9. Disclaimer -->
                    <section id="disclaimer" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">9. Disclaimer</h2>
                        <div class="text-muted">
                            <p class="mb-0">Layanan disediakan apa adanya.</p>
                        </div>
                    </section>

                    <!-- 10. Perubahan -->
                    <section id="perubahan" class="mb-5" style="scroll-margin-top: 100px;">
                        <h2 class="h4 fw-bold text-dark mb-3">10. Perubahan S&K</h2>
                        <div class="text-muted">
                            <p class="mb-0">Syarat dapat berubah sewaktu-waktu.</p>
                        </div>
                    </section>

                    <!-- Contact -->
                    <div class="bg-light rounded-4 p-4 mt-5">
                        <h3 class="h5 fw-bold text-dark mb-3">Hubungi Kami</h3>
                        <p class="text-muted mb-0">
                            Email: info@strawberrybakery.com<br>
                            WhatsApp: +62 812-3456-7890
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-white mt-4" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container text-center py-4">
        <h2 class="display-6 fw-bold mb-4">Ada Pertanyaan?</h2>
        <a href="{{ route('contact') }}"
           class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary">
            <i class="fa-solid fa-comment-dots me-2"></i> Hubungi Kami
        </a>
    </div>
</section>

@push('styles')
<style>
    .hover-primary:hover {
        color: var(--sb-primary) !important;
    }
    html {
        scroll-padding-top: 100px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for sidebar links
    document.querySelectorAll('aside .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
                
                // Update active link
                document.querySelectorAll('aside .nav-link').forEach(l => {
                    l.classList.remove('text-primary');
                    l.classList.add('text-muted');
                });
                this.classList.remove('text-muted');
                this.classList.add('text-primary');
            }
        });
    });

    // Update active link on scroll
    window.addEventListener('scroll', function() {
        let current = '';
        const sections = document.querySelectorAll('section[id]');
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (scrollY >= sectionTop - 120) {
                current = section.getAttribute('id');
            }
        });

        document.querySelectorAll('aside .nav-link').forEach(link => {
            link.classList.remove('text-primary');
            link.classList.add('text-muted');
            if (link.getAttribute('href').substring(1) === current) {
                link.classList.remove('text-muted');
                link.classList.add('text-primary');
            }
        });
    });
});
</script>
@endpush

@endsection
