@extends('ta.layouts.dashboard')

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

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM / NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>DOSEN PEMBIMBING</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($dosens as $dosen)
                                        @foreach ($dosen->mahasiswas as $mahasiswa)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $mahasiswa->nim.'/'.$mahasiswa->nama }}
                                                </td>
                                                <td>
                                                    {{ $mahasiswa->prodi }}
                                                </td>
                                                <td>
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('ta.bimbingan.admin.input.create', [$dosen->id, $mahasiswa->id]) }}" class="btn btn-primary btn-sm shadow">
                                                    <i class="fas fa-upload"></i> Input Bimbingan
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM / NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>DOSEN PEMBIMBING</th>
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
