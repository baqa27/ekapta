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
                        <li class="breadcrumb-item"><a href="#">Seminar TA</a></li>
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
                            <form action="{{ route('review.seminar.submit.acc.manual.store', $review->id) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @csrf

                                <div class="form-group">
                                    <label>Tanggal Acc Dosen Penguji: {{ $review->dosen->nama.', '.$review->dosen->gelar }}</label>
                                    <input type="date" class="form-control" name="tanggal_acc_manual" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lampiran Lembar Revisi</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('lampiran_lembar_revisi')is-invalid @enderror"
                                                   name="lampiran_lembar_revisi" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran_lembar_revisi')
                                    <small class="text-danger"
                                           style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div>
                                    @if ($review->lampiran)
                                        <div class="bg-light p-2 rounded">
                                            <small>
                                                <b>Lampiran sebelumnya : </b>
                                                <a href="{{ asset($review->lampiran) }}" class="ml-3 text-primary"
                                                   target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                                    {{ Str::substr($review->lampiran, 16) }}</a>
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




