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

            {{-- ============================================== --}}
            {{-- SECTION TUGAS AKHIR --}}
            {{-- ============================================== --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap mr-2"></i> Bimbingan Tugas Akhir (TA)</h3>
                </div>
                <div class="card-body">
                    {{-- Info Box TA --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Semua Bimbingan</span>
                                    <span class="info-box-number">{{ count($ta_bimbingans) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Review</span>
                                    <span class="info-box-number">{{ count($ta_bimbingans_review) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Revisi</span>
                                    <span class="info-box-number">{{ count($ta_bimbingans_revisi) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Diterima</span>
                                    <span class="info-box-number">{{ count($ta_bimbingans_diterima) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card TA --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">Bimbingan TA Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Bimbingan Diterima
                                        <span class="float-right"><b>{{ count($ta_bimbingans_diterima) }}</b>/{{ count($ta_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($ta_bimbingans) > 0 ? (count($ta_bimbingans_diterima) / count($ta_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Bimbingan Review
                                        <span class="float-right"><b>{{ count($ta_bimbingans_review) }}</b>/{{ count($ta_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($ta_bimbingans) > 0 ? (count($ta_bimbingans_review) / count($ta_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Bimbingan Revisi
                                        <span class="float-right"><b>{{ count($ta_bimbingans_revisi) }}</b>/{{ count($ta_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($ta_bimbingans) > 0 ? (count($ta_bimbingans_revisi) / count($ta_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================== --}}
            {{-- SECTION KERJA PRAKTIK --}}
            {{-- ============================================== --}}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i> Bimbingan Kerja Praktik (KP)</h3>
                </div>
                <div class="card-body">
                    {{-- Info Box KP --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Semua Bimbingan</span>
                                    <span class="info-box-number">{{ count($kp_bimbingans) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Review</span>
                                    <span class="info-box-number">{{ count($kp_bimbingans_review) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Revisi</span>
                                    <span class="info-box-number">{{ count($kp_bimbingans_revisi) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bimbingan Diterima</span>
                                    <span class="info-box-number">{{ count($kp_bimbingans_diterima) }} Bimbingan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card KP --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info">Bimbingan KP Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Bimbingan Diterima
                                        <span class="float-right"><b>{{ count($kp_bimbingans_diterima) }}</b>/{{ count($kp_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($kp_bimbingans) > 0 ? (count($kp_bimbingans_diterima) / count($kp_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Bimbingan Review
                                        <span class="float-right"><b>{{ count($kp_bimbingans_review) }}</b>/{{ count($kp_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($kp_bimbingans) > 0 ? (count($kp_bimbingans_review) / count($kp_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Bimbingan Revisi
                                        <span class="float-right"><b>{{ count($kp_bimbingans_revisi) }}</b>/{{ count($kp_bimbingans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($kp_bimbingans) > 0 ? (count($kp_bimbingans_revisi) / count($kp_bimbingans)) * 100 : 0 }}%"></div>
                                        </div>
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
