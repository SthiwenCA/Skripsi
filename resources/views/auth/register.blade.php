<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Map Kerusakan Jalan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#eaddcf] flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-[#e6dcd3] p-10 rounded-3xl shadow-xl my-8 border border-[#d1c2b5]">
        
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ url('/') }}" class="absolute left-0 text-gray-700 hover:text-[#4a2e1b] transition duration-200" title="Kembali ke Peta">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            
            <h2 class="text-[14px] sm:text-[15px] px-8 font-extrabold text-gray-800 text-center tracking-wide m-0 leading-snug">
                Buat akun baru untuk mulai menggunakan aplikasi
            </h2>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-5">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full px-4 py-3 rounded-full border-2 focus:ring-0 focus:border-[#4a3219] shadow-sm text-gray-900 transition-colors {{ $errors->has('name') ? 'border-red-500' : 'border-transparent' }}">
                @error('name')
                    <span class="text-red-600 text-xs font-bold mt-1.5 ml-2 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email (@gmail.com)</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    placeholder="nama@gmail.com"
                    pattern=".*@gmail\.com$" 
                    title="Harus menggunakan alamat email @gmail.com"
                    class="w-full px-4 py-3 rounded-full border-2 focus:ring-0 focus:border-[#4a3219] shadow-sm text-gray-900 transition-colors {{ $errors->has('email') ? 'border-red-500' : 'border-transparent' }}">
                @error('email')
                    <span class="text-red-600 text-xs font-bold mt-1.5 ml-2 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-full border-2 focus:ring-0 focus:border-[#4a3219] shadow-sm text-gray-900 transition-colors {{ $errors->has('password') ? 'border-red-500' : 'border-transparent' }}">
                @error('password')
                    <span class="text-red-600 text-xs font-bold mt-1.5 ml-2 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-full border-2 focus:ring-0 focus:border-[#4a3219] shadow-sm text-gray-900 transition-colors">
                @error('password_confirmation')
                    <span class="text-red-600 text-xs font-bold mt-1.5 ml-2 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-[#4a2e1b] text-white py-3 mt-2 rounded-xl font-extrabold text-base hover:bg-[#382314] transition-colors shadow-md">
                Register
            </button>

            <div class="text-center mt-6 text-sm font-bold text-gray-700">
                Sudah Punya Akun? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline hover:text-blue-800 transition">
                    Login
                </a>
            </div>
        </form>
    </div>

</body>
</html>