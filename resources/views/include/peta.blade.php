<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta UMKM</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <style>
        #menu {
        max-height: 300px;
        overflow-y: auto;
        }
        .submenupeta {
        display: none;
        padding-left: 20px;
        margin-top: 5px;
        }
        .submenu label {
        display: block;
        }
        .btn{
        font-size: 30px;
        }
        #zoom-slider {
        position: absolute;
        top: 10px;
        left: 20px;
        z-index: 1000;
        padding: 5px;
        border-radius: 5px;
        }
    </style>
</head>
<body>
    <div style="display: flex; justify-content: flex-end;">
        <button id="toggleRouting" class="btn" title="Aktifkan Rute">
            <i class="bi bi-signpost-2-fill"></i>
        </button>
        <button id="clearMap" class="btn" title="Hapus Rute">
            <i class="bi bi-trash3-fill"></i>
        </button>
        <div class="input-group" style="max-width: 300px; padding: 10px;">
            <input id="search-box" type="text" class="form-control" placeholder="Ketik di sini" aria-label="Search" aria-describedby="button-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" style="font-size: 18px" type="button" id="button-addon2">Cari</button>
            </div>
        </div>
    </div>
  
    <div id="mapid">
        <div class="container mt-4">
            <div id="menu-container">
                <h6>Pilih: <i id="menu-toggle" class="bi bi-chevron-down"></i></h6>
                <div id="menu" class="border rounded p-3">
                    <!-- Wilayah Checkbox Menu -->
                    <label><b> Kelurahan</b></label><br>
                    @foreach($kelurahan as $kel)
                        @if($selectedKecamatan && $kel->kecamatan_id == $selectedKecamatan->id)
                            @if($kel->geojson_path) <!-- Hanya jika ada geojson_path -->
                                <label>
                                    <input type="checkbox" class="kelurahan-checkbox" data-kelurahan-id="{{ $kel->id }}" data-kelurahan-name="{{ $kel->nama_kelurahan }}" data-geojson="{{ $kel->geojson_path }}">
                                    {{ $kel->nama_kelurahan }}
                                </label><br>
                            @else
                                <label>{{ $kel->nama_kelurahan }} (GeoJSON tidak tersedia)</label><br>
                            @endif
                        @endif
                    @endforeach  
                    <label><b>Tampilkan</b></label><br>
                    <label>
                        <input type="checkbox" id="toggle-layer" data-geojson-url="/geospasial/kota_banjarmasin.geojson"> Kota Banjarmasin
                    </label><br>
                    <label>
                        <input type="checkbox" class="umkmCheckbox" id="toggle-umkm-markers"> Penanda UMKM
                    </label>
                </div>
            </div>
            <div id="zoom-slider">
                <input type="range" id="zoomRange" min="13" max="19" step="0.1" value="{{ $zoomLevel }}"><br>
                <input type="number" id="zoomInput" min="13" max="19" step="0.1" value="{{ $zoomLevel }}" style="width: 50px;">
                <span id="zoomValue">{{ $zoomLevel }}</span>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
  
    <script> 
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Peta
            var map = L.map("mapid", {
                zoomSnap: 0.1, 
                zoomDelta: 0.1,
                zoomControl: false,
            }).setView([{{ $centerCoordinates[0] }}, {{ $centerCoordinates[1] }}], {{ $zoomLevel }});
            // Layer Dasar
            var initialTileLayer = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Set batas maksimum (max bounds) untuk Banjarmasin
            var southWest = L.latLng(-3.3821391999999260, 114.5219479000001002);
            var northEast = L.latLng(-3.2672272999999450, 114.6595898000000489);
            var bounds = L.latLngBounds(southWest, northEast);

            map.setMaxBounds(bounds);
            map.setMinZoom(13); // Set minimum zoom level yang sesuai
            map.setMaxZoom(19); // Set maximum zoom level

            // Hubungkan slider zoom dan input zoom dengan kontrol zoom di peta
            var zoomRange = document.getElementById('zoomRange');
            var zoomInput = document.getElementById('zoomInput');
            var zoomValue = document.getElementById('zoomValue');

            // Fungsi untuk menonaktifkan drag di peta saat slider di-drag
            function disableMapDrag() {
                map.dragging.disable();
            }

            // Fungsi untuk mengaktifkan kembali drag di peta
            function enableMapDrag() {
                map.dragging.enable();
            }

            // Event listener untuk slider zoom
            zoomRange.addEventListener('input', function() {
                var zoomLevel = parseFloat(this.value);
                map.setZoom(zoomLevel);
                zoomInput.value = zoomLevel.toFixed(1); // Update input field
                zoomValue.textContent = zoomLevel.toFixed(1); // Update text span
            });

            // Event listener untuk input field zoom
            zoomInput.addEventListener('input', function() {
                var zoomLevel = parseFloat(this.value);
                if (zoomLevel >= 13 && zoomLevel <= 19) {
                    map.setZoom(zoomLevel);
                    zoomRange.value = zoomLevel.toFixed(1); // Update slider
                    zoomValue.textContent = zoomLevel.toFixed(1); // Update text span
                }
            });

            // Sinkronkan slider dan input dengan zoom level peta
            map.on('zoomend', function() {
                var zoomLevel = map.getZoom().toFixed(1);
                zoomRange.value = zoomLevel;
                zoomInput.value = zoomLevel;
                zoomValue.textContent = zoomLevel;
            });

            // Nonaktifkan panning saat slider di-drag
            zoomRange.addEventListener('mousedown', disableMapDrag);
            zoomRange.addEventListener('mouseup', enableMapDrag);

            // Daftar warna utama
            const colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'];
            function getRandomColor(excludeColors) {
                const filteredColors = colors.filter(color => !excludeColors.includes(color));
                return filteredColors[Math.floor(Math.random() * filteredColors.length)];
            }

            // Mewarnai GeoJSON dengan memastikan warna yang tidak sama untuk wilayah yang berbatasan langsung
            fetch("{{ asset($geojsonFile) }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                const geoJsonLayer = L.geoJSON(data, {
                    style: function (feature) {
                        if (!feature.properties.color) {
                            let adjacentColors = [];
                            map.eachLayer(layer => {
                                if (layer instanceof L.GeoJSON && layer !== geoJsonLayer) {
                                    const featureLayer = layer.getLayers().find(l => l.feature && l.feature.id === feature.id);
                                    if (featureLayer) {
                                        adjacentColors.push(featureLayer.options.color);
                                    }
                                }
                            });
                            feature.properties.color = getRandomColor(adjacentColors);
                        }
                        return { color: feature.properties.color };
                    },
                    onEachFeature: function (feature, layer) {
                        if (feature.properties && feature.properties.kecamatan) {
                            console.log("Kecamatan fitur:", feature.properties.kecamatan);
                            layer.bindPopup(feature.properties.kecamatan);  // Menampilkan popup dengan nama kecamatan
                        } else {
                            console.log("Properti 'kecamatan' tidak ditemukan pada fitur:", feature);
                        }
                        // Event klik pada layer
                        layer.on('click', function (e) {
                            layer.openPopup();  // Membuka popup saat diklik
                        });
                    }
                }).addTo(map);
                // Zoom ke batas layer GeoJSON setelah layer ditambahkan
                var geojsonBounds = geoJsonLayer.getBounds();
                if (geojsonBounds.isValid()) {
                    map.fitBounds(geojsonBounds);
                } else {
                    console.log("GeoJSON bounds are not valid.");
                }
            })
            .catch(error => console.log('Error loading GeoJSON:', error));
            
            // Layer untuk marker UMKM
            var markersLayer = new L.LayerGroup();
            // Menambahkan marker UMKM
            @foreach($umkms as $umkm)
                @if(!is_null($umkm->latitude) && !is_null($umkm->longitude))
                    var marker = L.marker([{{ $umkm->latitude }}, {{ $umkm->longitude }}])
                        .bindPopup("<b>Nama:</b> {{ $umkm->nama }}<br><b>Jenis Usaha:</b> {{ $umkm->jenis_usaha }}<br><b>Alamat:</b> {{ $umkm->alamat }}<br><b>Kecamatan:</b> {{ $umkm->kecamatan->nama_kecamatan }}");
                    marker.options.title = "{{ $umkm->nama }}"; // Properti pencarian
                    markersLayer.addLayer(marker); // Tambahkan ke layer marker
                @endif
            @endforeach

            // Fungsi untuk menangani pencarian saat tombol 'Cari' ditekan
            document.getElementById('button-addon2').addEventListener('click', function() {
                var searchQuery = document.getElementById('search-box').value;
                var found = false;

                markersLayer.eachLayer(function(layer) {
                    if (layer.options.title.toLowerCase().includes(searchQuery.toLowerCase())) {
                        map.setView(layer.getLatLng(), 18); // Pindahkan ke marker yang ditemukan
                        layer.openPopup(); // Buka popup pada marker
                        found = true;
                    }
                });

                if (!found) {
                    alert('UMKM tidak ditemukan!');
                }
            });

            // Mengelola checkbox untuk menampilkan/menyembunyikan marker UMKM
            document.getElementById('toggle-umkm-markers').addEventListener('change', function() {
                if (this.checked) {
                    map.addLayer(markersLayer); // Tampilkan marker UMKM saat checkbox diaktifkan
                } else {
                    map.removeLayer(markersLayer); // Sembunyikan marker UMKM saat checkbox dinonaktifkan
                }
            });

            // Routing
            var startMarker, endMarker;
            var routeControl = null;
            var routingActive = false;
            map.on('click', function(e) {
                if (routingActive) {
                    if (!startMarker) {
                        startMarker = L.marker(e.latlng, { draggable: true }).addTo(map)
                            .bindPopup('Titik Awal').openPopup();
                    } else if (!endMarker) {
                        endMarker = L.marker(e.latlng, { draggable: true }).addTo(map)
                            .bindPopup('Titik Akhir').openPopup();

                        routeControl = L.Routing.control({
                            waypoints: [startMarker.getLatLng(), endMarker.getLatLng()],
                            routeWhileDragging: true
                        }).addTo(map);
                    } else {
                        map.removeLayer(startMarker);
                        map.removeLayer(endMarker);
                        if (routeControl) {
                            map.removeControl(routeControl);
                        }
                        startMarker = L.marker(e.latlng, { draggable: true }).addTo(map)
                            .bindPopup('Titik Awal').openPopup();
                        endMarker = null;
                    }
                }
            });
            document.getElementById('toggleRouting').addEventListener('click', function() {
                routingActive = !routingActive;
                this.classList.toggle('active', routingActive);
                if (!routingActive && routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }
            });
            document.getElementById('clearMap').addEventListener('click', function() {
                if (startMarker) {
                    map.removeLayer(startMarker);
                    startMarker = null;
                }
                if (endMarker) { 
                    map.removeLayer(endMarker);
                    endMarker = null;
                }
                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }
                routingActive = false;
                document.getElementById('toggleRouting').classList.remove('active');
            });

            // Array untuk menyimpan layer GeoJSON
            var geoJsonLayers = [];
            document.querySelectorAll('.kelurahan-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    var geojsonPath = this.getAttribute('data-geojson');
                    if (this.checked) {
                        fetch(geojsonPath)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                var newLayer = L.geoJSON(data, {
                                    style: function (feature) {
                                        let adjacentColors = [];
                                        map.eachLayer(layer => {
                                            if (layer instanceof L.GeoJSON) {
                                                layer.eachLayer(l => {
                                                    if (l.feature && l.feature.properties && l.feature.properties.color) {
                                                        if (l.getBounds().intersects(L.geoJSON(feature.geometry).getBounds())) {
                                                            adjacentColors.push(l.feature.properties.color);
                                                        }
                                                    }
                                                });
                                            }
                                        });

                                        const color = getRandomColor(adjacentColors);
                                        feature.properties.color = color;
                                        return { color: color };
                                    },
                                    onEachFeature: function (feature, layer) {
                                        // Menampilkan properti 'village' sebagai label
                                        if (feature.properties && feature.properties.village) {
                                            layer.bindPopup(feature.properties.village);  // Menampilkan nama desa (kelurahan)
                                        } else {
                                            console.log("Properti 'village' tidak ditemukan pada fitur:", feature);
                                        }

                                        // Event klik untuk menampilkan popup
                                        layer.on('click', function () {
                                            layer.openPopup();
                                        });
                                    }
                                });

                                // Menyimpan informasi warna pada layer
                                newLayer.eachLayer(function (layer) {
                                    layer.feature.properties.color = layer.options.style.color;
                                });

                                newLayer.options.geojsonPath = geojsonPath;
                                geoJsonLayers.push(newLayer);
                                newLayer.addTo(map);

                                // Zoom ke batas layer GeoJSON
                                var geojsonBounds = newLayer.getBounds();
                                if (geojsonBounds.isValid()) {
                                    map.fitBounds(geojsonBounds);
                                } else {
                                    console.log("GeoJSON bounds are not valid.");
                                }
                            })
                            .catch(error => console.log('Error loading GeoJSON:', error));
                    } else {
                        geoJsonLayers.forEach((layer, index) => {
                            if (layer.options.geojsonPath === geojsonPath) {
                                map.removeLayer(layer); // Remove from the map
                                geoJsonLayers.splice(index, 1); // Remove from the array
                            }
                        });
                    }
                });
            });

            // Fungsi untuk memuat dan menampilkan GeoJSON
            let geoJsonLayer = null;
            function loadGeoJson(url) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hapus layer lama jika ada
                        if (geoJsonLayer) {
                            map.removeLayer(geoJsonLayer);
                        }
                        // Tambahkan layer GeoJSON ke peta
                        geoJsonLayer = L.geoJSON(data, {
                            style: function (feature) {
                                let adjacentColors = [];
                                map.eachLayer(layer => {
                                    if (layer instanceof L.GeoJSON && layer !== geoJsonLayer) {
                                        const featureLayer = layer.getLayers().find(l => l.feature && l.feature.id === feature.id);
                                        if (featureLayer) {
                                            adjacentColors.push(featureLayer.options.color);
                                        }
                                    }
                                });
                                // Tentukan warna untuk fitur ini
                                const color = getRandomColor(adjacentColors);
                                return { color: color, weight: 2 }; // Sesuaikan styling jika perlu
                            },
                            onEachFeature: function (feature, layer) {
                                // Tambahkan popup pada setiap fitur
                                if (feature.properties && feature.properties.kecamatan) {
                                    layer.bindPopup(feature.properties.kecamatan); // Menggunakan properti 'kecamatan' dari fitur
                                } else {
                                    layer.bindPopup('No name available'); // Jika tidak ada properti 'kecamatan'
                                }
                                // Event listener klik pada layer
                                layer.on('click', function (e) {
                                    layer.openPopup(); // Membuka popup saat diklik
                                });
                            }
                        }).addTo(map);
                        // Zoom ke batas layer GeoJSON setelah layer ditambahkan
                        const geojsonBounds = geoJsonLayer.getBounds();
                        if (geojsonBounds.isValid()) {
                            map.fitBounds(geojsonBounds);
                        } else {
                            console.log("GeoJSON bounds are not valid.");
                        }
                    })
                    .catch(error => console.log('Error loading GeoJSON:', error));
                }
                // Event listener untuk checkbox
                const checkbox = document.getElementById('toggle-layer');
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        const geojsonUrl = this.getAttribute('data-geojson-url');
                        loadGeoJson(geojsonUrl);
                    } else {
                        if (geoJsonLayer) {
                        map.removeLayer(geoJsonLayer);
                    }
                }
            });

        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
        $('.geojsonCheckbox').change(function() {
            var submenuId = $(this).data('submenu');
            if (this.checked) {
            $('#' + submenuId).show();
            } else {
            $('#' + submenuId).hide();
            }
        });
        });
    </script>
</body>
</html>
