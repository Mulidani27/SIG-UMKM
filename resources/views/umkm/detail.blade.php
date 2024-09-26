{{-- Peta --}}
@extends('kerangka.master')
@section('title', 'Peta')
@section('content')
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-6">
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
                                    <img src="{{ asset('image/' . $umkm->foto) }}" alt="Foto UMKM" style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('image/placeholder.jpg') }}" alt="Placeholder" style="width: 245px; height: 245px; object-fit: cover; border-radius: 10px">
                                @endif
                                <p style="margin-top: 25px">Nama: {{ $umkm->nama}}</p>
                                <p>NIK: {{ $umkm->nik}}</p>
                                <p>Nama Usaha: {{ $umkm->nama_usaha}}</p>
                                <p>Jenis Usaha: {{ $umkm->jenis_usaha}}</p>
                                <p>Kecamatan: {{ $umkm->kecamatan->nama_kecamatan }}</p>
                                <p>Kelurahan: {{ $umkm->kelurahan->nama_kelurahan }}</p>
                                <p>Alamat: {{ $umkm->alamat }}</p>
                                <p>Latitude: {{ $umkm->latitude }}</p>
                                <p>Longitude: {{ $umkm->longitude }}</p>
                                <p>Kontak: {{ $umkm->phone }}</p>
                                
                            @else
                                <p>Data UMKM tidak ditemukan.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
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
                                var umkm = @json($umkm); // Mengonversi objek UMKM menjadi JSON
                            
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
                            
                                // Tambahkan layer dasar OpenStreetMap
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);
                            
                                // Tambahkan marker untuk UMKM yang sedang ditampilkan
                                L.marker([umkm.latitude, umkm.longitude]).addTo(map)
                                    .bindPopup(umkm.nama_usaha);
                            
                                // Tambahkan kontrol lapisan (layers control) dengan berbagai layer
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
                            
                                // Tambahkan kontrol lapisan ke peta
                                L.control.layers(baseLayers).addTo(map);
                            
                                // Set agar peta tidak bisa di-scroll di luar batas
                                map.on('drag', function() {
                                    map.panInsideBounds(bounds, { animate: true });
                                });
                            </script>                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
