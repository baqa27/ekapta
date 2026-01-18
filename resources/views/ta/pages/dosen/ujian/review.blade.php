@extends('ta.layouts.dashboard')

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
                        @if($review_ujian->dosen_status == 'penguji')
                            <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($review_ujian->status == 'review') bg-secondary
                            @elseif ($review_ujian->status == 'revisi')
                            bg-warning
                            @elseif ($review_ujian->status == 'diterima')
                            bg-success
                            @elseif ($review_ujian->status == 'ditolak')
                            bg-danger @endif
                            ">
                                {{ $review_ujian->status }}
                            </div>
                        </div>
                        @endif
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td>{{ $review_ujian->ujian->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td>{{ $review_ujian->ujian->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td>{{ $review_ujian->ujian->mahasiswa->prodi }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul TA</b></td>
                                    <td>{{ $review_ujian->ujian->pengajuan->judul }}
                                    </td>
                                </tr>
                            </table>
                            <hr>

                            <div class="mt-4 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                Tanggal Submit <b>{{ date('d M Y H:m', strtotime($review_ujian->created_at)) }}</b>
                            </div>

                            @if ($review_ujian->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Acc <b>{{ date('d M Y H:m', strtotime($review_ujian->tanggal_acc)) }}</b>
                                </div>
                            @endif

                            @if ($review_ujian->ujian->tanggal_ujian)
                                <div class="text-danger"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Ujian <b>{{ date('d M Y H:m', strtotime($review_ujian->ujian->tanggal_ujian)) }}</b>
                                </div>
                                <hr>
                            @endif

                            @if($review_ujian->dosen_status == 'penguji')
                            <b>Keterangan</b> <br>
                            <div class="p-2 rounded" style="background-color: #dbdbdb">
                                {!! nl2br($review_ujian->keterangan) !!}
                            </div>
                            <hr>

                            <div class="shadow-lg p-2 rounded">
                                <b>Laporan Skripsi : </b>
                                <a href="{{ asset($review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan) }}"
                                    class="ml-3 text-primary" target="_blank"><i
                                        class="fas fa-paperclip mr-2"></i>
                                    {{ Str::substr($review_ujian->lampiran ? $review_ujian->lampiran : $review_ujian->ujian->lampiran_laporan, 40) }}</a>
                            </div>
                            @endif

                        </div>
                        @if ($review_ujian->dosen_status == 'penguji' && $review_ujian->status == 'review')
                            <div class="card-footer">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                            data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi bimbingan
                                    </button>

                                    {{--<div onclick="confirmAcc()">
                                        <form action="{{ route('ta.review.ujian.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $review_ujian->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc bimbingan
                                            </button>
                                        </form>
                                    </div>--}}
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                            data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc bimbingan
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($review_ujian->status == 'diterima' || $review_ujian->dosen_status == 'pembimbing')
                        <div class="card card-primary card-outline mt-2">
                            <div class="card-header">Input Nilai</div>
                            <div class="card-body">
                                <form action="{{ route('ta.review.ujian.nilai') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $review_ujian->id }}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Substansi / Isi Materi</label>
                                                <input type="number" name="nilai_1" class="form-control @error('nilai_1') is-invalid @enderror" value="{{ $review_ujian->nilai_1 }}" required>
                                                @error('nilai_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Kompetensi Ilmu </label>
                                                <input type="number" name="nilai_2" class="form-control @error('nilai_2') is-invalid @enderror" value="{{ $review_ujian->nilai_2 }}" required>
                                                @error('nilai_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Metodologi dan Redaksi TA</label>
                                                <input type="number" name="nilai_3" class="form-control @error('nilai_3') is-invalid @enderror" value="{{ $review_ujian->nilai_3 }}" required>
                                                @error('nilai_3')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Presentasi</label>
                                                <input type="number" name="nilai_4" class="form-control @error('nilai_4') is-invalid @enderror" value="{{ $review_ujian->nilai_4 }}" required>
                                                @error('nilai_4')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @if($form_status == 1)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputFile">Status</label>
                                                    <select class="form-control" name="is_lulus" required>
                                                        <option value="">-- pilih --</option>
                                                        <option value="1" {{ $review_ujian->ujian->is_lulus == 1 ? 'selected' :'' }}>Lulus</option>
                                                        <option value="2" {{ $review_ujian->ujian->is_lulus == 2 ? 'selected' :'' }}>Tidak Lulus</option>
                                                    </select>
                                                    @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Submit Nilai</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if($review_ujian->dosen_status == 'penguji')
                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($review_ujian->revisis) }}
                                </span>
                            </h3>
                            @if ($review_ujian->status == 'revisi')
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
                                        <span class="mr-5">
                                            Direvisi oleh <b>Anda</b>
                                        </span>
                                        <div class="float-right">
                                            <div class="d-flex">
                                                <span class="mr-3">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    {{ $revisi->created_at->format('d M Y H:m') }}
                                                </span>
                                                <div onclick="confirmDelete()">
                                                    <form action="{{ route('ta.review.ujian.revisi.delete') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $revisi->id }}">
                                                        <button class="btn btn-danger btn-sm float-right"
                                                            type="submit">
                                                            <i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
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
                                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

    @if($review_ujian->dosen_status == 'penguji')
    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ta.review.ujian.revisi.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $review_ujian->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi Ujian TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Catatan</label>
                            <textarea id="summernote" name="catatan" required></textarea>
                            @error('catatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{-- <div class="form-group">
                            <label for="" class="form-label">Lampiran</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
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
                <form action="{{ route('ta.review.ujian.acc') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $review_ujian->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Acc Ujian TA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" required></textarea>
                            @error('catatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{--<div class="form-group">
                            <label for="" class="form-label">Lampiran</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
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
                        </div>--}}
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




