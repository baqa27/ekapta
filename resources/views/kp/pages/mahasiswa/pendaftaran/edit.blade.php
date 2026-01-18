@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kp.pendaftaran.update') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $pendaftaran->id }}">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" value="{{ $mahasiswa->prodi }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Dosen Pembimbing Kerja Praktik</label>
                                    <input type="text" class="form-control" value="{{ $dosen_pembimbing ? $dosen_pembimbing->nama.', '.$dosen_pembimbing->gelar : 'Belum ditentukan' }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Kerja Praktik</label>
                                    <input type="text" class="form-control" value="{{ $pendaftaran->pengajuan->judul }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Acc. Kaprodi <br>
                                        <small>Download disini : <a
                                                href="{{ route('kp.cetak.lembar.persetujuan.mahasiswa') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_1') is-invalid @enderror"
                                                name="lampiran_1">
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($pendaftaran->lampiran_1) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_1, 21) }}</a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bukti Lembar Pernyataan Keaslian Hasil TugasAkhir <br>
                                        <small>Download disini : <a href="{{ route('kp.cetak.lembar.pernyataan.keaslian') }}"
                                                target="_blank">Download</a></small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_2') is-invalid @enderror"
                                                name="lampiran_2">
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($pendaftaran->lampiran_2) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_2, 21) }}</a>
                                        </small>
                                    </div>
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
                                                name="lampiran_3">
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($pendaftaran->lampiran_3) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_3, 21) }}</a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pengumpulan KP <br>
                                        <small>bukti diminta di perpus FASTIKOM</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_4') is-invalid @enderror"
                                                name="lampiran_4">
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($pendaftaran->lampiran_4) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_4, 21) }}</a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Pembayaran Kerja Praktik <br>
                                        <small>Pembayaran Kerja Praktik (KP) ke Juru bayar FASTIKOM (Mas Harri) di kantor FASTIKOM atau bisa transfer melalui Bank BRI No. <b>011201103039505</b> a.n. Harri Kurniawan R.</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror"
                                                name="lampiran_5">
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
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ storage_url($pendaftaran->lampiran_5) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_5, 21) }}</a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Persetujuan Pembimbing (Bertanda Tangan)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_6') is-invalid @enderror"
                                                name="lampiran_6">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_6')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            @if ($pendaftaran->lampiran_6)
                                            <a href="{{ storage_url($pendaftaran->lampiran_6) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_6, 21) }}</a>
                                            @else
                                            <span class="text-muted ml-2">Belum ada file</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Diterima Instansi</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran_7') is-invalid @enderror"
                                                name="lampiran_7">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_7')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            @if ($pendaftaran->lampiran_7)
                                            <a href="{{ storage_url($pendaftaran->lampiran_7) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->lampiran_7, 21) }}</a>
                                            @else
                                            <span class="text-muted ml-2">Belum ada file</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Pendukung <br>
                                        <small>Upload 2 sertifikat peserta seminar KP (dijadikan 1 file PDF)</small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('dokumen_pendukung') is-invalid @enderror"
                                                name="dokumen_pendukung">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('dokumen_pendukung')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                    <div class="rounded bg-light">
                                        <small>
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            @if ($pendaftaran->dokumen_pendukung)
                                            <a href="{{ storage_url($pendaftaran->dokumen_pendukung) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($pendaftaran->dokumen_pendukung, 21) }}</a>
                                            @else
                                            <span class="text-muted ml-2">Belum ada file</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nomor Pembayaran <br> <small>(PBXXXX) yang
                                            tertera pada Bukti Bayar FASTIKOM (BUKAN NOMOR TRANSFER DARI
                                            BANK). Silahkan konfirmasi ke mas Harri (Telegram: <a href="tg://resolve?domain=harrrrrrrrrrr" target="_blank">@harrrrrrrrrrr</a> / WA: <a href="https://wa.me/6285643647643" target="_blank">085643647643</a> )</small></label>
                                    <input type="text"
                                        class="form-control @error('nomor_pembayaran') is-invalid @enderror"
                                        placeholder="Masukkan Nomor Pembayaran.."
                                        value="{{ $pendaftaran->nomor_pembayaran }}" name="nomor_pembayaran" required>
                                    @error('nomor_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembayaran <br>
                                        <small>Tanggal Pembayaran Sebelumnya :
                                            <b>{{ $pendaftaran->tanggal_pembayaran }}</b></small>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pembayaran') is-invalid @enderror"
                                        name="tanggal_pembayaran" value="{{ $pendaftaran->tanggal_pembayaran }}">
                                    @error('tanggal_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">BIAYA KERJA PRAKTIK (KP)
                                    </label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="300000"
                                            @if ($pendaftaran->biaya == 300000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Reguler (S1 & D3) : Rp. 300.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="150000"
                                            @if ($pendaftaran->biaya == 150000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Reguler (S1 & D3) : Rp. 150.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="350000"
                                            @if ($pendaftaran->biaya == 350000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Program
                                            Kelas Karyawan : Rp. 350.000,-</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input @error('biaya') is-invalid @enderror"
                                            type="radio" name="biaya" value="175000"
                                            @if ($pendaftaran->biaya == 175000) checked @endif>
                                        <label class="form-check-label" style="top: -1px; position:relative;">Perpanjang
                                            Program Kelas Karyawan : Rp. 175.000,-</label>
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




