@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.bimbingan.mahasiswa') }}">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($bimbingan->status == 'review') bg-secondary
                                @elseif ($bimbingan->status == 'revisi') bg-warning
                                @elseif ($bimbingan->status == 'diterima') bg-success
                                @elseif ($bimbingan->status == 'ditolak') bg-danger @endif">
                                {{ strtoupper($bimbingan->status) }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>NIM</strong></div>
                                <div class="col-md-9">{{ $bimbingan->mahasiswa->nim }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Nama</strong></div>
                                <div class="col-md-9">{{ $bimbingan->mahasiswa->nama }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Prodi</strong></div>
                                <div class="col-md-9">{{ $bimbingan->mahasiswa->prodi }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Judul KP</strong></div>
                                <div class="col-md-9">{{ $pengajuan ? $pengajuan->judul : '-' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Dosen Pembimbing</strong></div>
                                <div class="col-md-9">{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}</div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Bagian Bimbingan</strong></div>
                                <div class="col-md-9">
                                    <span class="badge bg-primary">{{ $bimbingan->bagian->bagian }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Tanggal Bimbingan</strong></div>
                                <div class="col-md-9">{{ $bimbingan->tanggal_bimbingan ? date('d M Y H:i', strtotime($bimbingan->tanggal_bimbingan)) : '-' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Tanggal ACC</strong></div>
                                <div class="col-md-9">
                                    @if ($bimbingan->tanggal_acc)
                                        <span class="text-success">{{ date('d M Y H:i', strtotime($bimbingan->tanggal_acc)) }}</span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            {{-- File Bimbingan (untuk mode online) --}}
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>File Bimbingan</strong></div>
                                <div class="col-md-9">
                                    @if ($bimbingan->lampiran)
                                        <a href="{{ storage_url($bimbingan->lampiran) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($bimbingan->lampiran) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            {{-- File ACC Manual (untuk mode manual/offline) --}}
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>File ACC Manual</strong></div>
                                <div class="col-md-9">
                                    @if ($bimbingan->lampiran_acc)
                                        <a href="{{ storage_url($bimbingan->lampiran_acc) }}" target="_blank" class="text-primary">
                                            <i class="fas fa-paperclip"></i> {{ basename($bimbingan->lampiran_acc) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            @if ($bimbingan->keterangan)
                            <div class="row mb-3">
                                <div class="col-md-3"><strong>Keterangan</strong></div>
                                <div class="col-md-9">{!! nl2br($bimbingan->keterangan) !!}</div>
                            </div>
                            @endif

                            <div class="mt-3 text-secondary">
                                <i class="fas fa-calendar mr-2"></i> Disubmit: {{ $bimbingan->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">{{ count($revisis) }}</span>
                            </h3>
                            <div class="card-tools">
                                {{ $revisis->links() }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                @forelse ($revisis as $revisi)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">
                                                @if ($revisi->reviewer_type == 'prodi' && $revisi->prodi)
                                                    <span class="badge bg-info">Prodi {{ $revisi->prodi->namaprodi }}</span>
                                                @elseif ($revisi->dosen)
                                                    {{ $revisi->dosen->nama . ', ' . $revisi->dosen->gelar }}
                                                @else
                                                    Admin
                                                @endif
                                            </span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:i a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br($revisi->catatan) !!}
                                            @if($revisi->tanggal_bimbingan)
                                                <div>
                                                    <small><i class="fas fa-calendar"></i> Tanggal bimbingan: {{ \Carbon\Carbon::parse($revisi->tanggal_bimbingan)->format('d M Y H:i a') }}</small>
                                                </div>
                                            @endif
                                            @if ($revisi->lampiran || $revisi->lampiran_revisi)
                                                <div class="p-1 mt-3 bg-light rounded">
                                                    @if ($revisi->lampiran_revisi)
                                                        <div>
                                                            <small>
                                                                <span class="text-secondary ml-2"><b>Lampiran revisi: </b></span>
                                                                <a href="{{ storage_url($revisi->lampiran_revisi) }}" target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    {{ basename($revisi->lampiran_revisi) }}
                                                                </a>
                                                            </small>
                                                        </div>
                                                    @endif
                                                    @if ($revisi->lampiran)
                                                        <small>
                                                            <span class="text-secondary ml-2"><b>Lampiran bimbingan sebelumnya: </b></span>
                                                            <a href="{{ storage_url($revisi->lampiran) }}" target="_blank">
                                                                <i class="fas fa-paperclip ml-1"></i>
                                                                {{ basename($revisi->lampiran) }}
                                                            </a>
                                                        </small>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Belum ada revisi</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection




