<x-app-layout>
    <!-- Header Halaman (Opsional, agar sesuai dengan desain bawaan Laravel Breeze) -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Map Kerusakan Jalan
        </h2>
    </x-slot>

    <!-- Konten Utama -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Pastikan CSS Leaflet dipanggil agar peta tidak berantakan -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

                <!-- Container Asli Milikmu -->
                <div class="container">
                    <!-- Sidebar -->
                    <div class="sidebar mb-4">
                        <h2 class="text-lg font-bold">Settings</h2>
                        <h4 class="font-semibold mt-2">Damage type</h4>

                        <div class="flex gap-2 mt-2">
                            <button class="btn-type px-4 py-2 bg-blue-200 rounded" data-type="crack">Cracks</button>
                            <button class="btn-type px-4 py-2 bg-red-200 rounded" data-type="pothole">Pothole</button>
                            <button class="btn-type px-4 py-2 bg-green-200 rounded" data-type="deformation">Deformation</button>
                        </div>
                    </div>

                    <!-- Map (Saya tambahkan style height agar peta bisa muncul) -->
                    <div id="map" style="height: 500px; width: 100%; z-index: 1;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Leaflet dan Script Asli Milikmu -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            var map = L.map('map').setView([-6.200, 106.845], 13);

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

            let activeFilters = [];

            function loadMarkers() {
                markerLayer.clearLayers();

                locations.forEach(loc => {
                    if (activeFilters.length > 0 && !activeFilters.includes(loc.type)) return;

                    L.circleMarker([loc.lat, loc.lng], {
                        color: getColor(loc.type),
                        radius: 8
                    }).addTo(markerLayer);
                });
            }

            loadMarkers();

            document.querySelectorAll('.btn-type').forEach(btn => {
                btn.addEventListener('click', function() {

                    let type = this.dataset.type;

                    if (activeFilters.includes(type)) {
                        activeFilters = activeFilters.filter(t => t !== type);
                        this.classList.remove('active', type);
                        // Tambahan visual agar kelihatan mana yang aktif
                        this.classList.remove('ring-2', 'ring-offset-2', 'ring-black');
                    } else {
                        activeFilters.push(type);
                        this.classList.add('active', type);
                        // Tambahan visual agar kelihatan mana yang aktif
                        this.classList.add('ring-2', 'ring-offset-2', 'ring-black');
                    }

                    clickLayer.clearLayers();
                    loadMarkers();
                });
            });

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