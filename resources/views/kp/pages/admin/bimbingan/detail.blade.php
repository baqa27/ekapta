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
                        <li class="breadcrumb-item"><a href="#">Detail Bimbingan KP</a></li>
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
                <a href="{{ route('kp.bimbingan.admin') }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Detail Bimbingan KP</h3>
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
                                    Judul KP
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
                                <div class="col-md-12 border rounded p-2">
                                    Pembimbing KP : <b>{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }} </b>
                                    <hr>
                                    @foreach ($mahasiswa->bimbingans as $bimbingan)
                                        @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                            <a href="{{ storage_url($bimbingan->lampiran) }}" target="_blank">
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ $bimbingan->bagian->bagian . ' [ Di Acc pada ' . \Carbon\Carbon::parse($bimbingan->tanggal_acc)->translatedFormat('d F Y') }}]
                                                </span>
                                            </a>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-circle mr-1"></i>
                                                {{ $bimbingan->bagian->bagian }}
                                            </span>
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




