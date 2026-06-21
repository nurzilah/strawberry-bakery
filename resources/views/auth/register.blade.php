<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Strawberry Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        <!-- Left Side - Info Section -->
        <div class="w-full md:w-2/5 bg-gradient-to-br from-pink-400 to-red-400 p-8 md:p-12 text-white">
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold">Strawberry Bakery</h1>
            </div>

            <h2 class="text-4xl font-bold mb-6">Bergabunglah dengan<br>Keluarga Kami</h2>
            <p class="text-pink-100 mb-8">Daftar sekarang dan nikmati berbagai keuntungan menjadi member Strawberry Bakery</p>

            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-pink-50">Diskon khusus member hingga 20%</p>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-pink-50">Akses pre-order lebih awal</p>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-pink-50">Poin reward setiap pembelian</p>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-pink-50">Info promo eksklusif via email</p>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-pink-50">Gratis ongkir untuk pembelian pertama</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full md:w-3/5 p-8 md:p-12">
            <div class="flex space-x-6 mb-8 border-b">
                <a href="{{ route('login') }}" class="pb-4 px-2 font-semibold text-gray-400 hover:text-gray-600">Login</a>
                <button class="pb-4 px-2 font-semibold text-pink-500 border-b-2 border-pink-500">Register</button>
            </div>

            <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun Baru</h2>
            <p class="text-gray-600 mb-8">Isi data diri Anda untuk membuat akun</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap"
                           required
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required
                               minlength="8"
                               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}"
                               title="Password harus minimal 8 karakter, memakai huruf besar, huruf kecil, angka, dan simbol."
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition">
                        <button type="button"
                                class="password-toggle absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-gray-700"
                                data-target="password"
                                aria-label="Tampilkan password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Minimal 8 karakter, memakai huruf besar, huruf kecil, angka, dan simbol.</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ketik ulang password"
                               required
                               minlength="8"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent transition">
                        <button type="button"
                                class="password-toggle absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-gray-700"
                                data-target="password_confirmation"
                                aria-label="Tampilkan konfirmasi password">
                            <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start">
                    <input type="checkbox"
                           id="terms"
                           name="terms"
                           required
                           class="js-submit-check w-4 h-4 mt-1 text-pink-500 border-gray-300 rounded focus:ring-pink-500">
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Saya setuju dengan
                        <a href="{{ route('terms') }}" class="text-pink-500 hover:text-pink-600 font-medium">Syarat & Ketentuan</a>
                        dan
                        <a href="#" class="text-pink-500 hover:text-pink-600 font-medium">Kebijakan Privasi</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="js-submit-button w-full bg-gradient-to-r from-pink-400 to-red-400 text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" disabled>
                    Daftar Sekarang
                </button>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau daftar dengan</span>
                    </div>
                </div>

                <!-- Social Register -->
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 w-100 py-3 rounded-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Google</span>
                    </a>
                </div>

                <!-- Login Link -->
                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-pink-500 hover:text-pink-600 font-semibold">Login di sini</a>
                </p>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.password-toggle').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.querySelector('.eye-open').classList.toggle('hidden', isPassword);
                button.querySelector('.eye-closed').classList.toggle('hidden', !isPassword);
            });
        });

        const submitCheck = document.querySelector('.js-submit-check');
        const submitButton = document.querySelector('.js-submit-button');
        if (submitCheck && submitButton) {
            const syncSubmit = () => {
                submitButton.disabled = !submitCheck.checked;
            };
            submitCheck.addEventListener('change', syncSubmit);
            syncSubmit();
        }
    </script>
</body>
</html>
