<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Map Kerusakan Jalan
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="relative w-full overflow-hidden" style="height: calc(100vh - 142px);">
        
        <!-- Tombol Buka Sidebar -->
        <button id="openSidebar" class="absolute top-4 left-4 z-[500] bg-[#4a3219] text-white p-2 rounded-md shadow-md hover:bg-[#382613] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- SIDEBAR KIRI -->
        <!-- Z-index [600] agar Sidebar selalu berada di lapisan teratas (menimpa Bottom Bar) -->
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
                <div class="flex flex-col gap-3">
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
            </div>

            <div class="p-6 shrink-0 border-t border-[#d8c8b8]">
                <p class="font-bold text-center text-gray-900 mb-2">Upload Photos</p>
                @auth
                    <!-- JIKA SUDAH LOGIN: Tampilkan tombol Upload -->
                    <button class="w-full flex items-center justify-center gap-2 bg-[#a38771] text-white py-2 rounded-full hover:bg-[#8c7460] transition shadow-md font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload
                    </button>
                @else
                    <!-- JIKA BELUM LOGIN: Arahkan ke halaman Login -->
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-[#4a3219] text-white py-2 rounded-full hover:bg-[#382613] transition shadow-md font-semibold text-sm text-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Login untuk Upload
                    </a>
                @endauth
            </div>
        </div>

        <!-- PETA UTAMA -->
        <div id="map" class="w-full h-full z-10 relative"></div>

        <!-- BOTTOM BAR (Informasi Detail Kerusakan) -->
        <!-- Z-index [550] agar posisinya berada di bawah Sidebar (z-[600]) -->
        <div id="infoBar" class="absolute bottom-0 left-0 w-full bg-[#eaddcf] z-[550] transform translate-y-full transition-transform duration-300 shadow-[0_-10px_20px_rgba(0,0,0,0.15)] flex h-48 border-t-[6px] border-[#8c7460]">
            
            <!-- Tombol Close (X) sekarang di DALAM kontainer, akan ikut hilang saat ditutup -->
            <button id="closeInfoBar" class="absolute top-4 right-8 bg-[#8c7460] text-white p-1.5 rounded-md hover:bg-[#6b5848] transition shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Agar teks tidak tertutup sidebar saat sidebar terbuka, tambahkan padding kiri opsional jika diperlukan -->
            <div class="flex-1 p-8 sm:pl-[350px] flex flex-col justify-center text-gray-900 cursor-default">
                <div class="grid grid-cols-2 gap-y-6">
                    <div>
                        <span class="font-extrabold">Address : </span>
                        <span id="infoAddress" class="font-medium">Jl. Aaaaa</span>
                    </div>
                    <div>
                        <span class="font-extrabold">Submitted Date : </span>
                        <span id="infoDate" class="font-medium">1 Januari 2000</span>
                    </div>
                    <div class="col-span-2">
                        <span class="font-extrabold">Damage Type : </span>
                        <span id="infoType" class="font-medium capitalize">cracks</span>
                    </div>
                </div>
            </div>

            <!-- Area Gambar -->
            <div class="w-[400px] shrink-0 border-l-[6px] border-[#8c7460]">
                <img id="infoImage" src="" alt="Damage Photo" class="w-full h-full object-cover">
            </div>
        </div>
        
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- LOGIKA SIDEBAR TOGGLE ---
            const sidebar = document.getElementById('sidebar');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            openSidebarBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });

            closeSidebarBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });

            // --- LOGIKA BOTTOM INFO BAR TOGGLE ---
            const infoBar = document.getElementById('infoBar');
            const closeInfoBarBtn = document.getElementById('closeInfoBar');

            closeInfoBarBtn.addEventListener('click', () => {
                infoBar.classList.add('translate-y-full'); // Sembunyikan bar
            });

            // --- LOGIKA MAPS ---
            var map = L.map('map', {
                zoomControl: false 
            }).setView([-6.200, 106.845], 13);

            L.control.zoom({ position: 'topright' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var markerLayer = L.layerGroup().addTo(map);
            var clickLayer = L.layerGroup().addTo(map);

            // DATA DUMMY
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
                        radius: 8,       // Ukuran default
                        fillOpacity: 0.8,
                        weight: 2        // Ketebalan garis outline
                    });

                    // --- TAMBAHAN BARU: EFEK HOVER ---
                    marker.on('mouseover', function (e) {
                        this.setRadius(14); // Membesar saat di-hover
                        this.setStyle({ fillOpacity: 1, weight: 3 }); // Warna lebih solid
                        this.bringToFront(); // Membawa pin ini ke lapisan paling atas agar tidak tertimpa
                    });

                    marker.on('mouseout', function (e) {
                        this.setRadius(8); // Kembali ke ukuran semula
                        this.setStyle({ fillOpacity: 0.8, weight: 2 }); // Kembali ke warna semula
                    });
                    // ---------------------------------

                    marker.on('click', function(e) {
                        L.DomEvent.stopPropagation(e);
                        
                        document.getElementById('infoAddress').innerText = loc.address;
                        document.getElementById('infoDate').innerText = loc.date;
                        document.getElementById('infoType').innerText = loc.type;
                        document.getElementById('infoImage').src = loc.image;

                        infoBar.classList.remove('translate-y-full');
                    });

                    marker.addTo(markerLayer);
                });
            }

            loadMarkers();

            document.querySelectorAll('.btn-type').forEach(btn => {
                btn.addEventListener('click', function() {
                    let type = this.dataset.type;

                    if (activeFilters.includes(type)) {
                        activeFilters = activeFilters.filter(t => t !== type);
                        this.classList.remove('bg-[#4a3219]');
                        this.classList.add('bg-[#a38771]');
                        infoBar.classList.add('translate-y-full');
                    } else {
                        activeFilters.push(type);
                        this.classList.remove('bg-[#a38771]');
                        this.classList.add('bg-[#4a3219]');
                    }

                    clickLayer.clearLayers();
                    loadMarkers();
                });
            });

            // LOGIKA KLIK PETA KOSONG
            map.on('click', function(e) {
                clickLayer.clearLayers();
                infoBar.classList.add('translate-y-full');

                L.circleMarker(e.latlng, {
                    color: 'black',
                    radius: 8
                }).addTo(clickLayer)
                .bindPopup("Koordinat Baru: " + e.latlng.lat.toFixed(5) + ", " + e.latlng.lng.toFixed(5))
                .openPopup();
            });

        });
    </script>
</x-app-layout>