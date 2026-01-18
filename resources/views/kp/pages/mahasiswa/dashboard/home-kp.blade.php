@extends('kp.layouts.dashboardMahasiswa')

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
                            <h3 class="card-title">Alur Kerja Praktek (KP)</h3>
                        </div>
                        <div class="card-body">
                            <div id="accordion">
                                <!-- Pengajuan -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                                <span class="badge bg-white textprimary">1</span> Pengajuan KP
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Mahasiswa mengajukan judul KP dengan mengisi form ajuan:
                                                    <ol>
                                                        <li>Judul KP</li>
                                                        <li>Lokasi KP</li>
                                                        <li>Alamat instansi</li>
                                                        <li>Gambaran singkat masalah + solusi</li>
                                                    </ol>
                                                </li>
                                                <li>
                                                    Upload Bukti diterima instansi dan File pendukung (opsional).
                                                </li>
                                                <li>
                                                    Jika status ajuan Diterima maka bisa melanjutkan ke proses
                                                    pendaftaran. Jika status Revisi maka dapat merevisi ajuan.
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
                                                <span class="badge bg-white textprimary">2</span> Pendaftaran KP & Surat Tugas
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Mahasiswa mencetak Lembar Persetujuan Pembimbing, meminta tanda tangan, lalu upload kembali.
                                                </li>
                                                <li>
                                                    Upload dokumen pendaftaran: ACC Kaprodi, Transkrip, Sertifikat KKL, Bukti Aktif, Bukti Bayar, Sertifikat Peserta KP.
                                                </li>
                                                <li>
                                                    Setelah Validasi Admin, Surat Tugas dan Lembar Bimbingan akan diterbitkan otomatis.
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
                                                <span class="badge bg-white textprimary">3</span> Bimbingan KP
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseThree" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Mahasiswa memulai bimbingan dengan memilih bagian (misal Bab I) kemudian upload file bimbingan Bab I
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Setelah direview Dosen:</p>
                                            <ul>
                                                <li>
                                                    Jika status dari dosen revisi, maka mahasiswa memperbaiki laporannya dan bisa upload kembali sampai status dari dosen Diterima
                                                </li>
                                                <li>
                                                    Setelah Bab I diterima, maka bisa melanjutkan bimbingan ke bab II dst
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
                                                <span class="badge bg-white textprimary">4</span> Seminar KP
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFour" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Syarat untuk mendaftar Seminar KP, harus sudah acc bab I sampai bab III
                                                </li>
                                                <li>
                                                    Mahasiswa mendaftar seminar dengan mengisi form pendaftaran, dokumen yang diupload adalah:
                                                    <ol>
                                                        <li>Bukti Lunas Pembayaran Seminar KP</li>
                                                        <li>File Laporan Final (PDF)</li>
                                                        <li>File Lembar Pengesahan</li>
                                                        <li>4 Sertifikat Seminar/Pelatihan</li>
                                                    </ol>
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Setelah Seminar KP:</p>
                                            <ul>
                                                <li>
                                                    Revisi Pasca Seminar (Upload Laporan Revisi + Bukti Perbaikan).
                                                </li>
                                                <li>
                                                    Upload Nilai KP dari Instansi.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- Jilid KP -->
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block w-100" data-toggle="collapse" href="#collapseFive">
                                                <span class="badge bg-white textprimary">5</span> Jilid KP
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseFive" class="collapse" data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    Syarat: Revisi Seminar beres & semua tahap terpenuhi.
                                                </li>
                                                <li>
                                                    Mahasiswa melengkapi semua file final ke sistem untuk diverifikasi Admin.
                                                </li>
                                            </ul>
                                            <ul>
                                                <li>
                                                    Wajib upload final:
                                                    <ol>
                                                        <li>Laporan Word & PDF Final</li>
                                                        <li>Lembar Pengesahan Final</li>
                                                        <li>File Project & Panduan</li>
                                                        <li>Berita Acara Serah Terima Produk</li>
                                                        <li>Form Nilai KP Lengkap</li>
                                                    </ol>
                                                </li>
                                            </ul>
                                            <p class="fw-semibold" style="margin-left: 18px;">Status KP Selesai setelah dokumen divalidasi Admin.</p>
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

            {{-- Timeline Pengajuan KP --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Timeline Kerja Praktek</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                <div class="timeline-step">
                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover"
                                        data-placement="top" title=""
                                        data-content="And here's some amazing content. It's very engaging. Right?"
                                        data-original-title="2003">
                                        <div class="inner-circle"></div>
                                        <p class="h6 mt-3 mb-1">Pengajuan Judul KP</p>
                                        @if ($mahasiswa->pengajuansKP()->first())
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
                                        <p class="h6 mt-3 mb-1">Pendaftaran KP</p>
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
                                        <p class="h6 mt-3 mb-1">Bimbingan KP</p>
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
                                        <p class="h6 mt-3 mb-1">Seminar KP</p>
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
                                        <p class="h6 mt-3 mb-1">Jilid KP</p>
                                        @if($is_pengumpulan_akhir_completed === true)
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




