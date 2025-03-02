<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data UMKM Sasirangan</title>
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
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 style="text-align: center">Statistik UMKM Sasirangan di Kota Banjarmasin</h4>
                    </div>
                    <div class="card-body">
                        <section class="section">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Jumlah UMKM per Kecamatan</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="chart"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Persentase UMKM per Kecamatan</h4>
                                        </div>
                                        <div class="card-body">
                                            <div id="pieChart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 style="text-align: center">Jumlah UMKM Sasirangan per Kecamatan di Kota Banjarmasin</h4>
                        <section class="section">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Kecamatan</th>
                                                <th>Jumlah UMKM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kecamatan as $index => $wilayah)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $wilayah->nama_kecamatan }}</td>
                                                    <td class="text-end">{{ $wilayah->umkms_count ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 style="text-align: center">Daftar UMKM Sasirangan di Kota Banjarmasin</h4>
                        <section class="section">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @if($umkms->isEmpty())
                                            <p>Tidak ada data UMKM yang tersedia.</p>
                                        @else
                                            <table id="myTable" class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Nama Usaha</th>
                                                        <th>Jenis Usaha</th>
                                                        <th>Alamat</th>
                                                        <th>Kecamatan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($umkms as $um)
                                                    <tr>
                                                        <td>{{ $um->nama }}</td>
                                                        <td>{{ $um->nama_usaha }}</td>
                                                        <td>{{ $um->jenis_usaha }}</td>
                                                        <td>{{ $um->alamat }}</td>
                                                        <td>{{ $um->kecamatan ? $um->kecamatan->nama_kecamatan : 'Tidak ada kecamatan' }}</td>
                                                        <td>
                                                            <div class="d-flex flex-wrap align-items-center">
                                                                <a class="btn btn-info mx-1" href="{{ route('landing.landingdetail', $um->id) }}">Detail</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('template/assets/static/js/components/datatables.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>

<script>
    // Bar Chart Configuration 
    var barOptions = {
        chart: {
            type: 'bar',
            toolbar: {
                show: true,
                tools: {
                    download: true
                }
            },
            responsive: [{
                breakpoint: 480, // Ketika ukuran layar kurang dari 480px
                options: {
                    chart: {
                        width: '100%', // Lebar penuh pada layar kecil
                        height: 300 // Tinggi yang disesuaikan untuk layar kecil
                    },
                    xaxis: {
                        labels: {
                            rotate: -90, // Putar label sumbu X agar tidak bertumpuk
                            style: {
                                fontSize: '10px' // Perkecil ukuran font untuk label
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            height: 350 // Tinggi default
        },
        series: [{
            name: 'Jumlah UMKM',
            data: @json($dataUmkmPerKecamatan->pluck('jumlah'))
        }],
        xaxis: {
            categories: @json($dataUmkmPerKecamatan->pluck('nama_kecamatan'))
        }
    };
    var barChart = new ApexCharts(document.querySelector("#chart"), barOptions);
    barChart.render();

    // Pie Chart Configuration
    var pieSeries = @json($dataUmkmPerKecamatan->pluck('jumlah')->map(function($jumlah) use ($totalUmkm) {
        return $totalUmkm > 0 ? round(($jumlah / $totalUmkm) * 100, 2) : 0;
    }));
    var pieOptions = {
        chart: {
            type: 'pie',
            toolbar: {
                show: true,
                tools: {
                    download: true
                }
            },
            responsive: [{
                breakpoint: 480, // Ketika ukuran layar kurang dari 480px
                options: {
                    chart: {
                        width: '100%',
                        height: 300 // Tinggi chart disesuaikan untuk layar kecil
                    },
                    legend: {
                        position: 'bottom', // Pindahkan legenda ke bawah untuk layar kecil
                        fontSize: '12px'
                    }
                }
            }],
            height: 350 // Tinggi default
        },
        series: pieSeries,
        labels: @json($dataUmkmPerKecamatan->pluck('nama_kecamatan'))
    };
    var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
    pieChart.render();
</script>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</body>
</html>
