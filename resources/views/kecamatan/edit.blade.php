@extends('kerangka.master')
@section('content')
<div class="page-content">
    <div class="page-heading">
        <h3>Edit Data Kecamatan</h3>
    </div>
    <section>
        <div class="card">
            <div class="card-header">
                <h4>Edit Data Kecamatan</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('kecamatan.update', $kec->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-4">
                            <label>Nama Kecamatan</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group has-icon-left">
                                <div class="position-relative">
                                    <input type="text" class="form-control" name="nama_kecamatan" value="{{ old('nama_kecamatan', $kec->nama_kecamatan) }}">
                                    <div class="form-control-icon">
                                        <i class="bi bi-tags"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File GeoJSON -->
                        <div class="col-md-4">
                            <label>File GeoJSON</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="file" class="form-control" name="geojson">
                                @if ($kec)
                                    <a href="{{ route('peta', ['kecamatan' => $kec->id]) }}" class="submenu-link" data-kecamatan="{{ $kec->id }}">
                                        {{ $kec->nama_kecamatan }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Map Picker -->
                        <div class="col-md-4">
                            <label for="latitude">Latitude</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group has-icon-left">
                                <div class="position-relative">
                                    <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="{{ old('latitude', $kec->latitude ?? '') }}">
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
                                    <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="{{ old('longitude', $kec->longitude ?? '') }}">
                                    <div class="form-control-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('include.mappicker', [
                            'latitude' => $kec->latitude,
                            'longitude' => $kec->longitude
                        ])

                        <div class="col-md-4" style="margin-top: 12px">
                            <label>Batas Wilayah</label>
                        </div>
                        <div class="col-md-8" style="margin-top: 12px">
                            <div class="form-group has-icon-left">
                                <input type="text" class="form-control" name="batas_wilayah" value="{{ old('batas_wilayah', $kec->batas_wilayah) }}">
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
