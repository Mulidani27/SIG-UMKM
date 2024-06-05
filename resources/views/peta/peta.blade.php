{{-- Peta --}}
@extends('kerangka.master')
@section('title','Peta')
@section('content')
<div class="page-content"> 
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pemetaan UMKM di Banjarmasin 
                                @if(isset($selectedKategori))
                                    Kategori {{ $selectedKategori->nama_kategori }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 485px;">
                                @include('include.peta', ['umkms' => $umkms])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
