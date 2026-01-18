@extends('ta.layouts.dashboard')

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
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Mahasiswa</span>
                            <span class="info-box-number">
                                {{ count($mahasiswas) }} Mahasiswa
                            </span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('mahasiswas') }}" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Dosen</span>
                            <span class="info-box-number">
                                {{ count($dosens) }} Dosen
                            </span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('dosens') }}" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Prodi</span>
                            <span class="info-box-number">{{ count($prodis) }} Prodi</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('prodis') }}" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Fakultas</span>
                            <span class="info-box-number">{{ count($fakultas) }} Fakultas</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('fakultas') }}" class="small-box-footer text-primary">More info
                                    <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pengajuan TA</span>
                            <span class="info-box-number">
                                {{ count($pengajuans) }} Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Pendaftaran TA</span>
                            <span class="info-box-number">
                                {{ count($pendaftarans) }} Mahasiswa
                            </span>
                        </div>
                    </div>
                </div>


                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Seminar Proposal</span>
                            <span class="info-box-number">{{ count($seminars) }} Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Ujian Pendadaran</span>
                            <span class="info-box-number">{{ count($ujians)}} Mahasiswa</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-secondary">
                            Pengajuan TA Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pengajuan Diterima
                                <span
                                    class="float-right"><b>{{ count($pengajuans_diterima) }}</b>/{{ count($pengajuans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        @if (count($pengajuans_diterima) != 0) style="width: {{ (count($pengajuans_diterima) / count($pengajuans)) * 100 }}%"
                                       @else
                                        style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Review
                                <span
                                    class="float-right"><b>{{ count($pengajuans_review) }}</b>/{{ count($pengajuans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        @if (count($pengajuans_review) != 0) style="width: {{ (count($pengajuans_review) / count($pengajuans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Revisi
                                <span
                                    class="float-right"><b>{{ count($pengajuans_revisi) }}</b>/{{ count($pengajuans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        @if (count($pengajuans_revisi) != 0) style="width: {{ (count($pengajuans_revisi) / count($pengajuans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pengajuan Ditolak
                                <span
                                    class="float-right"><b>{{ count($pengajuans_ditolak) }}</b>/{{ count($pengajuans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        @if (count($pengajuans_ditolak) != 0) style="width: {{ (count($pengajuans_ditolak) / count($pengajuans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-info">
                            Pendaftaran TA Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Pendaftaran Diterima
                                <span
                                    class="float-right"><b>{{ count($pendaftarans_diterima) }}</b>/{{ count($pendaftarans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success"
                                        @if (count($pendaftarans_diterima) != 0) style="width: {{ (count($pendaftarans_diterima) / count($pendaftarans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Review
                                <span
                                    class="float-right"><b>{{ count($pendaftarans_review) }}</b>/{{ count($pendaftarans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary"
                                        @if (count($pendaftarans_review) != 0) style="width: {{ (count($pendaftarans_review) / count($pendaftarans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Pendaftaran Revisi
                                <span
                                    class="float-right"><b>{{ count($pendaftarans_revisi) }}</b>/{{ count($pendaftarans) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning"
                                        @if (count($pendaftarans_revisi) != 0) style="width: {{ (count($pendaftarans_revisi) / count($pendaftarans)) * 100 }}%"
                                    @else
                                     style="width: 0%" @endif>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-primary">
                            Seminar Proposal Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Seminar Proposal Diterima
                                <span class="float-right"><b>{{ count($seminars_diterima) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ count($seminars_diterima) != 0 ? (count($seminars_diterima) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Proposal Review
                                <span class="float-right"><b>{{ count($seminars_review) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: {{ count($seminars_review) != 0 ? (count($seminars_review) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Seminar Proposal Revisi
                                <span class="float-right"><b>{{ count($seminars_revisi) }}</b>/{{ count($seminars) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ count($seminars_revisi) != 0 ? (count($seminars_revisi) / count($seminars)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" style="min-height: 16rem">
                        <div class="card-header bg-success">
                            Ujian Pendadaran Berdasarkan Status
                        </div>
                        <div class="card-body">

                            <div class="progress-group">
                                Ujian Pendadaran Diterima
                                <span class="float-right"><b>{{ count($ujians_diterima) }}</b>/{{ count($ujians) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: {{ count($ujians_diterima) != 0 ? (count($ujians_diterima) / count($ujians)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Ujian Pendadaran Review
                                <span class="float-right"><b>{{ count($ujians_review) }}</b>/{{ count($ujians) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-secondary" style="width: {{ count($ujians_review) != 0 ? (count($ujians_review) / count($ujians)) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="progress-group">
                                Ujian Pendadaran Revisi
                                <span class="float-right"><b>{{ count($ujians_revisi) }}</b>/{{ count($ujians) }}</span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" style="width: {{ count($ujians_revisi) != 0 ? (count($ujians_revisi) / count($ujians)) * 100 : 0 }}%">
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




