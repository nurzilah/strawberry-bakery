@extends('layouts.app')

@section('title', 'Pertanyaan yang Sering Diajukan - Strawberry Bakery')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container text-center py-4">
        <h1 class="display-5 fw-bold mb-3">? Pertanyaan yang Sering Diajukan</h1>
        <p class="lead mb-4">Temukan jawaban untuk pertanyaan Anda di sini</p>

        <!-- Search Bar -->
        <div class="mx-auto" style="max-width: 600px;">
            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden bg-white">
                <input type="text"
                       placeholder="Cari pertanyaan Anda..."
                       class="form-control border-0 shadow-none px-4"
                       id="faqSearch" style="border-radius: 50rem 0 0 50rem;">
                <button class="btn btn-white border-0 bg-white pe-4 text-muted" type="button" style="border-radius: 0 50rem 50rem 0;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="container py-5">
    <!-- Category Tabs -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <button class="category-tab btn btn-strawberry rounded-pill px-4 active" data-category="semua">Semua</button>
        <button class="category-tab btn btn-light border rounded-pill px-4" data-category="pemesanan">Pemesanan</button>
        <button class="category-tab btn btn-light border rounded-pill px-4" data-category="pembayaran">Pembayaran</button>
        <button class="category-tab btn btn-light border rounded-pill px-4" data-category="produk">Produk</button>
        <button class="category-tab btn btn-light border rounded-pill px-4" data-category="akun">Akun</button>
        <button class="category-tab btn btn-light border rounded-pill px-4" data-category="lainnya">Lainnya</button>
    </div>

    <div class="row g-4 g-lg-5">
        <!-- Sidebar Categories -->
        <aside class="col-12 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold mb-4">Kategori</h3>
                    <ul class="nav flex-column gap-2">
                        <li class="nav-item"><a href="#pemesanan" class="nav-link px-0 py-1 text-primary fw-medium">Pemesanan</a></li>
                        <li class="nav-item"><a href="#pembayaran" class="nav-link px-0 py-1 text-muted hover-primary">Pembayaran</a></li>
                        <li class="nav-item"><a href="#produk" class="nav-link px-0 py-1 text-muted hover-primary">Produk</a></li>
                        <li class="nav-item"><a href="#pengambilan" class="nav-link px-0 py-1 text-muted hover-primary">Pengambilan</a></li>
                        <li class="nav-item"><a href="#akun" class="nav-link px-0 py-1 text-muted hover-primary">Akun</a></li>
                        <li class="nav-item"><a href="#rekomendasi" class="nav-link px-0 py-1 text-muted hover-primary">Rekomendasi</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- FAQ Content -->
        <div class="col-12 col-lg-9 d-flex flex-column gap-5">

            <!-- Pemesanan Section -->
            <div id="pemesanan" class="faq-section" data-category="pemesanan">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">📝</span> Pemesanan
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Bagaimana cara melakukan pre-order?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Untuk melakukan pre-order, Anda dapat mengikuti langkah berikut:</p>
                            <ol class="mb-0 ps-3">
                                <li class="mb-1">Pilih produk yang ingin dipesan dari katalog kami</li>
                                <li class="mb-1">Klik tombol "Pre-Order" pada halaman produk</li>
                                <li class="mb-1">Isi formulir pemesanan dengan detail lengkap</li>
                                <li class="mb-1">Pilih tanggal pengambilan (minimal H-2)</li>
                                <li class="mb-1">Lakukan pembayaran sesuai instruksi</li>
                                <li>Anda akan menerima konfirmasi melalui email/WhatsApp</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Berapa minimal waktu pre-order?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-0">Minimal waktu pre-order adalah H-2 (2 hari sebelum pengambilan) untuk memastikan kami dapat menyiapkan pesanan Anda dengan kualitas terbaik. Untuk pesanan dalam jumlah besar atau kue custom, disarankan melakukan pemesanan minimal H-5.</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah bisa membatalkan atau mengubah pesanan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Pembatalan atau perubahan pesanan dapat dilakukan maksimal H-1 sebelum tanggal pengambilan. Untuk pembatalan:</p>
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">Pembatalan H-1: Refund 50% dari total pembayaran</li>
                                <li class="mb-1">Pembatalan di hari yang sama: Tidak dapat di-refund</li>
                                <li>Perubahan pesanan dapat dilakukan jika bahan masih tersedia</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Section -->
            <div id="pembayaran" class="faq-section" data-category="pembayaran">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">💳</span> Pembayaran
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Metode pembayaran apa saja yang tersedia?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Kami menerima berbagai metode pembayaran untuk kemudahan Anda:</p>
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">Transfer Bank (BCA, Mandiri, BNI)</li>
                                <li class="mb-1">E-Wallet (GoPay, OVO, Dana)</li>
                                <li class="mb-1">QRIS</li>
                                <li class="mb-1">Kartu Kredit/Debit</li>
                                <li>Cash on Pickup (dibayar saat pengambilan)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Berapa lama verifikasi pembayaran?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-0">Verifikasi pembayaran dilakukan secara otomatis dan biasanya memakan waktu 1-10 menit untuk pembayaran digital. Untuk transfer bank manual, verifikasi dilakukan maksimal 2 jam pada jam kerja (09.00-17.00 WIB).</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah ada biaya tambahan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-0">Tidak ada biaya tambahan atau biaya tersembunyi. Harga yang tertera adalah harga final. Namun, untuk beberapa metode pembayaran seperti kartu kredit, mungkin ada biaya admin yang dikenakan oleh payment gateway (biasanya 2-3%).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Section -->
            <div id="produk" class="faq-section" data-category="produk">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">🍰</span> Produk
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Berapa lama roti dapat bertahan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Ketahanan produk kami:</p>
                            <ul class="mb-3 ps-3">
                                <li class="mb-1">Roti tawar: 3-4 hari di suhu ruang, 7 hari di kulkas</li>
                                <li class="mb-1">Roti manis: 2-3 hari di suhu ruang</li>
                                <li class="mb-1">Kue tart: 2-3 hari di kulkas</li>
                                <li>Kue kering: 2-3 minggu di wadah kedap udara</li>
                            </ul>
                            <p class="mb-0">Untuk hasil terbaik, simpan dalam wadah kedap udara dan konsumsi secepatnya.</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah produk mengandung bahan pengawet?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-0">Tidak, semua produk kami dibuat tanpa bahan pengawet buatan. Kami hanya menggunakan bahan-bahan alami dan berkualitas tinggi untuk memastikan kesegaran dan rasa terbaik. Inilah mengapa produk kami memiliki masa simpan yang lebih pendek dibanding produk komersial.</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah tersedia produk untuk penderita alergi?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Ya, kami menyediakan opsi khusus untuk penderita alergi:</p>
                            <ul class="mb-3 ps-3">
                                <li class="mb-1">Gluten-free</li>
                                <li class="mb-1">Sugar-free</li>
                                <li class="mb-1">Dairy-free</li>
                                <li>Nut-free</li>
                            </ul>
                            <p class="mb-0">Silakan informasikan alergi Anda saat melakukan pemesanan agar kami dapat menyiapkan produk yang sesuai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengambilan Section -->
            <div id="pengambilan" class="faq-section" data-category="lainnya">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">📦</span> Pengambilan
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Dimana lokasi pengambilan pesanan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Lokasi pengambilan pesanan di toko kami:</p>
                            <p class="fw-bold text-dark mb-2">Strawberry Bakery<br>
                            <span class="fw-normal text-muted">Jl. AR. Hakim No.149, Mangkukusuman, Kota Tegal</span></p>
                            <p class="mb-0">Jam operasional: Setiap hari, 08.00 - 21.00 WIB</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Bagaimana jika terlambat mengambil pesanan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Pesanan dapat diambil sesuai jadwal yang dipilih. Jika terlambat mengambil:</p>
                            <ul class="mb-0 ps-3">
                                <li class="mb-1">Hari yang sama: Pesanan masih dapat diambil hingga jam tutup toko</li>
                                <li class="mb-1">Keesokan hari: Harap hubungi kami terlebih dahulu untuk memastikan pesanan masih tersedia</li>
                                <li>Lebih dari 1 hari: Pesanan dianggap hangus dan tidak dapat di-refund</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah bisa diwakilkan orang lain untuk mengambil pesanan?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Ya, pesanan dapat diwakilkan. Yang perlu disiapkan:</p>
                            <ul class="mb-3 ps-3">
                                <li class="mb-1">Nomor pesanan (contoh: ORD-20251109-001)</li>
                                <li class="mb-1">Nama pemesan</li>
                                <li>Nomor telepon yang terdaftar</li>
                            </ul>
                            <p class="mb-0">Untuk keamanan, kami akan melakukan verifikasi sebelum menyerahkan pesanan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Akun Section -->
            <div id="akun" class="faq-section" data-category="akun">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">👤</span> Akun
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Bagaimana cara membuat akun?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Membuat akun sangat mudah:</p>
                            <ol class="mb-3 ps-3">
                                <li class="mb-1">Klik tombol "Register" di pojok kanan atas</li>
                                <li class="mb-1">Isi formulir dengan nama, email, dan password</li>
                                <li class="mb-1">Verifikasi email Anda melalui link yang dikirimkan</li>
                                <li>Akun Anda siap digunakan!</li>
                            </ol>
                            <p class="mb-0">Dengan akun, Anda dapat melacak pesanan, menyimpan alamat, dan mendapatkan penawaran khusus.</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Lupa password, bagaimana cara resetnya?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Untuk mereset password:</p>
                            <ol class="mb-0 ps-3">
                                <li class="mb-1">Klik "Lupa Password?" di halaman login</li>
                                <li class="mb-1">Masukkan email yang terdaftar</li>
                                <li class="mb-1">Cek email Anda untuk link reset password</li>
                                <li class="mb-1">Klik link dan buat password baru</li>
                                <li>Login dengan password baru Anda</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistem Rekomendasi Section -->
            <div id="rekomendasi" class="faq-section" data-category="lainnya">
                <h2 class="h4 fw-bold mb-4 d-flex align-items-center">
                    <span class="fs-2 me-2">🔍</span> Sistem Rekomendasi
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Bagaimana sistem rekomendasi bekerja?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-2">Sistem rekomendasi kami menggunakan algoritma cerdas yang menganalisis:</p>
                            <ul class="mb-3 ps-3">
                                <li class="mb-1">Riwayat pembelian Anda</li>
                                <li class="mb-1">Produk yang sering dilihat</li>
                                <li class="mb-1">Preferensi rasa dan jenis produk</li>
                                <li>Popularitas produk secara umum</li>
                            </ul>
                            <p class="mb-0">Semakin sering Anda berbelanja, semakin akurat rekomendasi yang kami berikan!</p>
                        </div>
                    </div>

                    <div class="faq-item card border-0 shadow-sm rounded-3 overflow-hidden">
                        <button class="faq-question btn btn-light text-start px-4 py-3 w-100 d-flex justify-content-between align-items-center bg-white border-0 rounded-0 fw-medium">
                            <span>Apakah rekomendasi akan lebih akurat seiring waktu?</span>
                            <i class="fa-solid fa-chevron-down text-muted transition-transform"></i>
                        </button>
                        <div class="faq-answer px-4 py-3 bg-white text-muted d-none border-top">
                            <p class="mb-0">Ya! Sistem kami terus belajar dari aktivitas Anda. Setiap kali Anda melihat produk, menambahkan ke keranjang, atau melakukan pembelian, sistem akan semakin memahami preferensi Anda dan memberikan rekomendasi yang lebih personal dan relevan.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Still Have Questions Section -->
<section class="py-5 text-white text-center mt-4" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container py-4">
        <h2 class="display-6 fw-bold mb-3">Masih Ada Pertanyaan?</h2>
        <p class="lead mb-5">Tim kami siap membantu Anda kapan saja!</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('contact') }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary">
                <i class="fa-solid fa-phone me-2"></i> Hubungi Kami
            </a>
            <a href="https://wa.me/628157173118" target="_blank" class="btn btn-success rounded-pill px-5 py-3 fw-bold">
                <i class="fa-brands fa-whatsapp me-2"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
    .hover-primary:hover {
        color: var(--sb-primary) !important;
    }
    .transition-transform {
        transition: transform 0.3s ease;
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
    html {
        scroll-padding-top: 100px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const icon = this.querySelector('i.fa-chevron-down');

            if (answer.classList.contains('d-none')) {
                answer.classList.remove('d-none');
                icon.classList.add('rotate-180');
                this.classList.add('expanded');
            } else {
                answer.classList.add('d-none');
                icon.classList.remove('rotate-180');
                this.classList.remove('expanded');
            }
        });
    });

    // Category Filter
    const categoryTabs = document.querySelectorAll('.category-tab');
    const faqSections = document.querySelectorAll('.faq-section');

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;

            // Update active tab
            categoryTabs.forEach(t => {
                t.classList.remove('active', 'btn-strawberry');
                t.classList.add('btn-light', 'border');
            });
            this.classList.add('active', 'btn-strawberry');
            this.classList.remove('btn-light', 'border');

            // Filter sections
            if (category === 'semua') {
                faqSections.forEach(section => section.classList.remove('d-none'));
            } else {
                faqSections.forEach(section => {
                    if (section.dataset.category === category) {
                        section.classList.remove('d-none');
                    } else {
                        section.classList.add('d-none');
                    }
                });
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('faqSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            faqSections.forEach(section => {
                const faqItems = section.querySelectorAll('.faq-item');
                let hasVisibleItem = false;

                faqItems.forEach(item => {
                    const questionText = item.querySelector('.faq-question span').textContent.toLowerCase();
                    const answerText = item.querySelector('.faq-answer').textContent.toLowerCase();

                    if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                        item.classList.remove('d-none');
                        hasVisibleItem = true;
                    } else {
                        item.classList.add('d-none');
                    }
                });

                if (hasVisibleItem || searchTerm === '') {
                    section.classList.remove('d-none');
                } else {
                    section.classList.add('d-none');
                }
            });
        });
    }

    // Smooth scroll for sidebar links
    document.querySelectorAll('aside .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            // Ensure section is visible
            const semuaTab = document.querySelector('.category-tab[data-category="semua"]');
            if (semuaTab) semuaTab.click();
            
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
                
                // Update active link
                document.querySelectorAll('aside .nav-link').forEach(l => {
                    l.classList.remove('text-primary', 'fw-medium');
                    l.classList.add('text-muted');
                });
                this.classList.remove('text-muted');
                this.classList.add('text-primary', 'fw-medium');
            }
        });
    });
});
</script>
@endpush
@endsection
