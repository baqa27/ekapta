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
                        <li class="breadcrumb-item"><a href="{{ route('kp.bimbingan.mahasiswa') }}">Bimbingan KP</a></li>
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
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Mode Bimbingan Offline:</strong> Upload bukti ACC bimbingan dari dosen pembimbing Anda.
                            </div>

                            <form action="{{ route('kp.bimbingan.store.manual') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="bagian_id" value="{{ $bagian->id }}">

                                <div class="form-group">
                                    <label>Bagian Bimbingan</label>
                                    <input type="text" class="form-control" value="{{ $bagian->bagian }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal ACC Bimbingan <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_acc" class="form-control @error('tanggal_acc') is-invalid @enderror" required value="{{ old('tanggal_acc') }}">
                                    @error('tanggal_acc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Bukti ACC Bimbingan (Format: PDF, PNG, JPG, JPEG | Max: 2MB) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('lampiran_acc') is-invalid @enderror" name="lampiran_acc" accept=".pdf,.jpeg,.png,.jpg" required>
                                            <label class="custom-file-label">Pilih file...</label>
                                        </div>
                                    </div>
                                    @error('lampiran_acc')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">Upload foto/scan lembar bimbingan yang sudah ditandatangani dosen</small>
                                </div>

                                <div class="form-group">
                                    <label>Keterangan (Opsional)</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                                </div>

                                <div class="form-group mt-4">
                                    <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




