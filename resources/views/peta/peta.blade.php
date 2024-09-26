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
                            <h4 style="margin-bottom: -30px">Pemetaan UMKM Sasirangan
                                @if(isset($selectedKecamatan))
                                    Wilayah {{ $selectedKecamatan->nama_kecamatan }}
                                @endif
                            </h4>
                        </div>
                        <div class="card-body">
                            @include('include.peta')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
