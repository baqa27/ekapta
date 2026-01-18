@extends('kp.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Pengajuan KP</a></li>
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
                            <form action="{{ route('kp.pengajuan.update') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $pengajuan->id }}">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Judul</label>
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                        id="exampleInputEmail1" placeholder="Judul kerja Praktek" name="judul"
                                        value="{{ $pengajuan->judul }}" required>
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lokasi_kp">Lokasi KP</label>
                                    <input type="text" class="form-control @error('lokasi_kp') is-invalid @enderror"
                                        id="lokasi_kp" placeholder="Lokasi Kerja Praktek" name="lokasi_kp"
                                        value="{{ $pengajuan->lokasi_kp }}" required>
                                    @error('lokasi_kp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="alamat_instansi">Alamat Instansi</label>
                                    <input type="text" class="form-control @error('alamat_instansi') is-invalid @enderror"
                                        id="alamat_instansi" placeholder="Alamat Instansi" name="alamat_instansi"
                                        value="{{ $pengajuan->alamat_instansi }}" required>
                                    @error('alamat_instansi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Gambaran Singkat</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                        name="deskripsi" id="deskripsi" rows="4" 
                                        placeholder="Gambaran singkat masalah dan solusi">{{ $pengajuan->deskripsi }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lampiran">Bukti Diterima Instansi (.pdf)</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran') is-invalid @enderror"
                                            name="lampiran" accept=".pdf">
                                        <label class="custom-file-label" for="lampiran">Choose file</label>
                                    </div>
                                    @error('lampiran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    <div class="bg-light p-2 rounded">
                                        <small>
                                            <b>Lampiran sebelumnya : </b>
                                            <a href="{{ storage_url($pengajuan->lampiran) }}" class="ml-3 text-primary"
                                                target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                {{ basename($pengajuan->lampiran) }}</a>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="files_pendukung">File Pendukung</label>
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('files_pendukung') is-invalid @enderror"
                                            name="files_pendukung" accept=".pdf,.zip,.rar">
                                        <label class="custom-file-label" for="files_pendukung">Choose file</label>
                                    </div>
                                    @error('files_pendukung')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if($pengajuan->files_pendukung)
                                    <div class="bg-light p-2 rounded mt-2">
                                        <small>
                                            <b>File Pendukung sebelumnya : </b>
                                            <a href="{{ storage_url($pengajuan->files_pendukung) }}" class="ml-3 text-primary"
                                                target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                {{ basename($pengajuan->files_pendukung) }}</a>
                                        </small>
                                    </div>
                                    @endif
                                </div>
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




