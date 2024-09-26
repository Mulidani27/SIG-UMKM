<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta UMKM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('include.style')
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <style>
        #map-container {
            position: relative;
            width: 100%;
            height: 100%;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="map-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 10px;">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto bold-text">
                    <li class="nav-item ml-4">
                        <a class="nav-link" href="{{ route('landing') }}">Beranda</a>
                    </li>
                    <li class="nav-item ml-4 active">
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
            </div>
        </nav>
        <div class="page-content"> 
            <section class="row">
                <div class="col-12 col-lg-6 p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Detail
                                        @if(isset($umkm))
                                            {{ $umkm->nama_pemilik }}
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    @if(isset($umkm))
                                        @if(isset($umkm->foto))
                                            <img src="{{ asset('storage/umkm_photos/' . $umkm->foto) }}" alt="Foto UMKM" style="width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('image/placeholder.jpg') }}" alt="Placeholder" style="width: 245px; height: 245px; object-fit: cover; border-radius: 10px">
                                        @endif
                                        <p style="margin-top: 25px">Nama: {{ $umkm->nama}}</p>
                                        <p>Nama Usaha: {{ $umkm->nama_usaha}}</p>
                                        <p>Jenis Usaha: {{ $umkm->jenis_usaha}}</p>
                                        <p>Kategori: {{ $umkm->kategori->nama_kategori }}</p>
                                        {{-- <p>@if(isset($umkms))
                                            <p>Kategori: {{ $umkms->kategori ? $umkms->kategori->nama_kategori : 'Kategori tidak ditemukan' }}</p>
                                        @else
                                            <p>Data UMKM tidak ditemukan.</p>
                                        @endif</p> --}}
                                        <p>Alamat: {{ $umkm->alamat }}</p>
                                        <p>Latitude: {{ $umkm->latitude }}</p>
                                        <p>Longitude: {{ $umkm->longitude }}</p>
                                    @else
                                        <p>Data UMKM tidak ditemukan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Peta
                                        @if(isset($umkm))
                                            {{ $umkm->nama_pemilik}}
                                        @endif
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="width: 100%; height: 485px;"></div>

                                    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                                    <script>
                                        @if(isset($umkm))
                                            var umkm = @json($umkm);

                                            var map = L.map('map').setView([umkm.latitude, umkm.longitude], 13);

                                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                            }).addTo(map);

                                            L.marker([umkm.latitude, umkm.longitude]).addTo(map)
                                                .bindPopup(umkm.nama_usaha);

                                            var baseLayers = {
                                                "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                                }),
                                                "Google Streets": L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
                                                    maxZoom: 20,
                                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                                }),
                                                "Google Hybrid": L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', {
                                                    maxZoom: 20,
                                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                                }),
                                                "Google Satellite": L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
                                                    maxZoom: 20,
                                                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                                                })
                                            };

                                            L.control.layers(baseLayers).addTo(map);
                                        @else
                                            document.getElementById('map').innerHTML = "<p>Data UMKM tidak ditemukan, peta tidak dapat ditampilkan.</p>";
                                        @endif
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    @include('include.script')
</body>
</html>
