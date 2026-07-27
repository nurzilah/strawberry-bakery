<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Strawberry Bakery</title>
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
    <div class="w-full max-w-4xl glass-card rounded-2xl shadow-lg overflow-hidden flex flex-col md:flex-row" style="max-height: 90vh;">
        <!-- Left Side - Info Section -->
        <div class="hidden md:flex w-full md:w-5/12 bg-login-image p-6 lg:p-8 text-white flex-col justify-between relative">
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl shadow-lg flex items-center justify-center p-2">
                        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery Logo" class="w-full h-full object-contain filter drop-shadow-md">
                    </div>
                </div>

                <h2 class="text-3xl font-bold mb-3 leading-tight">Keamanan<br>Akun Anda</h2>
                <p class="text-pink-50 mb-5 text-sm">Jangan khawatir jika Anda lupa password. Kami akan membantu memulihkan akses Anda ke Strawberry Bakery.</p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-pink-50 font-medium">Pemulihan cepat & aman</p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <p class="text-pink-50 font-medium">Bantuan pelanggan 24/7</p>
                    </div>
                </div>
            </div>
            <div class="relative z-10 mt-6 text-xs text-pink-100 opacity-80">
                &copy; {{ date('Y') }} Strawberry Bakery.
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full md:w-7/12 p-5 md:p-6 lg:p-8 bg-white flex flex-col overflow-y-auto" style="max-height: 90vh;">
            <div class="mb-5 flex items-center md:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 mr-3 object-contain">
                <h2 class="text-xl font-bold text-gray-800">Lupa Password</h2>
            </div>
            
            <div class="mb-4 hidden md:block">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Lupa Password?</h2>
            </div>

            <p class="text-gray-500 text-xs mb-4">
                Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk membuat password baru yang aman.
            </p>

            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg mb-4 shadow-sm text-xs flex gap-2 items-center">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

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

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Email Address</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required
                           autofocus
                           class="w-full px-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-1 focus:ring-pink-500 focus:border-transparent transition shadow-sm">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white py-1.5 rounded-lg font-semibold text-sm shadow hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-200 mt-2">
                    Kirim Tautan Reset
                </button>

                <!-- Back to Login Link -->
                <p class="text-center text-[11px] text-gray-500 pt-3 border-t mt-4 border-gray-100">
                    Ingat password Anda? <a href="{{ route('login') }}" class="text-pink-600 hover:text-pink-700 font-bold transition">Kembali ke Login</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
