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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
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
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($pendaftaran->status == 'review') bg-secondary
                            @elseif ($pendaftaran->status == 'revisi')
                            bg-warning
                            @elseif ($pendaftaran->status == 'diterima')
                            bg-success @endif
                            ">
                                {{ $pendaftaran->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    NIM
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $pendaftaran->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-8">
                                    <b>{{ Auth::guard('mahasiswa')->user()->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Prodi
                                </div>
                                <div class="col-md-8">
                                    <b>{{ Auth::guard('mahasiswa')->user()->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dosen Pembimbing Kerja Praktik
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : 'Belum ditentukan' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Judul Kerja Praktik
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $pendaftaran->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dokumen Acc. Kaprodi
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($pendaftaran->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Lembar Pernyataan Keaslian Hasil Kerja Praktik
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($pendaftaran->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Transkrip Nilai
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($pendaftaran->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_3, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Pengumpulan KP
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($pendaftaran->lampiran_4) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_4, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Pembayaran Kerja Praktik
                                </div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($pendaftaran->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_4, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Lembar Persetujuan Pembimbing
                                </div>
                                <div class="col-md-8">
                                    @if($pendaftaran->lampiran_6)
                                    <a href="{{ storage_url($pendaftaran->lampiran_6) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_6, 40) }}</a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Bukti Diterima Instansi
                                </div>
                                <div class="col-md-8">
                                    @if($pendaftaran->lampiran_7)
                                    <a href="{{ storage_url($pendaftaran->lampiran_7) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->lampiran_7, 40) }}</a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Dokumen Pendukung
                                </div>
                                <div class="col-md-8">
                                    @if($pendaftaran->dokumen_pendukung)
                                    <a href="{{ storage_url($pendaftaran->dokumen_pendukung) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i> {{ Str::substr($pendaftaran->dokumen_pendukung, 40) }}</a>
                                    @else
                                    -
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Nomor Pembayaran
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $pendaftaran->nomor_pembayaran }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Pembayaran
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $pendaftaran->tanggal_pembayaran }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Biaya
                                </div>
                                <div class="col-md-8">
                                    <span class="text-success fs-5">Rp, {{ $pendaftaran->biaya }},-</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-8">
                                    <b>{{ $pendaftaran->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-8">
                                    @if ($pendaftaran->tanggal_acc)
                                        <b
                                            class="text-success">{{ date('d M Y H:m', strtotime($pendaftaran->tanggal_acc)) }}</b>
                                    @endif
                                </div>

                            </div>

                        </div>
                        @if ($pendaftaran->status == 'diterima')
                            <div class="card-footer">
                                <a href="{{ route('kp.cetak.surat.tugas.bimbingan') }}" class="btn btn-success btn-sm"
                                    target="_blank"><i class="fas fa-download mr-1"></i>
                                    Surat Tugas Bimbingan KP</a>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($revisis) }}
                                </span>
                            </h3>

                            <div class="card-tools">
                                {{ $revisis->links() }}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="p-2">

                                @foreach ($revisis as $revisi)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:m a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br($revisi->catatan) !!}
                                            @if ($revisi->lampiran)
                                                <div class="p-1 mt-3 bg-light rounded">
                                                    <small>
                                                        <span class="text-secondary ml-2"><b>Lampiran : </b></span>
                                                        <a href="{{ storage_url($revisi->lampiran) }}" target="_blank">
                                                            <i class="fas fa-paperclip ml-1"></i>
                                                            {{ Str::substr($revisi->lampiran, 40) }}
                                                        </a>
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




