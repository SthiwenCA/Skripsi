<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi & Panduan - Map Kerusakan Jalan</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 z-0 opacity-15" 
         style="background-color: #f4eae1; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M54.627 0l.83.83v1.58L53.04 4.825l-2.8 2.8v3.16l-7.46 7.46V23l-3.2 3.2v4.83l-4.74 4.74v4.54l-11.23 11.23v2.85l-7.24 7.24v2.4h-3.14V57.6l7.24-7.24v-2.85l11.23-11.23v-4.54l4.74-4.74v-4.83l3.2-3.2V18.28l7.46-7.46v-3.16l2.8-2.8V3.24l1.58-1.58V0h3.14zM24 60h-3.15l-7.23-7.24v-2.85l-7.3-7.3v-3.16l-6.32-6.32V30H0v-3.15l4.74-4.74v-4.54l11.23-11.23v-2.85l7.24-7.24v-2.4h3.15V3.15l-7.24 7.24v2.85l-11.23 11.23v4.54L3.15 33.76v4.83l6.32 6.32v3.16l7.3 7.3v2.85l7.23 7.24V60zm36 0h-3.15l-4.74-4.74v-4.54l-11.23-11.23v-2.85l-7.24-7.24v-4.83l-3.2-3.2v-4.83l-4.74-4.74V7.24L38.45 0h3.15v2.4l7.24 7.24v2.85l11.23 11.23v4.54l4.74 4.74v4.83l-1.58 1.58v3.16l-2.8 2.8v3.16l-7.46 7.46v4.83l-3.2 3.2v4.83l-4.74 4.74v4.54L60 56.85V60z\' fill=\'%234a3219\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');">
    </div>
    
    <div class="absolute inset-0 bg-[#f4eae1]/40 backdrop-blur-[1px] z-10"></div>

    <div class="relative z-20 w-full max-w-3xl bg-[#eaddcf] rounded-2xl shadow-2xl border border-[#c1b1a3] overflow-hidden" 
         x-data="{ 
            activeTab: 'konsep', 
            agreed: false, 
            hasVisited: false, 
            init() { 
                // Cek status kunjungan saat halaman dimuat
                this.hasVisited = localStorage.getItem('hasVisitedBefore') === 'true'; 
                
                // Jika sudah pernah berkunjung, otomatis anggap 'agreed' agar tombol langsung aktif
                if (this.hasVisited) { 
                    this.agreed = true; 
                } 
            } 
         }">
        
        <div class="px-8 py-6 border-b border-[#d8c8b8] bg-[#e3d1c0] flex items-center gap-3">
            <svg class="w-8 h-8 text-[#4a3219]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h1 class="text-2xl font-extrabold text-[#4a3219]">Informasi & Panduan Aplikasi</h1>
        </div>

        <div class="flex border-b border-[#d8c8b8] bg-[#f4eae1]">
            <button @click="activeTab = 'konsep'" :class="activeTab === 'konsep' ? 'border-b-4 border-[#4a3219] text-[#4a3219] font-extrabold bg-[#eaddcf]' : 'text-gray-600 font-semibold hover:bg-[#ebdccf]'" class="flex-1 py-4 text-center text-sm sm:text-base transition-all focus:outline-none">💡 Konsep & Fitur</button>
            <button @click="activeTab = 'ai'" :class="activeTab === 'ai' ? 'border-b-4 border-[#4a3219] text-[#4a3219] font-extrabold bg-[#eaddcf]' : 'text-gray-600 font-semibold hover:bg-[#ebdccf]'" class="flex-1 py-4 text-center text-sm sm:text-base transition-all focus:outline-none">🤖 Machine Learning</button>
            <button @click="activeTab = 'panduan'" :class="activeTab === 'panduan' ? 'border-b-4 border-[#4a3219] text-[#4a3219] font-extrabold bg-[#eaddcf]' : 'text-gray-600 font-semibold hover:bg-[#ebdccf]'" class="flex-1 py-4 text-center text-sm sm:text-base transition-all focus:outline-none">📍 Cara Pakai</button>
        </div>

        <div class="p-8 text-gray-800 min-h-[300px]">
            <div x-show="activeTab === 'konsep'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                <p class="text-base leading-relaxed"><strong>Map Kerusakan Jalan</strong> adalah platform pemetaan berbasis partisipasi publik (<em>crowdsourcing</em>). Web ini dirancang untuk menjembatani masyarakat dan pengelola infrastruktur dalam melaporkan serta memantau kondisi jalan rusak secara transparan dan <em>real-time</em>.</p>
                <div class="bg-[#ebdccf] p-6 rounded-xl border border-[#d8c8b8]">
                    <h4 class="font-bold text-lg text-[#4a3219] mb-4">Fitur Utama:</h4>
                    <ul class="list-disc pl-6 space-y-3 text-base text-gray-700">
                        <li><strong>Peta Interaktif:</strong> Visualisasi sebaran titik kerusakan jalan.</li>
                        <li><strong>Pelaporan Mandiri:</strong> Unggah foto jalan rusak langsung di lokasi kejadian.</li>
                        <li><strong>Validasi Perbaikan:</strong> Kirim bukti foto jika jalan telah selesai diperbaiki.</li>
                        <li><strong>Notifikasi Status:</strong> Pantau apakah laporan disetujui, ditolak, atau selesai divalidasi oleh Admin.</li>
                    </ul>
                </div>
            </div>

            <div x-show="activeTab === 'ai'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                <div class="flex items-start gap-4 bg-amber-100 border border-amber-300 p-5 rounded-xl text-amber-900 shadow-sm">
                    <span class="text-3xl mt-1">⚡</span>
                    <p class="text-sm font-semibold leading-relaxed">Sistem ini ditenagai AI canggih untuk memvalidasi gambar secara otomatis guna menghindari kesalahan pelaporan!</p>
                </div>
                <p class="text-base leading-relaxed">Saat Anda mengunggah foto kerusakan, sistem di latar belakang memprosesnya menggunakan model <strong>YOLOv11m</strong>. Ini adalah teknologi <em>Computer Vision</em> mutakhir yang dirancang khusus untuk mendeteksi objek dengan kecepatan dan akurasi tinggi.</p>
            </div>

            <div x-show="activeTab === 'panduan'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
                <ul class="space-y-5 text-base">
                    <li class="flex gap-4 items-start"><span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#4a3219] text-white font-bold shrink-0">1</span><p class="mt-1"><strong>Melihat Peta:</strong> Titik lingkaran di peta menandakan lokasi. Klik titik untuk melihat detail foto.</p></li>
                    <li class="flex gap-4 items-start"><span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#4a3219] text-white font-bold shrink-0">2</span><p class="mt-1"><strong>Melaporkan Titik Baru:</strong> Silakan login terlebih dahulu, buka sidebar kiri di halaman peta, lalu klik tombol <strong>Upload</strong>.</p></li>
                    <li class="flex gap-4 items-start"><span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#4a3219] text-white font-bold shrink-0">3</span><p class="mt-1"><strong>Konfirmasi Perbaikan:</strong> Jika jalan sudah mulus kembali, klik titik di peta, lalu gunakan formulir di panel bawah untuk mengirimkan foto bukti perbaikan Anda.</p></li>
                </ul>
            </div>

            <div class="mt-8 pt-6 border-t border-[#d8c8b8] flex flex-col items-center gap-5">
                
                <label x-cloak x-show="!hasVisited" class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" x-model="agreed" class="w-5 h-5 text-[#4a3219] bg-white border-gray-400 rounded focus:ring-[#4a3219] focus:ring-2 cursor-pointer transition">
                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition">Saya telah membaca dan memahami informasi serta panduan aplikasi ini.</span>
                </label>

                <button id="btnLanjutKePeta" 
                        :disabled="!agreed" 
                        :class="agreed ? 'bg-[#4a3219] text-white hover:bg-[#382613] shadow-lg transform hover:-translate-y-0.5' : 'bg-gray-400 text-gray-200 cursor-not-allowed opacity-60'" 
                        class="inline-flex items-center justify-center gap-2 px-10 py-3 rounded-full font-bold text-lg transition-all duration-300 focus:outline-none">
                    
                    <span x-text="hasVisited ? 'Kembali ke Peta' : 'Saya Mengerti & Buka Peta'"></span>
                    
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnLanjutKePeta').addEventListener('click', function() {
            // Tandai bahwa user sudah pernah menyetujui/mengunjungi panduan
            localStorage.setItem('hasVisitedBefore', 'true');
            // Kembali ke peta utama
            window.location.href = "{{ route('map') }}";
        });
    </script>
</body>
</html>