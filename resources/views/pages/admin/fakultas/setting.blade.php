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

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Setting Fakultas</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            Nama Fakultas
                                        </div>
                                        <div class="col-md-9">
                                            <span class="mr-3">:</span>
                                            <b>{{ $fakultas->namafakultas }}</b>
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded p-2" style="min-width: 160px">
                                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-1" data-toggle="modal"
                                        data-target="#modal-edit-fakultas">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit Stempel Fakultas
                                    </button>
                                    <img src="{{ asset($fakultas->image != null ? $fakultas->image : 'ekapta/assets/img/not-found.png') }}"
                                        alt="Stempel Fakultas" height="50">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            Dekan
                                        </div>
                                        <div class="col-md-9">
                                            <span class="mr-3">:</span>
                                            <b>{{ $dekanActive != null ? $dekanActive->namadekan . ', ' . $dekanActive->gelar : '' }}</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="border rounded p-2" style="min-width: 160px">
                                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-1" data-toggle="modal"
                                        data-target="#modal-edit">
                                        <i class="bi bi-pencil-square mr-2"></i> Edit TTD Dekan
                                    </button>
                                     @if($dekanActive)
                                        <img src="{{ asset($dekanActive->image != null ? $dekanActive->image : 'ekapta/assets/img/not-found.png') }}"
                                             alt="TTD Dekan" height="50">
                                    @else
                                        <img src="{{ asset('ekapta/assets/img/not-found.png') }}"
                                             alt="TTD Dekan" height="50">
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Program Studi</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-2 border rounded d-flex flex-wrap">

                                @foreach ($fakultas->prodis as $prodi)
                                    <div class="p-1 border border-success rounded mr-2 mb-2">
                                        <span class="mr-2"
                                            style="position: relative;top:3px"><b>{{ $prodi->namaprodi }}</b></span>
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('fakultas.delete.prodi') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="prodi" value="{{ $prodi->id }}">
                                                <button class="btn btn-danger btn-sm" type="submit"><i
                                                        class="bi bi-x"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <div class="mt-4">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($prodis as $prodi)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $prodi->namaprodi }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center" onclick="confirmAdd()">
                                                        <form action="{{ route('fakultas.add.prodi') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="fakultas"
                                                                value="{{ $fakultas->id }}">
                                                            <input type="hidden" name="prodi"
                                                                value="{{ $prodi->id }}">
                                                            <button class="btn btn-success btn-sm"><i
                                                                    class="bi bi-plus-circle mr-1"></i>
                                                                Tambahkan</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Prodi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Dekan Fakultas</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#modal-create">
                                <i class="bi bi-plus-circle mr-2"></i> Tambahkan Dekan Fakultas
                            </button>

                            <button type="button" class="btn btn-info mr-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="fas fa-upload mr-2"></i> Import Dekan Fakultas
                            </button>

                            <div class="mt-3">
                                <table id="example2" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Dekan</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($fakultas->dekans as $dekan)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dekan->namadekan . ', ' . $dekan->gelar . ' (' . $dekan->nidn . ')' }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($dekan->dari)->formatLocalized('%d %B %Y') . ' - ' . \Carbon\Carbon::parse($dekan->sampai)->formatLocalized('%d %B %Y') }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        @if ($dekan->status == null)
                                                            @if ($dekanActive == null)
                                                                <div onclick="confirmActive()" class="mr-1">
                                                                    <form action="{{ route('dekan.enabled') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="dekan"
                                                                            value="{{ $dekan->id }}">
                                                                        <button class="btn btn-success btn-sm"><i
                                                                                class="bi bi-check-circle mr-1"></i>
                                                                            Enable
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                            <div onclick="confirmDelete()">
                                                                <form action="{{ route('dekan.delete') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="dekan"
                                                                        value="{{ $dekan->id }}">
                                                                    <button class="btn btn-danger btn-sm"><i
                                                                            class="bi bi-trash mr-1"></i>
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif ($dekan->status == 'active')
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-primary btn-sm mr-1"
                                                                    data-toggle="modal" data-target="#modal-edit">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>

                                                                <button type="button" class="btn btn-info btn-sm mr-1"
                                                                    data-toggle="modal" data-target="#modal-detail">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </button>

                                                                <div onclick="confirmDisable()">
                                                                    <form action="{{ route('dekan.disabled') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="dekan"
                                                                            value="{{ $dekan->id }}">
                                                                        <button class="btn btn-danger btn-sm"><i
                                                                                class="bi bi-x-circle"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Dekan</th>
                                            <th>Periode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
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
                <form action="{{ route('dekan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="fakultas_id" value="{{ $fakultas->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Dekan Fakultas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">NIDN</label>
                            <input type="text" class="form-control @error('nidn') is-invalid @enderror" name="nidn"
                                placeholder="NIDN dekan..." value="{{ old('nidn') }}" required>
                            @error('nidn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Nama Dekan</label>
                            <input type="text" class="form-control @error('namadekan') is-invalid @enderror"
                                name="namadekan" placeholder="Nama dekan fakultas..." value="{{ old('namadekan') }}"
                                required>
                            @error('namadekan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="" class="form-label">Gelar</label>
                            <input type="text" class="form-control @error('gelar') is-invalid @enderror"
                                name="gelar" placeholder="Gelar..." value="{{ old('gelar') }}" required>
                            @error('gelar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="" class="form-label">Periode Dari</label>
                                <input type="date" class="form-control @error('dari') is-invalid @enderror"
                                    name="dari" value="{{ old('dari') }}" required>
                                @error('dari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="" class="form-label">Periode Sampai</label>
                                <input type="date" class="form-control @error('sampai') is-invalid @enderror"
                                    name="sampai" value="{{ old('sampai') }}" required>
                                @error('sampai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar TTD Dekan</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image')is-invalid @enderror"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger"><small>{{ $message }}</small></div>
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
                <form action="{{ route('dekan.import') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="fakultas" value="{{ $fakultas->id }}">

                    <div class="modal-header">
                        <h4 class="modal-title">Import Dekan Fakultas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
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

    <!-- Modal Edit Dekan-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dekan.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="dekan" value="{{ $dekanActive != null ? $dekanActive->id : '' }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Dekan </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar TTD Dekan</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image')is-invalid @enderror"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger"><small>{{ $message }}</small></div>
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

    <!-- Modal Edit Fakultas-->
    <div class="modal fade" id="modal-edit-fakultas">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('fakultas.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="fakultas" value="{{ $fakultas->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Fakultas </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="form-label">Pilih Gambar Stempel Fakultas</label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image')is-invalid @enderror"
                                        name="image" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Dokumen</span>
                                </div>
                            </div>
                            @error('image')
                                <div class="text-danger"><small>{{ $message }}</small></div>
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

    {{-- Modal Detail --}}
    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Detail Dekan Fakultas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" class="form-label">NIDN</label>
                        <input type="text" class="form-control"
                            value="{{ $dekanActive != null ? $dekanActive->nidn : '' }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="" class="form-label">Nama Dekan</label>
                        <input type="text" class="form-control"
                            value="{{ $dekanActive != null ? $dekanActive->namadekan . ', ' . $dekanActive->gelar : '' }}"
                            disabled>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="form-label">Periode Dari</label>
                            <input type="text"
                                value="{{ $dekanActive != null ? \Carbon\Carbon::parse($dekanActive->dari)->formatLocalized('%d %B %Y') : '' }}"
                                class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="form-label">Periode Sampai</label>
                            <input type="text"
                                value="{{ $dekanActive != null ? \Carbon\Carbon::parse($dekanActive->sampai)->formatLocalized('%d %B %Y') : '' }}"
                                class="form-control" disabled>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        @if ($dekanActive)
                            <img src="{{ asset($dekanActive->image != null ? $dekanActive->image : 'ekapta/assets/img/not-found.png') }}"
                                alt="TTD Dekan" height="150">
                        @endif
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
