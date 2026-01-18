@extends('layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Ujian TA</a></li>
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
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW) bg-secondary
                            @elseif ($ujian->is_valid == \App\Models\TA\Ujian::NOT_VALID_LULUS)
                            bg-warning
                            @elseif ($ujian->is_valid == \App\Models\TA\Ujian::VALID_LULUS)
                            bg-success @endif
                            ">
                                @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW)
                                    review
                                @elseif ($ujian->is_valid == \App\Models\TA\Ujian::DITERIMA)
                                    diterima
                                @elseif ($ujian->is_valid == \App\Models\TA\Ujian::REVISI)
                                    revisi
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Utama (1) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Pembimbing Pendamping (2) Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran SPP Sampai Semester Terakhir
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lunas Pembayaran Tugas Akhir (TA)
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Ijazah Terakhir Yang Asli
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_3, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan KTP / Kartu Keluarga Terbaru
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_4) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_4, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat TOEFL
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_5, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Tahfidz
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_6) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_6, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Scan Sertifikat Komputer
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_7) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_7, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Transkrip Nilai Semenara (Tanpa Nilai D/E/Kosong, kecuali nilai Tugas Akhir/Skripsi)
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($ujian->lampiran_8) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($ujian->lampiran_8, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Laporan Skripsi
                                </div>
                                <div class="col-md-7">
                                    @if($ujian->lampiran_laporan)
                                        <a href="{{ asset($ujian->lampiran_laporan) }}" target="_blank"><i
                                                class="fas fa-paperclip"></i>
                                            {{ Str::substr($ujian->lampiran_laporan, 40) }}</a>
                                    @else
                                        <span class="text-danger">Belum Upload Laporan Skripsi</span>
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $ujian->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>

                            @if ($ujian->tanggal_acc)
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Validasi Pendaftaran
                                    </div>
                                    <div class="col-md-7">
                                        <b class="text-success">{{ date('d M Y H:m', strtotime($ujian->tanggal_acc)) }}</b>
                                    </div>
                                </div>
                            @endif

                            @if ($ujian->tanggal_ujian)
                                @php
                                    $tanggal_ujian = \App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian);
                                @endphp
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tanggal Ujian
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger">{{ $tanggal_ujian }}</b>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Tempat Ujian
                                    </div>
                                    <div class="col-md-7">
                                        <b
                                            class="text-danger">{{ $ujian->tempat_ujian }}</b>
                                    </div>
                                </div>
                            @endif

                        </div>

                        @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW && Auth::guard('admin')->user())
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi ujian
                                    </button>

                                    {{-- <div onclick="confirmAcc()">
                                        <form action="{{ route('ujian.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $ujian->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc Pendaftaran ujian
                                            </button>
                                        </form>
                                    </div> --}}
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc Pendaftaran ujian
                                    </button>
                                </div>
                            </div>
                        @elseif ($ujian->is_valid == \App\Models\TA\Ujian::VALID_LULUS)
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-ploting-penguji">
                                        <i class="bi bi-pencil-square mr-2"></i>
                                        @if (count($dosens_penguji) == 0)
                                            Ploting
                                        @endif Dosen Penguji
                                    </button>
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal"
                                        data-target="#modal-set-date-exam">
                                        <i class="bi bi-calendar mr-2"></i> Tentukan Tanggal dan Tempat Ujian
                                    </button>
                                    <a href="{{ route('cetak.berita.acara.ujian.pendadaran', $ujian->id) }}"
                                        class="btn btn-success mr-2" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran
                                    </a>
                                    <a href="{{ route('cetak.berita.acara.ujian.proposal.blank', [$ujian->id, 2]) }}"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Pendadaran Kosong
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($ujian->revisis) }}
                                </span>
                            </h3>
                            @if ($ujian->is_valid == \App\Models\TA\Ujian::NOT_VALID_LULUS)
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-plus-square mr-2"></i> Tambahkan Revisi
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">

                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:m') }}
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('ujian.revisi.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $revisi->id }}">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>

                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            @if ($revisi->lampiran)
                                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3"
                                                    target="_blank"><i class="fas fa-paperclip"></i>
                                                    {{ Str::substr($revisi->lampiran, 40) }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW || $ujian->is_valid == \App\Models\TA\Ujian::NOT_VALID_LULUS)
        <!-- Modal Revisi -->
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ujian.revisi') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $ujian->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi ujian</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea id="summernote" name="catatan" required></textarea>
                            </div>
                            {{-- <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran')is-invalid @enderror"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran')
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                            </div> --}}
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

         <!-- Modal Acc -->
        <div class="modal fade" id="modal-acc">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ujian.acc') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $ujian->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi ujian</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan"></textarea>
                            </div>
                            {{-- <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran')is-invalid @enderror"
                                            name="lampiran">
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran')
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                            </div> --}}
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @else
        <div class="modal fade" id="modal-ploting-penguji">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">
                            @if (count($dosens_penguji) == 0)
                                Ploting
                            @endif Dosen Penguji
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (count($dosens_penguji) != 0)
                            <b>Dosen Penguji</b>
                            <div class="p-2 border rounded">
                                @php $no = 1; @endphp
                                @foreach ($dosens_penguji as $dosen)
                                    <span>Dosen Penguji{{ $no++ }}. <b>{{ $dosen->dosen->nama }},
                                            {{ $dosen->dosen->gelar }}</b></span>
                                    <br>
                                @endforeach
                            </div>
                        @endif

                        @if (count($reviews_check) == 0)
                            <form action="{{ route('ploting.penguji.ujian') }}" method="post">
                                @csrf

                                <input type="hidden" value="{{ $ujian->id }}" name="ujian_id" />
                                <div class="form-group mt-2">
                                    <label for="" class="form-label">Dosen Peguji 1</label>
                                    <div class="col-md-12">
                                        <select class="select-1" name="dosen_penguji[]" style="width: 100%;" required>
                                            <option value="">Pilih</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Penguji 2</label>
                                    <div class="col-md-12">
                                        <select class="select-2" name="dosen_penguji[]" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Penguji 3</label>
                                    <div class="col-md-12">
                                        <select class="select-3" name="dosen_penguji[]" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-success">Simpan</button>
                        @endif
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-set-date-exam">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Tentukan Tanggal dan Tempat Ujian Peserta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('ujian.set.date.exam') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{ $ujian->id }}" name="ujian_id" />
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tanggal Ujian</label>
                                <input type="datetime-local" class="form-control" name="tanggal_ujian" value="{{ $ujian->tanggal_ujian }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">Tempat Ujian</label>
                                <input type="text" class="form-control" name="tempat_ujian" value="{{ $ujian->tempat_ujian }}" required>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
    @endif
@endsection
