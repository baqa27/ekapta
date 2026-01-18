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
                    <div class="card-header">
                        <h3 class="card-title">Form Input Bimbingan Offline</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kp.bimbingan-offline.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="tanggal_bimbingan">Tanggal Bimbingan</label>
                                <input type="date" class="form-control @error('tanggal_bimbingan') is-invalid @enderror" 
                                    name="tanggal_bimbingan" value="{{ old('tanggal_bimbingan') }}" required>
                                @error('tanggal_bimbingan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                    name="keterangan" rows="4" placeholder="Keterangan bimbingan offline" required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bukti_bimbingan_offline">Bukti Bimbingan (.pdf, .jpg, .jpeg, .png)</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('bukti_bimbingan_offline') is-invalid @enderror"
                                        name="bukti_bimbingan_offline" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label class="custom-file-label" for="bukti_bimbingan_offline">Choose file</label>
                                </div>
                                @error('bukti_bimbingan_offline')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection




