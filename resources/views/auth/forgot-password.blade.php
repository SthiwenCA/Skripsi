<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password - Map Kerusakan Jalan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Memanggil Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white flex items-center justify-center min-h-screen">

    <!-- KOTAK CARD FORGOT PASSWORD (Desain seragam dengan Login/Register) -->
    <div class="w-full max-w-md bg-[#e6dcd3] p-10 rounded-2xl shadow-lg my-8">
        
        <!-- HEADER DENGAN TOMBOL BACK -->
        <div class="relative flex items-center justify-center mb-3">
            <!-- Tombol Back Kiri (Kembali ke halaman Login) -->
            <a href="{{ route('login') }}" class="absolute left-0 text-gray-700 hover:text-[#4a2e1b] transition duration-200" title="Kembali ke Login">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            
            <!-- Tulisan Tengah -->
            <h2 class="text-lg font-bold text-gray-800 tracking-wide m-0">
                Lupa Password?
            </h2>
        </div>

        <!-- Teks Penjelasan -->
        <div class="text-sm text-gray-700 mb-8 text-center leading-relaxed font-medium">
            Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </div>

        <!-- Session Status (Pesan Berhasil Kirim Email) -->
        @if (session('status'))
            <div class="mb-5 font-semibold text-sm text-green-700 bg-green-100 p-3 rounded-md text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- INPUT EMAIL -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- ERROR MESSAGE -->
            @if ($errors->any())
                <div class="text-red-600 text-sm font-semibold mb-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- TOMBOL KIRIM LINK -->
            <button type="submit" class="w-full bg-[#4a2e1b] text-white py-3 mt-2 rounded-lg font-bold text-sm uppercase tracking-wider hover:bg-[#382314] transition shadow-md">
                Kirim Link Reset Password
            </button>

            <!-- LINK KEMBALI KE LOGIN -->
            <div class="text-center mt-6 text-sm font-semibold text-gray-800">
                Ingat password Anda? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline hover:text-blue-800 transition">
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>