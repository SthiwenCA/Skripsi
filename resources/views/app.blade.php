<!DOCTYPE html>
<html>
<head>
    <title>Map Kerusakan Jalan</title>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #e6e1e1;
            padding: 20px;
        }

        .sidebar h2 {
            border: 2px dashed #000;
            display: inline-block;
            padding: 5px;
        }

        .btn-type {
            padding: 10px;
            border-radius: 20px;
            margin: 10px 0;
            cursor: pointer;
            background: white;
            border: 2px solid #ccc;
            transition: 0.3s;
        }

        /* ACTIVE MULTI */
        .btn-type.active.crack {
            background: #3b82f6;
            color: white;
        }

        .btn-type.active.pothole {
            background: #ef4444;
            color: white;
        }

        .btn-type.active.deformation {
            background: #22c55e;
            color: white;
        }

        .upload {
            margin-top: 40px;
            text-align: center;
        }

        .upload button {
            background: #8b5e3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
        }

        /* Map */
        #map {
            flex: 1;
            height: 100vh;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Settings</h2>

        <h4>Damage type</h4>

        <div class="btn-type" data-type="crack">Cracks</div>
        <div class="btn-type" data-type="pothole">Pothole</div>
        <div class="btn-type" data-type="deformation">Deformation</div>

        <div class="upload">
            <p>Upload Photos</p>
            <button>Upload</button>
        </div>
    </div>

    <!-- Map -->
    <div id="map"></div>

</div>

<!-- Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // INIT MAP
    var map = L.map('map').setView([-6.200, 106.845], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // =========================
    // LAYER SYSTEM
    // =========================
    var markerLayer = L.layerGroup().addTo(map); // data marker
    var clickLayer = L.layerGroup().addTo(map);  // marker klik

    // =========================
    // DATA (dummy / dari DB)
    // =========================
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

    // =========================
    // MULTI FILTER
    // =========================
    let activeFilters = [];

    function loadMarkers() {

        markerLayer.clearLayers();

        locations.forEach(loc => {

            if (activeFilters.length > 0 && !activeFilters.includes(loc.type)) {
                return;
            }

            L.circleMarker([loc.lat, loc.lng], {
                color: getColor(loc.type),
                radius: 8
            }).addTo(markerLayer);

        });
    }

    // LOAD AWAL
    loadMarkers();

    // =========================
    // BUTTON FILTER (MULTI)
    // =========================
    const buttons = document.querySelectorAll('.btn-type');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {

            let type = this.dataset.type;

            if (activeFilters.includes(type)) {
                activeFilters = activeFilters.filter(t => t !== type);
                this.classList.remove('active', type);
            } else {
                activeFilters.push(type);
                this.classList.add('active', type);
            }

            // 🔥 hapus marker klik saat filter dipakai
            clickLayer.clearLayers();

            loadMarkers();
        });
    });

    // =========================
    // MARKER KLIK (NO TRACE)
    // =========================
    var clickMarker;

    map.on('click', function(e) {

        // selalu hapus marker lama
        clickLayer.clearLayers();

        clickMarker = L.circleMarker(e.latlng, {
            color: 'black',
            radius: 8
        }).addTo(clickLayer)
        .bindPopup("Koordinat: " + e.latlng.lat + ", " + e.latlng.lng)
        .openPopup();

    });

</script>

</body>
</html>