@extends('kerangka.master')
@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
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
                                    <li class="breadcrumb-item active" aria-current="page">Daftar UMKM Sasirangan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar UMKM Sasirangan</h5>
                        <div class="d-flex">
                            <a class="btn btn-primary me-2" href="{{ route('umkm.create') }}">Tambah Data</a>
                            <!-- Tombol untuk membuka modal -->
                            <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                Import Excel
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="importModalLabel">Import Excel</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('umkm.import') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="fileInput" class="form-label">Pilih File Excel</label>
                                                    <input type="file" class="form-control" id="fileInput" name="file" required>
                                                </div>
                                                <!-- Tombol untuk mengunduh file contoh -->
                                                <div class="mb-3">
                                                    <a href="{{ asset('uploads/Sampel Data Import.xlsx') }}" class="btn btn-info" download="contoh_import.xlsx">Unduh Contoh File</a>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-warning">Import</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Ekspor
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li><a class="dropdown-item" href="{{ route('umkm.export.all') }}">Semua Data</a></li>
                                    <li><a class="dropdown-item" href="#">Berdasarkan kecamatan &raquo;</a>
                                        <ul class="dropdown-menu dropdown-submenu">
                                            <li>
                                              <a class="dropdown-item" href="#">
                                                <form action="{{ route('umkm.export.by.category') }}" method="GET">
                                                    @csrf
                                                    <select name="kecamatan_id" class="form-select" aria-label="Pilih kecamatan" onchange="this.form.submit()">
                                                        <option selected disabled>Pilih kecamatan</option>
                                                        @foreach($kecamatan as $kec)
                                                            <option value="{{ $kec->id }}">{{ $kec->nama_kecamatan }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                              </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Nama Usaha</th>
                                        <th>Jenis Usaha</th>
                                        <th>Alamat</th>
                                        <th>Kecamatan</th>
                                        <th>Kelurahan</th>
                                        {{-- <th>Latitude</th> --}}
                                        {{-- <th>Longitude</th> --}}
                                        <th>Kontak</th>
                                        {{-- <th>Foto</th> --}}
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($umkm as $um)
                                    <tr>
                                        <td>{{ $um->nama}}</td>
                                        <td>{{ $um->nik}}</td>
                                        <td>{{ $um->nama_usaha}}</td>
                                        <td>{{ $um->jenis_usaha}}</td>
                                        <td>{{ $um->alamat }}</td>
                                        <td>{{ $um->kecamatan->nama_kecamatan }}</td>
                                        <td>{{ $um->kelurahan->nama_kelurahan }}</td>
                                        {{-- <td>{{ $um->latitude }}</td> --}}
                                        {{-- <td>{{ $um->longitude }}</td> --}}
                                        <td>{{ $um->phone }}</td>
                                        {{-- <td><img src="{{ asset('storage/' . $um->foto) }}" alt="Foto UMKM"></td> --}}
                                        <td>
                                            <div class="d-flex flex-wrap align-items-center">
                                                <a class="btn btn-info mx-1" href="{{ route('umkm.show', $um->id) }}">Detail</a>
                                                <a class="btn btn-warning mx-1" href="{{ route('umkm.edit', $um->id) }}">Update</a>
                                                <form action="{{ route('umkm.destroy', $um->id) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger mx-1 delete-button">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.onclick = () => {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        };
    });
</script>
@endsection
