@extends('kerangka.master')
@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Statistik UMKM Perkecamatan</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Statistik</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
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
    </section>
</div>

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
@endsection
