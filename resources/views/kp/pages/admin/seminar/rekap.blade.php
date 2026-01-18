@extends('kp.layouts.dashboard')

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
                            {{--<a href="{{ route('kp.seminar.admin') }}" class="btn btn-secondary btn-sm shadow">
                                <i class="bi bi-chevron-left"></i> Kembali
                            </a>--}}
                            Tabel {{ $title }}
                        </div>
                        <div class="card-body">

                            <table id="examplebutton" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>PEMBIMBING KP</th>
                                        <th>JUDUL KP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($seminars as $seminar)
                                    @php
                                        $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                    @endphp
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $seminar->mahasiswa->nim }}</td>
                                            <td>{{ $seminar->mahasiswa->nama }}</td>
                                            <td>{{ $seminar->mahasiswa->prodi }}</td>
                                            <td>{{ $dosen_pembimbing ? $dosen_pembimbing->nama.', '.$dosen_pembimbing->gelar : '-' }}</td>
                                            <td>{{ $seminar->pengajuan->judul }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>NAMA MAHASISWA</th>
                                        <th>PRODI</th>
                                        <th>PEMBIMBING KP</th>
                                        <th>JUDUL KP</th>
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




