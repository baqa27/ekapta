@extends('layouts.dashboard')

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

            {{-- ============================================== --}}
            {{-- SECTION: BAGIAN BIMBINGAN KERJA PRAKTIK (KP) --}}
            {{-- ============================================== --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-briefcase mr-2"></i>
                                Bagian Bimbingan Kerja Praktik (KP) - Prodi {{ $prodi->namaprodi }}
                            </h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-info col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-create-kp">
                                <i class="fas fa-plus"></i> Buat Bagian Bimbingan KP
                            </button>

                            <button type="button" class="btn btn-secondary col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import-kp">
                                <i class="fas fa-upload"></i> Import Bagian Bimbingan KP
                            </button>

                            <ul class="list-group mt-3">
                                @php $noKP = 1; @endphp

                                @foreach ($prodi->bagiansKP as $bagianKP)
                                    <li class="list-group-item text-secondary {{ count($bagianKP->bimbingans) != 0 ? 'border-info' : '' }}">
                                        <span class="badge {{ count($bagianKP->bimbingans) != 0 ? 'badge-info' : 'badge-secondary' }} mr-2">{{ $noKP++ }}</span>
                                        <span style="position: relative;top:2px;">{{ $bagianKP->bagian }}</span>

                                        @php $tahuns = explode(',', $bagianKP->tahun_masuk); @endphp
                                        @foreach ($tahuns as $tahun)
                                            <span style="position: relative;top:2px;" class="badge bg-secondary">{{ $tahun }}</span>
                                        @endforeach

                                        @if ($bagianKP->is_seminar == 1)
                                            <span class="badge bg-info ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Seminar</span>
                                        @endif

                                        @if ($bagianKP->is_pendadaran == 1)
                                            <span class="badge bg-info ml-3" style="position: relative;top:2px;">
                                                <i class="bi bi-check-circle mr-1"></i>
                                                Sebagai Syarat Seminar KP</span>
                                        @endif

                                        <div class="float-right">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-info btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-kp-{{ $bagianKP->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @if (count($bagianKP->bimbingans) == 0)
                                                    <div onclick="confirmDelete()">
                                                        <form action="{{ route('kp.bagian.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $bagianKP->id }}">
                                                            <button class="btn btn-danger btn-sm float-right" type="submit">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                @if(count($prodi->bagiansKP) == 0)
                                    <li class="list-group-item text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada bagian bimbingan KP
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================== --}}
            {{-- SECTION: BAGIAN BIMBINGAN TUGAS AKHIR (TA) --}}
            {{-- ============================================== --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Bagian Bimbingan Tugas Akhir (TA) - Prodi {{ $prodi->namaprodi }}
                            </h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-create-ta">
                                <i class="fas fa-plus"></i> Buat Bagian Bimbingan TA
                            </button>

                            <button type="button" class="btn btn-info col-md-4 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import-ta">
                                <i class="fas fa-upload"></i> Import Bagian Bimbingan TA
                            </button>

                            <ul class="list-group mt-3">
                                @php $noTA = 1; @endphp

                                @foreach ($prodi->bagians as $bagian)
                                    <li class="list-group-item text-secondary {{ count($bagian->bimbingans) != 0 ? 'border-success' : '' }}">
                                        <span class="badge {{ count($bagian->bimbingans) != 0 ? 'badge-success' : 'badge-secondary' }} mr-2">{{ $noTA++ }}</span>
                                        <span style="position: relative;top:2px;">{{ $bagian->bagian }}</span>

                                        @php $tahuns = explode(',', $bagian->tahun_masuk); @endphp
                                        @foreach ($tahuns as $tahun)
                                            <span style="position: relative;top:2px;" class="badge bg-secondary">{{ $tahun }}</span>
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
                                                <button type="button" class="btn btn-primary btn-sm mr-2"
                                                    data-toggle="modal" data-target="#modal-edit-ta-{{ $bagian->id }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                @if (count($bagian->bimbingans) == 0)
                                                    <div onclick="confirmDelete()">
                                                        <form action="{{ route('bagian.delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $bagian->id }}">
                                                            <button class="btn btn-danger btn-sm float-right" type="submit">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                @if(count($prodi->bagians) == 0)
                                    <li class="list-group-item text-muted text-center">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada bagian bimbingan TA
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- ============================================== --}}
    {{-- MODALS TUGAS AKHIR (TA) --}}
    {{-- ============================================== --}}

    <!-- Modal Create TA -->
    <div class="modal fade" id="modal-create-ta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bagian.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">

                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Buat Bagian Bimbingan TA</h4>
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
        </div>
    </div>

    <!-- Modal Import TA -->
    <div class="modal fade" id="modal-import-ta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('bagian.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prodi" value="{{ $prodi->id }}">

                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Import Bagian Bimbingan TA</h4>
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
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
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
        </div>
    </div>

    <!-- Modal Edit TA -->
    @foreach ($prodi->bagians as $bagian)
        <div class="modal fade" id="modal-edit-ta-{{ $bagian->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('bagian.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bagian->id }}">

                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">Edit Bagian Bimbingan TA</h4>
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
            </div>
        </div>
    @endforeach


    {{-- ============================================== --}}
    {{-- MODALS KERJA PRAKTIK (KP) --}}
    {{-- ============================================== --}}

    <!-- Modal Create KP -->
    <div class="modal fade" id="modal-create-kp">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bagian.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">

                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Buat Bagian Bimbingan KP</h4>
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
        </div>
    </div>

    <!-- Modal Import KP -->
    <div class="modal fade" id="modal-import-kp">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.bagian.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="prodi" value="{{ $prodi->id }}">

                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Import Bagian Bimbingan KP</h4>
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
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
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
        </div>
    </div>

    <!-- Modal Edit KP -->
    @foreach ($prodi->bagiansKP as $bagianKP)
        <div class="modal fade" id="modal-edit-kp-{{ $bagianKP->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kp.bagian.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bagianKP->id }}">

                        <div class="modal-header bg-info">
                            <h4 class="modal-title">Edit Bagian Bimbingan KP</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="" class="form-label">Nama Bagian Bimbingan</label>
                                <input type="text" class="form-control @error('bagian') is-invalid @enderror"
                                    name="bagian" value="{{ $bagianKP->bagian }}" required>
                                @error('bagian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">Tahun Masuk <br><small>Tekan enter jika ingin input tahun masuk lebih dari 1</small></label>
                                <input type="text" class="form-control" data-role="tagsinput" name="tahun_masuk"
                                    value="{{ $bagianKP->tahun_masuk }}" required>
                                @error('tahun_masuk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_seminar"
                                    @if ($bagianKP->is_seminar == 1) checked @endif>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_pendadaran"
                                    @if ($bagianKP->is_pendadaran == 1) checked @endif>
                                <label class="form-check-label" for="exampleCheck1">Sebagai Syarat Seminar KP</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
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
