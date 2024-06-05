{{-- Dashboard --}}
@extends('kerangka.master')
@section('title', 'Dashboard')
@section('content')
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-9">
            <!-- Pemetaan UMKM Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pemetaan UMKM di Banjarmasin</h4>
                        </div>
                        <div class="card-body">
                            @include('include.peta')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Jumlah UMKM Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Jumlah UMKM</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pengunjung dan Pesan Masuk Cards -->
            <div class="row">
                <!-- Pengunjung Card -->
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Jumlah Pengunjung</h4>
                        </div>
                        <div class="card-body">
                            @foreach ([
                                ['color' => 'primary', 'region' => 'Europe', 'count' => 862, 'chartId' => 'chart-europe'],
                                ['color' => 'success', 'region' => 'America', 'count' => 375, 'chartId' => 'chart-america'],
                                ['color' => 'danger', 'region' => 'Indonesia', 'count' => 1025, 'chartId' => 'chart-indonesia']
                            ] as $visitor)
                            <div class="row">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-{{ $visitor['color'] }}" width="32" height="32" fill="blue" style="width:10px">
                                            <use xlink:href="{{ asset('template/assets/static/images/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">{{ $visitor['region'] }}</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0 text-end">{{ $visitor['count'] }}</h5>
                                </div>
                                <div class="col-12">
                                    <div id="{{ $visitor['chartId'] }}"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Pesan Masuk Card -->
                <div class="col-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pesan Masuk</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Pesan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ([
                                            ['img' => '5.jpg', 'name' => 'Putri', 'message' => 'Selamat, webnya cukup baik!'],
                                            ['img' => '2.jpg', 'name' => 'Putra', 'message' => 'Saran saya ada aplikasi serupa berbasis android.']
                                        ] as $message)
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md">
                                                        <img src="{{ asset('template/assets/compiled/jpg/' . $message['img']) }}">
                                                    </div>
                                                    <p class="font-bold ms-3 mb-0">{{ $message['name'] }}</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class="mb-0">{{ $message['message'] }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-12 col-lg-3">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body py-4 px-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">Putra</h5>
                            <h6 class="text-muted mb-0">@ptrhutasoit</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Pengguna Lainnya Card -->
            <div class="card">
                <div class="card-header">
                    <h4>Pengguna Lainnya</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="{{ asset('template/assets/compiled/jpg/1.jpg') }}">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Sanjaya</h5>
                            <h6 class="text-muted mb-0">@ptrhutasoit</h6>
                        </div>
                    </div>
                    <div class="px-4">
                        <a href="{{ route('pengguna') }}" class="btn btn-block btn-xl btn-outline-primary font-bold mt-3">Lihat Selengkapnya</a>
                    </div>
                </div>
            </div> 
            <!-- Profile Pengguna Card -->
            <div class="card">
                <div class="card-header">
                    <h4>Profile Pengguna</h4>
                </div>
                <div class="card-body">
                    <div id="chart-visitors-profile"></div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
