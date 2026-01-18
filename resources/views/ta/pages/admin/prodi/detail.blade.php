@extends('ta.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">

                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Bagian Bimbingan Prodi {{ $prodi->namaprodi }}</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="fas fa-plus"></i> Buat Bagian Bimbingan
                            </button>

                            <button type="button" class="btn btn-info col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="fas fa-upload"></i> Import Bagian Bimbingan
                            </button>

                            <ul class="list-group mt-3">
                                @php
                                    $no = 1;
                                @endphp

                                @foreach ($prodi->bagians as $bagian)
                                    <li
                                        class="list-group-item text-secondary {{ count($bagian->bimbingans) != 0 ? 'border-success' : '' }}">
                                        <span
                                            class="badge {{ count($bagian->bimbingans) != 0 ? 'badge-success' : 'badge-secondary' }} mr-2">{{ $no++ }}</span>
                                        <span style="position: relative;top:2px;">{{ $bagian->bagian }}</span>

                                        @php
                                            $tahuns = explode(',', $bagian->tahun_masuk);
                                        @endphp
                                        @foreach ($tahuns as $tahun)
                                            <span style="position: relative;top:2px;"
                                                class="badge bg-secondary">{{ $tahun }}</span>
                                        @endforeach

                                        @if ($bagian->is_seminar == 1)
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Seminar</span>
                                        @endif

                                        @if ($bagian->is_pendadaran == 1)
                                            <span class="badge bg-success ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Pendadaran</span>
                                        @endif

                                        <div class="float-right">
                                            <div class="d-flex">
                                                {{-- @if (count($bagian->bimbingans) == 0)
                                                    <div onclick="confirmActive()">
                                                        <form action="{{ route('ta.bagian.active') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $bagian->id }}">
                                                            <input type="hidden" name="prodi_id"
                                                                value="{{ $prodi->id }}">
                                                            <button class="btn btn-success btn-sm float-right mr-2"
                                                                type="submit">
                                                                <i class="fas fa-check-circle"></i>
                                                                Aktifkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif --}}


                                                {{-- <a href="{{ route('bagian.up', $bagian->id) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-arrow-up"></i>
                                                </a>
                                                <a href="{{ route('ta.bagian.down', $bagian->id) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-arrow-down"></i>
                                                </a> --}}

                                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-{{ $bagian->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @if (count($bagian->bimbingans) == 0)
                                                    <div onclick="confirmDelete()">
                                                        <form action="{{ route('ta.bagian.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $bagian->id }}">
                                                            <button class="btn btn-danger btn-sm float-right"
                                                                type="submit">
                                                                <i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                @endif

                                                {{-- TOMBOL ALTERNATIF DOWNLOAD, UNCOMMENT KODE DIBAWAH JIKA INGIN DIAKTIFKAN --}}
                                                {{-- <div onclick="confirmDelete()">
                                                    <form action="{{ route('ta.bagian.delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $bagian->id }}">
                                                        <button class="btn btn-danger btn-sm float-right"
                                                            type="submit">
                                                            <i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div> --}}
                                                {{-- END --}}

                                            </div>
                                        </div>

                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Create -->
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ta.bagian.store') }}" method="post">
                    @csrf

                    <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Buat Bagian Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Nama Bagian Bimbingan</label>
                            <input type="text" class="form-control @error('bagian') is-invalid @enderror" name="bagian"
                                placeholder="Nama bagian bimbingan..." required>
                            @error('bagian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                            <input type="text" class="form-control @error('tahun_masuk') is-invalid @enderror"
                                name="tahun_masuk" data-role="tagsinput" placeholder="Tahun masuk bagian bimbingan..." required>
                            @error('tahun_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ta.bagian.import') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="prodi" value="{{ $prodi->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Import Bagian Bimbingan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing"
                            class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i>
                            Download Template File Import</a> <br><br>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File Import<br>
                                <small>Format file : <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Modal Edit -->
    @foreach ($prodi->bagians as $bagian)
        <div class="modal fade" id="modal-edit-{{ $bagian->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ta.bagian.update') }}" method="post">
                        @csrf

                        <input type="hidden" name="id" value="{{ $bagian->id }}">

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Bagian Bimbingan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Bagian Bimbingan</label>
                                <input type="text" class="form-control @error('bagian') is-invalid @enderror"
                                    name="bagian" value="{{ $bagian->bagian }}" required>
                                @error('bagian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                                {{-- <input type="text" class="form-control @error('tahun_masuk') is-invalid @enderror"
                                    name="tahun_masuk" value="{{ $bagian->tahun_masuk }}" required> --}}
                                <input type="text" class="form-control" data-role="tagsinput" name="tahun_masuk"
                                    value="{{ $bagian->tahun_masuk }}" required>
                                @error('tahun_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_seminar"
                                    @if ($bagian->is_seminar == 1) checked @endif>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_pendadaran"
                                    @if ($bagian->is_pendadaran == 1) checked @endif>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Pendadaran</label>
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
    @endforeach
@endsection

@section('script')
    {{-- Tags Config --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script>
        $(function() {
            $('input')
                .on('change', function(event) {
                    var $element = $(event.target);
                    var $container = $element.closest('.example');

                    if (!$element.data('tagsinput')) return;

                    var val = $element.val();
                    if (val === null) val = 'null';
                    var items = $element.tagsinput('items');

                    $('code', $('pre.val', $container)).html(
                        $.isArray(val) ?
                        JSON.stringify(val) :
                        '"' + val.replace('"', '\\"') + '"'
                    );
                    $('code', $('pre.items', $container)).html(
                        JSON.stringify($element.tagsinput('items'))
                    );
                })
                .trigger('change');
        });
    </script>
@endsection




