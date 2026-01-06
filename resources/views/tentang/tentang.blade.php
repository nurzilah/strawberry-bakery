@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-300 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang Strawberry Bakery</h1>
        <p class="text-lg md:text-xl max-w-2xl mx-auto">
            Mempersembahkan roti dengan cinta dan dedikasi sejak 2015.
            Rasakan roti yang lembut, segar, dan rasa menggoda kami.
        </p>
    </div>
</section>

<!-- Cerita Kami Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Cerita Kami</h2>
        <div class="grid md:grid-cols-2 gap-8 items-center max-w-6xl mx-auto">
            <div>
                <img src="{{ asset('images/bread-story.jpg') }}" alt="Bread" class="rounded-lg shadow-lg w-full">
            </div>
            <div class="space-y-4">
                <p class="text-gray-700 leading-relaxed">
                    Strawberry Bakery dimulai dari passion sederhana: membuat roti berkualitas yang tidak membosankan siapa pun yang suka makan. Sejak 2015, kami telah berkomitmen untuk menggunakan bahan-bahan terbaik dan resep tradisional yang telah diwariskan dengan hati dan penuh dedikasi.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Setiap produk kami dibuat dengan tangan dan bahan berkualitas premium. Kami percaya bahwa roti yang baik dimulai dari gandum terbaik, dan kami hanya menggunakan tepung terigu tertinggi dari pemasok lokal terpercaya. Kami juga menggunakan mentega asli, telur organik, dan bahan alami tanpa pengawet atau pewarna buatan.
                </p>
                <p class="text-gray-700 leading-relaxed">
                    Kini dengan teknologi yang lebih maju di 25+ toko cabang, kami memiliki kebanggaan untuk terus menghadirkan produk yang tak kalah enak dan nikmat dari generasi ke generasi. Kami percaya bahwa roti yang baik bisa membawa kebahagiaan, menciptakan kenangan indah dan mempertemukan orang-orang tercinta Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Yang Kami Pegang Teguh Section -->
<section class="py-16 bg-pink-50">
    <div class="container mx-auto px-4">
        <p class="text-pink-500 text-center text-sm font-semibold mb-2">NILAI KAMI</p>
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Yang Kami Pegang Teguh</h2>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Kualitas Premium</h3>
                <p class="text-gray-600">
                    Kami hanya menggunakan bahan-bahan berkualitas tinggi dari sumber lokal yang dapat dipercaya untuk menghadirkan cita rasa terbaik.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Dibuat dengan Cinta</h3>
                <p class="text-gray-600">
                    Setiap produk dibuat dengan perhatian dan cinta oleh tim baker kami yang sangat berdedikasi dan berpengalaman.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Fresh Daily</h3>
                <p class="text-gray-600">
                    Memproduksi fresh setiap hari untuk memastikan kesegaran. Klik saat terbaik untuk hasil maksimal untuk Anda.
                </p>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Pelayanan Terbaik</h3>
                <p class="text-gray-600">
                    Memberikan pengalaman berbelanja yang menyenangkan dengan pelayanan ramah dan cepat kepada setiap pelanggan kami.
                </p>
            </div>

            <!-- Card 5 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Inovasi Berkelanjutan</h3>
                <p class="text-gray-600">
                    Terus berinovasi dengan menciptakan produk baru tanpa meninggalkan kualitas dan cita rasa khas kami.
                </p>
            </div>

            <!-- Card 6 -->
            <div class="bg-white rounded-lg p-6 text-center shadow-md">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Sustainability</h3>
                <p class="text-gray-600">
                    Berkomitmen pada praktik berkelanjutan dengan menggunakan kemasan ramah lingkungan dan mendukung petani lokal.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto text-center">
            <div>
                <h3 class="text-4xl md:text-5xl font-bold text-pink-400 mb-2">10+</h3>
                <p class="text-gray-600">Tahun Pengalaman</p>
            </div>
            <div>
                <h3 class="text-4xl md:text-5xl font-bold text-pink-400 mb-2">50K+</h3>
                <p class="text-gray-600">Pelanggan Puas</p>
            </div>
            <div>
                <h3 class="text-4xl md:text-5xl font-bold text-pink-400 mb-2">100+</h3>
                <p class="text-gray-600">Varian Produk</p>
            </div>
            <div>
                <h3 class="text-4xl md:text-5xl font-bold text-pink-400 mb-2">4.9</h3>
                <p class="text-gray-600">Rating Pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-16 bg-pink-50">
    <div class="container mx-auto px-4">
        <p class="text-pink-500 text-center text-sm font-semibold mb-2">PROSES KAMI</p>
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Dari Adonan ke Meja Anda</h2>

        <div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                    1
                </div>
                <h3 class="text-xl font-bold mb-3">Seleksi Bahan</h3>
                <p class="text-gray-600">
                    Memilih bahan-bahan premium berkualitas tinggi dari supplier terpercaya lokal maupun internasional.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                    2
                </div>
                <h3 class="text-xl font-bold mb-3">Pembuatan Adonan</h3>
                <p class="text-gray-600">
                    Mencampur dan menguleni adonan dengan cermat untuk mencapai tekstur yang sempurna.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                    3
                </div>
                <h3 class="text-xl font-bold mb-3">Fermentasi & Pembakaran</h3>
                <p class="text-gray-600">
                    Proses fermentasi sempurna dan pembakaran dengan suhu yang tepat pada oven yang terjaga.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-300 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">
                    4
                </div>
                <h3 class="text-xl font-bold mb-3">Pengemasan</h3>
                <p class="text-gray-600">
                    Dikemas dengan hati-hati dalam wadah yang aman untuk menjaga kesegaran produk.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-pink-500 text-center text-sm font-semibold mb-2">TIM KAMI</p>
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Kenali Master Baker Kami</h2>

        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Baker 1 -->
            <div class="text-center">
                <img src="{{ asset('images/baker1.jpg') }}" alt="Chef Andre Wijaya" class="w-48 h-48 rounded-lg mx-auto mb-4 object-cover shadow-lg">
                <h3 class="text-xl font-bold mb-1">Chef Andre Wijaya</h3>
                <p class="text-pink-400 text-sm mb-3">Master Baker</p>
                <p class="text-gray-600 text-sm">
                    Dengan pengalaman 15 tahun di industri bakery, Chef Andre memimpin tim dengan dedikasi tinggi dan hasrat untuk menciptakan roti berkualitas.
                </p>
            </div>

            <!-- Baker 2 -->
            <div class="text-center">
                <img src="{{ asset('images/baker2.jpg') }}" alt="Chef Sarah Putri" class="w-48 h-48 rounded-lg mx-auto mb-4 object-cover shadow-lg">
                <h3 class="text-xl font-bold mb-1">Chef Sarah Putri</h3>
                <p class="text-pink-400 text-sm mb-3">Pastry Chef</p>
                <p class="text-gray-600 text-sm">
                    Spesialis pastry dengan sentuhan inovatif. Chef Sarah menciptakan kreasi kue yang tidak hanya lezat tetapi juga memukau secara visual.
                </p>
            </div>

            <!-- Baker 3 -->
            <div class="text-center">
                <img src="{{ asset('images/baker3.jpg') }}" alt="Chef David Chen" class="w-48 h-48 rounded-lg mx-auto mb-4 object-cover shadow-lg">
                <h3 class="text-xl font-bold mb-1">Chef David Chen</h3>
                <p class="text-pink-400 text-sm mb-3">Artisan Baker</p>
                <p class="text-gray-600 text-sm">
                    Ahli dalam pembuatan roti artisan klasik dan cita rasa autentik. Chef David menggunakan teknik traditional untuk hasil terbaik.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-pink-400 to-pink-300 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Bergabunglah dengan Keluarga Kami</h2>
        <p class="text-lg mb-8 max-w-2xl mx-auto">
            Kunjungi toko kami dan rasakan kelezatan roti segar setiap hari!
        </p>

    </div>
</section>
@endsection
