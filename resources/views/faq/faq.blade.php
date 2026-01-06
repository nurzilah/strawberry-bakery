@extends('layouts.app')

@section('title', 'Pertanyaan yang Sering Diajukan - Strawberry Bakery')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-500 py-16 px-4">
    <div class="container mx-auto text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">? Pertanyaan yang Sering Diajukan</h1>
        <p class="text-lg mb-6">Temukan jawaban untuk pertanyaan Anda di sini</p>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <div class="relative">
                <input type="text"
                       placeholder="Cari pertanyaan Anda..."
                       class="w-full px-6 py-3 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300"
                       id="faqSearch">
                <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Category Tabs -->
<section class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap gap-3 justify-center mb-8">
        <button class="category-tab active px-6 py-2 rounded-full bg-pink-500 text-white font-medium" data-category="semua">Semua</button>
        <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" data-category="pemesanan">Pemesanan</button>
        <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" data-category="pembayaran">Pembayaran</button>
        <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" data-category="produk">Produk</button>
        <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" data-category="akun">Akun</button>
        <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 font-medium hover:bg-gray-300" data-category="lainnya">Lainnya</button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Categories -->
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-bold text-lg mb-4">Kategori</h3>
                <ul class="space-y-3">
                    <li><a href="#pemesanan" class="text-pink-500 hover:underline">Pemesanan</a></li>
                    <li><a href="#pembayaran" class="text-gray-700 hover:text-pink-500">Pembayaran</a></li>
                    <li><a href="#produk" class="text-gray-700 hover:text-pink-500">Produk</a></li>
                    <li><a href="#pengambilan" class="text-gray-700 hover:text-pink-500">Pengambilan</a></li>
                    <li><a href="#akun" class="text-gray-700 hover:text-pink-500">Akun</a></li>
                    <li><a href="#rekomendasi" class="text-gray-700 hover:text-pink-500">Rekomendasi</a></li>
                </ul>
            </div>
        </aside>

        <!-- FAQ Content -->
        <div class="lg:col-span-3 space-y-8">

            <!-- Pemesanan Section -->
            <div id="pemesanan" class="faq-section" data-category="pemesanan">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">📝</span>Pemesanan
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Bagaimana cara melakukan pre-order?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Untuk melakukan pre-order, Anda dapat mengikuti langkah berikut:</p>
                            <ol class="list-decimal ml-5 mt-2 space-y-1">
                                <li>Pilih produk yang ingin dipesan dari katalog kami</li>
                                <li>Klik tombol "Pre-Order" pada halaman produk</li>
                                <li>Isi formulir pemesanan dengan detail lengkap</li>
                                <li>Pilih tanggal pengambilan (minimal H-2)</li>
                                <li>Lakukan pembayaran sesuai instruksi</li>
                                <li>Anda akan menerima konfirmasi melalui email/WhatsApp</li>
                            </ol>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Berapa minimal waktu pre-order?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Minimal waktu pre-order adalah H-2 (2 hari sebelum pengambilan) untuk memastikan kami dapat menyiapkan pesanan Anda dengan kualitas terbaik. Untuk pesanan dalam jumlah besar atau kue custom, disarankan melakukan pemesanan minimal H-5.</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah bisa membatalkan atau mengubah pesanan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Pembatalan atau perubahan pesanan dapat dilakukan maksimal H-1 sebelum tanggal pengambilan. Untuk pembatalan:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Pembatalan H-1: Refund 50% dari total pembayaran</li>
                                <li>Pembatalan di hari yang sama: Tidak dapat di-refund</li>
                                <li>Perubahan pesanan dapat dilakukan jika bahan masih tersedia</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran Section -->
            <div id="pembayaran" class="faq-section" data-category="pembayaran">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">💳</span>Pembayaran
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Metode pembayaran apa saja yang tersedia?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Kami menerima berbagai metode pembayaran untuk kemudahan Anda:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Transfer Bank (BCA, Mandiri, BNI)</li>
                                <li>E-Wallet (GoPay, OVO, Dana)</li>
                                <li>QRIS</li>
                                <li>Kartu Kredit/Debit</li>
                                <li>Cash on Pickup (dibayar saat pengambilan)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Berapa lama verifikasi pembayaran?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Verifikasi pembayaran dilakukan secara otomatis dan biasanya memakan waktu 1-10 menit untuk pembayaran digital. Untuk transfer bank manual, verifikasi dilakukan maksimal 2 jam pada jam kerja (09.00-17.00 WIB).</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah ada biaya tambahan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Tidak ada biaya tambahan atau biaya tersembunyi. Harga yang tertera adalah harga final. Namun, untuk beberapa metode pembayaran seperti kartu kredit, mungkin ada biaya admin yang dikenakan oleh payment gateway (biasanya 2-3%).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Section -->
            <div id="produk" class="faq-section" data-category="produk">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">🍰</span>Produk
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Berapa lama roti dapat bertahan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Ketahanan produk kami:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Roti tawar: 3-4 hari di suhu ruang, 7 hari di kulkas</li>
                                <li>Roti manis: 2-3 hari di suhu ruang</li>
                                <li>Kue tart: 2-3 hari di kulkas</li>
                                <li>Kue kering: 2-3 minggu di wadah kedap udara</li>
                            </ul>
                            <p class="mt-2">Untuk hasil terbaik, simpan dalam wadah kedap udara dan konsumsi secepatnya.</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah produk mengandung bahan pengawet?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Tidak, semua produk kami dibuat tanpa bahan pengawet buatan. Kami hanya menggunakan bahan-bahan alami dan berkualitas tinggi untuk memastikan kesegaran dan rasa terbaik. Inilah mengapa produk kami memiliki masa simpan yang lebih pendek dibanding produk komersial.</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah tersedia produk untuk penderita alergi?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Ya, kami menyediakan opsi khusus untuk penderita alergi:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Gluten-free</li>
                                <li>Sugar-free</li>
                                <li>Dairy-free</li>
                                <li>Nut-free</li>
                            </ul>
                            <p class="mt-2">Silakan informasikan alergi Anda saat melakukan pemesanan agar kami dapat menyiapkan produk yang sesuai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengambilan Section -->
            <div id="pengambilan" class="faq-section" data-category="lainnya">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">📦</span>Pengambilan
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Dimana lokasi pengambilan pesanan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Lokasi pengambilan pesanan di toko kami:</p>
                            <p class="font-medium mt-2">Strawberry Bakery<br>
                            Jl. Makmur Sejahtera No. 123<br>
                            Ngawi, East Java 63251</p>
                            <p class="mt-2">Jam operasional: Senin-Sabtu (09.00-19.00), Minggu (10.00-17.00)</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Bagaimana jika terlambat mengambil pesanan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Pesanan dapat diambil sesuai jadwal yang dipilih. Jika terlambat mengambil:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Hari yang sama: Pesanan masih dapat diambil hingga jam tutup toko</li>
                                <li>Keesokan hari: Harap hubungi kami terlebih dahulu untuk memastikan pesanan masih tersedia</li>
                                <li>Lebih dari 1 hari: Pesanan dianggap hangus dan tidak dapat di-refund</li>
                            </ul>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah bisa diwakilkan orang lain untuk mengambil pesanan?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Ya, pesanan dapat diwakilkan. Yang perlu disiapkan:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Nomor pesanan (contoh: ORD-20251109-001)</li>
                                <li>Nama pemesan</li>
                                <li>Nomor telepon yang terdaftar</li>
                            </ul>
                            <p class="mt-2">Untuk keamanan, kami akan melakukan verifikasi sebelum menyerahkan pesanan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Akun Section -->
            <div id="akun" class="faq-section" data-category="akun">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">👤</span>Akun
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Bagaimana cara membuat akun?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Membuat akun sangat mudah:</p>
                            <ol class="list-decimal ml-5 mt-2 space-y-1">
                                <li>Klik tombol "Daftar" di pojok kanan atas</li>
                                <li>Isi formulir dengan nama, email, dan password</li>
                                <li>Verifikasi email Anda melalui link yang dikirimkan</li>
                                <li>Akun Anda siap digunakan!</li>
                            </ol>
                            <p class="mt-2">Dengan akun, Anda dapat melacak pesanan, menyimpan alamat, dan mendapatkan penawaran khusus.</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Lupa password, bagaimana cara resetnya?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Untuk mereset password:</p>
                            <ol class="list-decimal ml-5 mt-2 space-y-1">
                                <li>Klik "Lupa Password?" di halaman login</li>
                                <li>Masukkan email yang terdaftar</li>
                                <li>Cek email Anda untuk link reset password</li>
                                <li>Klik link dan buat password baru</li>
                                <li>Login dengan password baru Anda</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistem Rekomendasi Section -->
            <div id="rekomendasi" class="faq-section" data-category="lainnya">
                <h2 class="text-2xl font-bold mb-4 flex items-center">
                    <span class="text-3xl mr-2">🔍</span>Sistem Rekomendasi
                </h2>
                <div class="space-y-3">
                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Bagaimana sistem rekomendasi bekerja?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Sistem rekomendasi kami menggunakan algoritma cerdas yang menganalisis:</p>
                            <ul class="list-disc ml-5 mt-2 space-y-1">
                                <li>Riwayat pembelian Anda</li>
                                <li>Produk yang sering dilihat</li>
                                <li>Preferensi rasa dan jenis produk</li>
                                <li>Popularitas produk secara umum</li>
                            </ul>
                            <p class="mt-2">Semakin sering Anda berbelanja, semakin akurat rekomendasi yang kami berikan!</p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-lg shadow-sm">
                        <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium">Apakah rekomendasi akan lebih akurat sering waktu?</span>
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="faq-answer hidden px-6 py-4 text-gray-600">
                            <p>Ya! Sistem kami terus belajar dari aktivitas Anda. Setiap kali Anda melihat produk, menambahkan ke keranjang, atau melakukan pembelian, sistem akan semakin memahami preferensi Anda dan memberikan rekomendasi yang lebih personal dan relevan.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Still Have Questions Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-500 py-16 px-4 mt-12">
    <div class="container mx-auto text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Masih Ada Pertanyaan?</h2>
        <p class="text-lg mb-6">Tim kami siap membantu Anda kapan saja!</p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('contact') }}" class="bg-white text-pink-500 px-8 py-3 rounded-full font-medium hover:bg-gray-100 transition">
                ☎️ Hubungi Kami
            </a>
            <a href="https://wa.me/6281234567890" target="_blank" class="bg-green-500 text-white px-8 py-3 rounded-full font-medium hover:bg-green-600 transition">
                💬 Chat WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- FAQ Accordion Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            const arrow = this.querySelector('svg');

            answer.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
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
                t.classList.remove('active', 'bg-pink-500', 'text-white');
                t.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.add('active', 'bg-pink-500', 'text-white');
            this.classList.remove('bg-gray-200', 'text-gray-700');

            // Filter sections
            if (category === 'semua') {
                faqSections.forEach(section => section.style.display = 'block');
            } else {
                faqSections.forEach(section => {
                    if (section.dataset.category === category) {
                        section.style.display = 'block';
                    } else {
                        section.style.display = 'none';
                    }
                });
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('faqSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();

        faqSections.forEach(section => {
            const faqItems = section.querySelectorAll('.faq-item');
            let hasVisibleItem = false;

            faqItems.forEach(item => {
                const questionText = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answerText = item.querySelector('.faq-answer').textContent.toLowerCase();

                if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
                    item.style.display = 'block';
                    hasVisibleItem = true;
                } else {
                    item.style.display = 'none';
                }
            });

            section.style.display = hasVisibleItem || searchTerm === '' ? 'block' : 'none';
        });
    });
});
