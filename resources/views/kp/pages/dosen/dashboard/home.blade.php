@extends('kp.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Semua Bimbingan</span>
                            <span class="info-box-number">
                                {{ count($bimbingans) }} Bimbingan
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Review</span>
                            <span class="info-box-number">
                                {{ count($bimbingans_review) }} Bimbingan
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Revisi</span>
                            <span class="info-box-number"> {{ count($bimbingans_revisi) }} Bimbingan</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Bimbingan Diterima</span>
                            <span class="info-box-number"> {{ count($bimbingans_diterima) }} Bimbingan</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            Bimbingan KP Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Bimbingan Diterima
                                <span
                                    class="float-right"><b>{{ count($bimbingans_diterima) }}</b>/{{ count($bimbingans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        @if (count($bimbingans_diterima) != 0) style="width: {{ (count($bimbingans_diterima) / count($bimbingans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Review
                                <span
                                    class="float-right"><b>{{ count($bimbingans_review) }}</b>/{{ count($bimbingans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        @if (count($bimbingans_review) != 0) style="width: {{ (count($bimbingans_review) / count($bimbingans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Bimbingan Revisi
                                <span
                                    class="float-right"><b>{{ count($bimbingans_revisi) }}</b>/{{ count($bimbingans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        @if (count($bimbingans_revisi) != 0) style="width: {{ (count($bimbingans_revisi) / count($bimbingans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
@endsection




