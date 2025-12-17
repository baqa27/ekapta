@extends('layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                        </div>
                        <div class="card-body">

                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>NAMA DOSEN</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUMLAH</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($dosens as $dosen)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dosen->nidn }}</td>
                                            <td>{{ $dosen->nama . ', ' . $dosen->gelar }}</td>
                                            <td>{{ \App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen) }}</td>
                                            <td>{{ \App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen, false) }}
                                            </td>
                                            <td>{{ \App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen) + \App\Helpers\AppHelper::count_mahasiswa_bimbingan_dosen($dosen, false) }}
                                            </td>
                                            <td>
                                                @if (count($dosen->mahasiswas) != 0)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modal-default-{{ $dosen->id }}">
                                                        <i class="fas fa-users"></i> List Mahasiswa
                                                    </button>
                                                    <div class="modal fade" id="modal-default-{{ $dosen->id }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">List Mahasiswa</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        @foreach ($dosen->mahasiswas()->whereDoesntHave('jilid')->get() as $mahasiswa)
                                                                            <div class="p-2 border m-1 border-dark col-md-5">{{ $mahasiswa->nama . '/' . $mahasiswa->nim }}</div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>NAMA DOSEN</th>
                                        <th>PEMBIMBING 1</th>
                                        <th>PEMBIMBING 2</th>
                                        <th>JUMLAH</th>
                                        <th>AKSI</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
