@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white text-center" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-4">Tentang Strawberry Bakery</h1>
        <p class="lead mb-0 opacity-90 mx-auto" style="max-width: 700px;">
            Mempersembahkan roti dengan cinta dan dedikasi sejak 2015.
            Rasakan roti yang lembut, segar, dan rasa menggoda kami.
        </p>
    </div>
</section>

<!-- Cerita Kami Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <h2 class="h2 fw-bold text-dark text-center mb-5">Cerita Kami</h2>
        <div class="row align-items-center g-5">
            <div class="col-12 col-md-6">
                <!-- Using Toko.webp as the main store image -->
                <img src="{{ asset('images/tentangkami/Toko.webp') }}" alt="Strawberry Bakery Store" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover" style="max-height: 500px;">
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex flex-column gap-3 text-muted fs-5">
                    <p>
                        Strawberry Bakery dimulai dari passion sederhana: membuat roti berkualitas yang tidak membosankan siapa pun yang suka makan. Sejak 2015, kami telah berkomitmen untuk menggunakan bahan-bahan terbaik dan resep tradisional yang telah diwariskan dengan hati dan penuh dedikasi.
                    </p>
                    <p>
                        Setiap produk kami dibuat dengan tangan dan bahan berkualitas premium. Kami percaya bahwa roti yang baik dimulai dari gandum terbaik, dan kami hanya menggunakan tepung terigu tertinggi dari pemasok lokal terpercaya. Kami juga menggunakan mentega asli, telur organik, dan bahan alami tanpa pengawet atau pewarna buatan.
                    </p>
                    <p>
                        Kini dengan teknologi yang lebih maju di 25+ toko cabang, kami memiliki kebanggaan untuk terus menghadirkan produk yang tak kalah enak dan nikmat dari generasi ke generasi. Kami percaya bahwa roti yang baik bisa membawa kebahagiaan, menciptakan kenangan indah dan mempertemukan orang-orang tercinta Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Galeri Toko Section (New) -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <p class="text-primary fw-bold mb-2">GALERI KAMI</p>
            <h2 class="h2 fw-bold text-dark">Suasana Toko & Kreasi Kami</h2>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="{{ asset('images/tentangkami/Foto1.webp') }}" alt="Gallery 1" class="card-img-top object-fit-cover h-100" style="min-height: 250px;">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="{{ asset('images/tentangkami/Foto2.jpeg') }}" alt="Gallery 2" class="card-img-top object-fit-cover h-100" style="min-height: 250px;">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="{{ asset('images/tentangkami/Foto3.jpeg') }}" alt="Gallery 3" class="card-img-top object-fit-cover h-100" style="min-height: 250px;">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <img src="{{ asset('images/tentangkami/Foto4.jpeg') }}" alt="Gallery 4" class="card-img-top object-fit-cover h-100" style="min-height: 250px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Yang Kami Pegang Teguh Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <p class="text-primary fw-bold mb-2">NILAI KAMI</p>
            <h2 class="h2 fw-bold text-dark">Yang Kami Pegang Teguh</h2>
        </div>

        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-star text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Kualitas Premium</h3>
                        <p class="text-muted mb-0">
                            Kami hanya menggunakan bahan-bahan berkualitas tinggi dari sumber lokal yang dapat dipercaya untuk menghadirkan cita rasa terbaik.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-heart text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Dibuat dengan Cinta</h3>
                        <p class="text-muted mb-0">
                            Setiap produk dibuat dengan perhatian dan cinta oleh tim baker kami yang sangat berdedikasi dan berpengalaman.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-clock text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Fresh Daily</h3>
                        <p class="text-muted mb-0">
                            Memproduksi fresh setiap hari untuk memastikan kesegaran. Klik saat terbaik untuk hasil maksimal untuk Anda.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-bell-concierge text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Pelayanan Terbaik</h3>
                        <p class="text-muted mb-0">
                            Memberikan pengalaman berbelanja yang menyenangkan dengan pelayanan ramah dan cepat kepada setiap pelanggan kami.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-lightbulb text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Inovasi Berkelanjutan</h3>
                        <p class="text-muted mb-0">
                            Terus berinovasi dengan menciptakan produk baru tanpa meninggalkan kualitas dan cita rasa khas kami.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 bg-light">
                    <div class="card-body p-4 p-lg-5">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-leaf text-primary" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-dark mb-3">Sustainability</h3>
                        <p class="text-muted mb-0">
                            Berkomitmen pada praktik berkelanjutan dengan menggunakan kemasan ramah lingkungan dan mendukung petani lokal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-4 text-center justify-content-center">
            <div class="col-6 col-md-3">
                <h3 class="display-5 fw-bold text-primary mb-2">{{ date('Y') - 2020 }}+</h3>
                <p class="text-muted fw-medium mb-0">Tahun Pengalaman</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="display-5 fw-bold text-primary mb-2">{{ $totalCustomers }}+</h3>
                <p class="text-muted fw-medium mb-0">Pelanggan Puas</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="display-5 fw-bold text-primary mb-2">{{ $totalProducts }}+</h3>
                <p class="text-muted fw-medium mb-0">Varian Produk</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="display-5 fw-bold text-primary mb-2">{{ number_format($averageRating, 1) }}</h3>
                <p class="text-muted fw-medium mb-0">Rating Pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <p class="text-primary fw-bold mb-2">PROSES KAMI</p>
            <h2 class="h2 fw-bold text-dark">Dari Adonan ke Meja Anda</h2>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Step 1 -->
            <div class="col-12 col-md-6 col-lg-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem; font-weight: bold;">
                    1
                </div>
                <h3 class="h5 fw-bold text-dark mb-3">Seleksi Bahan</h3>
                <p class="text-muted">
                    Memilih bahan-bahan premium berkualitas tinggi dari supplier terpercaya lokal maupun internasional.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="col-12 col-md-6 col-lg-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem; font-weight: bold;">
                    2
                </div>
                <h3 class="h5 fw-bold text-dark mb-3">Pembuatan Adonan</h3>
                <p class="text-muted">
                    Mencampur dan menguleni adonan dengan cermat untuk mencapai tekstur yang sempurna.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="col-12 col-md-6 col-lg-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem; font-weight: bold;">
                    3
                </div>
                <h3 class="h5 fw-bold text-dark mb-3">Fermentasi & Pembakaran</h3>
                <p class="text-muted">
                    Proses fermentasi sempurna dan pembakaran dengan suhu yang tepat pada oven yang terjaga.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="col-12 col-md-6 col-lg-3 text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem; font-weight: bold;">
                    4
                </div>
                <h3 class="h5 fw-bold text-dark mb-3">Pengemasan</h3>
                <p class="text-muted">
                    Dikemas dengan hati-hati dalam wadah yang aman untuk menjaga kesegaran produk.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="text-center mb-5">
            <p class="text-primary fw-bold mb-2">TIM KAMI</p>
            <h2 class="h2 fw-bold text-dark">Kenali Master Baker Kami</h2>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Baker 1 -->
            <div class="col-12 col-md-4 text-center">
                <div class="card border-0 bg-transparent">
                    <img src="{{ asset('images/baker1.jpg') }}" alt="Chef Andre Wijaya" class="rounded-circle mx-auto mb-4 object-fit-cover shadow-sm" style="width: 200px; height: 200px;">
                    <h3 class="h5 fw-bold text-dark mb-1">Chef Andre Wijaya</h3>
                    <p class="text-primary fw-medium mb-3">Master Baker</p>
                    <p class="text-muted">
                        Dengan pengalaman 15 tahun di industri bakery, Chef Andre memimpin tim dengan dedikasi tinggi dan hasrat untuk menciptakan roti berkualitas.
                    </p>
                </div>
            </div>

            <!-- Baker 2 -->
            <div class="col-12 col-md-4 text-center">
                <div class="card border-0 bg-transparent">
                    <img src="{{ asset('images/baker2.jpg') }}" alt="Chef Sarah Putri" class="rounded-circle mx-auto mb-4 object-fit-cover shadow-sm" style="width: 200px; height: 200px;">
                    <h3 class="h5 fw-bold text-dark mb-1">Chef Sarah Putri</h3>
                    <p class="text-primary fw-medium mb-3">Pastry Chef</p>
                    <p class="text-muted">
                        Spesialis pastry dengan sentuhan inovatif. Chef Sarah menciptakan kreasi kue yang tidak hanya lezat tetapi juga memukau secara visual.
                    </p>
                </div>
            </div>

            <!-- Baker 3 -->
            <div class="col-12 col-md-4 text-center">
                <div class="card border-0 bg-transparent">
                    <img src="{{ asset('images/baker3.jpg') }}" alt="Chef David Chen" class="rounded-circle mx-auto mb-4 object-fit-cover shadow-sm" style="width: 200px; height: 200px;">
                    <h3 class="h5 fw-bold text-dark mb-1">Chef David Chen</h3>
                    <p class="text-primary fw-medium mb-3">Artisan Baker</p>
                    <p class="text-muted">
                        Ahli dalam pembuatan roti artisan klasik dan cita rasa autentik. Chef David menggunakan teknik traditional untuk hasil terbaik.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-white text-center" style="background: linear-gradient(135deg, var(--sb-primary) 0%, var(--sb-primary-dark) 100%);">
    <div class="container py-4">
        <h2 class="h2 fw-bold mb-4">Bergabunglah dengan Keluarga Kami</h2>
        <p class="lead mb-0 opacity-90 mx-auto" style="max-width: 600px;">
            Kunjungi toko kami dan rasakan kelezatan roti segar setiap hari!
        </p>
    </div>
</section>
@endsection
