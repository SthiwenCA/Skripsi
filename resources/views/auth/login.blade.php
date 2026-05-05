<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Map Kerusakan Jalan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Memanggil Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white flex items-center justify-center min-h-screen">

    <!-- KOTAK CARD LOGIN -->
    <!-- Warna background disesuaikan dengan gambar (#e6d7cf) -->
    <div class="w-full max-w-md bg-[#e6dcd3] p-10 rounded-2xl shadow-lg">
        
        <!-- HEADER DENGAN TOMBOL BACK -->
        <div class="relative flex items-center justify-center mb-8">
            <!-- Tombol Back Kiri -->
            <a href="{{ url('/') }}" class="absolute left-0 text-gray-700 hover:text-[#4a2e1b] transition duration-200" title="Kembali ke Peta">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            
            <!-- Tulisan Tengah -->
            <h2 class="text-[15px] font-semibold text-gray-800 tracking-wide m-0">
                Login untuk lanjut ke aplikasi
            </h2>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- INPUT USERNAME (Memakai name="email" untuk backend bawaan Laravel) -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- INPUT PASSWORD -->
            <div class="mb-2">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- LUPA PASSWORD -->
            <div class="flex justify-end mb-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600 transition">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- PANAH 1: ERROR MESSAGE -->
            <!-- Akan muncul otomatis dengan warna merah jika kombinasi email/password salah -->
            @if ($errors->any())
                <div class="text-red-600 text-sm font-semibold mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- TOMBOL LOGIN -->
            <button type="submit" class="w-full bg-[#4a2e1b] text-white py-3 mt-2 rounded-lg font-bold text-lg hover:bg-[#382314] transition shadow-md">
                Login
            </button>

            <!-- PANAH 2: LINK REGISTER -->
            <div class="text-center mt-6 text-sm font-semibold text-gray-800">
                Tidak Punya Akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline hover:text-blue-800 transition">
                    Register
                </a>
            </div>
        </form>
    </div>

</body>
</html>