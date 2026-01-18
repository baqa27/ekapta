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
                        <li class="breadcrumb-item"><a href="#">Bimbingan TA</a></li>
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
                            @if ($bimbingan->status == 'review') bg-secondary
                            @elseif ($bimbingan->status == 'revisi')
                            bg-warning
                            @elseif ($bimbingan->status == 'diterima')
                            bg-success
                            @elseif ($bimbingan->status == 'ditolak')
                            bg-danger @endif
                            ">
                                {{ $bimbingan->status }}
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title"><strong>Bagian </strong>{{ $bimbingan->bagian->bagian }}</h3>
                        </div>
                        <div class="card-body">
                            <p><b>Keterangan</b></p>
                            {!! nl2br($bimbingan->keterangan) !!}
                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                {{ date('d M Y H:m', strtotime($bimbingan->tanggal_bimbingan)) }}
                            </div>
                            @if ($bimbingan->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    {{ date('d M Y H:m', strtotime($bimbingan->tanggal_acc)) }}
                                </div>
                            @endif
                            <hr>
                            <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($bimbingan->lampiran) }}" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i> {{ Str::substr($bimbingan->lampiran, 40) }}</a></p>
                        </div>
                        <!-- /.card-body -->
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
                                            <span
                                                class="direct-chat-name float-left">{{ $revisi->dosen->nama . ', ' . $revisi->dosen->gelar }}
                                            </span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:m a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br($revisi->catatan) !!}
                                            @if($revisi->tanggal_bimbingan)
                                                <div>
                                                    <small><i class="fas fa-calendar"></i> Tanggal bimbingan: {{ \Carbon\Carbon::parse($revisi->tanggal_bimbingan)->format('d M Y H:m a') }}</small>
                                                </div>
                                            @endif
                                            @if ($revisi->lampiran || $revisi->lampiran_revisi)
                                                <div class="p-1 mt-3 bg-light rounded">
                                                    @if ($revisi->lampiran_revisi)
                                                        <div>
                                                            <small>
                                                                <span class="text-secondary ml-2"><b>Lampiran revisi: </b></span>
                                                                <a href="{{ asset($revisi->lampiran_revisi) }}" target="_blank">
                                                                    <i class="fas fa-paperclip ml-1"></i>
                                                                    {{ Str::substr($revisi->lampiran_revisi, 40) }}
                                                                </a>
                                                            </small>
                                                        </div>
                                                    @endif
                                                    @if ($revisi->lampiran)
                                                        <small>
                                                            <span class="text-secondary ml-2"><b>Lampiran bimbingan sebelumnya: </b></span>
                                                            <a href="{{ asset($revisi->lampiran) }}" target="_blank">
                                                                <i class="fas fa-paperclip ml-1"></i>
                                                                {{ Str::substr($revisi->lampiran, 40) }}
                                                            </a>
                                                        </small>
                                                    @endif
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
@endsection




