@extends('kp.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">{{ $title }}</h3>
                            <h3 class="card-title flex-shrink-0">{{ $dosen->nama . ', ' . $dosen->gelar }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($bimbingan->catatan)
                                <div class="alert alert-warning mb-2">
                                    <i class="fas fa-info-circle"></i> Catatan revisi: {{ $bimbingan->catatan }}
                                </div>
                            @endif
                            
                            @if (!$bimbingan->lampiran_acc)
                                <form action="{{ route('kp.bimbingan.submit.acc.manual.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $bimbingan->id }}">

                                    <div class="form-group">
                                        <label>Bagian Bimbingan</label>
                                        <input type="text" class="form-control" value="{{ $bimbingan->bagian->bagian }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Lembar Acc Bimbingan (Format: PDF, PNG, JPG, JPEG | Max: 1Mb)</label>
                                        <div class="input-group mb-3">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('lampiran_acc')is-invalid @enderror" name="lampiran_acc" accept=".pdf,.jpeg,.png,.jpg" required>
                                                <label class="custom-file-label">Choose file</label>
                                            </div>
                                        </div>
                                        @error('lampiran_acc')
                                            <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Acc Bimbingan</label>
                                        <input type="date" name="tanggal_manual_acc" class="form-control" required>
                                    </div>
                                    <div class="form-group mt-4">
                                        <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Submit</button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Sudah input lembar acc bimbingan. Silahkan tunggu validasi dari prodi.
                                </div>
                                <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




