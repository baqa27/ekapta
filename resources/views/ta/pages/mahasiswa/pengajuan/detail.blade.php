@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
                        <div class="card-header">
                            <h3 class="card-title"><strong>Judul Tugas Akhir : </strong>{{ $pengajuan->judul }}</h3>
                        </div>
                        <div class="card-body">
                            <p><b>Deskripsi</b></p>
                            {!! nl2br($pengajuan->deskripsi) !!}
                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                {{ $pengajuan->created_at->format('d M Y H:s') }}
                            </div>
                            @if ($pengajuan->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    {{ date('d M Y H:s', strtotime($pengajuan->tanggal_acc)) }}
                                </div>
                            @endif
                            <hr>
                            <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($pengajuan->lampiran) }}" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i>
                                    {{ Str::substr($pengajuan->lampiran, 40) }}</a></p>
                        </div>

                    </div>

                    {{-- Revisi --}}
                    @if ($pengajuan->status != 'dibatalkan')
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
                                                <span
                                                    class="direct-chat-name float-left">{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pengajuan->mahasiswa->nim)->prodi }}</span>
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
                    @endif

                    {{-- Bimbingan Canceled History --}}
                    @if ($pengajuan->status == 'dibatalkan')
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Riwayat Bimbingan</b>
                                </h3>
                            </div>

                            <div class="card-body row">

                                @foreach ($pengajuan->bimbingan_canceleds as $bimbingan)
                                    <div class="col-md-6 p-2 rounded border mb-2">
                                        <span class="badge bg-info">{{ $bimbingan->bagian->bagian }}</span>
                                        @if ($bimbingan->status == 'review')
                                            <span class="badge bg-secondary">Review</span>
                                        @elseif ($bimbingan->status == 'revisi')
                                            <span class="badge bg-warning">Revisi</span>
                                        @elseif ($bimbingan->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                        @else
                                            <span class="badge bg-secondary">Belum melakukan bimbingan</span>
                                        @endif
                                        @if ($bimbingan->lampiran)
                                            <a href="{{ asset($bimbingan->lampiran) }}" target="_blank"><i class="fas fa-download"></i>
                                                Lampiran</a>
                                        @endif
                                        <br>Dosen pembimbing {{ $bimbingan->pembimbing }} :
                                        {{ $bimbingan->dosen->nama . ',' . $bimbingan->dosen->gelar }}
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection




