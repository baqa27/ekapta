@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Seminar TA</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pendaftaran Seminar TA</a></li>
                        <li class="breadcrumb-item active">Form Pendaftaran Seminar TA</li>
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
                            <h3 class="card-title">Form Pendaftaran Seminar TA</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ta.seminar.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

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
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="{{ $pengajuan_acc->judul }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tanggal Pembuatan Tugas Akhir</label>
                                    <input type="text" class="form-control"
                                        value="{{ \Carbon\Carbon::parse($pengajuan_acc->created_at)->formatLocalized('%d %B %Y') }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Lunas Pembayaran SPP Sampai Semester
                                        Terakhir & Bukti Lunas Pembayaran SPP Variabel Khusus Kelas B / Ekstensi
                                        (Pegawai) <br> <small>Dijadikan 1 file PDF / JPEG</small> </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="lampiran_1"
                                                @error('lampiran_1') is-invalid @enderror required>
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
                                    <label for="exampleInputEmail1">Bukti Lunas Pembayaran Seminar Tugas Akhir (TA) <b>Rp
                                            100.000</b>
                                        <br>
                                        <small>Pembayaran Seminar Tugas Akhir (TA) ke Juru bayar FASTIKOM (Mas Harri) di
                                            kantor FASTIKOM atau bisa transfer melalui Bank BRI No. <b>011201103039505</b>
                                            a.n. <b>Harri Kurniawan R</b>.</small>
                                    </label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="lampiran_2"
                                                @error('lampiran_2') is-invalid @enderror required>
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
                                    <label for="exampleInputFile">File Laporan Proposal <br><small>Sudah di ACC Pembimbing 1
                                            dan 2</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="lampiran_3"
                                                @error('lampiran_3') is-invalid @enderror required>
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
                                    <label for="exampleInputEmail1">Nomor Pembayaran <br> <small>(PBXXXX) yang
                                            tertera pada Bukti Bayar FASTIKOM (BUKAN NOMOR TRANSFER DARI
                                            BANK). Silahkan konfirmasi ke mas Harri (Telegram: <a
                                                href="tg://resolve?domain=harrrrrrrrrrr" target="_blank">@harrrrrrrrrrr</a>
                                            / WA: <a href="https://wa.me/6285643647643" target="_blank">085643647643</a>
                                            )</small></label>
                                    <input type="text"
                                        class="form-control @error('nomor_pembayaran') is-invalid @enderror"
                                        placeholder="Masukkan Nomor Pembayaran.." value="{{ old('nomor_pembayaran') }}"
                                        name="nomor_pembayaran" required>
                                    @error('nomor_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jumlah Pembayaran</label>
                                    <input type="number" class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                        placeholder="Masukkan Jumlah Pembayaran.." value="{{ old('jumlah_bayar') }}"
                                        name="jumlah_bayar" min="100000" max="100000" required>
                                    @error('jumlah_bayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label for="exampleInputFile">Scan Lembar Bimbingan TA Yang Telah di Acc. --}}
                                {{--                                        Oleh Dosen Pembimbing 1 dan 2</label> --}}
                                {{--                                    <div class="input-group mb-3"> --}}
                                {{--                                        <div class="custom-file"> --}}
                                {{--                                            <input type="file" class="custom-file-input" name="lampiran_4" --}}
                                {{--                                                @error('lampiran_4') is-invalid @enderror required> --}}
                                {{--                                            <label class="custom-file-label" for="exampleInputFile">Choose --}}
                                {{--                                                file</label> --}}
                                {{--                                        </div> --}}
                                {{--                                        <div class="input-group-append"> --}}
                                {{--                                            <span class="input-group-text">Dokumen</span> --}}
                                {{--                                        </div> --}}
                                {{--                                    </div> --}}
                                {{--                                    @error('lampiran_4') --}}
                                {{--                                        <small class="text-danger" --}}
                                {{--                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small> --}}
                                {{--                                    @enderror --}}
                                {{--                                </div> --}}

                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label for="exampleInputFile">Scan Lembar Persetujuan</label> --}}
                                {{--                                    <div class="input-group mb-3"> --}}
                                {{--                                        <div class="custom-file"> --}}
                                {{--                                            <input type="file" class="custom-file-input" name="lampiran_5" --}}
                                {{--                                                @error('lampiran_5') is-invalid @enderror required> --}}
                                {{--                                            <label class="custom-file-label" for="exampleInputFile">Choose --}}
                                {{--                                                file</label> --}}
                                {{--                                        </div> --}}
                                {{--                                        <div class="input-group-append"> --}}
                                {{--                                            <span class="input-group-text">Dokumen</span> --}}
                                {{--                                        </div> --}}
                                {{--                                    </div> --}}
                                {{--                                    @error('lampiran_5') --}}
                                {{--                                        <small class="text-danger" --}}
                                {{--                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small> --}}
                                {{--                                    @enderror --}}
                                {{--                                </div> --}}

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




