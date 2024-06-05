<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peta UMKM</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
</head>
<body>
  <div id="menu-container">
    <h6>Pilih Wilayah: <i id="menu-toggle" class="bi bi-chevron-down"></i></h6>
    <div id="menu">
      <!-- Wilayah Checkbox Menu -->
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/kota_banjarmasin.geojson"> Kota Banjarmasin</label><br>
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_utara.geojson"> Banjarmasin Utara</label><br>
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_timur.geojson"> Banjarmasin Timur</label><br>
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_tengah.geojson"> Banjarmasin Tengah</label><br>
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_selatan.geojson"> Banjarmasin Selatan</label><br>
      <label><input type="checkbox" class="geojsonCheckbox" value="geospasial/banjarmasin_barat.geojson"> Banjarmasin Barat</label><br>
    </div>
  </div>  
  <div id="mapid"></div>
  
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script>
    // Inisialisasi Peta
    var map = L.map("mapid").setView([-3.314955, 114.592537], 13);

    // Layer Dasar
    var initialTileLayer = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // Google Maps Layers
    var googleStreets = L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    var googleHybrid = L.tileLayer('http://{s}.google.com/vt?lyrs=s,h&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    var googleSatellite = L.tileLayer('http://{s}.google.com/vt?lyrs=s&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    // Menambahkan Layer Control
    var baseLayers = {
      "OpenStreetMap": initialTileLayer,
      "Google Streets": googleStreets,
      "Google Hybrid": googleHybrid,
      "Google Satellite": googleSatellite
    };
    L.control.layers(baseLayers).addTo(map);

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
            style: function (feature) {
              return { color: color };
            },
            onEachFeature: function (feature, layer) {
              layer.bindPopup(name);
              layer.on('click', function (e) {
                alert('Nama Wilayah: ' + name);
              });
            }
          }).addTo(map);

          activeGeoJSONLayers[url] = geojsonLayer;
        })
        .catch(error => console.error('Error loading the GeoJSON file:', error));
    }

    // Event Listener untuk Checkbox
    var checkboxes = document.querySelectorAll('.geojsonCheckbox');
    checkboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', function() {
        var selectedUrl = this.value;
        var isChecked = this.checked;
        if (isChecked) {
          var color, name;
          switch(selectedUrl) {
            case 'geospasial/kota_banjarmasin.geojson':
              color = 'blue';
              name = 'Kota Banjarmasin';
              break;
            case 'geospasial/banjarmasin_utara.geojson':
              color = 'red';
              name = 'Banjarmasin Utara';
              break;
            case 'geospasial/banjarmasin_timur.geojson':
              color = 'green';
              name = 'Banjarmasin Timur';
              break;
            case 'geospasial/banjarmasin_tengah.geojson':
              color = 'purple';
              name = 'Banjarmasin Tengah';
              break;
            case 'geospasial/banjarmasin_selatan.geojson':
              color = 'orange';
              name = 'Banjarmasin Selatan';
              break;
            case 'geospasial/banjarmasin_barat.geojson':
              color = 'yellow';
              name = 'Banjarmasin Barat';
              break;
            default:
              color = 'black';
              name = 'Wilayah Tidak Diketahui';
          }
          addGeoJSONLayer(selectedUrl, color, name);
        } else {
          var geojsonLayer = activeGeoJSONLayers[selectedUrl];
          if (geojsonLayer) {
            map.removeLayer(geojsonLayer);
            delete activeGeoJSONLayers[selectedUrl];
          }
        }
      });
    });

    // Menambahkan Marker UMKM
    @foreach($umkms as $umkm)
      L.marker([{{ $umkm->latitude }}, {{ $umkm->longitude }}]).addTo(map)
        .bindPopup("<b>Nama UMKM:</b> {{ $umkm->nama }}<br><b>Alamat:</b> {{ $umkm->alamat }}");
    @endforeach
  </script>
</body>
</html>
