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
                                    <li class="breadcrumb-item active" aria-current="page">Daftar Kategori UMKM</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Kategori UMKM</h5>
                        <a class="btn btn-primary" href="{{ route('kategori.create') }}">Tambah Kategori</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="myTable" class="display">
                                <thead>
                                    <tr>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori as $kat)
                                    <tr>
                                        <td>{{ $kat->nama_kategori }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap align-items-center">
                                                <a class="btn btn-warning mx-1" href="{{ route('kategori.edit', $kat->id) }}">Update</a>
                                                <form action="{{ route('kategori.destroy', $kat->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger mx-1">Delete</button>
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
@endsection
