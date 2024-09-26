<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peta UMKM</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
  <style>
    #mapid {
      width: 100%;
      height: 100vh;
      position: fixed;
    }
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
    #map-container {
      position: relative;
      width: 100%;
      height: 100%;
      font-size: 18px;
      font-weight: bold;
    }
    #menu-container {
      position: absolute;
      top: 10px;
      left: 55px;
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
  </style>
</head>
<body>
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Welcome!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif
  <div id="map-container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 10px;">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto bold-text">
                <li class="nav-item ml-4 active">
                    <a class="nav-link" href="{{ route('landing') }}">Beranda</a>
                </li>
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('landing.landingumkm') }}">Data UMKM</a>
                </li>
                <li class="nav-item dropdown ml-4 has-sub">
                    <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Kategori
                    </a>
                    <div class="dropdown-menu" aria-labelledby="kategoriDropdown">
                        @foreach ($kategori as $kat)
                            <a href="{{ route('landing', ['kategori' => $kat->id]) }}" class="dropdown-item submenu-link" data-kategori="{{ $kat->id }}">
                                {{ $kat->nama_kategori }}
                            </a>
                        @endforeach
                    </div>
                </li>
                <li class="nav-item ml-4">
                    <a class="nav-link" href="https://www.instagram.com/ptrhutasoit?igsh=dGFxb2Zub3VkbWJ0">Kontak</a>
                </li>
                <li class="nav-item ml-4">
                    <a class="nav-link" href="{{ route('dashboard') }}">Masuk</a>
                </li>
            </ul>
            <div style="display: flex; justify-content: flex-end;">
                <button id="toggleRouting" class="btn" title="Aktifkan Rute">
                    <i class="bi bi-signpost-2-fill"></i>
                </button>
                <button id="clearMap" class="btn" title="Hapus Rute">
                    <i class="bi bi-trash3-fill"></i>
                </button>
            </div>
            <!-- Formulir Pencarian -->
            <form class="form-inline my-2 my-lg-0">
                <input id="search-box" class="form-control mr-sm-2" type="text" placeholder="Ketik di sini" aria-label="Search" aria-describedby="button-addon2">
                <button class="btn btn-primary my-2 my-sm-0" type="button" id="button-addon2">Cari</button>
              </form>
        </div>
    </nav>
    <div id="mapid">
        <div id="menu-container">
            <h6>Pilih Wilayah: <i id="menu-toggle" class="bi bi-chevron-down"></i></h6>
            <div id="menu" class="border rounded p-3">
                <label><b>Kecamatan</b></label><br>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" value="geospasial/kota_banjarmasin.geojson"> Kota Banjarmasin
                </label><br>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_utara.geojson" data-submenu="submenu-banjarmasin-utara"> Banjarmasin Utara
                </label><br>
                <div id="submenu-banjarmasin-utara" class="submenupeta">
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/sungai_jingah.geojson"> Sungai Jingah</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/sungai_andai.geojson"> Sungai Andai</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/surgi_mufti.geojson"> Surgi Mufti</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/antasan_kecil_timur.geojson"> Antasan Kecil Timur</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/sungai_miai.geojson"> Sungai Miai</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/pangeran.geojson"> Pangeran</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/kuin_utara.geojson"> Kuin Utara</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/alalak_utara.geojson"> Alalak Utara</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/alalak_selatan.geojson"> Alalak Selatan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/utara/alalak_tengah.geojson"> Alalak Tengah</label><br>
                </div>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" data-submenu="submenu-banjarmasin-timur" value="geospasial/banjarmasin_timur.geojson"> Banjarmasin Timur
                </label><br>
                <div id="submenu-banjarmasin-timur" class="submenupeta">
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/benua_anyar.geojson"> Benua Anyar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/karang_mekar.geojson"> Karang Mekar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/kebun_bunga.geojson"> Kebun Bunga</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/kuripan.geojson"> Kuripan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/pekapuran_raya.geojson"> Pekapuran Raya</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/pemurus_luar.geojson"> Pemurus Luar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/pengambangan.geojson"> Pengambangan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/sungai_bilu.geojson"> Sungai Bilu</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/timur/sungai_lulut.geojson"> Sungai Lulut</label><br>
                </div>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" data-submenu="submenu-banjarmasin-tengah" value="geospasial/banjarmasin_tengah.geojson"> Banjarmasin Tengah
                </label><br>
                <div id="submenu-banjarmasin-tengah" class="submenupeta">
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/antasan_besar.geojson"> Antasan Besar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/gadang.geojson"> Gadang</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/kelayan_luar.geojson"> Kelayan Luar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/kertak_baru_ilir.geojson"> Kertak Baru Ilir</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/kertak_baru_ulu.geojson"> Kertak Baru Ulu</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/mawar.geojson"> Mawar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/melayu.geojson"> Melayu</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/pasar_lama.geojson"> Pasar Lama</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/pekapuran_laut.geojson"> Pekapuran Laut</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/seberang_mesjid.geojson"> Seberang Mesjid</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/sungai_baru.geojson"> Sungai Baru</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/tengah/teluk_dalam.geojson"> Teluk Dalam</label><br>
                </div>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" data-submenu="submenu-banjarmasin-selatan" value="geospasial/banjarmasin_selatan.geojson"> Banjarmasin Selatan
                </label><br>
                <div id="submenu-banjarmasin-selatan" class="submenupeta">
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/bukit_besar.geojson"> Bukit Besar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/kelayan_ajar.geojson"> Kelayan Ajar</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/kelayan_dalam.geojson"> Kelayan Dalam</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/pelambuan.geojson"> Pelambuan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/pekojan.geojson"> Pekojan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/pulau_kambang.geojson"> Pulau Kambang</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/telawang.geojson"> Telawang</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/kuin_selatan.geojson"> Kuin Selatan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/selatan/kuin_timur.geojson"> Kuin Timur</label><br>
                </div>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" data-submenu="submenu-banjarmasin-barat" value="geospasial/banjarmasin_barat.geojson"> Banjarmasin Barat
                </label><br>
                <div id="submenu-banjarmasin-barat" class="submenupeta">
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/basirih.geojson"> Basirih</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/belitung_selatan.geojson"> Belitung Selatan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/belitung_utara.geojson"> Belitung Utara</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/kuin_cerucuk.geojson"> Kuin Cerucuk</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/kuin_selatan.geojson"> Kuin Selatan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/pelambuan.geojson"> Pelambuan</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/telaga_biru.geojson"> Telaga Biru</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/telawang.geojson"> Telawang</label><br>
                    <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/barat/teluk_tiram.geojson"> Teluk Tiram</label><br>
                </div>
                <label><b>Tampilkan</b></label><br>
                <label>
                    <input type="checkbox" class="geojsonCheckbox" id="toggle-umkm-markers"> Penanda UMKM
                </label>
            </div> 
        </div>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Peta
        var map = L.map("mapid").setView([{{ $centerCoordinates[0] }}, {{ $centerCoordinates[1] }}], {{ $zoomLevel }});

        // Layer Dasar
        var initialTileLayer = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        // Google Maps Layers
        var googleLayers = {
            "Streets": L.tileLayer('https://{s}.googleapis.com/vt?lyrs=m&x={x}&y={y}&z={z}', { maxZoom: 20 }),
            "Hybrid": L.tileLayer('https://{s}.googleapis.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', { maxZoom: 20 }),
            "Satellite": L.tileLayer('https://{s}.googleapis.com/vt?lyrs=s&x={x}&y={z}', { maxZoom: 20 })
        };

        // Menambahkan Layer Control
        L.control.layers({
            "OpenStreetMap": initialTileLayer,
            "Google Streets": googleLayers.Streets,
            "Google Hybrid": googleLayers.Hybrid,
            "Google Satellite": googleLayers.Satellite
        }).addTo(map);

        // URL ke file GeoJSON
        var geojsonUrl = "{{ asset($geojsonFile) }}";
        if (geojsonUrl) {
            fetch(geojsonUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    // Menambahkan GeoJSON dengan warna hijau
                    L.geoJSON(data, {
                        style: function (feature) {
                            return { color: 'green' }; // Menetapkan warna hijau untuk GeoJSON
                        }
                    }).addTo(map);
                })
                .catch(error => console.log('Error loading GeoJSON:', error));
        } else {
            console.error('URL GeoJSON tidak ditemukan.');
        }

        // Menyimpan Layer GeoJSON yang Aktif
        var activeGeoJSONLayers = {};

        // Fungsi untuk Menambahkan Layer GeoJSON
        function addGeoJSONLayer(url, color, name) {
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    var geojsonLayer = L.geoJSON(data, {
                        style: () => ({ color: color }),
                        onEachFeature: (feature, layer) => {
                            layer.bindPopup(name);
                            layer.on('click', () => alert('Nama Wilayah: ' + name));
                        }
                    }).addTo(map);

                    activeGeoJSONLayers[url] = geojsonLayer;
                })
                .catch(error => console.error('Error loading the GeoJSON file:', error));
        }

        // Event Listener untuk Checkbox
        var geojsonData = {
            'geospasial/kota_banjarmasin.geojson': { color: 'blue', name: 'Kota Banjarmasin' },
            'geospasial/banjarmasin_utara.geojson': { color: 'red', name: 'Banjarmasin Utara' },
            'geospasial/banjarmasin_timur.geojson': { color: 'green', name: 'Banjarmasin Timur' },
            'geospasial/banjarmasin_tengah.geojson': { color: 'purple', name: 'Banjarmasin Tengah' },
            'geospasial/banjarmasin_selatan.geojson': { color: 'orange', name: 'Banjarmasin Selatan' },
            'geospasial/banjarmasin_barat.geojson': { color: 'yellow', name: 'Banjarmasin Barat' },
            // Banjarmasin Utara
            'geospasial/utara/sungai_jingah.geojson': { color: 'red', name: 'Sungai Jingah' },
            'geospasial/utara/sungai_andai.geojson': { color: 'red', name: 'Sungai Andai' },
            'geospasial/utara/surgi_mufti.geojson': { color: 'red', name: 'Surgi Mufti' },
            'geospasial/utara/antasan_kecil_timur.geojson': { color: 'red', name: 'Antasan Kecil Timur' },
            'geospasial/utara/sungai_miai.geojson': { color: 'red', name: 'Sungai Miai' },
            'geospasial/utara/pangeran.geojson': { color: 'red', name: 'Pangeran' },
            'geospasial/utara/kuin_utara.geojson': { color: 'red', name: 'Kuin Utara' },
            'geospasial/utara/alalak_utara.geojson': { color: 'red', name: 'Alalak Utara' },
            'geospasial/utara/alalak_selatan.geojson': { color: 'red', name: 'Alalak Selatan' },
            'geospasial/utara/alalak_tengah.geojson': { color: 'red', name: 'Alalak Tengah' },
            // Banjarmasin Timur
            'geospasial/timur/benua_anyar.geojson': { color: 'green', name: 'Benua Anyar' },
            'geospasial/timur/karang_mekar.geojson': { color: 'green', name: 'Karang Mekar' },
            'geospasial/timur/kebun_bunga.geojson': { color: 'green', name: 'Kebun Bunga' },
            'geospasial/timur/kuripan.geojson': { color: 'green', name: 'Kuripan' },
            'geospasial/timur/pekapuran_raya.geojson': { color: 'green', name: 'Pekapuran Raya' },
            'geospasial/timur/pemurus_luar.geojson': { color: 'green', name: 'Pemurus Luar' },
            'geospasial/timur/pengambangan.geojson': { color: 'green', name: 'Pengambangan' },
            'geospasial/timur/sungai_bilu.geojson': { color: 'green', name: 'Sungai Bilu' },
            'geospasial/timur/sungai_lulut.geojson': { color: 'green', name: 'Sungai Lulut' },
            // Banjarmasin Tengah
            'geospasial/tengah/antasan_besar.geojson': { color: 'purple', name: 'Antasan Besar' },
            'geospasial/tengah/gadang.geojson': { color: 'purple', name: 'Gadang' },
            'geospasial/tengah/kelayan_luar.geojson': { color: 'purple', name: 'Kelayan Luar' },
            'geospasial/tengah/kertak_baru_ilir.geojson': { color: 'purple', name: 'Kertak Baru Ilir' },
            'geospasial/tengah/kertak_baru_ulu.geojson': { color: 'purple', name: 'Kertak Baru Ulu' },
            'geospasial/tengah/mawar.geojson': { color: 'purple', name: 'Mawar' },
            'geospasial/tengah/melayu.geojson': { color: 'purple', name: 'Melayu' },
            'geospasial/tengah/pasar_lama.geojson': { color: 'purple', name: 'Pasar Lama' },
            'geospasial/tengah/pekapuran_laut.geojson': { color: 'purple', name: 'Pekapuran Laut' },
            'geospasial/tengah/seberang_mesjid.geojson': { color: 'purple', name: 'Seberang Mesjid' },
            'geospasial/tengah/sungai_baru.geojson': { color: 'purple', name: 'Sungai Baru' },
            'geospasial/tengah/teluk_dalam.geojson': { color: 'purple', name: 'Teluk Dalam' },
            // Banjarmasin Selatan
            'geospasial/selatan/basirih_selatan.geojson': { color: 'orange', name: 'Basirih Selatan' },
            'geospasial/selatan/kelayan_barat.geojson': { color: 'orange', name: 'Kelayan Barat' },
            'geospasial/selatan/kelayan_dalam.geojson': { color: 'orange', name: 'Kelayan Dalam' },
            'geospasial/selatan/kelayan_selatan.geojson': { color: 'orange', name: 'Kelayan Selatan' },
            'geospasial/selatan/kelayan_tengah.geojson': { color: 'orange', name: 'Kelayan Tengah' },
            'geospasial/selatan/kelayan_timur.geojson': { color: 'orange', name: 'Kelayan Timur' },
            'geospasial/selatan/mantuil.geojson': { color: 'orange', name: 'Mantuil' },
            'geospasial/selatan/murung_raya.geojson': { color: 'orange', name: 'Murung Raya' },
            'geospasial/selatan/pekauman.geojson': { color: 'orange', name: 'Pekauman' },
            'geospasial/selatan/pemurus_baru.geojson': { color: 'orange', name: 'Pemurus Baru' },
            'geospasial/selatan/pemurus_dalam.geojson': { color: 'orange', name: 'Pemurus Dalam' },
            'geospasial/selatan/tanjung_pagar.geojson': { color: 'orange', name: 'Tanjung Pagar' },
            // Banjarmasin Barat
            'geospasial/barat/basirih.geojson': { color: 'yellow', name: 'Basirih' },
            'geospasial/barat/belitung_selatan.geojson': { color: 'yellow', name: 'Belitung Selatan' },
            'geospasial/barat/belitung_utara.geojson': { color: 'yellow', name: 'Belitung Utara' },
            'geospasial/barat/kuin_cerucuk.geojson': { color: 'yellow', name: 'Kuin Cerucuk' },
            'geospasial/barat/kuin_selatan.geojson': { color: 'yellow', name: 'Kuin Selatan' },
            'geospasial/barat/pelambuan.geojson': { color: 'yellow', name: 'Pelambuan' },
            'geospasial/barat/telaga_biru.geojson': { color: 'yellow', name: 'Telaga Biru' },
            'geospasial/barat/telawang.geojson': { color: 'yellow', name: 'Telawang' },
            'geospasial/barat/teluk_tiram.geojson': { color: 'yellow', name: 'Teluk Tiram' }
        };

        document.querySelectorAll('.geojsonCheckbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var url = this.value;
                var isChecked = this.checked;
                var info = geojsonData[url];

                if (isChecked && info) {
                    addGeoJSONLayer(url, info.color, info.name);
                } else if (!isChecked && activeGeoJSONLayers[url]) {
                    map.removeLayer(activeGeoJSONLayers[url]);
                    delete activeGeoJSONLayers[url];
                }
            });
        });

        // Layer untuk marker UMKM
        var markersLayer = new L.LayerGroup();

        // Menambahkan marker UMKM
        @foreach($umkms as $umkm)
            @if(!is_null($umkm->latitude) && !is_null($umkm->longitude))
                var marker = L.marker([{{ $umkm->latitude }}, {{ $umkm->longitude }}])
                    .bindPopup("<b>Nama:</b> {{ $umkm->nama }}<br><b>Jenis Usaha:</b> {{ $umkm->jenis_usaha }}<br><b>Alamat:</b> {{ $umkm->alamat }}<br><b>Kategori:</b> {{ $umkm->kategori->nama_kategori }}");
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
    });
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuToggle = document.getElementById('menu-toggle');
        const navigation = document.getElementById('navigation');
    
        // Tambahkan event listener untuk menu toggle
        menuToggle.addEventListener('click', function() {
            navigation.classList.toggle('show');
        });
    }); 
  </script>

  {{-- Dropdown Kecamatan --}}
  <script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
      var menu = document.getElementById('menu');
      var toggleIcon = document.getElementById('menu-toggle');
      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        toggleIcon.classList.remove('bi-chevron-down');
        toggleIcon.classList.add('bi-chevron-up');
      } else {
        menu.classList.add('hidden');
        toggleIcon.classList.remove('bi-chevron-up');
        toggleIcon.classList.add('bi-chevron-down');
      }
    });
  </script>
</body>
</html>
