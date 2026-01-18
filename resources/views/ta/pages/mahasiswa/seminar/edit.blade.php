@extends('ta.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Seminar TA</a></li>
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
                            <form action="{{ route('ta.seminar.update', $seminar->id) }}" method="post"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="form-group">
                                    <label for="exampleInputEmail1">NIM</label>
                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->nim }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->nama }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Prodi</label>
                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->prodi }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul Tugas Akhir</label>
                                    <input type="text" class="form-control" value="{{ $seminar->pengajuan->judul }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Bukti Lunas Pembayaran SPP Sampai Semester
                                        Terakhir</label>
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
                                            <a href="{{ asset($seminar->lampiran_1) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($seminar->lampiran_1, 21) }}</a>
                                        </small>
                                    </div>
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
                                            <a href="{{ asset($seminar->lampiran_2) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($seminar->lampiran_2, 21) }}</a>
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Berkas File Tugas Akhir Lengkap</label>
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
                                            @if ($seminar->lampiran_3)
                                            <span class="ml-3">Lampiran sebelumnya : </span>
                                            <a href="{{ asset($seminar->lampiran_3) }}" class="text-primary"
                                                target="_blank"><i class="fas fa-paperclip ml-2"></i>
                                                {{ Str::substr($seminar->lampiran_3, 21) }}</a>
                                            @else
                                                <span class="text-danger">BELUM UPLOAD FILE LAPORAN PROPOSAL</span>
                                            @endif
                                        </small>
                                    </div>
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
                                        placeholder="Masukkan Nomor Pembayaran.." value="{{ $seminar->nomor_pembayaran }}"
                                        name="nomor_pembayaran" required>
                                    @error('nomor_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jumlah Pembayaran</label>
                                    <input type="number" class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                        placeholder="Masukkan Jumlah Pembayaran.." value="{{ $seminar->jumlah_bayar }}"
                                        name="jumlah_bayar" min="100000" max="100000" required>
                                    @error('jumlah_bayar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label for="exampleInputFile"> Scan Lembar Bimbingan TA Yang Telah di Acc</label> --}}
                                {{--                                    <div class="input-group mb-3"> --}}
                                {{--                                        <div class="custom-file"> --}}
                                {{--                                            <input type="file" --}}
                                {{--                                                class="custom-file-input @error('lampiran_4') is-invalid @enderror" --}}
                                {{--                                                name="lampiran_4"> --}}
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
                                {{--                                    <div class="rounded bg-light"> --}}
                                {{--                                        <small> --}}
                                {{--                                            <span class="ml-3">Lampiran sebelumnya : </span> --}}
                                {{--                                            <a href="{{ asset($seminar->lampiran_4) }}" class="text-primary" --}}
                                {{--                                                target="_blank"><i class="fas fa-paperclip ml-2"></i> --}}
                                {{--                                                {{ Str::substr($seminar->lampiran_4, 21) }}</a> --}}
                                {{--                                        </small> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}

                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label for="exampleInputFile">Scan Lembar Persetujuan</label> --}}
                                {{--                                    <div class="input-group mb-3"> --}}
                                {{--                                        <div class="custom-file"> --}}
                                {{--                                            <input type="file" --}}
                                {{--                                                class="custom-file-input @error('lampiran_5') is-invalid @enderror" --}}
                                {{--                                                name="lampiran_5"> --}}
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
                                {{--                                    <div class="rounded bg-light"> --}}
                                {{--                                        <small> --}}
                                {{--                                            <span class="ml-3">Lampiran sebelumnya : </span> --}}
                                {{--                                            <a href="{{ asset($seminar->lampiran_5) }}" class="text-primary" --}}
                                {{--                                                target="_blank"><i class="fas fa-paperclip ml-2"></i> --}}
                                {{--                                                {{ Str::substr($seminar->lampiran_5, 21) }}</a> --}}
                                {{--                                        </small> --}}
                                {{--                                    </div> --}}
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




