<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda SIG</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        #mapid {
          width: 100%;
          height: 670px;
        }
        #menu {
          max-height: 200px;
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
        #map-container {
          position: relative;
          width: 100%;
          height: 100%;
          font-size: 18px;
          font-weight: bold;
        }
        #menu-container {
          position: absolute;
          top: 70px;
          left: 25px;
          background-color: white;
          padding: 10px;
          border: 1px solid #ccc;
          border-radius: 5px;
          z-index: 1000;
        }
        .hidden {
          display: none;
        }
        #menu h6 {
          display: inline-block;
          margin: 0;
        }
    
        .hidden {
          display: none;
        }
        .btn{
          font-size: 20px;
          margin-right: 5px;
        }
        #zoom-slider {
            position: absolute;
            top: 10px;
            left: 20px;
            z-index: 1000;
            padding: 5px;
            border-radius: 5px;
        }
        @media only screen and (max-width: 768px) {
            div[style*="padding: 40px"] {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary navbar-sm" style="padding: 15px;">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('landing') }}">
                        <img src="{{ asset('template/assets/compiled/png/logo2.png') }}" alt="Logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link active me-3" aria-current="page" href="{{ route('landing') }}"><i class="bi bi-house-fill"></i> Beranda</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-3" href="{{ route('landing.landingumkm') }}"><i class="bi bi-table"></i> UMKM</a>
                            </li>
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-stack"></i> Wilayah
                                </a>
                                <div class="dropdown-menu" aria-labelledby="kategoriDropdown">
                                    @foreach ($kecamatan as $kec)
                                        <a href="{{ route('landing', ['kecamatan' => $kec->id]) }}" class="dropdown-item submenu-link" data-kecamatan="{{ $kec->id }}">
                                            {{ $kec->nama_kecamatan }}
                                        </a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-3" href="https://www.instagram.com/diskopumkerbanjarmasin?igsh=MWh6cjBnOTd2OXRkbQ=="><i class="bi bi-chat-left-text-fill"></i> Kontak</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-3" href="{{ route('dashboard') }}"><i class="bi bi-box-arrow-in-right"></i> Masuk</a>
                            </li>
                            <div style="display: flex; justify-content: flex-end;">
                                <button id="toggleRouting" class="btn btn-light" style="font-size: 16px" title="Aktifkan Rute">
                                    <i class="bi bi-signpost-2-fill"></i>
                                </button>
                                <button id="clearMap" class="btn btn-light" style="font-size: 16px" title="Hapus Rute">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div> 
                        </ul>
                        <form class="form-inline my-2 my-lg-0">
                            <input id="search-box" class="form-control mr-sm-2" type="text" placeholder="Ketik di sini" aria-label="Search" aria-describedby="button-addon2">
                            <button class="btn btn-light my-2 my-sm-0" type="button" id="button-addon2">Cari</button>
                        </form>
                    </div>
                </div>
            </nav>
            <div style="padding: 40px">
                <div class="card" style="margin-top: -35px">
                    <div class="card-header">
                        <h2 style="text-align: center">SIG Pemetaan UMKM Sasirangan Kota Banjarmasin
                            @if(isset($selectedKecamatan))
                                Wilayah {{ $selectedKecamatan->nama_kecamatan }}
                            @endif
                        </h2>
                    </div>
                    <div class="card-body">                                         
                        <div id="mapid">
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
                                        <input type="checkbox" class="umkmCheckbox" id="toggle-grid"> Tampilkan Grid
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
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
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
            var southWest = L.latLng(-3.3821391999999260, 114.5219479000001002);//barat daya
            var northEast = L.latLng(-3.2672272999999450, 114.6595898000000489);//timur laut
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
    
            // Variabel untuk menyimpan garis grid
            window.gridLines = [];

            // Fungsi untuk menggambar grid
            function drawInteractiveGrid() {
                // Hapus grid yang ada
                if (window.gridLines.length) {
                    window.gridLines.forEach(function(line) {
                        map.removeLayer(line);
                    });
                }

                window.gridLines = []; // Inisialisasi array untuk menyimpan garis grid

                var gridSize = 0.05 / Math.pow(2, map.getZoom() - 12); // Ukuran grid

                var bounds = map.getBounds(); // Mendapatkan batas peta saat ini

                // Menggambar garis horizontal
                for (var lat = Math.floor(bounds.getSouth() / gridSize) * gridSize; lat < bounds.getNorth(); lat += gridSize) {
                    var line = L.polyline([[lat, bounds.getWest()], [lat, bounds.getEast()]], { color: 'red', weight: 1 }).addTo(map);
                    line.bindTooltip("Latitude: " + lat.toFixed(5), { permanent: true, direction: 'top' });
                    
                    line.on('mouseover', function(e) {
                        this.openTooltip();
                    });
                    
                    line.on('mouseout', function(e) {
                        this.closeTooltip();
                    });

                    window.gridLines.push(line); // Simpan garis ke array
                }
                
                // Menggambar garis vertikal
                for (var lng = Math.floor(bounds.getWest() / gridSize) * gridSize; lng < bounds.getEast(); lng += gridSize) {
                    var line = L.polyline([[bounds.getSouth(), lng], [bounds.getNorth(), lng]], { color: 'red', weight: 1 }).addTo(map);
                    line.bindTooltip("Longitude: " + lng.toFixed(5), { permanent: true, direction: 'right' });
                    
                    line.on('mouseover', function(e) {
                        this.openTooltip();
                    });
                    
                    line.on('mouseout', function(e) {
                        this.closeTooltip();
                    });

                    window.gridLines.push(line); // Simpan garis ke array
                }
            }

            // Event listener untuk toggle grid
            document.getElementById('toggle-grid').addEventListener('change', function() {
                if (this.checked) {
                    drawInteractiveGrid(); // Gambar grid jika checkbox dicentang
                } else {
                    // Hapus grid jika checkbox tidak dicentang
                    if (window.gridLines.length) {
                        window.gridLines.forEach(function(line) {
                            map.removeLayer(line);
                        });
                        window.gridLines = []; // Kosongkan array garis grid
                    }
                }
            });

            // Event listener untuk memperbarui grid saat peta dipindahkan atau di-zoom
            map.on('moveend', function() {
                if (document.getElementById('toggle-grid').checked) {
                    drawInteractiveGrid(); // Gambar ulang grid jika checkbox dicentang
                }
            });

            map.on('zoomend', function() {
                if (document.getElementById('toggle-grid').checked) {
                    drawInteractiveGrid(); // Gambar ulang grid jika checkbox dicentang
                }
            });

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
            
            // Variabel global untuk kontrol routing
            var routingControl = null;
            var isRoutingActive = false; // Status untuk melacak apakah rute aktif

            // Layer untuk marker UMKM
            var markersLayer = new L.LayerGroup();

            // Menambahkan marker UMKM
            @foreach($umkms as $umkm)
                @if(!is_null($umkm->latitude) && !is_null($umkm->longitude))
                    var marker = L.marker([{{ $umkm->latitude }}, {{ $umkm->longitude }}])
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
                                        { color: 'red', opacity: 1, weight: 5 } // Menentukan warna dan ketebalan garis
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
                                                    { color: 'red', opacity: 1, weight: 5 } // Menentukan warna dan ketebalan garis
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
                                return { color: color, weight: 3 }; // Sesuaikan styling jika perlu
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
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
        document.getElementById('menu-toggle').addEventListener('click', function() {
            var menu = document.getElementById('menu');
            var toggleIcon = document.getElementById('menu-toggle');
      
            // Toggle visibility of the menu
            menu.classList.toggle('hidden');
      
            // Toggle the icon based on the menu visibility
            if (menu.classList.contains('hidden')) {
                toggleIcon.classList.remove('bi-caret-up-fill');
                toggleIcon.classList.add('bi-caret-down-fill');
            } else {
                toggleIcon.classList.remove('bi-caret-down-fill');
                toggleIcon.classList.add('bi-caret-up-fill');
            }
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

    <!-- Scripts -->
    <script src="{{ asset('template/assets/static/js/pages/horizontal-layout.js') }}"></script>
    <script src="{{ asset('template/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('template/assets/static/js/pages/dashboard.js') }}"></script>
</body>
</html>