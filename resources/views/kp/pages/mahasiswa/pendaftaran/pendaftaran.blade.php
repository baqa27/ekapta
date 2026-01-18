@extends('kp.layouts.dashboardMahasiswa')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
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
    <div class="content">
        <div class="container">

            @if (count($pendaftarans_review_acc_revisi) == 0 )
                <a href="{{ route('kp.pendaftaran.create') }}" class="btn btn-primary mb-4"><i
                        class="fas fa-plus mr-2"></i>
                    {{ $title }}</a>
            @endif

            @if (count($pendaftaranIsAcc) != 0)
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Selamat! Pendaftaran kerja praktik anda sudah di Acc oleh Admin, anda bisa memulai <b><a
                            href="{{ route('kp.bimbingan.mahasiswa') }}">Bimbingan Kerja
                            Praktik.</a></b>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pendaftaran Anda</h3>
                        </div>
                        <div class="card-body">

                            <table>
                                <tr>
                                    <td><span class="mr-2">Dosen Pembimbing</span></td>
                                    <td><span class="mr-2">:</span></td>
                                    <td><strong>
                                            @if ($dosen_pembimbing)
                                                {{ $dosen_pembimbing->nama.', '.$dosen_pembimbing->gelar }}
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </strong>
                                    </td>
                                </tr>
                            </table>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul KP</th>
                                    <th>Tanggal Pendaftaran</th>
                                    <th>Tanggal Acc</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($pendaftarans as $pendaftaran)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <a href="{{ url('pendaftaran/detail/'.$pendaftaran->id) }}">{{
                                    $pendaftaran->pengajuan->judul }}</a>
                                        </td>
                                        <td>{{ $pendaftaran->created_at->format('d M Y H:m') }}</td>
                                        <td>
                                            @if ($pendaftaran->tanggal_acc)
                                                {{ date('d M Y H:m', strtotime($pendaftaran->tanggal_acc)) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pendaftaran->status =='diterima')
                                                <span class="badge bg-success">Diterima</span>

                                            @elseif ($pendaftaran->status =='revisi')
                                                <span class="badge bg-warning">Revisi</span>

                                            @elseif ($pendaftaran->status =='review')
                                                <span class="badge bg-secondary">Review</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pendaftaran->status =='diterima')
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="{{ url('pendaftaran/detail/'.$pendaftaran->id) }}"
                                                       class="btn btn-primary btn-sm mr-2 mb-1"><i
                                                            class="fas fa-info-circle mr-1"></i>Detail</a>
                                                    <a href="{{ route('kp.cetak.surat.tugas.bimbingan') }}"
                                                       class="btn btn-success btn-sm mb-1" target="_blank"><i
                                                            class="fas fa-download mr-1"></i>
                                                        Surat Tugas Bimbingan KP</a>
                                                </div>

                                            @elseif ($pendaftaran->status =='revisi')
                                                <a href="{{ url('pendaftaran/edit/'.$pendaftaran->id) }}"
                                                   class="btn btn-primary btn-sm"><i class="fa fa-upload mr-1"></i>
                                                    Submit Revisi</a>

                                            @elseif ($pendaftaran->status =='review')
                                                <a href="{{ url('pendaftaran/detail/'.$pendaftaran->id) }}"
                                                   class="btn btn-primary btn-sm"><i
                                                        class="fas fa-info-circle mr-1"></i>Detail</a>

                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Pendaftaran</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Tanggal Acc</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
</div>
</div>
<!-- /.content -->

@endsection




