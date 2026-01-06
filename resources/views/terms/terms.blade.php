@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Strawberry Bakery')

@section('content')

<!-- Hero Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-500 py-10 px-4">
    <div class="container mx-auto text-center text-white">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Syarat & Ketentuan</h1>
        <p class="text-lg opacity-90">
            Harap membaca dengan seksama sebelum menggunakan layanan kami
        </p>
    </div>
</section>

<!-- Terms Content -->
<section class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- Sidebar Navigation -->
        <aside class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-md border border-pink-100 p-6 sticky top-24">
                <h3 class="font-bold text-lg mb-4 text-pink-600">Daftar Isi</h3>
                <nav class="space-y-2 text-sm">
                    <a href="#pendahuluan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Pendahuluan</a>
                    <a href="#akun" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Akun Pengguna</a>
                    <a href="#pemesanan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Pemesanan</a>
                    <a href="#pembayaran" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Pembayaran</a>
                    <a href="#pengambilan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Pengambilan Pesanan</a>
                    <a href="#pembatalan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Pembatalan & Refund</a>
                    <a href="#kekayaan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Hak Kekayaan Intelektual</a>
                    <a href="#privasi" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Privasi</a>
                    <a href="#disclaimer" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Disclaimer</a>
                    <a href="#perubahan" class="block py-1 text-gray-700 hover:text-pink-600 transition font-medium">Perubahan S&K</a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="lg:col-span-9">
            <div class="bg-white rounded-xl shadow-sm p-8 max-w-4xl">

                <!-- Update Info -->
                <div class="bg-pink-50 border-l-4 border-pink-500 p-4 mb-10 rounded">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        <strong>Tanggal diperbaharui:</strong> 1 November 2024<br>
                        <strong>Berlaku efektif:</strong> 1 November 2024
                    </p>
                </div>

                <!-- 1. Pendahuluan -->
                <section id="pendahuluan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">1. Pendahuluan</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>
                            Selamat datang di Strawberry Bakery. Dengan mengakses dan menggunakan website ini,
                            Anda menyetujui untuk terikat dan mematuhi syarat dan ketentuan penggunaan berikut.
                        </p>
                        <p>
                            Website ini dikelola oleh Strawberry Bakery yang berlokasi di Ngawi, East Java.
                            Setiap pesanan yang dilakukan melalui website ini dianggap sebagai penawaran untuk
                            membeli produk kami.
                        </p>
                    </div>
                </section>

                <!-- 2. Akun Pengguna -->
                <section id="akun" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">2. Akun Pengguna</h2>

                    <h3 class="text-lg font-semibold text-gray-700 mb-3">2.1 Pendaftaran Akun</h3>
                    <div class="prose prose-pink max-w-none text-gray-600 mb-4">
                        <ul>
                            <li>Menjaga kerahasiaan password</li>
                            <li>Memperbarui informasi akun</li>
                            <li>Tidak membagikan akun</li>
                            <li>Melaporkan penggunaan tanpa izin</li>
                        </ul>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-700 mb-3">2.2 Keamanan Akun</h3>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>
                            Anda bertanggung jawab penuh atas aktivitas akun Anda.
                        </p>
                    </div>
                </section>

                <!-- 3. Pemesanan -->
                <section id="pemesanan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">3. Pemesanan</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Pemesanan dilakukan minimal H-2 sebelum pengambilan.</p>
                    </div>
                </section>

                <!-- 4. Pembayaran -->
                <section id="pembayaran" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">4. Pembayaran</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <ul>
                            <li>Transfer Bank</li>
                            <li>E-Wallet</li>
                            <li>QRIS</li>
                        </ul>
                    </div>
                </section>

                <!-- 5. Pengambilan -->
                <section id="pengambilan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">5. Pengambilan Pesanan</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Pengambilan dilakukan di toko Strawberry Bakery.</p>
                    </div>
                </section>

                <!-- 6. Pembatalan -->
                <section id="pembatalan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">6. Pembatalan & Refund</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Pembatalan H-1 mendapat refund penuh.</p>
                    </div>
                </section>

                <!-- 7. Hak Kekayaan -->
                <section id="kekayaan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">7. Hak Kekayaan Intelektual</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Seluruh konten dilindungi undang-undang.</p>
                    </div>
                </section>

                <!-- 8. Privasi -->
                <section id="privasi" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">8. Privasi</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Kami menjaga keamanan data pribadi Anda.</p>
                    </div>
                </section>

                <!-- 9. Disclaimer -->
                <section id="disclaimer" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">9. Disclaimer</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Layanan disediakan apa adanya.</p>
                    </div>
                </section>

                <!-- 10. Perubahan -->
                <section id="perubahan" class="mb-12 scroll-mt-24">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">10. Perubahan S&K</h2>
                    <div class="prose prose-pink max-w-none text-gray-600">
                        <p>Syarat dapat berubah sewaktu-waktu.</p>
                    </div>
                </section>

                <!-- Contact -->
                <div class="bg-gray-50 rounded-lg p-6 mt-10">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Hubungi Kami</h3>
                    <p class="text-gray-600">
                        Email: info@strawberrybakery.com<br>
                        WhatsApp: +62 812-3456-7890
                    </p>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-500 py-14 px-4">
    <div class="container mx-auto text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ada Pertanyaan?</h2>
        <a href="{{ route('contact') }}"
           class="inline-block bg-white text-pink-500 px-8 py-3 rounded-full font-medium hover:bg-gray-100 transition">
            💬 Hubungi Kami
        </a>
    </div>
</section>

@endsection
