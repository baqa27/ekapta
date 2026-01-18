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
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Tabel {{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mahasiswa</th>
                                    <th>Prodi</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($pengajuans as $pengajuan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ $pengajuan->mahasiswa->nama }} - {{ $pengajuan->mahasiswa->nim }}
                                    </td>
                                    <td>
                                        {{ $pengajuan->prodi->namaprodi }}
                                    </td>
                                    <td>{{ $pengajuan->judul }}</td>
                                    <td>
                                        @if ($pengajuan->status == 'review')
                                        <span class="badge bg-secondary">Review</span>
                                        @elseif ($pengajuan->status == 'revisi')
                                        <span class="badge bg-warning">Revisi</span>
                                        @elseif ($pengajuan->status == 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                        @elseif ($pengajuan->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @else
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('kp.pengajuan.review.admin', $pengajuan->id) }}"
                                            class="btn btn-primary btn-sm shadow">
                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                        </a>
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
                                    <th>Status</th>
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
    </div>
</section>
<!-- /.content -->

@endsection




