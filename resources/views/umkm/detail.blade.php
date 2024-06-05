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
                            <h4>Detail UMKM
                                @if(isset($umkm))
                                    {{ $umkm->nama_usaha }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @if(isset($umkm))
                                @if(isset($umkm->foto))
                                    <img src="{{ asset('storage/' . $umkm->foto) }}" alt="Foto UMKM" style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/placeholder.jpg') }}" alt="Placeholder" style="width: 245px; height: 245px; object-fit: cover; border-radius: 10px">
                                @endif
                                <p>Nama Pemilik: {{ $umkm->nama_pemilik }}</p>
                                <p>Kategori: {{ $umkm->kategori->nama_kategori }}</p>
                                <p>Latitude: {{ $umkm->latitude }}</p>
                                <p>Longitude: {{ $umkm->longitude }}</p>
                                <p>Phone: {{ $umkm->phone }}</p>
                                <p>Alamat: {{ $umkm->alamat }}</p>
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
                            <h4>Peta UMKM
                                @if(isset($umkm))
                                    {{ $umkm->nama_usaha }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 485px;"></div>
    
                            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                            <script>
                                var umkm = @json($umkm); // Mengonversi objek UMKM menjadi JSON
                                
                                var map = L.map('map').setView([umkm.latitude, umkm.longitude], 13);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);
                                
                                // Tambahkan marker untuk UMKM yang sedang ditampilkan
                                L.marker([umkm.latitude, umkm.longitude]).addTo(map)
                                    .bindPopup(umkm.nama_usaha);
                                
                                // Tambahkan kontrol lapisan
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
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
