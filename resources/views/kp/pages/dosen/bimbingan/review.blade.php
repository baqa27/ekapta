@extends('kp.layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="{{ route('kp.bimbingan.dosen') }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($bimbingan->status == 'review') bg-secondary
                            @elseif ($bimbingan->status == 'revisi')
                            bg-warning
                            @elseif ($bimbingan->status == 'diterima')
                            bg-success
                            @elseif ($bimbingan->status == 'ditolak')
                            bg-danger @endif
                            ">
                                {{ $bimbingan->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table>
                                <tr>
                                    <td><b class="mr-3">Nim</b></td>
                                    <td>{{ $bimbingan->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td>{{ $bimbingan->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td>{{ $bimbingan->mahasiswa->prodi }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td>{{ $pengajuan->judul }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Bagian</b></td>
                                    <td>{{ $bimbingan->bagian->bagian }}</td>
                                </tr>
                            </table>
                            <hr>

                            <b>Keterangan</b> <br>
                            <div class="p-2 rounded" style="background-color: #dbdbdb">
                                {!! nl2br($bimbingan->keterangan) !!}
                            </div>

                            <div class="mt-4 text-secondary"><i class="fas fa-calendar mr-2"></i>
                                Tanggal Submit <b>{{ date('d M Y H:m', strtotime($bimbingan->tanggal_bimbingan)) }}</b>
                            </div>

                            @if ($bimbingan->tanggal_acc)
                                <div class="text-success"><i class="fas fa-calendar-check mr-2"></i>
                                    Tanggal Acc <b>{{ date('d M Y H:m', strtotime($bimbingan->tanggal_acc)) }}</b>
                                </div>
                            @endif
                            <hr>

                            <p class="mt-3"><b>Lampiran : </b> <a href="{{ storage_url($bimbingan->lampiran) }}" class="ml-3"
                                    target="_blank"><i class="fas fa-paperclip"></i>
                                    {{ basename($bimbingan->lampiran) }}</a></p>

                            <hr>
                            <div class="bordered mt-2">
                                <b>Bagian Bimbingan Kerja Praktek</b>

                                <div class="mt-2 border p-2 rounded">

                                    @php
                                        $dosenPembimbing = $bimbingan->mahasiswa->dosens()->where('dosen_id', Auth::guard('dosen')->user()->id)->first();
                                    @endphp

                                    {{-- Bimbingan Dosen Utama --}}
                                    @if ($dosenPembimbing->pivot->status == 'utama')
                                        @foreach ($mahasiswa->bimbingans()->where('pembimbing', 'utama')->get() as $bimbinganMahasiswa)
                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbinganMahasiswa->id))
                                                <a href="{{ storage_url($bimbingan->lampiran) }}"
                                                    class="badge badge-success mr-1" target="_blank">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ $bimbinganMahasiswa->bagian->bagian }}
                                                </a>
                                            @else
                                                <span class="badge badge-secondary mr-1">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    {{ $bimbinganMahasiswa->bagian->bagian }}
                                                </span>
                                            @endif
                                        @endforeach

                                        {{-- Bimbingan Dosen Pendamping --}}
                                    @elseif ($dosenPembimbing->pivot->status == 'pendamping')
                                        @foreach ($mahasiswa->bimbingans()->where('pembimbing', 'pendamping')->get() as $bimbinganMahasiswa)
                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbinganMahasiswa->id))
                                                <a href="{{ storage_url($bimbingan->lampiran) }}"
                                                    class="badge badge-success mr-1" target="_blank">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    {{ $bimbinganMahasiswa->bagian->bagian }}
                                                </a>
                                            @else
                                                <span class="badge badge-secondary mr-1">
                                                    <i class="fas fa-circle mr-1"></i>
                                                    {{ $bimbinganMahasiswa->bagian->bagian }}
                                                </span>
                                            @endif
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('kp.bimbingan.dosen') }}" class="btn btn-secondary mr-2">
                                        <i class="bi bi-arrow-left mr-2"></i> Kembali
                                </a>
                                @if ($bimbingan->status == 'review')
                                    <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#modal-revisi">
                                        <i class="bi bi-pencil-square mr-2"></i> Revisi bimbingan
                                    </button>

                                    {{--<div onclick="confirmAcc()">
                                        <form action="{{ route('kp.bimbingan.acc') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                                            <button type="submit" class="btn btn-success mr-2">
                                                <i class="fas fa-check mr-2"></i> Acc bimbingan
                                            </button>
                                        </form>
                                    </div>--}}
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal"
                                        data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> Acc bimbingan
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
                                    {{ count($bimbingan->revisis) }}
                                </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <span class="mr-5">Direview oleh <b>Anda</b>
                                        </span>
                                        <div class="float-right">
                                            <div class="d-flex">
                                                <span class="mr-3">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    {{ $revisi->created_at->format('d M Y H:m') }}
                                                </span>
                                                @if ($revisi->dosen->id == Auth::guard('dosen')->user()->id)
                                                    {{--<div onclick="confirmDelete()">
                                                        <form action="{{ route('kp.bimbingan.revisi.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $revisi->id }}">
                                                            <button class="btn btn-danger btn-sm float-right"
                                                                type="submit">
                                                                <i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>--}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                        @if($revisi->tanggal_bimbingan)
                                            <div class="mt-4 text-secondary">
                                                <small><i class="fas fa-calendar mr-2"></i> Tanggal Bimbingan <b>{{ date('d M Y H:m', strtotime($revisi->tanggal_bimbingan)) }}</b></small>
                                            </div>
                                        @endif
                                        @if ($revisi->lampiran_revisi)
                                            <small>
                                                Lampiran revisi:
                                                @if ($revisi->lampiran_revisi)
                                                    <a href="{{ storage_url($bimbingan->lampiran_revisi) }}" class="ml-3" target="_blank"><i
                                                            class="fas fa-paperclip"></i>
                                                        {{ basename($revisi->lampiran_revisi) }}</a>
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                    @if ($revisi->lampiran)
                                    <div class="card-footer">
                                        <small>
                                            Lampiran bimbingan sebelumnya:
                                                <a href="{{ storage_url($bimbingan->lampiran) }}" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    {{ basename($revisi->lampiran) }}</a>
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

    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bimbingan.revisi.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan">Catatan</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" rows="4" placeholder="Catatan revisi" required></textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Lampiran (Opsional)</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
                                        name="lampiran">
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
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

    <!-- Modal Acc -->
    <div class="modal fade" id="modal-acc">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bimbingan.acc') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Acc bimbingan</h4>
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

@endsection




