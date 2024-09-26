<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data UMKM Sasirangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/iconly.css') }}">
    @include('include.style')
    
    <style>
        .btn{
        font-size: 20px;
        margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
          <nav class="navbar navbar-expand-lg navbar-dark bg-primary navbar-sm" style="padding: 15px">
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
                            <a class="nav-link me-3" aria-current="page" href="{{ route('landing') }}"><i class="bi bi-house-fill"></i> Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active me-3" href="{{ route('landing.landingumkm') }}"><i class="bi bi-table"></i> Data UMKM</a>
                        </li>
                        <li class="nav-item dropdown me-3">
                          <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="bi bi-stack"></i> Wilayah
                          </a>
                          <div class="dropdown-menu" aria-labelledby="kecamatanDropdown">
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
                    </ul>
                </div>
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
                                        <p>kecamatan: {{ $umkm->kecamatan->nama_kecamatan }}</p>
                                        {{-- <p>@if(isset($umkms))
                                            <p>kecamatan: {{ $umkms->kecamatan ? $umkms->kecamatan->nama_kecamatan : 'kecamatan tidak ditemukan' }}</p>
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

                                            // Inisialisasi peta dengan posisi awal di koordinat UMKM
                                            var map = L.map('map').setView([umkm.latitude, umkm.longitude], 13);
                                            
                                            // Tetapkan batas koordinat untuk Banjarmasin
                                            var bounds = [
                                                [-3.3821391999999260, 114.5219479000001002], // Sudut barat daya
                                                [-3.2672272999999450, 114.6595898000000489]  // Sudut timur laut
                                            ];
                                        
                                            // Batasi peta agar tidak bisa di-scroll keluar dari area ini
                                            map.setMaxBounds(bounds);
                                        
                                            // Opsi untuk mencegah pengguna menggulir di luar batas
                                            map.options.minZoom = 13; // Menetapkan zoom minimum yang cocok
                                            map.options.maxZoom = 19; // Zoom maksimal

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
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    @include('include.script')
</body>
</html>
