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
                                    <li class="breadcrumb-item active" aria-current="page">Daftar Kelurahan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Kelurahan</h5>
                        <a class="btn btn-primary" href="{{ route('kelurahan.create') }}">Tambah Data</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>Nama Kelurahan</th>
                                        <th>Nama Kecamatan</th>
                                        <th>Batas Wilayah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kelurahan as $kel)
                                    <tr>
                                        <td>{{ $kel->nama_kelurahan }}</td>
                                        <td>{{ $kel->kecamatan->nama_kecamatan }}</td>
                                        <td>{{ $kel->batas_wilayah }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap align-items-center">
                                                <a class="btn btn-warning mx-1" href="{{ route('kelurahan.edit', $kel->id) }}">Update</a>
                                                <form action="{{ route('kelurahan.destroy', $kel->id) }}" method="POST" class="delete-form">
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
