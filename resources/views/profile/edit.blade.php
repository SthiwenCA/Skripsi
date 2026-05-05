<x-app-layout>
    <!-- HEADER -->
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <!-- Tombol Kembali Bentuk Kapsul -->
            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full font-bold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#4a3219] focus:ring-offset-2 transition shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>

            <h2 class="font-extrabold text-xl text-gray-800 leading-tight">
                Profile Settings
            </h2>
        </div>
    </x-slot>

    <!-- KONTEN HALAMAN -->
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Kotak Form 1: Update Profil -->
            <div class="p-6 sm:p-10 bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Kotak Form 2: Update Password -->
            <div class="p-6 sm:p-10 bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Kotak Form 3: Delete Account -->
            <div class="p-6 sm:p-10 bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Kotak 4: Log Out (Dibuat Beda Warna agar Menonjol) -->
            <div class="p-6 sm:p-8 bg-[#eaddcf] shadow-lg sm:rounded-2xl border-t-4 border-[#8c7460] flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Selesai Mengatur Profil?</h3>
                    <p class="text-sm text-gray-800 font-medium mt-1">Anda dapat keluar dari akun Anda kapan saja dengan aman.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="m-0 w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-red-600 border border-transparent rounded-full font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition shadow-md">
                        Log Out
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>