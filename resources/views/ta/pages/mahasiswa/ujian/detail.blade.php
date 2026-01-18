@extends('ta.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran Ujian Pendadaran TA</a></li>
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
                            @if ($ujian->is_valid == 0) bg-secondary
                            @elseif ($ujian->is_valid == 2)
                            bg-warning
                            @elseif ($ujian->is_valid == 1)
                            bg-success @endif
                            ">
                                @if ($ujian->is_valid == 0)
                                REVIEW
                                @elseif ($ujian->is_valid == 1)
                                VALID
                                @elseif ($ujian->is_valid == 2)
                                TIDAK VALID
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $ujian->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $ujian->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Utama (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Pendamping (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Ijazah Terakhir Yang Asli
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_3, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan KTP / Kartu Keluarga Terbaru
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_4) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_4, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat TOEFL
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_5, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Tahfidz
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_6) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_6, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Komputer
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_7) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_7, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_8) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_8, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Laporan Skripsi
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_laporan) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_laporan, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>

                            @if ($ujian->tanggal_acc)
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-7">
                                    @if ($ujian->tanggal_acc)
                                    <b class="text-success">{{ date('d M Y H:m', strtotime($ujian->tanggal_acc)) }}</b>
                                    @endif
                                </div>
                            </div>
                            @endif

                        </div>
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
                                                        <a href="{{ asset($revisi->lampiran) }}" target="_blank">
                                                            <i class="fas fa-paperclip ml-1"></i>
                                                            {{ Str::substr($revisi->lampiran, 16) }}
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




