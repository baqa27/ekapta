@extends('ta.layouts.dashboard')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($pengajuan->status == 'review') bg-secondary
                            @elseif ($pengajuan->status == 'revisi')
                            bg-warning
                            @elseif ($pengajuan->status == 'diterima')
                            bg-success
                            @elseif ($pengajuan->status == 'ditolak' || $pengajuan->status == 'dibatalkan')
                            bg-danger @endif
                            ">
                                {{ $pengajuan->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td>{{ $pengajuan->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td>{{ $pengajuan->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td>{{ $pengajuan->prodi->namaprodi }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul TA</b></td>
                                    <td>
                                        {{--@if ($pengajuan->status == 'diterima')
                                            {{ $pengajuan->judul }}
                                        @else
                                            <span
                                                class="text-{{ count($pengajuanCekIsPlagiat) <= 1 ? 'success' : 'warning' }}">{{ $pengajuan->judul }}</span>

                                            <a type="button" class="ml-2" data-toggle="modal" data-target="#modal-cek">
                                                <i class="bi bi-check-circle mr-1"></i> Check Plagiarism
                                            </a>
                                        @endif--}}
                                        <span class="text-{{ count($pengajuanCekIsPlagiat) <= 1 ? 'success' : 'warning' }}">{{ $pengajuan->judul }}</span>
                                        <a type="button" class="ml-2" data-toggle="modal" data-target="#modal-cek">
                                            <i class="bi bi-check-circle mr-1"></i> Check Plagiarism
                                        </a>
                                    </td>
                                </tr>

                            </table>
                            <hr>
                            <p><b>Deskripsi</b></p>
                            {!! nl2br($pengajuan->deskripsi) !!}
                            <div class="mt-3 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                {{ $pengajuan->created_at->format('d M Y H:m') }}
                            </div>
                            @if ($pengajuan->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    {{ date('d M Y H:m', strtotime($pengajuan->tanggal_acc)) }}
                                </div>
                            @endif
                            <hr>
                            <p class="mt-3"><b>Lampiran : </b> <a href="{{ asset($pengajuan->lampiran) }}" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i>
                                    {{ Str::substr($pengajuan->lampiran, 40) }}</a></p>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('ta.pengajuan.prodi') }}" class="btn btn-secondary mr-2">
                                        <i class="bi bi-arrow-left mr-2"></i> Kembali
                                </a>
                                @if ($pengajuan->status == 'review')
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi Pengajuan
                                    </button>

                                    {{--<div onclick="confirmAcc()">
                                        <form action="{{ route('ta.pengajuan.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc Pengajuan
                                            </button>
                                        </form>
                                    </div>--}}
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="bi bi-check-circle mr-2"></i> Acc Pengajuan
                                    </button>

                                    <button type="button" class="btn btn-danger mr-2" data-toggle="modal"
                                        data-target="#modal-tolak">
                                        <i class="fas fa-x mr-2"></i> Tolak Pengajuan
                                    </button>
                                @elseif($pengajuan->status == 'diterima')
                                    @if (count($mahasiswa->bimbingans) == 0)
                                        <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                            data-target="#modal-edit">
                                            <i class="bi bi-pencil-square mr-2"></i> Ploting Dosen Pembimbing
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                                            data-target="#modal-show">
                                            <i class="bi bi-info-circle mr-2"></i> Dosen Pendamping
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-secondary mr-2" data-toggle="modal"
                                        data-target="#modal-edit-judul">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit Judul Tugas Akhir
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($pengajuan->revisis) }}
                                </span>
                            </h3>
                            @if ($pengajuan->status == 'revisi')
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
                                    <div class="card-header"><i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:m') }}
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('ta.pengajuan.revisi.delete') }}" method="post">
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
                                        <small>
                                            Lampiran :
                                            @if ($revisi->lampiran)
                                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3"
                                                    target="_blank"><i class="fas fa-paperclip"></i>
                                                    {{ Str::substr($revisi->lampiran, 40) }}</a>
                                            @endif
                                        </small>
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

    <!-- Modal Cek Is Plagiat -->
    <div class="modal fade" id="modal-cek">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Check Plagiarism</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b> Judul Pengajuan Tugas Akhir :</b> <br>
                    <span
                        class="text-{{ count($pengajuanCekIsPlagiat) <= 1 ? 'success' : 'warning' }}">{{ $pengajuan->judul }}
                        <i
                            class="bi bi-{{ count($pengajuanCekIsPlagiat) <= 1 ? 'check' : 'info' }}-circle ml-1"></i></span>
                    <hr>
                    <b> Semua judul pengajuan tugas akhir yang sudah digunakan :</b> <br>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($pengajuanCekIsPlagiat as $result)
                        @if ($result->nim == $pengajuan->nim)
                            <del>
                                <span class="text-secondary">
                                    [{{ $no++ }}]
                                    [Judul : {{ $result->judul }}]
                                    [Prodi : {{ $result->prodi->namaprodi }} ]
                                    [Status : {{ $result->status }} ]</span>
                            </del>
                            <br>
                        @else
                            <span class="text-primary">[{{ $no++ }}][Judul : {{ $result->judul }}]</span>
                            <span class="text-info">[Prodi : {{ $result->prodi }} ]</span>
                            <span class="text-{{ $result->status == 'review' ? 'secondary' : 'success' }}">
                                [Status : {{ $result->status }} ]
                            </span>
                            <br>
                        @endif
                    @endforeach

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Revisi -->
    @if ($pengajuan->status == 'revisi' || $pengajuan->status == 'review')
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.pengajuan.revisi') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea id="summernote" name="catatan" required>
                    </textarea>
                            </div>
                            <div class="form-group">
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
                            </div>
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
    @endif

    <!-- Modal Tolak -->
    @if ($pengajuan->status == 'review')
        <div class="modal fade" id="modal-tolak">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.pengajuan.tolak') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Tolak Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan">
                    </textarea>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Lampiran</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran') is-invalid @enderror"
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
                            </div>
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

        <div class="modal fade" id="modal-acc">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.pengajuan.acc') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Acc Pengajuan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Catatan</label>
                                <textarea class="form-control" name="catatan" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Konfirmasi</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endif

    @if ($pengajuan->status == 'diterima')
        @if (count($mahasiswa->bimbingans) == 0)
            <!-- Modal Ploting Dosen Pembimbing -->
            <div class="modal fade" id="modal-edit">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('ta.ploting.pembimbing') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $pengajuan->id }}">
                            <input type="hidden" name="nim" value="{{ $pengajuan->mahasiswa->nim }}">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ count($mahasiswa->bimbingans) == 0 ? 'Ploting' : 'Edit' }} Dosen Pembimbing</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <a href="{{ route('ta.bimbingan.rekap.dosen') }}" class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-users"></i> Lihat Rekap Bimbingan Dosen
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Pembimbing Utama</label>
                                    <div class="col-md-12">
                                        <select class="select-1" name="dosen_utama" style="width: 100%;" required>
                                            <option value="">Pilih</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}"
                                                    @if ($dosen_utama) {{ $dosen_utama->id == $dosen->id ? 'selected' : '' }} @endif>
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">Dosen Pembimbing Pendamping</label>
                                    <div class="col-md-12">
                                        <select class="select-2" name="dosen_pendamping" style="width: 100%" required>
                                            <option value="">Pilih</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->id }}"
                                                    @if ($dosen_pendamping) {{ $dosen_pendamping->id == $dosen->id ? 'selected' : '' }} @endif>
                                                    {{ $dosen->nama . ', ' . $dosen->gelar }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
        @endif

        @if (count($mahasiswa->dosens) != 0)
            <!-- Modal Show Pembimbing -->
            <div class="modal fade" id="modal-show">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Dosen Pembimbing</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <a href="{{ route('ta.bimbingan.rekap.dosen') }}" class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fas fa-users"></i> Lihat Rekap Bimbingan Dosen
                                </a>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Dosen Pembimbing Utama </label>
                                <input type="text" class="form-control"
                                    value="{{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="" class="form-label">Dosen Pembimbing Pendamping </label>
                                <input type="text" class="form-control"
                                    value="{{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}" disabled>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        @endif

        <div class="modal fade" id="modal-edit-judul">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.pengajuan.edit.judul', $pengajuan->id) }}" method="post">
                        @method('PUT')
                        @csrf

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Judul Tugas Akhir</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Judul Tugas Akhir</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="judul" class="form-control"
                                        value="{{ $pengajuan->judul }}" required>
                                </div>
                                @error('judul')
                                    <small class="text-danger"
                                        style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                @enderror
                            </div>
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
    @endif

@endsection




