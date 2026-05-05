<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <!-- Tombol Kembali ke Peta -->
            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full font-bold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#4a3219] focus:ring-offset-2 transition shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
            <h2 class="font-extrabold text-xl text-gray-800 leading-tight">
                {{ __('Upload Laporan Kerusakan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:p-10 bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="max-w-2xl mx-auto">
                    
                    <h3 class="text-xl font-bold text-gray-900">Isi Detail Laporan</h3>
                    <p class="text-gray-700 font-medium mt-1 mb-10 leading-relaxed">
                        Silakan unggah foto jalan rusak dan lengkapi data berikut.
                    </p>

                    <!-- Form Input -->
                    <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- INPUT TERSEMBUNYI UNTUK TANGGAL -->
                        <!-- Tanggal otomatis terkirim ke backend tanpa terlihat di layar -->
                        <input type="hidden" name="submission_date" value="{{ $todayDate }}">

                        <!-- 1. Alamat -->
                        <div>
                            <label for="address" class="block font-semibold text-gray-700">Alamat Lokasi Kerusakan</label>
                            <input id="address" class="block w-full mt-2 px-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900" type="text" name="address" required autofocus placeholder="Contoh: Jl. Sudirman No. 10" />
                        </div>

                        <!-- 2. Tipe Kerusakan -->
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Tipe Kerusakan</label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                
                                <label class="damage-option group cursor-pointer">
                                    <input type="radio" name="damage_type" value="crack" class="sr-only" required />
                                    <div class="flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#8c7460] transition duration-300 shadow-sm border-2 border-transparent">
                                        <span class="w-3 h-3 rounded-full bg-blue-500 ring-2 ring-white"></span>
                                        <span class="font-semibold text-sm">Cracks</span>
                                    </div>
                                </label>

                                <label class="damage-option group cursor-pointer">
                                    <input type="radio" name="damage_type" value="pothole" class="sr-only" />
                                    <div class="flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#8c7460] transition duration-300 shadow-sm border-2 border-transparent">
                                        <span class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-white"></span>
                                        <span class="font-semibold text-sm">Pothole</span>
                                    </div>
                                </label>

                                <label class="damage-option group cursor-pointer">
                                    <input type="radio" name="damage_type" value="deformation" class="sr-only" />
                                    <div class="flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#8c7460] transition duration-300 shadow-sm border-2 border-transparent">
                                        <span class="w-3 h-3 rounded-full bg-green-500 ring-2 ring-white"></span>
                                        <span class="font-semibold text-sm">Deformation</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. Upload File & Kotak Preview (Susunan Vertikal Simetris) -->
                        <div class="space-y-5">
                            <!-- Input Upload -->
                            <div>
                                <label for="image" class="block font-semibold text-gray-700">Upload Foto (PNG, JPG, JPEG)</label>
                                <input id="image" class="w-full mt-2 px-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#e6dcd3] file:text-[#4a3219] hover:file:bg-[#d8c8b8] cursor-pointer transition" type="file" name="image" accept=".png, .jpeg, .jpg" required onchange="previewImage(this);" />
                            </div>
                            
                            <!-- Kotak Preview Foto Full Lebar -->
                            <div class="relative w-full h-56 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shadow-inner">
                                <img id="photo-preview" src="#" alt="Pratinjau Foto" class="hidden w-full h-full object-cover">
                                <span id="no-photo-text" class="text-sm text-gray-400 font-medium">Belum ada foto yang dipilih</span>

                                <!-- Tombol Silang (X) -->
                                <button type="button" id="remove-photo-btn" onclick="removeImage()" class="hidden absolute top-3 right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold shadow-md hover:bg-red-600 transition duration-200 z-10" title="Hapus Foto">
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="inline-flex justify-center items-center px-8 py-3 bg-[#4a3219] rounded-full font-bold text-white tracking-widest hover:bg-[#382314] active:bg-[#281a0d] focus:outline-none focus:ring-2 focus:ring-[#4a3219] focus:ring-offset-2 transition shadow-md">
                                SUBMIT LAPORAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript -->
    <script>
        // Fitur Preview dan Hapus Foto
        function previewImage(input) {
            const preview = document.getElementById('photo-preview');
            const noPhotoText = document.getElementById('no-photo-text');
            const removeBtn = document.getElementById('remove-photo-btn');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); 
                    noPhotoText.classList.add('hidden'); 
                    removeBtn.classList.remove('hidden'); 
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const preview = document.getElementById('photo-preview');
            const noPhotoText = document.getElementById('no-photo-text');
            const removeBtn = document.getElementById('remove-photo-btn');
            const fileInput = document.getElementById('image');

            preview.src = '#';
            preview.classList.add('hidden');
            noPhotoText.classList.remove('hidden');
            removeBtn.classList.add('hidden');

            fileInput.value = '';
        }

        // Fitur Toggle (ON/OFF) Tipe Kerusakan
        document.addEventListener('DOMContentLoaded', function() {
            let previousDamageType = null;
            const damageRadios = document.querySelectorAll('input[name="damage_type"]');

            damageRadios.forEach(radio => {
                radio.addEventListener('click', function(e) {
                    if (previousDamageType === this.value) {
                        this.checked = false;
                        previousDamageType = null;
                    } else {
                        previousDamageType = this.value;
                    }
                });
            });
        });
    </script>

    <style>
        .damage-option input[type="radio"]:checked + div {
            background-color: #4a3219;
            border-color: #4a3219;
            transform: scale(1.02);
        }
    </style>
</x-app-layout>