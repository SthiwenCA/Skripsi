<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi Email - Map Kerusakan Jalan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#eaddcf] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-[#e6dcd3] p-10 rounded-3xl shadow-2xl border-2 border-[#d1c2b5] text-center">
        
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center pointer-events-none">
                <svg class="w-10 h-10 text-[#4a3219]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Cek Email Anda</h2>

        <div class="mb-6 text-sm text-gray-700 font-medium leading-relaxed px-2">
            Terima kasih telah mendaftar! Sebelum mulai menggunakan aplikasi, mohon verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email tersebut, silakan klik tombol di bawah ini.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-100 border border-green-300 rounded-xl text-sm font-bold text-green-800 shadow-sm">
                Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-[#4a2e1b] border-2 border-[#382314] text-white py-3 rounded-xl font-extrabold text-base hover:bg-[#382314] hover:shadow-xl transition-all duration-200 focus:outline-none">
                    Kirim Ulang Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800 hover:underline transition focus:outline-none">
                    Log Out
                </button>
            </form>
        </div>
    </div>

</body>
</html>