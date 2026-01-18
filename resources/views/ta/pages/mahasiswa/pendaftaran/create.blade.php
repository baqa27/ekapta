@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
                        <li class="breadcrumb-item active">Form Pendaftaran TA</li>
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
                        <div class="card-header">
                            <h3 class="card-title">Form Pendaftaran Tugas Akhir</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ta.pendaftaran.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->nim }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->nama }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control"
                                        value="{{ Auth::guard('mahasiswa')->user()->prodi }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Utama (1) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Pembimbing Pendamping (2) Tugas Akhir <br>
                                    </label>
                                    <input type="text" class="form-control"
                                        value="{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan->judul }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Acc. Kaprodi <br>
                                        <small>Download disini : <a
                                                href="{{ route('ta.cetak.lembar.persetujuan.mahasiswa') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_1') is-invalid @enderror"
                                                name="lampiran_1" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_1')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bukti Lembar Pernyataan Keaslian Hasil Tugas
                                        Akhir
                                        <br>
                                        <small>Download disini : <a href="{{ route('ta.cetak.lembar.pernyataan.keaslian') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_2') is-invalid @enderror"
                                                name="lampiran_2" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_2')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Transkrip Nilai <br>
                                        <small>Jika SKS lulus lebih dari 120 maka pengajuan Surat Tugas
                                            Pembimbing akan diproses namun jika belum memenuhi 120 sks lulus
                                            maka pengajuan akan dibatalkan.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_3') is-invalid @enderror"
                                                name="lampiran_3" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_3')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pengumpulan KP <br>
                                        <small>bukti diminta di perpus FASTIKOM</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_4') is-invalid @enderror"
                                                name="lampiran_4" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_4')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Tugas Akhir <br>
                                        <small>Pembayaran Tugas Akhir (TA) ke Juru bayar FASTIKOM (Mas Harri) di kantor FASTIKOM atau bisa transfer melalui Bank BRI No. <b>011201103039505</b> a.n. <b>Harri Kurniawan R</b>.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror"
                                                name="lampiran_5" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_5')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nomor Pembayaran<br> <small>(PBXXXX) yang
                                            tertera pada Bukti Bayar FASTIKOM (BUKAN NOMOR TRANSFER DARI
                                            BANK). Silahkan konfirmasi ke mas Harri (Telegram: <a href="tg://resolve?domain=harrrrrrrrrrr" target="_blank">@harrrrrrrrrrr</a> / WA: <a href="https://wa.me/6285643647643" target="_blank">085643647643</a> ).</small></label>
                                    <input type="text"
                                        class="form-control @error('nomor_pembayaran') is-invalid @enderror"
                                        placeholder="Masukkan Nomor Pembayaran.." value="{{ old('nomor_pembayaran') }}"
                                        name="nomor_pembayaran">
                                    @error('nomor_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                        name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran') }}"
                                        placeholder="Tanggal Pembayaran.." required>
                                    @error('tanggal_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">BIAYA TUGAS AKHIR (TA) / SKRIPSI
                                    </label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="750000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Sarjana Kelas A
                                            (Reguler) : Rp. 750.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="375000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Sarjana
                                            Kelas A (Reguler) : Rp. 375.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="850000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Sarjana Kelas B
                                            (Ekstensi) : Rp. 850.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="425000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Sarjana
                                            Kelas B (Ekstensi) : Rp. 425.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="650000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Diploma Kelas A
                                            (Reguler) : Rp. 650.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="325000">
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Diploma
                                            Kelas A (Reguler) : Rp. 325.000,-</label>
                                    </div>
                                    @error('biaya')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




