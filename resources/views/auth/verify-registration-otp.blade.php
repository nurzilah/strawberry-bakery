<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Strawberry Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; background: #fff0f3; }</style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <main class="w-full max-w-md bg-white rounded-3xl shadow-xl p-7 md:p-9 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Strawberry Bakery" class="w-16 h-16 object-contain mx-auto mb-5">
        <h1 class="text-2xl font-bold text-gray-800">Verifikasi akun Anda</h1>
        <p class="mt-3 text-sm text-gray-600 leading-6">
            Kode OTP 6 digit telah dikirim melalui <strong>{{ $pending['method'] === 'email' ? 'email' : 'WhatsApp' }}</strong> ke<br>
            <strong class="text-pink-600 break-all">{{ $pending['destination'] }}</strong>.
            Kode berlaku selama 5 menit.
        </p>

        @if (session('status'))
            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600 text-left">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.otp.store') }}" class="mt-6">
            @csrf
            <label for="code" class="sr-only">Kode OTP</label>
            <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" required autofocus
                   class="w-full rounded-xl border border-gray-300 px-4 py-3 text-center text-2xl font-bold tracking-[0.45em] text-gray-800 focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none"
                   placeholder="000000">
            <button class="mt-4 w-full rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 py-3 text-sm font-semibold text-white shadow-md hover:from-pink-600 hover:to-rose-600">Verifikasi & Aktifkan Akun</button>
        </form>

        <form method="POST" action="{{ route('register.otp.resend') }}" class="mt-4">
            @csrf
            <button id="resend-button" class="text-sm font-semibold text-pink-600 hover:text-pink-700 disabled:text-gray-400 disabled:cursor-not-allowed" {{ $resendAvailableAt && $resendAvailableAt->isFuture() ? 'disabled' : '' }}>
                Kirim ulang OTP<span id="countdown"></span>
            </button>
        </form>
        <p class="mt-5 text-xs text-gray-500">Salah tujuan atau belum menerima kode? Tunggu cooldown lalu kirim ulang OTP.</p>
    </main>

    <script>
        const button = document.getElementById('resend-button');
        const countdown = document.getElementById('countdown');
        const availableAt = @json($resendAvailableAt?->toIso8601String());
        if (availableAt) {
            const tick = () => {
                const seconds = Math.max(0, Math.ceil((new Date(availableAt) - new Date()) / 1000));
                button.disabled = seconds > 0;
                countdown.textContent = seconds > 0 ? ` (${seconds} dtk)` : '';
                if (seconds > 0) setTimeout(tick, 1000);
            };
            tick();
        }
    </script>
</body>
</html>
