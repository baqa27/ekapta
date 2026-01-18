@extends('kp.layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="{{ route('kp.pengajuan.admin') }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($pengajuan->status == 'review') bg-secondary
                            @elseif ($pengajuan->status == 'revisi')
                            bg-warning
                            @elseif ($pengajuan->status == 'diterima')
                            bg-success
                            @elseif ($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan')
                            bg-danger @endif
                            ">
                                {{ $pengajuan->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td>{{ $pengajuan->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td>{{ $pengajuan->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td>{{ $pengajuan->prodi->namaprodi }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td>{{ $pengajuan->judul }}</td>
                                </tr>

                            </table>
                            <hr>
                            <strong>Lokasi KP</strong> <br>
                            {{ $pengajuan->lokasi_kp }}
                            <br><br>
                            <strong>Alamat Instansi</strong> <br>
                            {{ $pengajuan->alamat_instansi }} }
                            <br><br>
                            @if ($pengajuan->lampiran)
                                <strong>Bukti Diterima Instansi</strong> <br>
                                <a href="{{ storage_url($pengajuan->lampiran) }}" target="_blank">
                                    <i class="fas fa-paperclip"></i> Lihat Bukti Diterima Instansi
                                </a>
                                <br><br>
                            @endif
                            @if ($pengajuan->files_pendukung)
                                <strong>File Pendukung</strong> <br>
                                <a href="{{ storage_url($pengajuan->files_pendukung) }}" target="_blank">
                                    <i class="fas fa-paperclip"></i> Lihat File Pendukung
                                </a>
                                <br><br>
                            @endif
                            <hr>
                            <p><b>Gambaran Singkat</b></p>
                            {!! nl2br($pengajuan->deskripsi) !!}
                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                {{ $pengajuan->created_at->format('d M y H:m') }}
                            </div>
                            @if ($pengajuan->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    {{ date('d M y H:m', strtotime($pengajuan->tanggal_acc)) }}
                                </div>
                            @endif
                            </div>

                    </div>

                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($pengajuan->revisis) }}
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header"><i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M y H:m') }}
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    @if ($revisi->lampiran)
                                    <div class="card-footer">
                                        <small>
                                            Lampiran :
                                            @if ($revisi->lampiran)
                                                <a href="{{ storage_url($pengajuan->lampiran) }}" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    {{ basename($revisi->lampiran) }}</a>
                                            @endif
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




