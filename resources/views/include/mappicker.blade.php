<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>
<body>
    <div class="col-md-12">
        <div id="map"></div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Latitude dan Longitude awal
            var lat = {{ $latitude ?? 0 }};
            var lng = {{ $longitude ?? 0 }};
            var map = L.map('map').setView([lat, lng], 13);
    
            // Batas wilayah (contoh: Banjarmasin)
            var bounds = L.latLngBounds(
                [[-3.3821391999999260, 114.5219479000001002], // Southwest corner
                [-3.2672272999999450, 114.6595898000000489]]  // Northeast corner
            );
    
            map.setMaxBounds(bounds);
            map.on('drag', function () {
                map.panInsideBounds(bounds, { animate: false });
            });
    
            // Tambahkan peta tile
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                minZoom: 13,
                maxZoom: 19
            }).addTo(map);
    
            // Tambahkan marker
            var marker = L.marker([lat, lng]).addTo(map);
    
            // Update marker saat peta diklik
            map.on('click', function (e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
    
            // Update marker saat input berubah
            document.getElementById('latitude').addEventListener('input', updateMarker);
            document.getElementById('longitude').addEventListener('input', updateMarker);
    
            function updateMarker() {
                var lat = parseFloat(document.getElementById('latitude').value);
                var lng = parseFloat(document.getElementById('longitude').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    var newLatLng = [lat, lng];
                    marker.setLatLng(newLatLng);
                    map.setView(newLatLng, map.getZoom());
                }
            }
        });
    </script>
</body>
</html>