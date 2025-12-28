@extends('layouts.dashboard')

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
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Seminar KP</span>
                            <span class="info-box-number">
                                {{ count($seminars) }} Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Seminar Review</span>
                            <span class="info-box-number">
                                {{ count($seminars_review) }} Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Seminar Diterima</span>
                            <span class="info-box-number">{{ count($seminars_diterima) }} Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Seminar Revisi</span>
                            <span class="info-box-number">{{ count($seminars_revisi) }} Mahasiswa</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-info card-outline" style="min-height: 16rem">
                        <div class="card-header">
                            <h3 class="card-title">Seminar KP Berdasarkan Status</h3>
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Seminar Diterima
                                <span class="float-right"><b>{{ count($seminars_diterima) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ count($seminars) > 0 ? (count($seminars_diterima) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Review
                                <span class="float-right"><b>{{ count($seminars_review) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: {{ count($seminars) > 0 ? (count($seminars_review) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Revisi
                                <span class="float-right"><b>{{ count($seminars_revisi) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ count($seminars) > 0 ? (count($seminars_revisi) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-secondary card-outline" style="min-height: 16rem">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Panel Himpunan</h3>
                        </div>
                        <div class="card-body">
                            <p>Anda dapat mengelola:</p>
                            <ul>
                                <li><strong>Validasi Seminar KP</strong> - Review dan validasi pendaftaran seminar mahasiswa</li>
                                <li><strong>Penjadwalan Seminar</strong> - Atur jadwal seminar untuk mahasiswa yang sudah divalidasi</li>
                                <li><strong>Rekap Seminar</strong> - Lihat rekap semua data seminar KP</li>
                            </ul>
                            <p class="text-muted mt-3">Gunakan menu di sidebar untuk mengakses fitur-fitur tersebut.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
