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
        .compass {
            position: absolute;
            bottom: 10px; /* Jarak dari bawah peta */
            right: 10px; /* Jarak dari kanan peta */
            width: 130px; /* Ukuran kompas */
            height: 130px;
            background: url('{{ asset('image/kompas transparan.png') }}') no-repeat center center;
            background-size: contain;
            z-index: 500; /* Pastikan tampil di atas lapisan peta */
            pointer-events: none; /* Agar tidak menghalangi interaksi dengan peta */
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
                <h6>Tampilkan <i id="menu-toggle" class="bi bi-caret-down-fill"></i></h6>
                <div id="menu" class="border rounded p-3 scrollable-menu">
                    <!-- Wilayah Checkbox Menu -->
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
                    @if(!$selectedKecamatan)
                        <label>
                            <input type="checkbox" id="toggle-layer" data-geojson-url="/geospasial/kota_banjarmasin.geojson"> Kota Banjarmasin
                        </label><br>
                    @endif                   
                </div>                          
                <div id="umkm-grid-container" class="border rounded p-3 mt-2">
                    <label>
                        <input type="checkbox" class="umkmCheckbox" id="toggle-umkm-markers"> Penanda UMKM
                    </label><br>
                    <label>
                        <input type="checkbox" class="umkmCheckbox" id="toggle-kecamatan-markers"> Kantor Kecamatan
                    </label><br>
                    <label>
                        <input type="checkbox" class="umkmCheckbox" id="toggle-kelurahan-markers"> Kantor Kelurahan
                    </label><br>
                    <label>
                        <input type="checkbox" class="umkmCheckbox" id="toggle-grid"> Tampilkan Grid
                    </label><br>
                    <label>
                        <input type="checkbox" id="toggle-custom-grid"> Batas Kota Banjarmasin
                    </label>
                </div>
            </div>
            <div id="zoom-slider">
                <input type="range" id="zoomRange" min="12" max="19" step="0.1" value="{{ $zoomLevel }}"><br>
                <input type="number" id="zoomInput" min="12" max="19" step="0.1" value="{{ $zoomLevel }}" style="width: 50px;">
                <span id="zoomValue">{{ $zoomLevel }}</span>
            </div>
            <div class="compass"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
    <!-- Impor SweetAlert2 CSS dan JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
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

            // Google Maps Layers
            var googleLayers = {
                "Streets": L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] }),
                "Hybrid": L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] }),
                "Satellite": L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', { maxZoom: 20, subdomains: ['mt0', 'mt1', 'mt2', 'mt3'] })
            };

            // Menambahkan Layer Control
            L.control.layers({
                "OpenStreetMap": initialTileLayer,
                "Google Streets": googleLayers.Streets,
                "Google Hybrid": googleLayers.Hybrid,
                "Google Satellite": googleLayers.Satellite
            }).addTo(map);

            // Menambahkan kontrol skala
            L.control.scale({
                imperial: false, // Set ke true jika ingin menampilkan dalam satuan imperial (mil)
                metric: true     // Set ke true jika ingin menampilkan dalam satuan metrik (meter/kilometer)
            }).addTo(map);

            // Set batas maksimum (max bounds) untuk Banjarmasin
            var southWest = L.latLng(-3.3821391999999260, 114.5219479000001002);
            var northEast = L.latLng(-3.2672272999999450, 114.6595898000000489);
            var bounds = L.latLngBounds(southWest, northEast);

            map.setMaxBounds(bounds);
            map.setMinZoom(12); // Set minimum zoom level yang sesuai
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
    
            // Fungsi untuk mengubah koordinat menjadi DMS
            function toDMS(lat, lng) {
                var latDeg = Math.floor(Math.abs(lat));
                var latMin = Math.floor((Math.abs(lat) - latDeg) * 60);
                var latSec = Math.round((Math.abs(lat) - latDeg - latMin / 60) * 3600);

                var lngDeg = Math.floor(Math.abs(lng));
                var lngMin = Math.floor((Math.abs(lng) - lngDeg) * 60);
                var lngSec = Math.round((Math.abs(lng) - lngDeg - lngMin / 60) * 3600);

                var latDMS = `${latDeg}° ${latMin}' ${latSec}"`;
                var lngDMS = `${lngDeg}° ${lngMin}' ${lngSec}"`;

                if (latDeg === 0 && latMin === 0 && latSec === 0) {
                    latDMS = '';
                }

                if (lngDeg === 0 && lngMin === 0 && lngSec === 0) {
                    lngDMS = '';
                }

                return `${latDMS} ${lngDMS}`;
            }

            // Array untuk menyimpan garis grid interaktif dan grid khusus
            window.gridLines = [];
            window.customGridLines = [];

            // Fungsi untuk menggambar grid khusus
            function drawCustomGrid() {
                // Hapus grid khusus yang ada
                if (window.customGridLines && window.customGridLines.length) {
                    window.customGridLines.forEach(function (line) {
                        map.removeLayer(line);
                    });
                }
                window.customGridLines = []; // Inisialisasi ulang array untuk menyimpan garis grid khusus

                // Definisi batas grid khusus dalam derajat
                var south = -(3 + 16 / 60 + 46 / 3600); // 3°16'46" LS
                var north = -(3 + 22 / 60 + 54 / 3600); // 3°22'54" LS
                var west = 114 + 31 / 60 + 40 / 3600; // 114°31'40" BT
                var east = 114 + 39 / 60 + 55 / 3600; // 114°39'55" BT

                console.log("South:", south, "North:", north, "West:", west, "East:", east);

                // Menggambar garis horizontal atas
                var topLine = L.polyline([[north, west], [north, east]], { color: 'blue', weight: 1 }).addTo(map);
                topLine.bindTooltip("Selatan: " + toDMS(north, 0), { permanent: true, direction: 'top' });
                window.customGridLines.push(topLine);

                // Menggambar garis horizontal bawah
                var bottomLine = L.polyline([[south, west], [south, east]], { color: 'blue', weight: 1 }).addTo(map);
                bottomLine.bindTooltip("Utara: " + toDMS(south, 0), { permanent: true, direction: 'top' });
                window.customGridLines.push(bottomLine);

                // Menggambar garis vertikal kiri
                var leftLine = L.polyline([[south, west], [north, west]], { color: 'blue', weight: 1 }).addTo(map);
                leftLine.bindTooltip("Barat: " + toDMS(0, west), { permanent: true, direction: 'right' });
                window.customGridLines.push(leftLine);

                // Menggambar garis vertikal kanan
                var rightLine = L.polyline([[south, east], [north, east]], { color: 'blue', weight: 1 }).addTo(map);
                rightLine.bindTooltip("Timur: " + toDMS(0, east), { permanent: true, direction: 'right' });
                window.customGridLines.push(rightLine);
            }

            // Fungsi untuk menggambar grid interaktif
            function drawInteractiveGrid() {
                // Hapus grid interaktif yang ada
                if (window.gridLines.length) {
                    window.gridLines.forEach(function (line) {
                        map.removeLayer(line);
                    });
                }
                window.gridLines = []; // Inisialisasi ulang array untuk menyimpan garis grid interaktif

                var gridSize = 0.05 / Math.pow(2, map.getZoom() - 12); // Ukuran grid
                var bounds = map.getBounds(); // Mendapatkan batas peta saat ini

                // Menggambar garis horizontal
                for (var lat = Math.floor(bounds.getSouth() / gridSize) * gridSize; lat < bounds.getNorth(); lat += gridSize) {
                    var line = L.polyline([[lat, bounds.getWest()], [lat, bounds.getEast()]], { color: 'red', weight: 1 }).addTo(map);
                    var latDMS = toDMS(lat, 0);
                    if (latDMS.includes('°')) {
                        line.bindTooltip(latDMS, { permanent: true, direction: 'top' });
                    }
                    window.gridLines.push(line); // Simpan garis ke array interaktif
                }

                // Menggambar garis vertikal
                for (var lng = Math.floor(bounds.getWest() / gridSize) * gridSize; lng < bounds.getEast(); lng += gridSize) {
                    var line = L.polyline([[bounds.getSouth(), lng], [bounds.getNorth(), lng]], { color: 'red', weight: 1 }).addTo(map);
                    var lngDMS = toDMS(0, lng);
                    if (lngDMS.includes('°')) {
                        line.bindTooltip(lngDMS, { permanent: true, direction: 'right' });
                    }
                    window.gridLines.push(line); // Simpan garis ke array interaktif
                }
            }

            // Event listener untuk grid interaktif
            document.getElementById('toggle-grid').addEventListener('change', function () {
                if (this.checked) {
                    drawInteractiveGrid(); // Gambar grid interaktif jika checkbox dicentang
                } else {
                    // Hapus grid interaktif jika checkbox tidak dicentang
                    if (window.gridLines.length) {
                        window.gridLines.forEach(function (line) {
                            map.removeLayer(line);
                        });
                        window.gridLines = []; // Kosongkan array garis grid interaktif
                    }
                }
            });

            // Event listener untuk grid khusus
            document.getElementById('toggle-custom-grid').addEventListener('change', function () {
                if (this.checked) {
                    drawCustomGrid(); // Gambar grid khusus jika checkbox dicentang
                } else {
                    // Hapus grid khusus jika checkbox tidak dicentang
                    if (window.customGridLines.length) {
                        window.customGridLines.forEach(function (line) {
                            map.removeLayer(line);
                        });
                        window.customGridLines = []; // Kosongkan array garis grid khusus
                    }
                }
            });

            // Event listener untuk memperbarui grid interaktif saat peta dipindahkan atau di-zoom
            map.on('moveend', function () {
                if (document.getElementById('toggle-grid').checked) {
                    drawInteractiveGrid();
                }
            });

            map.on('zoomend', function () {
                if (document.getElementById('toggle-grid').checked) {
                    drawInteractiveGrid();
                }
            });

            // Daftar warna untuk setiap kecamatan
            const kecamatanColors = {
                'Banjarmasin Barat': '#6d87cd',
                'Banjarmasin Timur': '#f0b87f',
                'Banjarmasin Utara': '#e35d94',
                'Banjarmasin Selatan': '#dda3d2',
                'Banjarmasin Tengah': '#6fc57f'
            };

            // Daftar warna utama
            const colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF'];

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
                            // Menggunakan warna yang ditentukan untuk kecamatan yang ada di dalam objek kecamatanColors
                            if (!feature.properties.color) {
                                let adjacentColors = [];
                                if (feature.properties.kecamatan && kecamatanColors[feature.properties.kecamatan]) {
                                    feature.properties.color = kecamatanColors[feature.properties.kecamatan];
                                } else {
                                    // Jika warna kecamatan tidak ada, cari warna acak
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
                            }
                            return { color: feature.properties.color, fillOpacity: 0.7 };
                        },
                        onEachFeature: function (feature, layer) {
                            if (feature.properties && feature.properties.kecamatan) {
                                console.log("Kecamatan fitur:", feature.properties.kecamatan);  // Debugging: menampilkan nama kecamatan
                                layer.bindPopup(feature.properties.kecamatan);  // Menampilkan popup dengan nama kecamatan
                            } else {
                                console.log("Properti 'kecamatan' tidak ditemukan pada fitur:", feature);  // Jika properti 'kecamatan' tidak ada
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
                                // var geojsonBounds = newLayer.getBounds();
                                // if (geojsonBounds.isValid()) {
                                //     map.fitBounds(geojsonBounds);
                                // } else {
                                //     console.log("GeoJSON bounds are not valid.");
                                // }
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

            let geoJsonLayer = null;
            // Fungsi untuk memuat dan menampilkan GeoJSON
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
                    // Tentukan warna untuk fitur ini berdasarkan nama kecamatan
                    const color = kecamatanColors[feature.properties.kecamatan];
                    return { color: color, weight: 3, fillOpacity: 0.7 }; // Sesuaikan styling jika perlu
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
            
            // Variabel global untuk kontrol routing
            var routingControl = null;
            var isRoutingActive = false; // Status untuk melacak apakah rute aktif

            // Layer untuk marker UMKM
            var markersLayer = new L.LayerGroup();

            // Definisi ikon untuk kecamatan yang berbeda
            var kecamatanIcons = {
                "Banjarmasin Utara": L.icon({
                    iconUrl: '/image/marker-icon-2x-red.png', // Ikon untuk Kecamatan 1
                    iconSize: [25, 41], // Ukuran ikon
                    iconAnchor: [12, 41], // Titik anchor
                    popupAnchor: [1, -34], // Titik popup
                }),
                "Banjarmasin Selatan": L.icon({
                    iconUrl: '/image/marker-icon-2x-violet.png', // Ikon untuk Kecamatan 2
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                }),
                "Banjarmasin Tengah": L.icon({
                    iconUrl: '/image/marker-icon-2x-green.png', // Ikon untuk Kecamatan 3
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                }),
                "Banjarmasin Timur": L.icon({
                    iconUrl: '/image/marker-icon-2x-brown.png', // Ikon untuk Kecamatan 3
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                }),
                "Banjarmasin Barat": L.icon({
                    iconUrl: '/image/marker-icon-2x-blue.png', // Ikon untuk Kecamatan 3
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                }),
            };

            // Menambahkan marker UMKM
            @foreach($umkms as $umkm)
                @if(!is_null($umkm->latitude) && !is_null($umkm->longitude))
                    // Pilih ikon berdasarkan kecamatan
                    var kecamatanName = "{{ $umkm->kecamatan->nama_kecamatan }}";
                    var selectedIcon = kecamatanIcons[kecamatanName] || L.Icon.Default; // Default jika tidak ada kecocokan

                    var marker = L.marker([{{ $umkm->latitude }}, {{ $umkm->longitude }}], { icon: selectedIcon })
                        .bindPopup(
                            "<b>Nama:</b> {{ $umkm->nama }}<br>" +
                            "<b>Nama Usaha:</b> {{ $umkm->nama_usaha }}<br>" +
                            "<b>Jenis Usaha:</b> {{ $umkm->jenis_usaha }}<br>" +
                            "<b>Alamat:</b> {{ $umkm->alamat }}<br>" +
                            "<b>Kecamatan:</b> {{ $umkm->kecamatan->nama_kecamatan }}<br>" +
                            "<b>Kelurahan:</b> {{ $umkm->kelurahan->nama_kelurahan }}<br>" +
                            "<button onclick='showRouteOptions({{ $umkm->latitude }}, {{ $umkm->longitude }})'>Rute ke UMKM</button>"
                        );
                    marker.options.title = "{{ $umkm->nama }}"; // Properti pencarian
                    markersLayer.addLayer(marker); // Tambahkan ke layer marker
                @endif
            @endforeach

            // Layer untuk marker kecamatan
            var markerskecLayer = new L.LayerGroup();

            // Menambahkan marker kecamatan
            @foreach($kecamatan as $kecamatan)
                @if(!is_null($kecamatan->latitude) && !is_null($kecamatan->longitude))
                    var marker = L.marker([{{ $kecamatan->latitude }}, {{ $kecamatan->longitude }}])
                        .bindPopup(
                            "<b>Kantor Kecamatan:</b> {{ $kecamatan->nama_kecamatan }}"
                        );
                    markerskecLayer.addLayer(marker); // Tambahkan ke layer marker
                @endif
            @endforeach

            // Layer untuk marker kelurahan
            var markerskelLayer = new L.LayerGroup();

            // Menambahkan marker kelurahan
            @foreach($kelurahan as $kelurahan)
                @if(!is_null($kelurahan->latitude) && !is_null($kelurahan->longitude))
                    var marker = L.marker([{{ $kelurahan->latitude }}, {{ $kelurahan->longitude }}])
                        .bindPopup(
                            "<b>Kantor Kelurahan:</b> {{ $kelurahan->nama_kelurahan }}"
                        );
                    markerskelLayer.addLayer(marker); // Tambahkan ke layer marker
                @endif
            @endforeach

            // Fungsi untuk menampilkan opsi rute
            window.showRouteOptions = function(lat, lon) {
                // Cek jika tombol rute tidak aktif
                if (!isRoutingActive) {
                    Swal.fire('Rute tidak aktif!', 'Aktifkan rute terlebih dahulu.', 'info');
                    return; // Keluar dari fungsi jika rute tidak aktif
                }

                Swal.fire({
                    title: 'Pilih Lokasi',
                    text: "Apakah Anda ingin menggunakan lokasi otomatis atau manual?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Otomatis',
                    cancelButtonText: 'Manual'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lokasi otomatis menggunakan GPS
                        map.locate({ setView: true, maxZoom: 16 });
                        map.on('locationfound', function(e) {
                            var userLat = e.latitude;
                            var userLon = e.longitude;

                            // Cek jika ada rute sebelumnya dan hapus
                            if (routingControl) {
                                map.removeControl(routingControl);
                                routingControl = null;
                            }

                            // Tambahkan rute baru
                            routingControl = L.Routing.control({
                                waypoints: [
                                    L.latLng(userLat, userLon),
                                    L.latLng(lat, lon)
                                ],
                                routeWhileDragging: true,
                                lineOptions: {
                                    styles: [
                                        { color: 'blue', opacity: 1, weight: 5 } // Menentukan warna dan ketebalan garis
                                    ]
                                }
                            }).addTo(map);
                        });
                    } else {
                        // Lokasi manual melalui klik pada peta
                        if (isRoutingActive) { // Hanya tambahkan event listener jika rute aktif
                            map.on('click', function(e) {
                                var clickedLat = e.latlng.lat;
                                var clickedLon = e.latlng.lng;

                                Swal.fire({
                                    title: 'Konfirmasi Koordinat',
                                    text: `Apakah Anda ingin menggunakan koordinat berikut?\nLatitude: ${clickedLat}\nLongitude: ${clickedLon}`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Ya',
                                    cancelButtonText: 'Tidak'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Cek jika ada rute sebelumnya dan hapus
                                        if (routingControl) {
                                            map.removeControl(routingControl);
                                            routingControl = null;
                                        }

                                        // Tambahkan rute baru
                                        routingControl = L.Routing.control({
                                            waypoints: [
                                                L.latLng(clickedLat, clickedLon),
                                                L.latLng(lat, lon)
                                            ],
                                            routeWhileDragging: true,
                                            lineOptions: {
                                                styles: [
                                                    { color: 'blue', opacity: 1, weight: 5 } // Menentukan warna dan ketebalan garis
                                                ]
                                            }
                                        }).addTo(map);
                                    }
                                });
                            });
                        }
                    }
                });
            };

            // Fungsi untuk menghapus rute
            window.removeRoute = function() {
                if (routingControl) {
                    map.removeControl(routingControl); // Hapus kontrol rute dari peta
                    routingControl = null; // Reset kontrol rute setelah dihapus
                    Swal.fire('Rute dihapus!', '', 'success');
                } else {
                    Swal.fire('Tidak ada rute untuk dihapus!', '', 'info');
                }
            };

            // Menangani kesalahan lokasi pengguna
            map.on('locationerror', function(e) {
                alert("Tidak dapat menemukan lokasi Anda. Pastikan layanan lokasi diaktifkan.");
            });

            // Menambahkan event listener pada tombol "Aktifkan Rute"
            document.getElementById('toggleRouting').addEventListener('click', function() {
                isRoutingActive = !isRoutingActive; // Toggle status rute

                if (isRoutingActive) {
                    // Ubah tampilan tombol menjadi aktif
                    this.classList.add('btn-success');
                    this.classList.remove('btn-light');
                    this.title = 'Rute Aktif';
                    Swal.fire('Rute diaktifkan!', 'Anda sekarang dapat memilih rute.', 'success');

                    // Aktifkan event listener klik pada peta
                    map.on('click', function(e) {
                        // Tidak ada implementasi di sini, hanya untuk menunjukkan bahwa peta dapat diklik.
                        // Logic untuk menunjukkan konfirmasi koordinat akan ditangani di showRouteOptions.
                    });
                } else {
                    // Ubah tampilan tombol menjadi non-aktif
                    this.classList.add('btn-light');
                    this.classList.remove('btn-success');
                    this.title = 'Aktifkan Rute';
                    removeRoute(); // Hapus rute jika dinonaktifkan
                    Swal.fire('Rute dinonaktifkan!', 'Rute sebelumnya dihapus.', 'info');

                    // Hapus event listener klik pada peta
                    map.off('click');
                }
            });

            // Event listener pada tombol "Hapus Rute"
            document.getElementById('clearMap').addEventListener('click', function() {
                removeRoute(); // Panggil fungsi removeRoute saat tombol diklik
            });
    
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

            // Mengelola checkbox untuk menampilkan/menyembunyikan marker Kecamatan
            document.getElementById('toggle-kecamatan-markers').addEventListener('change', function() {
                if (this.checked) {
                    map.addLayer(markerskecLayer); // Tampilkan marker UMKM saat checkbox diaktifkan
                } else {
                    map.removeLayer(markerskecLayer); // Sembunyikan marker UMKM saat checkbox dinonaktifkan
                }
            });

            // Mengelola checkbox untuk menampilkan/menyembunyikan marker Kelurahan
            document.getElementById('toggle-kelurahan-markers').addEventListener('change', function() {
                if (this.checked) {
                    map.addLayer(markerskelLayer); // Tampilkan marker UMKM saat checkbox diaktifkan
                } else {
                    map.removeLayer(markerskelLayer); // Sembunyikan marker UMKM saat checkbox dinonaktifkan
                }
            });

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
    {{-- Dropdown Kecamatan --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuToggle = document.getElementById('menu-toggle');
            const menu = document.getElementById('menu');
            const umkmGridContainer = document.getElementById('umkm-grid-container');
    
            // Fungsi untuk toggle visibilitas menu
            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('d-none'); // Menyembunyikan atau menampilkan menu
                // Menyembunyikan umkm-grid-container jika menu disembunyikan
                if (menu.classList.contains('d-none')) {
                    umkmGridContainer.classList.add('d-none');
                } else {
                    umkmGridContainer.classList.remove('d-none');
                }
            });
        });
    </script>
    <script>
        // Menambahkan event listener untuk menghentikan event scroll yang mempengaruhi peta
        document.getElementById('menu').addEventListener('wheel', function(e) {
            e.stopPropagation(); // Mencegah scroll dari event leaflet
        });
        // Untuk sentuhan pada perangkat mobile (touch event)
        document.getElementById('menu').addEventListener('touchmove', function(e) {
            e.stopPropagation(); // Mencegah swipe/touch dari menggerakkan peta
        });
    </script>
</body>
</html>
