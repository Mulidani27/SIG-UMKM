{{-- Dashboard --}}
@php
    use Illuminate\Support\Str;
@endphp
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
                            <h4 style="margin-bottom: -30px">Pemetaan UMKM di Banjarmasin</h4>
                        </div>
                        <div class="card-body">
                            @include('include.peta')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-12 col-lg-3">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Jumlah UMKM per Wilayah</h4>
                    </div>
                    <div class="card-body">
                        @foreach ($kecamatan as $wilayah)
                            <div class="row mb-3">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 ms-3">{{ $wilayah->nama_kecamatan }}</p>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <p class="mb-0 text-end">
                                        {{ $wilayah->umkms_count ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
