<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - Map Kerusakan Jalan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-[#eaddcf] w-full max-w-md p-8 sm:p-10 rounded-[2rem] shadow-xl relative">
        
        <a href="{{ route('login') }}" class="absolute top-8 left-8 text-[#1f2937] hover:text-[#4a3219] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>

        <h2 class="text-lg font-bold text-center text-[#1f2937] mb-8 mt-1">Buat Password Baru</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-5">
                <label for="email" class="block font-bold text-sm text-[#374151] mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                       class="block w-full rounded-full border border-gray-300 bg-white px-5 py-3 text-gray-900 focus:border-[#4a3219] focus:ring-2 focus:ring-[#4a3219] shadow-sm transition">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 font-semibold" />
            </div>

            <div class="mb-5">
                <label for="password" class="block font-bold text-sm text-[#374151] mb-2">Password Baru</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                       class="block w-full rounded-full border border-gray-300 bg-white px-5 py-3 text-gray-900 focus:border-[#4a3219] focus:ring-2 focus:ring-[#4a3219] shadow-sm transition">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 font-semibold" />
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block font-bold text-sm text-[#374151] mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                       class="block w-full rounded-full border border-gray-300 bg-white px-5 py-3 text-gray-900 focus:border-[#4a3219] focus:ring-2 focus:ring-[#4a3219] shadow-sm transition">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 font-semibold" />
            </div>

            <button type="submit" class="w-full bg-[#4a3219] hover:bg-[#382613] text-white font-bold py-3.5 rounded-xl transition shadow-md text-center text-[15px]">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>