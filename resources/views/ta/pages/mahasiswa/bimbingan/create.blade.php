@extends('ta.layouts.dashboardMahasiswa')

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
                    <li class="breadcrumb-item"><a href="#">Bimbingan TA</a></li>
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
                        <h3 class="card-title">Form Pengajuan Tugas Akhir</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ta.bimbingan.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Bagian Bimbingan</label>
                                <select class="form-control @error('bagian_id') is-invalid @enderror" name="bagian_id"
                                    required>
                                    <option value="">Pilih</option>
                                    @foreach ($bagians as $bagian)
                                    <option value="{{ $bagian->id }}" {{ $bagian->id == old('bagian_id') ? 'selected' :
                                        '' }}>{{ $bagian->bagian }}</option>
                                    @endforeach
                                </select>
                                @error('bagian_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Keterangan</label>
                                <textarea id="summernote" name="keterangan" required>
                                    {{ old('keterangan') }}
                                </textarea>
                                @error('keterangan')
                                <div class="text-danger"><small>{{ $message }}</small></div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Dokumen</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file"
                                            class="custom-file-input @error('lampiran') is-invalid @enderror"
                                            name="lampiran" required>
                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Dokumen</span>
                                    </div>
                                </div>
                                @error('lampiran')
                                <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message
                                    }}</small>
                                @enderror
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




