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
                    download: true  // Enable export/download feature
                }
            }
        },
        series: [{
            name: 'Jumlah UMKM',
            data: @json($dataUmkmPerKecamatan->pluck('jumlah'))
        }],
        xaxis: {
            categories: @json($dataUmkmPerKecamatan->pluck('nama_kecamatan'))
        }
    }
    var barChart = new ApexCharts(document.querySelector("#chart"), barOptions);
    barChart.render();
    // Pie Chart Configuration
    @if ($totalUmkm > 0)
    var pieOptions = {
        chart: {
            type: 'pie',
            toolbar: {
                show: true,
                tools: {
                    download: true  // Enable export/download feature
                }
            }
        },
        series: @json($dataUmkmPerKecamatan->pluck('jumlah')->map(function($jumlah) use ($totalUmkm) {
            return round(($jumlah / $totalUmkm) * 100, 2);
        })),
        labels: @json($dataUmkmPerKecamatan->pluck('nama_kecamatan'))
    }
    @else
    var pieOptions = {
        chart: {
            type: 'pie',
            toolbar: {
                show: true,
                tools: {
                    download: true  // Enable export/download feature
                }
            }
        },
        series: [100],  // Default to 100% for a single "No Data" slice
        labels: ['No Data Available']
    }
    @endif
    var pieChart = new ApexCharts(document.querySelector("#pieChart"), pieOptions);
    pieChart.render();
</script>
@endsection
