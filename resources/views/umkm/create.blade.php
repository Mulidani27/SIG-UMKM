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
                            <li class="breadcrumb-item active" aria-current="page">Tambah UMKM</li>
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
                        <h4 class="card-title">Tambah UMKM</h4>
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
    
                            <form class="form form-horizontal" method="POST" action="{{ route('umkm.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama Usaha</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama Usaha"
                                                        id="nama_usaha" name="nama_usaha">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-buildings"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nama Pemilik</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama Pemilik"
                                                        id="nama_pemilik" name="nama_pemilik">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kategori</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="kategori_id" id="kategori" class="form-control">
                                                        @foreach($kategori as $kat)
                                                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                                        @endforeach
                                                    </select>                                       
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-tags"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-4">
                                            <label for="password-horizontal-icon">Latitude</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="password-horizontal-icon">Longitude</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact-info-horizontal-icon">Phone</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="number" class="form-control" placeholder="Phone" id="phone" name="phone">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-phone"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="address-horizontal-icon">Alamat</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Alamat" id="alamat" name="alamat">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-map"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="address-horizontal-icon">Foto</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="file" class="form-control @error('foto')is in-valid @enderror" id="foto" name="foto">
                                                    @error('foto')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-upload"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Simpan</button>
                                            <button type="reset"
                                                class="btn btn-light-secondary me-1 mb-1">Reset</button>
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
