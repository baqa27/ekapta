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
            {{-- MASTER DATA --}}
            {{-- ============================================== --}}
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Mahasiswa</span>
                            <span class="info-box-number">{{ count($mahasiswas) }} Mahasiswa</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('mahasiswas') }}" class="small-box-footer text-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Dosen</span>
                            <span class="info-box-number">{{ count($dosens) }} Dosen</span>
                            <small class="bg-light d-flex justify-content-center">
                                <a href="{{ route('dosens') }}" class="small-box-footer text-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                                <a href="{{ route('prodis') }}" class="small-box-footer text-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                                <a href="{{ route('fakultas') }}" class="small-box-footer text-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================== --}}
            {{-- SECTION TUGAS AKHIR --}}
            {{-- ============================================== --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-graduation-cap mr-2"></i> Tugas Akhir (TA)</h3>
                </div>
                <div class="card-body">
                    {{-- Info Box TA --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengajuan TA</span>
                                    <span class="info-box-number">{{ count($ta_pengajuans) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendaftaran TA</span>
                                    <span class="info-box-number">{{ count($ta_pendaftarans) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Seminar Proposal</span>
                                    <span class="info-box-number">{{ count($ta_seminars) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ujian Pendadaran</span>
                                    <span class="info-box-number">{{ count($ta_ujians) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card TA --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-secondary">Pengajuan TA Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Pengajuan Diterima
                                        <span class="float-right"><b>{{ count($ta_pengajuans_diterima) }}</b>/{{ count($ta_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($ta_pengajuans) > 0 ? (count($ta_pengajuans_diterima) / count($ta_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Review
                                        <span class="float-right"><b>{{ count($ta_pengajuans_review) }}</b>/{{ count($ta_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($ta_pengajuans) > 0 ? (count($ta_pengajuans_review) / count($ta_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Revisi
                                        <span class="float-right"><b>{{ count($ta_pengajuans_revisi) }}</b>/{{ count($ta_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($ta_pengajuans) > 0 ? (count($ta_pengajuans_revisi) / count($ta_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Ditolak
                                        <span class="float-right"><b>{{ count($ta_pengajuans_ditolak) }}</b>/{{ count($ta_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" style="width: {{ count($ta_pengajuans) > 0 ? (count($ta_pengajuans_ditolak) / count($ta_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-info">Pendaftaran TA Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Pendaftaran Diterima
                                        <span class="float-right"><b>{{ count($ta_pendaftarans_diterima) }}</b>/{{ count($ta_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($ta_pendaftarans) > 0 ? (count($ta_pendaftarans_diterima) / count($ta_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pendaftaran Review
                                        <span class="float-right"><b>{{ count($ta_pendaftarans_review) }}</b>/{{ count($ta_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($ta_pendaftarans) > 0 ? (count($ta_pendaftarans_review) / count($ta_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pendaftaran Revisi
                                        <span class="float-right"><b>{{ count($ta_pendaftarans_revisi) }}</b>/{{ count($ta_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($ta_pendaftarans) > 0 ? (count($ta_pendaftarans_revisi) / count($ta_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-primary">Seminar Proposal Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Seminar Proposal Diterima
                                        <span class="float-right"><b>{{ count($ta_seminars_diterima) }}</b>/{{ count($ta_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($ta_seminars) > 0 ? (count($ta_seminars_diterima) / count($ta_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Seminar Proposal Review
                                        <span class="float-right"><b>{{ count($ta_seminars_review) }}</b>/{{ count($ta_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($ta_seminars) > 0 ? (count($ta_seminars_review) / count($ta_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Seminar Proposal Revisi
                                        <span class="float-right"><b>{{ count($ta_seminars_revisi) }}</b>/{{ count($ta_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($ta_seminars) > 0 ? (count($ta_seminars_revisi) / count($ta_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-success">Ujian Pendadaran Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Ujian Pendadaran Diterima
                                        <span class="float-right"><b>{{ count($ta_ujians_diterima) }}</b>/{{ count($ta_ujians) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($ta_ujians) > 0 ? (count($ta_ujians_diterima) / count($ta_ujians)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Ujian Pendadaran Review
                                        <span class="float-right"><b>{{ count($ta_ujians_review) }}</b>/{{ count($ta_ujians) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($ta_ujians) > 0 ? (count($ta_ujians_review) / count($ta_ujians)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Ujian Pendadaran Revisi
                                        <span class="float-right"><b>{{ count($ta_ujians_revisi) }}</b>/{{ count($ta_ujians) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($ta_ujians) > 0 ? (count($ta_ujians_revisi) / count($ta_ujians)) * 100 : 0 }}%"></div>
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
                    <h3 class="card-title"><i class="fas fa-briefcase mr-2"></i> Kerja Praktik (KP)</h3>
                </div>
                <div class="card-body">
                    {{-- Info Box KP --}}
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pengajuan KP</span>
                                    <span class="info-box-number">{{ count($kp_pengajuans) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendaftaran KP</span>
                                    <span class="info-box-number">{{ count($kp_pendaftarans) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix hidden-md-up"></div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Seminar KP</span>
                                    <span class="info-box-number">{{ count($kp_seminars) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jilid KP</span>
                                    <span class="info-box-number">{{ count($kp_pengumpulan_akhir) }} Mahasiswa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card KP --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-secondary">Pengajuan KP Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Pengajuan Diterima
                                        <span class="float-right"><b>{{ count($kp_pengajuans_diterima) }}</b>/{{ count($kp_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($kp_pengajuans) > 0 ? (count($kp_pengajuans_diterima) / count($kp_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Review
                                        <span class="float-right"><b>{{ count($kp_pengajuans_review) }}</b>/{{ count($kp_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($kp_pengajuans) > 0 ? (count($kp_pengajuans_review) / count($kp_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Revisi
                                        <span class="float-right"><b>{{ count($kp_pengajuans_revisi) }}</b>/{{ count($kp_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($kp_pengajuans) > 0 ? (count($kp_pengajuans_revisi) / count($kp_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pengajuan Ditolak
                                        <span class="float-right"><b>{{ count($kp_pengajuans_ditolak) }}</b>/{{ count($kp_pengajuans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-danger" style="width: {{ count($kp_pengajuans) > 0 ? (count($kp_pengajuans_ditolak) / count($kp_pengajuans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-info">Pendaftaran KP Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Pendaftaran Diterima
                                        <span class="float-right"><b>{{ count($kp_pendaftarans_diterima) }}</b>/{{ count($kp_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($kp_pendaftarans) > 0 ? (count($kp_pendaftarans_diterima) / count($kp_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pendaftaran Review
                                        <span class="float-right"><b>{{ count($kp_pendaftarans_review) }}</b>/{{ count($kp_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($kp_pendaftarans) > 0 ? (count($kp_pendaftarans_review) / count($kp_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Pendaftaran Revisi
                                        <span class="float-right"><b>{{ count($kp_pendaftarans_revisi) }}</b>/{{ count($kp_pendaftarans) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($kp_pendaftarans) > 0 ? (count($kp_pendaftarans_revisi) / count($kp_pendaftarans)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-primary">Seminar KP Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Seminar KP Diterima
                                        <span class="float-right"><b>{{ count($kp_seminars_diterima) }}</b>/{{ count($kp_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($kp_seminars) > 0 ? (count($kp_seminars_diterima) / count($kp_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Seminar KP Review
                                        <span class="float-right"><b>{{ count($kp_seminars_review) }}</b>/{{ count($kp_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($kp_seminars) > 0 ? (count($kp_seminars_review) / count($kp_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Seminar KP Revisi
                                        <span class="float-right"><b>{{ count($kp_seminars_revisi) }}</b>/{{ count($kp_seminars) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($kp_seminars) > 0 ? (count($kp_seminars_revisi) / count($kp_seminars)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card" style="min-height: 16rem">
                                <div class="card-header bg-success">Jilid KP Berdasarkan Status</div>
                                <div class="card-body">
                                    <div class="progress-group">
                                        Jilid KP Diterima
                                        <span class="float-right"><b>{{ count($kp_pengumpulan_akhir_diterima) }}</b>/{{ count($kp_pengumpulan_akhir) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success" style="width: {{ count($kp_pengumpulan_akhir) > 0 ? (count($kp_pengumpulan_akhir_diterima) / count($kp_pengumpulan_akhir)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Jilid KP Review
                                        <span class="float-right"><b>{{ count($kp_pengumpulan_akhir_review) }}</b>/{{ count($kp_pengumpulan_akhir) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-secondary" style="width: {{ count($kp_pengumpulan_akhir) > 0 ? (count($kp_pengumpulan_akhir_review) / count($kp_pengumpulan_akhir)) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="progress-group">
                                        Jilid KP Revisi
                                        <span class="float-right"><b>{{ count($kp_pengumpulan_akhir_revisi) }}</b>/{{ count($kp_pengumpulan_akhir) }}</span>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-warning" style="width: {{ count($kp_pengumpulan_akhir) > 0 ? (count($kp_pengumpulan_akhir_revisi) / count($kp_pengumpulan_akhir)) * 100 : 0 }}%"></div>
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
