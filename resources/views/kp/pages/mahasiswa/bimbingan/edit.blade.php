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
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
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
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">{{ $title }}</h3>
                            <h3 class="card-title flex-shrink-0">{{ $dosen->nama.', '.$dosen->gelar }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kp.bimbingan.update') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" value="{{ $bimbingan->id }}">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bagian Bimbingan</label>
                                    <input type="text" class="form-control" value="{{ $bimbingan->bagian->bagian }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                        name="keterangan" id="keterangan" rows="4" 
                                        placeholder="Keterangan bimbingan" required>{{ $bimbingan->keterangan }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lampiran (Format: .pdf, Max: 5Mb)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran')is-invalid @enderror"
                                                name="lampiran" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        
                                    </div>
                                    @error('lampiran')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    @if ($bimbingan->lampiran)
                                        <div class="bg-light p-2 rounded">
                                            <small>
                                                <b>Lampiran sebelumnya : </b>
                                                <a href="{{ storage_url($bimbingan->lampiran) }}" class="ml-3 text-primary"
                                                    target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                    {{ basename($bimbingan->lampiran) }}</a>
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




