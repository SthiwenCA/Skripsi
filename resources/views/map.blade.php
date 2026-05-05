<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Map Kerusakan Jalan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Memanggil Tailwind CSS & Alpine.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">

    <!-- Kontainer Full Screen -->
    <div class="relative w-screen h-screen overflow-hidden bg-gray-100">
        
        <!-- ========================================== -->
        <!-- NOTIFIKASI SUKSES (Muncul setelah submit form) -->
        <!-- ========================================== -->
        @if (session('success'))
            <div id="toast-success" class="absolute top-20 left-1/2 transform -translate-x-1/2 z-[1000] bg-[#4a3219] text-white px-6 py-3 rounded-full shadow-xl font-semibold flex items-center gap-3 transition-opacity duration-500">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
            <script>
                // Hilangkan notifikasi setelah 3 detik
                setTimeout(() => {
                    const toast = document.getElementById('toast-success');
                    if(toast) {
                        toast.classList.add('opacity-0');
                        setTimeout(() => toast.remove(), 500);
                    }
                }, 3000);
            </script>
        @endif

        <!-- 1. TOMBOL BUKA SIDEBAR -->
        <button id="openSidebar" class="absolute top-4 left-4 z-[500] bg-[#4a3219] text-white p-2 rounded-md shadow-md hover:bg-[#382613] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- 2. TOMBOL DROPDOWN KANAN ATAS -->
        <div class="absolute top-4 right-4 z-[500]">
            @auth
                <!-- Jika Login -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 bg-white px-4 py-2 border border-gray-200 rounded-md shadow-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-cloak x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log Out</button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Jika Belum Login -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 bg-white px-4 py-2 border border-gray-200 rounded-md shadow-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        <span>Guest</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-cloak x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</a>
                        @endif
                    </div>
                </div>
            @endauth
        </div>

        <!-- 3. SIDEBAR KIRI -->
        <div id="sidebar" class="absolute top-0 left-0 h-full w-80 bg-[#eaddcf] z-[600] transform -translate-x-full transition-transform duration-300 shadow-2xl flex flex-col">
            <div class="flex justify-between items-center p-6 border-b border-[#d8c8b8] shrink-0">
                <h2 class="text-2xl font-bold text-gray-900">Settings</h2>
                <button id="closeSidebar" class="bg-[#a38771] text-white p-1 rounded-md hover:bg-[#8c7460] transition shadow">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 flex-1 overflow-y-auto">
                <h4 class="font-bold text-lg mb-4 text-gray-900">Damage type</h4>
                
                <!-- Tombol Pilihan Jenis Kerusakan -->
                <div class="flex flex-col gap-3 mb-6">
                    <button class="btn-type flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#4a3219] transition duration-300" data-type="crack">
                        <span class="w-3 h-3 rounded-full bg-blue-500 ring-2 ring-white"></span>
                        <span class="font-semibold text-sm">Cracks</span>
                    </button>
                    
                    <button class="btn-type flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#4a3219] transition duration-300" data-type="pothole">
                        <span class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-white"></span>
                        <span class="font-semibold text-sm">Pothole</span>
                    </button>
                    
                    <button class="btn-type flex items-center gap-3 px-4 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#4a3219] transition duration-300" data-type="deformation">
                        <span class="w-3 h-3 rounded-full bg-green-500 ring-2 ring-white"></span>
                        <span class="font-semibold text-sm">Deformation</span>
                    </button>
                </div>

                <!-- TOMBOL CLEAR & SELECT ALL BARU -->
                <div class="flex justify-center gap-3 border-t border-[#d8c8b8] pt-6">
                    <!-- Tombol Clear -->
                    <button id="clearFiltersBtn" class="flex items-center gap-2 px-5 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#8c7460] transition shadow-sm font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path>
                        </svg>
                        Clear
                    </button>
                    
                    <!-- Tombol Select All -->
                    <button id="selectAllFiltersBtn" class="flex items-center gap-2 px-5 py-2 bg-[#a38771] text-white rounded-full hover:bg-[#8c7460] transition shadow-sm font-bold text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Select All
                    </button>
                </div>
            </div>

            <div class="p-6 shrink-0 border-t border-[#d8c8b8]">
                <p class="font-bold text-center text-gray-900 mb-2">Upload Photos</p>
                @auth
                    <!-- JIKA SUDAH LOGIN: TAUTAN KE HALAMAN FORM -->
                    <a href="{{ route('submissions.create') }}" class="w-full flex items-center justify-center gap-2 bg-[#4a3219] text-white py-2 rounded-full hover:bg-[#382613] transition shadow-md font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload
                    </a>
                @else
                    <!-- JIKA BELUM LOGIN: MUNCULKAN MODAL -->
                    <button onclick="openModal()" class="w-full flex items-center justify-center gap-2 bg-[#4a3219] text-white py-2 rounded-full hover:bg-[#382613] transition shadow-md font-semibold text-sm text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login untuk Upload
                    </button>
                @endauth
            </div>
        </div>

        <!-- 4. PETA UTAMA -->
        <div id="map" class="absolute inset-0 z-10"></div>

        <!-- 5. FLOATING CARD POPUP -->
        <div id="damage-detail-popup" class="hidden absolute bottom-10 left-1/2 transform -translate-x-1/2 bg-[#e6dcd3] p-5 rounded-2xl shadow-2xl z-[550] w-[600px] max-w-[90vw] flex-row gap-6 border border-[#c1b1a3]">
            <!-- Tombol Close (Silang) di Kanan Atas -->
            <button id="closeDetailPopupBtn" class="absolute -top-3 -right-3 bg-[#6b4e3d] text-white rounded-lg w-8 h-8 flex items-center justify-center font-bold shadow-md hover:bg-[#4a3224] transition">
                ✕
            </button>
            <!-- Gambar Jalan Rusak -->
            <div class="w-2/5 shrink-0">
                <img id="detail-image" src="" alt="Foto Kerusakan" class="w-full h-36 object-cover rounded-xl shadow-sm border border-gray-300">
            </div>
            <!-- Informasi Detail -->
            <div class="w-3/5 flex flex-col justify-center gap-4 text-gray-900 cursor-default">
                <div class="text-[15px]">
                    <span class="font-extrabold">Address : </span>
                    <span id="detail-address" class="font-medium"></span>
                </div>
                <div class="text-[15px]">
                    <span class="font-extrabold">Damage Type : </span>
                    <span id="detail-type" class="font-medium capitalize"></span>
                </div>
                <div class="text-[15px]">
                    <span class="font-extrabold">Submitted Date : </span>
                    <span id="detail-date" class="font-medium"></span>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- 6. KOMPONEN MODAL NOTIFIKASI LOGIN         -->
        <!-- ========================================== -->
        <div id="modalOverlay" class="fixed inset-0 bg-black/50 z-[1000] hidden opacity-0 transition-opacity duration-300"></div>

        <div id="loginModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#eaddcf] rounded-xl shadow-2xl z-[1001] w-[90%] max-w-md hidden opacity-0 scale-95 transition-all duration-300 overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-[#d8c8b8]">
                <h2 class="text-lg font-bold text-gray-900">Notifikasi</h2>
                <button onclick="closeModal()" class="text-[#4a3219] hover:text-red-600 transition font-bold text-xl leading-none">&times;</button>
            </div>
            <div class="px-6 py-8 text-center">
                <h3 class="font-bold text-gray-900 text-lg mb-2">Login Untuk Upload</h3>
                <p class="text-gray-800 text-sm mb-6">Anda Harus Login Untuk Mengupload Foto</p>
                <a href="{{ route('login') }}" class="inline-block bg-[#4a3219] text-white px-8 py-2 rounded-full font-semibold hover:bg-[#382613] transition shadow-md">
                    Login
                </a>
            </div>
        </div>
        
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Modal Functions
        function openModal() {
            const overlay = document.getElementById('modalOverlay');
            const modal = document.getElementById('loginModal');
            overlay.classList.remove('hidden');
            modal.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modal.classList.remove('opacity-0', 'scale-95');
            }, 10);
        }

        function closeModal() {
            const overlay = document.getElementById('modalOverlay');
            const modal = document.getElementById('loginModal');
            overlay.classList.add('opacity-0');
            modal.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }, 300);
        }

        window.addEventListener('click', function(e) {
            const overlay = document.getElementById('modalOverlay');
            if (e.target === overlay) {
                closeModal();
            }
        });

        // =====================================
        // FUNGSI LEAFLET MAP & SIDEBAR
        // =====================================
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById('sidebar');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            openSidebarBtn.addEventListener('click', () => { sidebar.classList.remove('-translate-x-full'); });
            closeSidebarBtn.addEventListener('click', () => { sidebar.classList.add('-translate-x-full'); });

            const detailPopup = document.getElementById('damage-detail-popup');
            const closePopupBtn = document.getElementById('closeDetailPopupBtn');

            closePopupBtn.addEventListener('click', () => {
                detailPopup.classList.add('hidden');
                detailPopup.classList.remove('flex'); 
            });

            var map = L.map('map', { zoomControl: false }).setView([-6.200, 106.845], 13);
            L.control.zoom({ position: 'bottomright' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var markerLayer = L.layerGroup().addTo(map);
            var clickLayer = L.layerGroup().addTo(map);

            var locations = [
                { lat: -6.200, lng: 106.845, type: 'crack', address: 'Jl. Sudirman Kav. 12, Jakarta', date: '10 Februari 2026', image: 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=400' },
                { lat: -6.202, lng: 106.847, type: 'pothole', address: 'Jl. Thamrin No. 8, Jakarta', date: '25 Maret 2026', image: 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=400' },
                { lat: -6.204, lng: 106.848, type: 'deformation', address: 'Jl. Gatot Subroto, Jakarta', date: '15 April 2026', image: 'https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?auto=format&fit=crop&q=80&w=400' }
            ];

            function getColor(type) {
                if (type === 'crack') return 'blue';
                if (type === 'pothole') return 'red';
                if (type === 'deformation') return 'green';
            }

            let activeFilters = [];

            function loadMarkers() {
                markerLayer.clearLayers();

                locations.forEach(loc => {
                    if (!activeFilters.includes(loc.type)) return;

                    let marker = L.circleMarker([loc.lat, loc.lng], {
                        color: getColor(loc.type),
                        radius: 8,       
                        fillOpacity: 0.8,
                        weight: 2        
                    });

                    marker.on('mouseover', function (e) {
                        this.setRadius(14); 
                        this.setStyle({ fillOpacity: 1, weight: 3 }); 
                        this.bringToFront(); 
                    });

                    marker.on('mouseout', function (e) {
                        this.setRadius(8); 
                        this.setStyle({ fillOpacity: 0.8, weight: 2 }); 
                    });

                    marker.on('click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        document.getElementById('detail-address').innerText = loc.address;
                        document.getElementById('detail-date').innerText = loc.date;
                        document.getElementById('detail-type').innerText = loc.type;
                        document.getElementById('detail-image').src = loc.image;
                        detailPopup.classList.remove('hidden');
                        detailPopup.classList.add('flex');
                    });

                    marker.addTo(markerLayer);
                });
            }

            loadMarkers();

            // KUMPULAN TOMBOL FILTER
            const filterBtns = document.querySelectorAll('.btn-type');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const selectAllFiltersBtn = document.getElementById('selectAllFiltersBtn');

            // Logika Klik per Tombol Tipe Kerusakan
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    let type = this.dataset.type;

                    if (activeFilters.includes(type)) {
                        activeFilters = activeFilters.filter(t => t !== type);
                        this.classList.remove('bg-[#4a3219]');
                        this.classList.add('bg-[#a38771]');
                        detailPopup.classList.add('hidden');
                        detailPopup.classList.remove('flex');
                    } else {
                        activeFilters.push(type);
                        this.classList.remove('bg-[#a38771]');
                        this.classList.add('bg-[#4a3219]');
                    }

                    clickLayer.clearLayers();
                    loadMarkers();
                });
            });

            // LOGIKA TOMBOL CLEAR (Hapus Semua Pilihan)
            clearFiltersBtn.addEventListener('click', function() {
                activeFilters = []; // Kosongkan daftar filter
                
                // Ubah semua warna tombol filter menjadi mati/terang
                filterBtns.forEach(btn => {
                    btn.classList.remove('bg-[#4a3219]');
                    btn.classList.add('bg-[#a38771]');
                });

                // Sembunyikan popup & bersihkan peta
                detailPopup.classList.add('hidden');
                detailPopup.classList.remove('flex');
                clickLayer.clearLayers();
                loadMarkers();
            });

            // LOGIKA TOMBOL SELECT ALL (Pilih Semua Pilihan)
            selectAllFiltersBtn.addEventListener('click', function() {
                // Masukkan semua tipe ke dalam daftar filter
                activeFilters = ['crack', 'pothole', 'deformation'];
                
                // Ubah semua warna tombol filter menjadi aktif/gelap
                filterBtns.forEach(btn => {
                    btn.classList.remove('bg-[#a38771]');
                    btn.classList.add('bg-[#4a3219]');
                });

                // Bersihkan klik dan muat ulang marker di peta
                clickLayer.clearLayers();
                loadMarkers();
            });

            map.on('click', function(e) {
                clickLayer.clearLayers();
                detailPopup.classList.add('hidden');
                detailPopup.classList.remove('flex');

                L.circleMarker(e.latlng, {
                    color: 'black',
                    radius: 8
                }).addTo(clickLayer)
                .bindPopup("Koordinat Baru: " + e.latlng.lat.toFixed(5) + ", " + e.latlng.lng.toFixed(5))
                .openPopup();
            });
        });
    </script>
</body>
</html>