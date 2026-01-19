@extends('layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pilih Sistem</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pilih Sistem</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">

            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="https://unsiq.ac.id/img/UNSIQ-bunder.ico" alt="UNSIQ Logo" style="width: 70px; height: 70px;">
                                </div>
                                <div class="col">
                                    <h4 class="mb-1">Selamat Datang, <strong>{{ Auth::guard('mahasiswa')->user()->nama }}</strong></h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-id-card mr-1"></i> {{ Auth::guard('mahasiswa')->user()->nim }} &bull;
                                        <i class="fas fa-graduation-cap ml-2 mr-1"></i> {{ Auth::guard('mahasiswa')->user()->prodi }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pilih Sistem Cards -->
            <div class="row">
                <!-- Kerja Praktik Card (KIRI) -->
                <div class="col-lg-6 col-md-6">
                    <div class="card card-primary card-outline card-outline-tabs h-100">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-briefcase mr-2"></i> Kerja Praktik (KP)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-primary mx-auto mb-3">
                                    <i class="fas fa-building fa-3x text-white"></i>
                                </div>
                                <h5 class="text-primary font-weight-bold">Sistem Elektronik Kerja Praktik</h5>
                                <p class="text-muted small">Pengelolaan KP secara Online</p>
                            </div>

                            <hr>

                            <div class="feature-list">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">1</span>
                                    <span>Pengajuan Judul KP</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">2</span>
                                    <span>Pendaftaran Kerja Praktik</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">3</span>
                                    <span>Bimbingan dengan Dosen</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">4</span>
                                    <span>Seminar KP</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">5</span>
                                    <span>Pengumpulan Laporan Akhir</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">6</span>
                                    <span>Penilaian Akhir KP</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('kp.dashboard.mahasiswa') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sistem Kerja Praktik
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tugas Akhir Card (KANAN) -->
                <div class="col-lg-6 col-md-6">
                    <div class="card card-primary card-outline card-outline-tabs h-100">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="fas fa-book mr-2"></i> Tugas Akhir (Skripsi)
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-primary mx-auto mb-3">
                                    <i class="fas fa-file-alt fa-3x text-white"></i>
                                </div>
                                <h5 class="text-primary font-weight-bold">Sistem Elektronik Tugas Akhir</h5>
                                <p class="text-muted small">Pengelolaan Skripsi secara Online</p>
                            </div>

                            <hr>

                            <div class="feature-list">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">1</span>
                                    <span>Pengajuan Judul TA</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">2</span>
                                    <span>Pendaftaran Tugas Akhir</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">3</span>
                                    <span>Bimbingan dengan Dosen</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">4</span>
                                    <span>Seminar Proposal</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">5</span>
                                    <span>Ujian Pendadaran</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">6</span>
                                    <span>Pengumpulan Jilid</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="{{ route('ta.dashboard.mahasiswa') }}" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sistem Tugas Akhir
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Callout -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info-circle mr-2"></i> Informasi</h5>
                        <p class="mb-0">
                            Pilih sistem sesuai dengan kebutuhan akademik Anda.
                            <strong>Tugas Akhir</strong> untuk mahasiswa yang sedang menyusun skripsi,
                            dan <strong>Kerja Praktik</strong> untuk mahasiswa yang sedang melaksanakan KP di instansi/perusahaan.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-outline-tabs .card-header {
            border-radius: 0.25rem 0.25rem 0 0;
        }
        .feature-list .badge {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
        }
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .btn-lg {
            padding: 12px 20px;
            font-size: 16px;
        }
    </style>
@endsection
