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
                        <li class="breadcrumb-item"><a href="#">Pendaftaran TA</a></li>
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
                            @if ($pendaftaran->status == 'review') bg-secondary
                            @elseif ($pendaftaran->status == 'revisi')
                            bg-warning
                            @elseif ($pendaftaran->status == 'diterima')
                            bg-success @endif
                            ">
                                {{ $pendaftaran->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $pendaftaran->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $pendaftaran->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tahun Masuk
                                </div>
                                <div class="col-md-7">
                                    <b>{{  $pendaftaran->mahasiswa->thmasuk }}</b>
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
                                    <b>{{ $pendaftaran->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Email
                                </div>
                                <div class="col-md-7">
                                    <b>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pendaftaran->mahasiswa->nim)->email }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    No. HP
                                </div>
                                <div class="col-md-7">
                                    <b>{{ \App\Helpers\AppHelper::instance()->getMahasiswa($pendaftaran->mahasiswa->nim)->hp }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Semester
                                </div>
                                <div class="col-md-7">
                                    <b>{{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($pendaftaran->nim)->semester : '' }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Dokumen Acc. Kaprodi
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($pendaftaran->lampiran_1) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_1, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Lembar Pernyataan Keaslian Hasil Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($pendaftaran->lampiran_2) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_2, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Transkrip Nilai
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($pendaftaran->lampiran_3) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_3, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Pengumpulan KP
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($pendaftaran->lampiran_4) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_4, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Bukti Pembayaran Tugas Akhir
                                </div>
                                <div class="col-md-7">
                                    <a href="{{ asset($pendaftaran->lampiran_5) }}" target="_blank"><i
                                            class="fas fa-paperclip"></i>
                                        {{ Str::substr($pendaftaran->lampiran_5, 40) }}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nomor Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->nomor_pembayaran }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pembayaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->tanggal_pembayaran }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Biaya
                                </div>
                                <div class="col-md-7">
                                    <span class="text-success fs-5">Rp, {{ $pendaftaran->biaya }},-</span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Pendaftaran
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $pendaftaran->created_at->format('d M Y H:m') }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Validasi
                                </div>
                                <div class="col-md-7">
                                    @if ($pendaftaran->tanggal_acc)
                                        <b>{{ date('d M Y H:m', strtotime($pendaftaran->tanggal_acc)) }}</b>
                                    @endif
                                </div>
                            </div>

                            @if ($pendaftaran->status == 'diterima')
                                <hr>
                                <div class="row">
                                    <div class="col-md-5">
                                        Surat Tugas Bimbingan
                                    </div>
                                    <div class="col-md-7">

                                        <a href="{{ url('cetak/surat-tugas-bimbingan/' . $pendaftaran->id) }}"
                                            target="_blank"><i class="fas fa-download"></i> Surat tugas bimbingan TA</a>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="card-footer">
                            <div class="d-flex">
                                @if ($pendaftaran->status == 'review')
                                    <a href="{{ route('ta.pendaftaran.admin') }}" class="btn btn-secondary mr-2">
                                            <i class="bi bi-arrow-left mr-2"></i> Kembali
                                    </a>
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi Pendaftaran
                                    </button>

                                    {{--<div onclick="confirmAcc()">
                                        <form action="{{ route('ta.pendaftaran.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc Pendaftaran
                                            </button>
                                        </form>
                                    </div>--}}

                                    <!-- Modal Confirm Acc -->
                                    <button type="button" class="btn btn-success"
                                        data-toggle="modal" data-target="#modal-confirm-acc">
                                        <i class="fas fa-check mr-2"></i> Acc Pendaftaran
                                    </button>

                                    <div class="modal fade" id="modal-confirm-acc">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('ta.pendaftaran.acc') }}" method="post">
                                                    @csrf

                                                    <input type="hidden" name="id" value="{{ $pendaftaran->id }}">

                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Konfirmasi Acc Pendaftaran Tugas Akhir</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                NIM
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $pendaftaran->mahasiswa->nim }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Nama Lengkap
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $pendaftaran->mahasiswa->nama }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                Prodi
                                                            </div>
                                                            <div class="col-md-7">
                                                                <b>{{ $pendaftaran->mahasiswa->prodi }}</b>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="" class="form-label text-danger">Mahasiswa akan tergabung pada bimbingan dengan tahun masuk:</label>
                                                            <input type="text" name="tahun_masuk" value="{{ $pendaftaran->mahasiswa->thmasuk }}" class="form-control" required>
                                                        </div>
                                                        <br>
                                                        <span class="text-danger">* Jika ingin mengubah tahun masuk bimbingan, maka ubah data tahun masuk mahasiswa!</span>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($pendaftaran->revisis) }}
                                </span>
                            </h3>
                            @if ($pendaftaran->status == 'revisi')
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
                                            <form action="{{ route('ta.pendaftaran.revisi.delete') }}" method="post">
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
                                            <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                                    class="fas fa-paperclip"></i>
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

    <!-- Modal Revisi -->
    @if ($pendaftaran->status != 'diterima')
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.pendaftaran.revisi') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $pendaftaran->id }}">
                        <div class="modal-header">
                            <h4 class="modal-title">Revisi Pendaftaran</h4>
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
@endsection




