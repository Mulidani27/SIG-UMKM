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
    
                            <form class="form form-horizontal" method="POST" action="{{ route('umkm.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama"
                                                        id="nama" name="nama">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>NIK</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="NIK"
                                                        id="nik" name="nik">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person-vcard"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nama Usaha</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama Usaha Jika Ada"
                                                        id="nama_usaha" name="nama_usaha">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jenis Usaha</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Jenis Usaha"
                                                        id="jenis_usaha" name="jenis_usaha">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-shop"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kecamatan</label>
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
                                            <label>Kelurahan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="kelurahan_id" id="kelurahan" class="form-control">
                                                        <option value="" selected disabled>Pilih Kelurahan</option>
                                                    </select>                                       
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-tags"></i>
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
                                        @include('include.mappicker')
                                        {{-- <div class="col-md-4">
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
                                        </div> --}}
                                        <div class="col-md-4" style="margin-top: 12px">
                                            <label for="contact-info-horizontal-icon">Kontak</label>
                                        </div>
                                        <div class="col-md-8" style="margin-top: 12px">
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
<!-- Tambahkan jQuery atau script AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#kecamatan').change(function() {
            var kecamatan_id = $(this).val();
            if(kecamatan_id) {
                $.ajax({
                    url: '/kelurahan/' + kecamatan_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#kelurahan').empty();
                        $('#kelurahan').append('<option value="" selected disabled>Pilih Kelurahan</option>');
                        $.each(data, function(key, value) {
                            $('#kelurahan').append('<option value="'+ value.id +'">'+ value.nama_kelurahan +'</option>');
                        });
                    }
                });
            } else {
                $('#kelurahan').empty();
                $('#kelurahan').append('<option value="" selected disabled>Pilih Kelurahan</option>');
            }
        });
    });
</script>
@endsection
