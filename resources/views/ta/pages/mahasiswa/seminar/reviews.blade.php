@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Review Seminar TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                @foreach ($seminar->reviews as $review)
                    @if ($review->dosen_status == 'penguji')
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div
                                        class="ribbon
                                @if ($review->status == 'diterima') bg-success
                                @elseif($review->status == 'revisi')
                                bg-warning
                                @elseif($review->status == 'review')
                                bg-secondary
                                @else
                                bg-danger @endif
                                ">
                                        @if ($review->status == 'diterima')
                                            Diterima
                                        @elseif($review->status == 'revisi')
                                            Revisi
                                        @elseif($review->status == 'review')
                                            Review
                                        @else
                                            Belum Submit
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">
                                    Dosen Penguji : <br>
                                    <b>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</b> <br><br>

                                    {{-- Reviews --}}
                                    Catatan Dosen <span class="badge bg-danger"> {{ count($review->revisis) }}
                                    </span><br><br>
                                    <div class="p-2 rounded reviews-box">
                                        @foreach ($review->revisis()->orderBy('created_at', 'desc')->get() as $revisi)
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">{{ $review->dosen->nama }},
                                                        {{ $review->dosen->gelar }}</span>
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

                                @if($review->status == 'review')
                                    <div class="card-footer">
                                       @if ($review->tanggal_acc_manual && $review->lampiran_lembar_revisi && $review->status =='review')
                                       <a href="#"
                                        class="btn btn-secondary col-md-12">
                                        <i class="bi bi-hourglass-bottom"></i> Submit Acc Manual Dalam Review Prodi
                                    </a>
                                       @else
                                       <a href="{{ route('ta.review.seminar.submit.acc.manual', $review->id) }}"
                                        class="btn btn-primary col-md-12">
                                        <i class="bi bi-upload"></i> Submit Acc Manual
                                    </a>
                                       @endif
                                    </div>
                                @elseif ($review->status == null || $review->status == 'revisi')
                                    <div class="card-footer">
                                        <a href="{{ route('review.seminar.edit', $review->id) }}"
                                            class="btn btn-primary col-md-12">
                                            <i class="bi bi-upload"></i> Submit Laporan Proposal
                                        </a>
                                    </div>
                                @elseif($review->status == 'review' || $review->status == 'diterima')
                                    <div class="card-footer">
                                        Keterangan :
                                        <div class="bg-secondary rounded p-2">{!! $review->keterangan !!}
                                            <div class="bg-light p-1 rounded mt-1">
                                                <small>
                                                    <b>Lampiran sebelumnya: </b>
                                                    <a href="{{ asset($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3) }}"
                                                        class="ml-3 text-primary" target="_blank"><i
                                                            class="fas fa-paperclip mr-2"></i>
                                                        {{ Str::substr($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3, 40) }}</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




