@extends('ta.layouts.dashboardFotokopi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ config('app.name') }}</h1>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="mt-3 row">
                                <div class="col-12 p-3 rounded border">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            NIM/NAMA MAHASISWA
                                        </div>
                                        <div class="col-md-9">
                                            <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            PRODI
                                        </div>
                                        <div class="col-md-9">
                                            <b>{{ $prodi ? $prodi->namaprodi : '-' }}</b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            JUDUL SKRIPSI
                                        </div>
                                        <div class="col-md-9">
                                            <b>{{ $pengajuan ? $pengajuan->judul : '-' }}</b>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            TANGGAL DAFTAR
                                        </div>
                                        <div class="col-md-9">
                                            <b>{{ $pengajuan ? \App\Helpers\AppHelper::parse_date($pengajuan->pendaftaran->created_at) : '-' }}</b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            TANGGAL BERAKHIR BIMBINGAN
                                        </div>
                                        <div class="col-md-9">
                                            @if($pendaftaran)
                                                @if ($is_expired)
                                                    <span class="badge bg-danger">TIDAK AKTIF</span>
                                                @else
                                                    <span class="bg-primary rounded badge bg-primary countdown"
                                                        data-expire="{{ \Carbon\Carbon::parse($date_expired)->format('Y/m/d h:i:s') }}">
                                                    </span>
                                                @endif
                                            @else
                                            -
                                            @endif
                                        </div>
                                    </div>

                                    {{--<table>
                                        <tr>
                                            <td>NIM/NAMA MAHASISWA</td>
                                            <td>: <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>PRODI</td>
                                            <td>: <b>{{ $prodi ? $prodi->namaprodi : '' }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>JUDUL SKRIPSI</td>
                                            <td>: <b>{{ $pengajuan ? $pengajuan->judul : '' }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>TANGGAL DAFTAR</td>
                                            <td>: <b>{{ $pendaftaran ? \App\Helpers\AppHelper::parse_date($pendaftaran->created_at) : '' }}</b></td>
                                        </tr>
                                        <tr>
                                            <td>TANGGAL BERAKHIR BIMBINGAN</td>
                                            <td>
                                                @if($pendaftaran)
                                                    @if ($is_expired)
                                                        <span class="badge bg-danger">TIDAK AKTIF</span>
                                                    @else
                                                        <span class="d-flex justify-content-center bg-primary rounded badge bg-primary countdown"
                                                            data-expire="{{ \Carbon\Carbon::parse($date_expired)->format('Y/m/d h:i:s') }}">
                                                        </span>
                                                    @endif
                                                @else
                                                -
                                                @endif
                                            </td>
                                        </tr>
                                    </table>--}}
                                </div>
                                <div class="col-12 mt-3 mb-3">
                                    <span class="badge badge-success"> <i class="fas fa-check-circle mr-1"></i>
                                        Diterima/Acc
                                    </span>
                                    <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                                        Belum Bimbingan/Review/Belum Di Acc
                                    </span>
                                </div>
                                <div class="col-md-6 p-3 rounded border mb-2">
                                    @if($dosen_utama)
                                        Pembimbing 1: <b>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</b><hr>
                                        @foreach ($mahasiswa->bimbingans()->where('pembimbing', 'utama')->get() as $bimbingan)
                                            <a href="#"
                                                class="btn {{ $bimbingan->status == 'diterima' ? 'btn-success' : 'btn-secondary' }} mb-3 btn-sm"><i
                                                    class="fas {{ $bimbingan->status == 'diterima' ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                {{ $bimbingan->bagian->bagian }}</a>
                                        @endforeach
                                    @else
                                        TIDAK ADA BIMBINGAN
                                    @endif
                                </div>
                                <div class="col-md-6 p-3 rounded border mb-2">
                                    @if($dosen_pendamping)
                                        Pembimbing 2: <b>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</b><hr>
                                        @foreach ($mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get() as $bimbingan)
                                            <a href="#"
                                                class="btn {{ $bimbingan->status == 'diterima' ? 'btn-success' : 'btn-secondary' }} mb-3 btn-sm"><i
                                                    class="fas {{ $bimbingan->status == 'diterima' ? 'fa-check-circle' : 'fa-circle' }}"></i>
                                                {{ $bimbingan->bagian->bagian }}</a>
                                        @endforeach
                                    @else
                                        TIDAK ADA BIMBINGAN
                                    @endif
                                </div>
                            </div>
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




