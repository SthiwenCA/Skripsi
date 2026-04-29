<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Map Kerusakan Jalan
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="relative w-full overflow-hidden" style="height: calc(100vh - 142px);">
        
        <button id="openSidebar" class="absolute top-4 left-4 z-[500] bg-[#4a3219] text-white p-2 rounded-md shadow-md hover:bg-[#382613] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

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
                <button class="w-full flex items-center justify-center gap-2 bg-[#a38771] text-white py-2 rounded-full hover:bg-[#8c7460] transition shadow-md font-semibold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Upload
                </button>
            </div>
        </div>

        <div id="map" class="w-full h-full z-10 relative"></div>
        
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- LOGIKA SIDEBAR TOGGLE ---
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');

            openBtn.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
            });

            closeBtn.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
            });

            // --- LOGIKA MAPS ---
            var map = L.map('map', {
                zoomControl: false 
            }).setView([-6.200, 106.845], 13);

            L.control.zoom({ position: 'bottomright' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var markerLayer = L.layerGroup().addTo(map);
            var clickLayer = L.layerGroup().addTo(map);

            var locations = [
                { lat: -6.200, lng: 106.845, type: 'crack' },
                { lat: -6.202, lng: 106.847, type: 'pothole' },
                { lat: -6.204, lng: 106.848, type: 'deformation' }
            ];

            function getColor(type) {
                if (type === 'crack') return 'blue';
                if (type === 'pothole') return 'red';
                if (type === 'deformation') return 'green';
            }

            // Default: tidak ada filter yang aktif
            let activeFilters = [];

            function loadMarkers() {
                markerLayer.clearLayers();

                locations.forEach(loc => {
                    // LOGIKA BARU: Jika tipe tidak ada di activeFilters, pin tidak akan dimunculkan
                    if (!activeFilters.includes(loc.type)) return;

                    L.circleMarker([loc.lat, loc.lng], {
                        color: getColor(loc.type),
                        radius: 8,
                        fillOpacity: 0.8
                    }).addTo(markerLayer);
                });
            }

            // Panggil saat pertama load (Peta akan kosong karena activeFilters masih kosong)
            loadMarkers();

            document.querySelectorAll('.btn-type').forEach(btn => {
                btn.addEventListener('click', function() {
                    let type = this.dataset.type;

                    if (activeFilters.includes(type)) {
                        // Jika dihapus dari filter (diklik ulang)
                        activeFilters = activeFilters.filter(t => t !== type);
                        
                        // Kembalikan ke warna coklat burem
                        this.classList.remove('bg-[#4a3219]');
                        this.classList.add('bg-[#a38771]');
                    } else {
                        // Jika ditambahkan ke filter (dipilih)
                        activeFilters.push(type);
                        
                        // Jadikan warna coklat gelap permanen
                        this.classList.remove('bg-[#a38771]');
                        this.classList.add('bg-[#4a3219]');
                    }

                    clickLayer.clearLayers(); // Hapus marker klik (jika ada) saat memfilter
                    loadMarkers();
                });
            });

            // --- LOGIKA KLIK PETA UNTUK KOORDINAT ---
            map.on('click', function(e) {
                clickLayer.clearLayers();

                L.circleMarker(e.latlng, {
                    color: 'black',
                    radius: 8
                }).addTo(clickLayer)
                .bindPopup("Koordinat: " + e.latlng.lat + ", " + e.latlng.lng)
                .openPopup();
            });

        });
    </script>
</x-app-layout>