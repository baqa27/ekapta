@extends('ta.layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Detail Bimbingan TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            Detail Bimbingan TA
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    Nama / NIM
                                </div>
                                <div class="col-md-8">
                                    : {{ $pengajuan->mahasiswa->nama }} / {{ $pengajuan->mahasiswa->nim }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    Judul TA
                                </div>
                                <div class="col-md-8">
                                    : {{ $pengajuan->judul }}
                                </div>
                            </div>
                            <hr>

                            <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i> Diterima/Acc
                            </span>
                            <span class="badge badge-secondary">
                                <i class="fas fa-circle mr-1"></i> Review/Belum Di Acc
                            </span>

                            <div class="row justify-content-center mt-4">
                                <div class="col-md-6 border rounded p-2">
                                    Pembimbing Utama : <b>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }} </b>
                                    <hr>
                                    @foreach ($mahasiswa->bimbingans as $bimbingan)
                                        @if ($bimbingan->pembimbing == 'utama')
                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                <a href="{{ asset($bimbingan->lampiran) }}" target="_blank">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        {{ $bimbingan->bagian->bagian . ' [ Di Acc pada ' . \Carbon\Carbon::parse($bimbingan->tanggal_acc)->formatLocalized('%d %B %Y') }}]
                                                    </span>
                                                </a>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    {{ $bimbingan->bagian->bagian }}
                                                </span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>

                                <div class="col-md-6 border rounded p-2">
                                    Pembimbing Pendamping :
                                    <b>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}
                                    </b>
                                    <hr>
                                    @foreach ($mahasiswa->bimbingans as $bimbingan)
                                        @if ($bimbingan->pembimbing == 'pendamping')
                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                <a href="{{ asset($bimbingan->lampiran) }}" target="_blank">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        {{ $bimbingan->bagian->bagian . ' [ Di Acc pada ' . \Carbon\Carbon::parse($bimbingan->tanggal_acc)->formatLocalized('%d %B %Y') }}]
                                                    </span>
                                                </a>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    {{ $bimbingan->bagian->bagian }}
                                                </span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




