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
    <form action="/your-action-url" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-md-4">
            <label for="latitude">Latitude</label>
        </div>
        <div class="col-md-8">
            <div class="form-group has-icon-left">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="{{ old('latitude', isset($umkm) ? $umkm->latitude : '') }}">
                    <div class="form-control-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label for="longitude">Longitude</label>
        </div>
        <div class="col-md-8">
            <div class="form-group has-icon-left">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="{{ old('longitude', isset($umkm) ? $umkm->longitude : '') }}">
                    <div class="form-control-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="map"></div>
        </div>
        
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = {{ old('latitude', isset($umkm) ? $umkm->latitude : 0) }};
                var lng = {{ old('longitude', isset($umkm) ? $umkm->longitude : 0) }};
                var map = L.map('map').setView([lat, lng], 13);

                var bounds = L.latLngBounds(
                    [[-3.3821391999999260, 114.5219479000001002], // Southwest corner
                    [-3.2672272999999450, 114.6595898000000489]]  // Northeast corner
                );

                map.setMaxBounds(bounds);
                map.on('drag', function() {
                    map.panInsideBounds(bounds, { animate: false });
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    minZoom: 13,
                    maxZoom: 19
                }).addTo(map);

                var marker = L.marker([lat, lng]).addTo(map);

                // Add marker on map click
                map.on('click', function(e) {
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;
                    marker.setLatLng(e.latlng);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                });

                // Update marker position when inputs change
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

                if (!lat && !lng) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            setDefaultLocation(position.coords.latitude, position.coords.longitude);
                        }, function() {
                            setDefaultLocation(-3.3150328705636007, 114.59252371356496);
                        });
                    } else {
                        setDefaultLocation(-3.3150328705636007, 114.59252371356496);
                    }
                }

                function setDefaultLocation(lat, lng) {
                    var defaultLatLng = [lat, lng];
                    map.setView(defaultLatLng, 13);
                    marker.setLatLng(defaultLatLng);
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                }
            });
        </script>
    </form>
</body>
</html>