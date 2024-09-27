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
                            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
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
                        <h4 class="card-title">Edit Data</h4>
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
                            <form class="form form-horizontal" method="POST" action="{{ route('umkm.update', $umkm->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control" placeholder="Nama"
                                                        id="nama" name="nama" value="{{ old('nama')??$umkm->nama}}">
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
                                                        id="nik" name="nik" value="{{ old('nik')??$umkm->nik }}">
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
                                                        id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha')??$umkm->nama_usaha }}">
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
                                                        id="jenis_usaha" name="jenis_usaha" value="{{ old('jenis_usaha')??$umkm->jenis_usaha }}">
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
                                                        @foreach($kecamatan as $kec)
                                                            <option value="{{ $kec->id }}" {{ $umkm->kecamatan_id == $kec->id ? 'selected' : '' }}>
                                                                {{ $kec->nama_kecamatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>                                       
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-tags"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kelurahan">Kelurahan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="kelurahan_id" id="kelurahan" class="form-control">
                                                        <option value="" selected disabled>Pilih Kelurahan</option>
                                                        <!-- Kelurahan yang sesuai dengan kecamatan terpilih akan dimuat di sini -->
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
                                                    <input type="text" class="form-control" placeholder="Alamat" id="alamat" name="alamat" value="{{ old('alamat')??$umkm->alamat }}">
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
                                                    <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="{{ old('latitude')??$umkm->latitude }}">
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
                                                    <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="{{ old('longitude')??$umkm->longitude }}">
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
                                                    <input type="number" class="form-control" placeholder="Phone" id="phone" name="phone" value="{{ old('phone')??$umkm->phone }}">
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
                                                    <input type="file" class="form-control" id="foto" name="foto">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var selectedKelurahanID = {{ $umkm->kelurahan_id ?? 'null' }}; // ID kelurahan yang sudah dipilih sebelumnya
        // Panggil fungsi untuk memuat kelurahan berdasarkan kecamatan yang dipilih
        var kecamatanID = $('#kecamatan').val();
        if (kecamatanID) {
            loadKelurahan(kecamatanID, selectedKelurahanID);
        }

        // Event listener ketika kecamatan diubah
        $('#kecamatan').on('change', function() {
            var kecamatanID = $(this).val();
            loadKelurahan(kecamatanID, null); // Set null saat kecamatan berubah agar kelurahan tidak auto-terpilih
        });

        // Fungsi untuk memuat kelurahan berdasarkan kecamatan
        function loadKelurahan(kecamatanID, selectedKelurahanID) {
            $.ajax({
                url: '/kelurahan/' + kecamatanID,
                method: 'GET',
                success: function(data) {
                    var $kelurahanSelect = $('#kelurahan');
                    $kelurahanSelect.empty(); // Hapus opsi yang ada

                    // Tambahkan opsi default
                    $kelurahanSelect.append('<option value="" selected disabled>Pilih Kelurahan</option>');

                    // Tambahkan opsi kelurahan dari server
                    $.each(data, function(index, kelurahan) {
                        var isSelected = selectedKelurahanID && kelurahan.id == selectedKelurahanID ? 'selected' : '';
                        $kelurahanSelect.append('<option value="' + kelurahan.id + '" ' + isSelected + '>' + kelurahan.nama_kelurahan + '</option>');
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching kelurahan:', xhr);
                }
            });
        }
    });
</script>
@endsection
