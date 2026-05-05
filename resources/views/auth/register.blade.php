<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Map Kerusakan Jalan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Memanggil Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white flex items-center justify-center min-h-screen">

    <!-- KOTAK CARD REGISTER (Desain sama dengan Login) -->
    <div class="w-full max-w-md bg-[#e6dcd3] p-10 rounded-2xl shadow-lg my-8">
        
        <!-- HEADER DENGAN TOMBOL BACK -->
        <div class="relative flex items-center justify-center mb-8">
            <!-- Tombol Back Kiri -->
            <a href="{{ url('/') }}" class="absolute left-0 text-gray-700 hover:text-[#4a2e1b] transition duration-200" title="Kembali ke Peta">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            
            <!-- Tulisan Tengah (Dibuat agak kecil/rapat agar muat jika layarnya kecil) -->
            <h2 class="text-[14px] sm:text-[15px] px-8 font-semibold text-gray-800 text-center tracking-wide m-0">
                Buat akun baru untuk mulai menggunakan aplikasi
            </h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- INPUT NAME -->
            <div class="mb-5">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- INPUT EMAIL (Bisa dianggap sebagai Username untuk login nanti) -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email / Username</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- INPUT PASSWORD -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- INPUT CONFIRM PASSWORD -->
            <div class="mb-5">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-full border-none focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900">
            </div>

            <!-- ERROR MESSAGE -->
            @if ($errors->any())
                <div class="text-red-600 text-sm font-semibold mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- TOMBOL REGISTER -->
            <button type="submit" class="w-full bg-[#4a2e1b] text-white py-3 mt-2 rounded-lg font-bold text-lg hover:bg-[#382314] transition shadow-md">
                Register
            </button>

            <!-- LINK KEMBALI KE LOGIN -->
            <div class="text-center mt-6 text-sm font-semibold text-gray-800">
                Sudah Punya Akun? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline hover:text-blue-800 transition">
                    Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>