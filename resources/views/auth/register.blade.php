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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff0f3;
        }
        .bg-login-image {
            background-image: url('{{ asset('images/tentangkami/Toko.webp') }}');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .bg-login-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(232, 62, 140, 0.25) 0%, rgba(220, 53, 69, 0.25) 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-4xl glass-card rounded-[24px] shadow-[0_12px_40px_rgba(0,0,0,0.08)] overflow-hidden flex flex-col md:flex-row" style="max-height: 90vh;">
        <!-- Left Side - Info Section -->
        <div class="hidden md:flex w-full md:w-5/12 bg-login-image p-6 lg:p-8 text-white flex-col justify-between relative">
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl shadow-lg flex items-center justify-center p-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery Logo" class="w-full h-full object-contain filter drop-shadow-md">
                    </div>
                </div>

                <h2 class="text-3xl font-bold mb-3 leading-tight">Bergabunglah dengan<br>Keluarga Kami</h2>
                <p class="text-pink-50 mb-5 text-sm">Daftar sekarang dan nikmati berbagai keuntungan menjadi member Strawberry Bakery.</p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-pink-50 font-medium">Akses pre-order awal</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-pink-50 font-medium">Info promo eksklusif</p>
                    </div>
                </div>
            </div>
            <div class="relative z-10 mt-6 text-xs text-pink-100 opacity-80">
                &copy; {{ date('Y') }} Strawberry Bakery.
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="w-full md:w-7/12 p-5 md:p-6 lg:p-8 bg-white flex flex-col overflow-y-auto" style="max-height: 90vh;">
            
            <!-- Segmented Control for Tabs -->
            <div class="flex p-1 mb-6 bg-gray-100/80 border border-gray-200/60 rounded-xl shadow-inner">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 text-sm font-medium rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition-all duration-300">Login</a>
                <div class="flex-1 text-center py-2.5 text-sm font-semibold rounded-lg bg-white text-pink-600 shadow-[0_2px_8px_rgba(0,0,0,0.04)] cursor-default transition-all duration-300">Register</div>
            </div>

            <div class="mb-3 hidden md:block">
                <h2 class="text-xl font-bold text-gray-800">Daftar Akun Baru</h2>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-lg mb-4 shadow-sm flex gap-2 items-start">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <ul class="list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <!-- Name & Phone -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda"
                               required
                               autofocus
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-pink-400/50 focus:border-pink-400 placeholder-gray-400 transition-all duration-300 shadow-sm">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">No. Telepon</label>
                        <input type="text"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               required
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-pink-400/50 focus:border-pink-400 placeholder-gray-400 transition-all duration-300 shadow-sm">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required
                           class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-pink-400/50 focus:border-pink-400 placeholder-gray-400 transition-all duration-300 shadow-sm">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Minimal 8 karakter"
                               required
                               minlength="8"
                               pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}"
                               title="Password harus minimal 8 karakter, memakai huruf besar, huruf kecil, angka, dan simbol."
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl pr-10 focus:bg-white focus:ring-2 focus:ring-pink-400/50 focus:border-pink-400 placeholder-gray-400 transition-all duration-300 shadow-sm">
                        <button type="button"
                                class="password-toggle absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                                data-target="password"
                                aria-label="Tampilkan password">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Password Rules -->
                    <div class="mt-3 p-3 bg-white border border-gray-100 rounded-xl shadow-sm">
                        <p class="text-[11px] text-gray-500 font-semibold mb-2 uppercase tracking-wider">Kriteria password:</p>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-2">
                            <div class="flex items-center text-xs text-gray-400 transition-colors duration-300" id="rule-length">
                                <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                                <span>Min 8 karakter</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-400 transition-colors duration-300" id="rule-upper">
                                <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                                <span>Huruf besar (A-Z)</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-400 transition-colors duration-300" id="rule-lower">
                                <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                                <span>Huruf kecil (a-z)</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-400 transition-colors duration-300" id="rule-number">
                                <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                                <span>Angka (0-9)</span>
                            </div>
                            <div class="flex items-center text-xs text-gray-400 transition-colors duration-300" id="rule-symbol">
                                <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/></svg>
                                <span>Simbol (@, #, !)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ulangi password Anda"
                               required
                               class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl pr-10 focus:bg-white focus:ring-2 focus:ring-pink-400/50 focus:border-pink-400 placeholder-gray-400 transition-all duration-300 shadow-sm">
                        <button type="button"
                                class="password-toggle absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                                data-target="password_confirmation"
                                aria-label="Tampilkan password">
                            <svg class="eye-open w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg class="eye-closed w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.249-2.383A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.306M15 12a3 3 0 00-3-3m0 0a3 3 0 00-3 3m3-3l9 9M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start py-1">
                    <input type="checkbox"
                           id="terms"
                           name="terms"
                           required
                           class="js-submit-check w-4 h-4 mt-0.5 text-pink-500 border-gray-300 rounded focus:ring-pink-500 focus:ring-offset-1 cursor-pointer transition-all">
                    <label for="terms" class="ml-2 text-sm text-gray-600 cursor-pointer">
                        Menyetujui
                        <a href="{{ route('terms') }}" target="_blank" class="text-pink-600 hover:text-pink-700 font-semibold transition-colors">S&K</a>
                        serta
                        <a href="{{ route('terms') }}#privasi" target="_blank" class="text-pink-600 hover:text-pink-700 font-semibold transition-colors">Privasi</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="js-submit-button w-full bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white py-2.5 rounded-xl font-semibold text-sm shadow-[0_4px_14px_rgba(236,72,153,0.3)] hover:shadow-[0_6px_20px_rgba(236,72,153,0.4)] transform hover:-translate-y-0.5 transition-all duration-300 mt-2">
                    Daftar Sekarang
                </button>
                
                <!-- Divider -->
                <div class="relative my-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="px-2 bg-white text-[11px] text-gray-400 font-medium">Atau</span>
                    </div>
                </div>

                <!-- Social Register -->
                <div>
                    <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-gray-700 font-semibold text-sm">Daftar dengan Google</span>
                    </a>
                </div>
                
                <!-- Login Link -->
                <p class="text-center text-sm text-gray-500 mt-4 mb-4">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-pink-600 hover:text-pink-700 font-bold transition-colors">Masuk</a>
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
        const requiredInputs = document.querySelectorAll('form input[required]');
        
        if (submitCheck && submitButton) {
            const syncSubmit = () => {
                let allFilled = true;
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        allFilled = false;
                    }
                });

                const isEnabled = submitCheck.checked && allFilled;
                submitButton.disabled = !isEnabled;
                
                if (!isEnabled) {
                    submitButton.classList.add('opacity-60', 'cursor-not-allowed');
                } else {
                    submitButton.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            };

            submitCheck.addEventListener('change', syncSubmit);
            requiredInputs.forEach(input => {
                input.addEventListener('input', syncSubmit);
            });
            
            syncSubmit();
        }

        // Real-time Password Strength Validation
        const passwordInput = document.getElementById('password');
        const validationRules = {
            length: { regex: /.{8,}/, el: document.getElementById('rule-length') },
            upper: { regex: /[A-Z]/, el: document.getElementById('rule-upper') },
            lower: { regex: /[a-z]/, el: document.getElementById('rule-lower') },
            number: { regex: /[0-9]/, el: document.getElementById('rule-number') },
            symbol: { regex: /[^A-Za-z0-9]/, el: document.getElementById('rule-symbol') }
        };

        if (passwordInput) {
            passwordInput.addEventListener('input', function(e) {
                const val = e.target.value;
                for (const key in validationRules) {
                    const rule = validationRules[key];
                    if (rule.regex.test(val)) {
                        rule.el.classList.remove('text-gray-400', 'text-red-500');
                        rule.el.classList.add('text-green-500');
                    } else if (val.length > 0) {
                        rule.el.classList.remove('text-gray-400', 'text-green-500');
                        rule.el.classList.add('text-red-500');
                    } else {
                        rule.el.classList.remove('text-green-500', 'text-red-500');
                        rule.el.classList.add('text-gray-400');
                    }
                }
            });
        }
    </script>
</body>
</html>
