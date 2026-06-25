@extends('layouts.app')

@section('title', 'Hubungi Kami')

@push('styles')
    <style>
        .contact-hero {
            margin-top: -3rem;
            padding: 4.5rem 0 3.5rem;
        }

        .contact-hero__title {
            color: #ffffff;
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 700;
            line-height: 1.12;
            margin-bottom: 0;
        }

        .contact-hero__text {
            color: rgba(255, 255, 255, 0.88);
            font-size: 1rem;
            line-height: 1.75;
            margin: 1rem auto 0;
            max-width: 42rem;
        }

        .contact-section {
            background-color: var(--sb-primary-soft);
            padding: 3rem 0 4rem;
        }

        .contact-panel {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1rem;
            box-shadow: var(--sb-shadow);
        }

        .contact-info-card {
            display: flex;
            gap: 1rem;
            height: 100%;
            padding: 1.25rem;
        }

        .contact-icon {
            align-items: center;
            background-color: var(--sb-primary-soft);
            border: 1px solid rgba(233, 79, 112, 0.18);
            border-radius: 999px;
            color: var(--sb-primary);
            display: inline-flex;
            flex: 0 0 auto;
            height: 3rem;
            justify-content: center;
            width: 3rem;
        }

        .contact-title {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 0;
        }

        .contact-card-title {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }

        .contact-muted {
            color: #64748b;
            font-size: 0.92rem;
            line-height: 1.65;
            margin-bottom: 0;
        }

        .contact-link {
            color: var(--sb-primary);
            display: block;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .contact-map {
            aspect-ratio: 16 / 7;
            min-height: 320px;
            width: 100%;
        }

        .contact-faq details {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            padding: 1rem 1.25rem;
        }

        .contact-faq summary {
            align-items: center;
            color: #0f172a;
            cursor: pointer;
            display: flex;
            font-weight: 700;
            justify-content: space-between;
            list-style: none;
        }

        .contact-faq summary::-webkit-details-marker {
            display: none;
        }

        .contact-social {
            align-items: center;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            border-radius: 999px;
            color: var(--sb-primary);
            display: inline-flex;
            height: 3rem;
            justify-content: center;
            transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
            width: 3rem;
        }

        .contact-social:hover {
            background-color: var(--sb-primary);
            color: #ffffff;
            transform: translateY(-2px);
        }

        @media (max-width: 575.98px) {
            .contact-hero {
                margin-top: -2rem;
                padding: 3.25rem 0 2.75rem;
            }

            .contact-info-card {
                padding: 1rem;
            }

            .contact-map {
                aspect-ratio: 1 / 1;
                min-height: 280px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="text-white text-center py-5 mb-5" style="background: linear-gradient(135deg, rgba(233, 79, 112, 0.95), rgba(201, 52, 85, 0.95)), url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200') center/cover; border-radius: 0 0 2rem 2rem; margin-top: -3rem; padding-top: 5rem !important;">
        <div class="container py-4">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 shadow-sm fw-bold"><i class="fa-solid fa-headset me-1"></i> Customer Support</span>
            <h1 class="display-4 fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Hubungi Kami</h1>
            <p class="lead mb-0 text-white-50 mx-auto" style="max-width: 600px;">
                Kami siap membantu pemesanan, pre-order, pembayaran, dan informasi produk Strawberry Cake & Bakery Tegal.
            </p>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-5">
                    <div class="row g-3">
                        <div class="col-12">
                            <article class="contact-panel contact-info-card">
                                <span class="contact-icon"><i class="fa-solid fa-location-dot"></i></span>
                                <div>
                                    <h2 class="contact-card-title">Alamat Toko</h2>
                                    <p class="contact-muted">
                                        Jl. AR. Hakim No.149, Mangkukusuman, Kec. Tegal Tim., Kota Tegal, Jawa Tengah.
                                    </p>
                                </div>
                            </article>
                        </div>

                        <div class="col-12 col-md-6 col-lg-12">
                            <article class="contact-panel contact-info-card">
                                <span class="contact-icon"><i class="fa-solid fa-phone"></i></span>
                                <div>
                                    <h2 class="contact-card-title">Telepon</h2>
                                    <a href="tel:+6281575123118" class="contact-link">+62 815-7512-3118</a>
                                    <a href="tel:+6285842810778" class="contact-link">+62 858-4281-0778</a>
                                </div>
                            </article>
                        </div>

                        <div class="col-12 col-md-6 col-lg-12">
                            <article class="contact-panel contact-info-card">
                                <span class="contact-icon"><i class="fa-regular fa-envelope"></i></span>
                                <div>
                                    <h2 class="contact-card-title">Email</h2>
                                    <a href="mailto:info@strawberrybakery.com" class="contact-link">info@strawberrybakery.com</a>
                                    <a href="mailto:order@strawberrybakery.com" class="contact-link">order@strawberrybakery.com</a>
                                </div>
                            </article>
                        </div>

                        <div class="col-12">
                            <article class="contact-panel contact-info-card">
                                <span class="contact-icon"><i class="fa-regular fa-clock"></i></span>
                                <div>
                                    <h2 class="contact-card-title">Jam Operasional</h2>
                                    <p class="contact-muted">Setiap hari, 08.00 - 21.00 WIB.</p>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="contact-panel p-4 p-md-5">
                        <h2 class="contact-title">Kirim Pesan</h2>
                        <p class="contact-muted mt-2 mb-4">
                            Isi formulir berikut, tim kami akan menghubungi Anda secepatnya.
                        </p>

                        @if(session('success'))
                            <div class="alert alert-success border-0 rounded-4 mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="phone" class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="subject" class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                                    <input id="subject" type="text" name="subject" value="{{ old('subject') }}" class="form-control @error('subject') is-invalid @enderror" placeholder="Contoh: Pre-order cake" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                    <textarea id="message" name="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-strawberry rounded-pill w-100 py-3">
                                        <i class="fa-regular fa-paper-plane"></i>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-4">
                <span class="text-uppercase fw-bold" style="color: var(--sb-primary);">Lokasi</span>
                <h2 class="contact-title mt-2">Lokasi Kami</h2>
                <p class="contact-muted mt-2">Jl. AR. Hakim No.149, Mangkukusuman, Kec. Tegal Tim., Kota Tegal, Jawa Tengah.</p>
            </div>

            <div class="contact-panel overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1139371247173!2d109.13642019999999!3d-6.8769504999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fb9d443a4af25%3A0x8b142b6849cf9ecd!2sStrawberry%20Bakery!5e0!3m2!1sen!2sid!4v1782323122049!5m2!1sen!2sid"
                    class="contact-map border-0 d-block"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    title="Lokasi Strawberry Cake & Bakery Tegal">
                </iframe>
            </div>
        </div>
    </section>

    <section class="py-5" style="background-color: var(--sb-primary-soft);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="text-center mb-4">
                        <span class="text-uppercase fw-bold" style="color: var(--sb-primary);">FAQ</span>
                        <h2 class="contact-title mt-2">Pertanyaan yang Sering Diajukan</h2>
                    </div>

                    <div class="contact-faq d-grid gap-3">
                        <details>
                            <summary>Bagaimana cara melakukan pre-order?<i class="fa-solid fa-chevron-down small"></i></summary>
                            <p class="contact-muted mt-3">Pilih produk dari katalog, masukkan ke keranjang, lalu selesaikan pembayaran. Pre-order minimal H-2 sebelum tanggal pengambilan.</p>
                        </details>

                        <details>
                            <summary>Metode pembayaran apa saja yang tersedia?<i class="fa-solid fa-chevron-down small"></i></summary>
                            <p class="contact-muted mt-3">Saat ini pembayaran dapat dilakukan melalui transfer bank dan e-wallet yang tersedia di halaman checkout.</p>
                        </details>

                        <details>
                            <summary>Apakah bisa membatalkan pesanan?<i class="fa-solid fa-chevron-down small"></i></summary>
                            <p class="contact-muted mt-3">Pembatalan dapat dibantu maksimal H-1 sebelum tanggal pengambilan. Hubungi admin untuk konfirmasi lebih lanjut.</p>
                        </details>

                        <details>
                            <summary>Apakah tersedia pengiriman?<i class="fa-solid fa-chevron-down small"></i></summary>
                            <p class="contact-muted mt-3">Kami melayani pengiriman area Kota Tegal dan sekitarnya sesuai jadwal serta ketersediaan kurir.</p>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="contact-title">Ikuti Kami</h2>
            <p class="contact-muted mt-2 mb-4">Dapatkan update produk baru dan promo menarik dari Strawberry Cake & Bakery Tegal.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="contact-social" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="contact-social" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://wa.me/6281575123118" target="_blank" rel="noopener" class="contact-social" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </section>
@endsection
