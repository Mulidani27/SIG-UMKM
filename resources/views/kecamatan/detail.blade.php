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
                                @if(isset($kecamatan))
                                    {{ $kecamatan->nama_kecamatan }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if(isset($kecamatan))
                                <p style="margin-top: 25px">Wilayah: {{ $kecamatan->nama_kecamatan}}</p>
                                <p>Latitude: {{ $kecamatan->latitude}}</p>
                                <p>longitude: {{ $kecamatan->longitude}}</p>
                                <p>Batas Wilayah: {{ $kecamatan->batas_wilayah}}</p>
                            @else
                                <p>Data kecamatan tidak ditemukan.</p>
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
                                @if(isset($kecamatan))
                                    {{ $kecamatan->batas_wilayah}}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 485px;"></div>
    
                            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                            <script>
                                var kecamatan = @json($kecamatan); // Mengonversi objek kecamatan menjadi JSON
                            
                                // Inisialisasi peta dengan posisi awal di koordinat kecamatan
                                var map = L.map('map').setView([kecamatan.latitude, kecamatan.longitude], 13);
                            
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
                            
                                // Tambahkan marker untuk kecamatan yang sedang ditampilkan
                                L.marker([kecamatan.latitude, kecamatan.longitude]).addTo(map)
                                    .bindPopup(kecamatan.nama_kecamatan);
                            
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
