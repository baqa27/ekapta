@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Hai! {{ Auth::guard('mahasiswa')->user()->nama }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">

            <!-- Alur Ekapta -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Alur Pengajuan Tugas Akhir</h3>
                        </div>
                        <div class="card-body">
                            <div id="accordion">
                                <!-- Pengajuan -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                                <span class="badge bg-white textprimary">1</span> Pengajuan TA
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Mahasiswa mengajukan judul TA dengan mengisi form ajuan
                                                    diantaranya: Judul TA, deskripsi,
                                                    dan upload file
                                                </li>
                                                <li>
                                                    Jika status ajuan Diterima maka bisa melanjutkan ke proses
                                                    pendaftaran. Jika status Revisi
                                                    maka dapat dapat merevisi ajuan. Jika status Ditolak maka
                                                    harus membuat ajuan baru.
                                                </li>
                                                <li>
                                                    Setelah status ajuan diterima, mahasiswa dapat mencetak
                                                    Lembar Persetujuan Pembimbing yang
                                                    harus ditandatangani oleh calon dosen pembimbing, kemudian
                                                    diupload di Form Pendaftaran
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pendaftaran -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                                <span class="badge bg-white textprimary">2</span> Pendaftaran TA
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Pendaftaran hanya bisa dilakukan Jika status ajuan diterima
                                                </li>
                                                <li>
                                                    Mahasiswa mengisi formular pendaftaran dan mengupload
                                                    beberapa dokumen termasuk lembar
                                                    persetujuan calon dosen pembimbing yang sudah ditandatangani
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Bimbingan -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                                                <span class="badge bg-white textprimary">3</span> Bimbingan
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Mahaiswa memulai bimbingan dengan memilih bagian (missal Bab
                                                    I) kemudian mahasiswa upload
                                                    file bimbingan Bab I
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Setelah direview
                                                Dosen :</p>
                                            <ul>
                                                <li>
                                                    Jika status dari dosen revisi, maka mahasiswisa memperbaiki
                                                    laporannya dan bisa upload
                                                    Kembali sampai status dari dosen Diterima
                                                </li>
                                                <li>
                                                    Setelah Bab I diterima, maka bisa melanjutkan bimbingan ke
                                                    bab II dst
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Seminar -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseFour">
                                                <span class="badge bg-white textprimary">4</span> Seminar
                                                Proposal
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Syarat untuk mendaftar seminar proposal, harus sudah acc bab
                                                    I sampai bab III
                                                </li>
                                                <li>
                                                    Mahasiswa mendaftara seminar dengan mengisi form pendaftaran, dokumen yang diupload adalah:
                                                    <ol>
                                                        <li>Bukti Lunas Pembayaran SPP Sampai Semester Terakhir</li>
                                                        <li>Bukti Lunas Pembayaran Seminar Tugas Akhir (TA)</li>
                                                        <li>File Laporan Proposal</li>
                                                    </ol>
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Setelah Seminar
                                                Proposal :</p>
                                            <ul>
                                                <li>
                                                    Mahasiswa upload bimbingan revisi seminar proposal ditujukan
                                                    ke dosen penguji
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Ujian -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseFive">
                                                <span class="badge bg-white textprimary">5</span> Ujian
                                                Pendadaran
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Syarat untuk mendaftar ujian pendadaran, harus sudah acc
                                                    semua bab, produk, dan artikel
                                                </li>
                                                <li>
                                                    Mahasiswa mendaftar ujian pendadaran dengan mengisi form
                                                    pendaftaran, dokumen yang diupload adalah:
                                                    <ol>
                                                        <li>Bukti lunas pembayaran SPP sampai semester terakhir</li>
                                                        <li>Bukti lunas pembayaran Tugas Akhir</li>
                                                        <li>Scan ijazah terakhir yang asli</li>
                                                        <li>Scan KTP / Kartu Keluarga</li>
                                                        <li>Scan Sertifikat TOEFL</li>
                                                        <li>Scan Sertifikat Tahfidz</li>
                                                        <li>Scan Sertifikat Komputer</li>
                                                        <li>Transkrip Nilai Sementara (Tanpa nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)</li>
                                                        <li>Laporan Skripsi</li>
                                                    </ol>
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Setelah Ujian
                                                Pendadaran :</p>
                                            <ul>
                                                <li>
                                                    Mahasiswa upload bimbingan revisi ujian pendadaran ditujukan
                                                    ke dosen penguji
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            {{-- Timeline Pengajuan TA --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Timeline Tugas Akhir</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                <div class="timeline-step">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2003">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Pengajuan Judul TA</p>
                                        @if ($mahasiswa->pengajuans()->first())
                                            @if ($pengajuan_acc)
                                                <div class="bg-soft-success text-success rounded mt-3">Selesai</div>
                                            @else
                                                <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                            @endif
                                        @else
                                            <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-step">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2004">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Pendaftaran TA</p>
                                        @if ($pengajuan_acc)
                                            @if ($pendaftaran_acc)
                                                <div class="bg-soft-success text-success rounded mt-3">Selesai</div>
                                            @else
                                                <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                            @endif
                                        @else
                                            <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-step">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2005">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Bimbingan TA</p>
                                        @if($is_bimbingan_completed === true)
                                            <div class="bg-soft-success text-success rounded mt-3">Selesai</div>
                                        @else
                                            <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-step">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2010">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Seminar Proposal</p>
                                        @if($is_seminar_completed === true)
                                            <div class="bg-soft-success text-success rounded mt-3">Selesai</div>
                                        @else
                                            <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-step mb-0">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2020">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Ujian Pendadaran</p>
                                        @if($is_ujian_completed === true)
                                            <div class="bg-soft-success text-success rounded mt-3">Selesai</div>
                                        @else
                                            <div class="bg-soft-warning text-warning rounded mt-3">Belum Selesai</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.content -->
    @endsection




