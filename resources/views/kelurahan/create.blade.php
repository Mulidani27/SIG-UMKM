@extends('kerangka.master')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tabel Data</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Data</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif 
                            <form class="form form-horizontal" method="POST" action="{{ route('kelurahan.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama Kelurahan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama Kelurahan" id="nama_kelurahan" name="nama_kelurahan">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-border-style"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Nama Kecamatan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="kecamatan_id" id="kecamatan" class="form-control">
                                                        <option value="" selected disabled>Pilih Kecamatan</option>
                                                        @foreach($kecamatan as $kec)
                                                            <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                                        @endforeach
                                                    </select>                                       
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-tags"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Upload File GeoJSON</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="file" class="form-control" name="geojson" accept=".geojson">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Latitude</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Latitude" name="latitude" id="latitude">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Longitude</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Longitude" name="longitude" id="longitude">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @include('include.mappicker')

                                        <div class="col-md-4" style="margin-top: 12px">
                                            <label>Batas Wilayah</label>
                                        </div>
                                        <div class="col-md-8" style="margin-top: 12px">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Batas Wilayah" id="batas_wilayah" name="batas_wilayah">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-info-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
