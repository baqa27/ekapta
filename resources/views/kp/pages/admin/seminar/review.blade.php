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
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
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
                <a href="{{ route('kp.seminar.admin') }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($seminar->is_valid == 0) bg-secondary
                            @elseif ($seminar->is_valid == 2)
                            bg-warning
                            @elseif ($seminar->is_valid == 1)
                            bg-success @endif
                            ">
                                @if ($seminar->is_valid == 0)
                                    review
                                @elseif ($seminar->is_valid == 1)
                                    diterima
                                @elseif ($seminar->is_valid == 2)
                                    revisi
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_utama ? $dosen_utama->nama . ', ' . $dosen_utama->gelar : '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dosen Penguji
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->dosenPenguji ? $seminar->dosenPenguji->nama . ', ' . $seminar->dosenPenguji->gelar : '-' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Kerja Praktek
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($seminar->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Seminar KP
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ storage_url($seminar->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($seminar->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    File Laporan Proposal
                                </div>
                                <div class="col-md-7">
                                    @if ($seminar->lampiran_3)
                                    <a href="{{ storage_url($seminar->lampiran_3) }}" target="_blank"><i
                                        class="fas fa-paperclip"></i>
                                    {{ Str::substr($seminar->lampiran_3, 40) }}</a>
                                    @else
                                    <span class="text-danger">Belum Upload File Laporan Proposal</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nomor Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->nomor_pembayaran }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Jumlah Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b
                                        class="text-success">{{ $seminar->jumlah_bayar ? 'Rp ' . $seminar->jumlah_bayar : '' }}</b>
                                </div>
                            </div>
                            <hr>

                            {{--                            <div class="row"> --}}
                            {{--                                <div class="col-md-5"> --}}
                            {{--                                    Berkas File Kerja Praktek Lengkap --}}
                            {{--                                </div> --}}
                            {{--                                <div class="col-md-7"> --}}
                            {{--                                    <a href="{{ storage_url($seminar->lampiran_3) }}" target="_blank"><i --}}
                            {{--                                            class="fas fa-paperclip"></i> --}}
                            {{--                                        {{ Str::substr($seminar->lampiran_3, 40) }}</a> --}}
                            {{--                                </div> --}}
                            {{--                            </div> --}}
                            {{--                            <hr> --}}

                            {{--                            <div class="row"> --}}
                            {{--                                <div class="col-md-5"> --}}
                            {{--                                    Scan Lembar Bimbingan KP Yang Telah di Acc --}}
                            {{--                                </div> --}}
                            {{--                                <div class="col-md-7"> --}}
                            {{--                                    <a href="{{ storage_url($seminar->lampiran_4) }}" target="_blank"><i --}}
                            {{--                                            class="fas fa-paperclip"></i> --}}
                            {{--                                        {{ Str::substr($seminar->lampiran_4, 40) }}</a> --}}
                            {{--                                </div> --}}
                            {{--                            </div> --}}
                            {{--                            <hr> --}}

                            {{--                            <div class="row"> --}}
                            {{--                                <div class="col-md-5"> --}}
                            {{--                                    Scan Lembar Persetujuan --}}
                            {{--                                </div> --}}
                            {{--                                <div class="col-md-7"> --}}
                            {{--                                    <a href="{{ storage_url($seminar->lampiran_5) }}" target="_blank"><i --}}
                            {{--                                            class="fas fa-paperclip"></i> --}}
                            {{--                                        {{ Str::substr($seminar->lampiran_5, 40) }}</a> --}}
                            {{--                                </div> --}}
                            {{--                            </div> --}}
                            {{--                            <hr> --}}

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>

                            @if ($seminar->tanggal_acc)
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Validasi Pendaftaran
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-success">{{ date('d M Y H:m', strtotime($seminar->tanggal_acc)) }}</b>
                                    </div>
                                </div>
                            @endif

                            @if ($seminar->tanggal_ujian)
                                @php
                                    $tanggal_ujian = \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian);
                                @endphp
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tanggal Seminar
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger">{{ $tanggal_ujian }}</b>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tempat Seminar
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger">{{ $seminar->tempat_ujian }}</b>
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- Info: Seminar KP dikelola oleh Himpunan --}}
                        @if ($seminar->is_valid == 0)
                            <div class="card-footer">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pendaftaran seminar KP dikelola oleh <strong>Himpunan</strong>. Admin/Prodi hanya dapat melihat data.
                                </div>
                            </div>
                        @elseif ($seminar->is_valid == 1)
                            {{-- Sudah diterima - tampilkan download berita acara --}}
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="{{ route('kp.cetak.berita.acara.ujian.proposal', $seminar->id) }}"
                                        class="btn btn-success mr-2" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Seminar KP
                                    </a>
                                    <a href="{{ route('kp.cetak.berita.acara.ujian.proposal.blank', [$seminar->id, 1]) }}"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Seminar KP Kosong
                                    </a>
                                </div>
                            </div>
                        @elseif ($seminar->is_valid == 2)
                            <div class="card-footer">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Status: <strong>Revisi</strong> - Menunggu mahasiswa memperbaiki dokumen.
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($seminar->revisis) }}
                                </span>
                            </h3>
                            {{-- Tombol tambah revisi dihapus - dikelola oleh Himpunan --}}
                        </div>

                        <div class="card-body">
                            @forelse ($revisis as $revisi)
                                <div class="card bg-light mb-2">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            <a href="{{ storage_url($revisi->lampiran) }}" class="ml-3" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($revisi->lampiran) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada revisi</p>
                            @endforelse
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

    {{-- Modal tidak diperlukan - Seminar KP dikelola oleh Himpunan --}}

@endsection




