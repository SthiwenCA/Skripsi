<x-app-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full font-bold text-sm text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="font-extrabold text-xl text-gray-800 leading-tight">Upload Laporan Kerusakan</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 sm:p-10 bg-white shadow-lg sm:rounded-2xl border border-gray-100">
                <div class="max-w-2xl mx-auto">
                    
                    <h3 class="text-xl font-bold text-gray-900">Tentukan Lokasi & Unggah Foto</h3>
                    <p class="text-gray-700 font-medium mt-1 mb-8 leading-relaxed">
                        Silakan klik pada peta untuk menentukan titik lokasi kerusakan jalan. Alamat akan terisi otomatis.
                    </p>

                    <form method="POST" action="{{ route('submissions.store') }}" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        <input type="hidden" name="submission_date" value="{{ $todayDate ?? date('Y-m-d') }}">

                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Pilih Titik Lokasi (Pinpoint)</label>
                            
                            <div id="map-selector" class="w-full h-72 rounded-2xl border-2 border-gray-300 shadow-inner z-0 relative"></div>
                            
                            <input type="hidden" name="latitude" id="lat-input" required>
                            <input type="hidden" name="longitude" id="lng-input" required>
                            <input type="hidden" name="address" id="address-input" required>
                            
                            <div class="mt-3 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-500 font-medium">Koordinat: <span id="coords-display" class="font-bold text-[#4a3219]">Belum dipilih</span></p>
                                <p class="text-sm text-gray-700 font-medium mt-1">Alamat: <span id="address-display" class="font-bold text-[#4a3219]">Belum ada lokasi yang dipilih</span></p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="image" class="block font-semibold text-gray-700">Upload Foto (PNG, JPG, JPEG)</label>
                                <input id="image" class="w-full mt-2 px-4 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-[#4a3219] shadow-sm text-gray-900 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#e6dcd3] file:text-[#4a3219] hover:file:bg-[#d8c8b8] cursor-pointer transition" type="file" name="image" accept=".png, .jpeg, .jpg" required onchange="previewImage(this);" />
                            </div>
                            
                            <div class="relative w-full h-56 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shadow-inner">
                                <img id="photo-preview" src="#" alt="Pratinjau Foto" class="hidden w-full h-full object-cover">
                                <span id="no-photo-text" class="text-sm text-gray-400 font-medium">Belum ada foto yang dipilih</span>
                                <button type="button" id="remove-photo-btn" onclick="removeImage()" class="hidden absolute top-3 right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold shadow-md hover:bg-red-600 transition z-10">✕</button>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="inline-flex justify-center items-center px-8 py-3 bg-[#4a3219] rounded-full font-bold text-white tracking-widest hover:bg-[#382314] active:bg-[#281a0d] transition shadow-md">
                                SUBMIT LAPORAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // MENGHILANGKAN ATTRIBUTION LEAFLET DI SINI
            var map = L.map('map-selector', { attributionControl: false }).setView([-6.200000, 106.845000], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            var marker;

            // FUNGSI UNTUK MENCARI ALAMAT DARI KOORDINAT (REVERSE GEOCODING)
            function getAddress(lat, lng) {
                document.getElementById('address-display').innerText = "Mencari alamat...";
                
                // Memanggil API OpenStreetMap
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        let fullAddress = data.display_name;
                        // Menampilkan ke layar dan mengisi input tersembunyi
                        document.getElementById('address-display').innerText = fullAddress;
                        document.getElementById('address-input').value = fullAddress;
                    })
                    .catch(error => {
                        document.getElementById('address-display').innerText = "Gagal mendapatkan alamat otomatis";
                        document.getElementById('address-input').value = "Koordinat: " + lat + ", " + lng;
                    });
            }

            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                document.getElementById('lat-input').value = lat;
                document.getElementById('lng-input').value = lng;
                document.getElementById('coords-display').innerText = lat.toFixed(6) + ", " + lng.toFixed(6);

                // Panggil pencari alamat
                getAddress(lat, lng);

                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng, {draggable: true}).addTo(map);
                    marker.on('dragend', function(event) {
                        var position = marker.getLatLng();
                        document.getElementById('lat-input').value = position.lat;
                        document.getElementById('lng-input').value = position.lng;
                        document.getElementById('coords-display').innerText = position.lat.toFixed(6) + ", " + position.lng.toFixed(6);
                        
                        // Panggil pencari alamat lagi saat marker digeser
                        getAddress(position.lat, position.lng);
                    });
                }
            });
        });

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
            document.getElementById('photo-preview').classList.add('hidden');
            document.getElementById('no-photo-text').classList.remove('hidden');
            document.getElementById('remove-photo-btn').classList.add('hidden');
            document.getElementById('image').value = '';
        }
    </script>
</x-app-layout>