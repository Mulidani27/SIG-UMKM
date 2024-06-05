@extends('kerangka.master')
@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar UMKM</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Alamat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Graiden</td>
                                    <td>vehicula.aliquet@semconsequat.co.uk</td>
                                    <td>076 4820 8838</td>
                                    <td>Offenburg</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Dale</td>
                                    <td>fringilla.euismod.enim@quam.ca</td>
                                    <td>0500 527693</td>
                                    <td>New Quay</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Nathaniel</td>
                                    <td>mi.Duis@diam.edu</td>
                                    <td>(012165) 76278</td>
                                    <td>Grumo Appula</td>
                                    <td><span class="badge bg-danger">Inactive</span></td>
                                </tr>
                                <tr>
                                    <td>Darius</td>
                                    <td>velit@nec.com</td>
                                    <td>0309 690 7871</td>
                                    <td>Ways</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Emmanuel</td>
                                    <td>eget.lacus.Mauris@feugiatSednec.org</td>
                                    <td>(016977) 8208</td>
                                    <td>Saint-Remy-Geest</td>
                                    <td><span class="badge bg-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection

