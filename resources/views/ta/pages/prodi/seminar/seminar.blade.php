@extends('ta.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                    <a href="{{ route('ta.seminar.rekap') }}" class="btn btn-success btn-sm shadow mt-3" target="_blank">
                        <i class="bi bi-people"></i> Rekap Pendaftaran Seminar Mahasiswa
                    </a>
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
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Tempat Ujian</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($seminars as $seminar)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            {{  $seminar->mahasiswa->nama }} - {{ $seminar->mahasiswa->nim }}
                                        </td>
                                        <td>
                                            {{  $seminar->mahasiswa->prodi }}
                                        </td>
                                        <td>{{ $seminar->pengajuan->judul }}</td>
                                        <td>
                                            {{ date('d M Y H:i', strtotime($seminar->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : null }}
                                        </td>
                                        <td>{{ $seminar->tempat_ujian }}</td>
                                        <td class="text-center">
                                            <b>{{ count($seminar->reviews()->where('status','diterima')->get()) >= 4 ? \App\Helpers\AppHelper::hitung_nilai_mahasiswa($seminar)['nilai'] : null }}</b>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('seminar.review.admin', $seminar->id) }}"
                                                    class="btn btn-info btn-sm shadow mr-2">
                                                    <i class="fas fa-check-circle mr-1"></i> Review
                                                </a>
                                                <a href="{{ route('seminar.prodi.detail' , $seminar->id) }}"
                                                    class="btn btn-primary btn-sm shadow">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Tempat Ujian</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
    </section>
    <!-- /.content -->
@endsection




