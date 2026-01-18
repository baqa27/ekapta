@extends('kp.layouts.dashboard')

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
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-success btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-tambah">
                                <i class="bi bi-plus-circle mr-1"></i> Tambah Dosen
                            </button>

                            <button type="button" class="btn btn-primary btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-1"></i> Import Data Dosen
                            </button>

                            <button type="button" class="btn btn-info btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import-penugasan">
                                <i class="bi bi-upload mr-1"></i> Import Data Penugasan
                            </button>

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-2">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                            @endif

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>Nama Dosen</th>
                                        <th>Prodi</th>
                                        <th>TTD</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($dosens as $dosen)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dosen->nidn }}</td>
                                            <td>{{ $dosen->nama . ', ' . $dosen->gelar }}</td>
                                            {{-- <td>
                                                {{ \App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi) != null ? \App\Helpers\AppHelper::instance()->getProdi($dosen->kodeprodi)->namaprodi : '' }}
                                            </td> --}}
                                            <td>
                                                @foreach ($dosen->prodis as $prodi)
                                                    {{ $prodi->namaprodi }},
                                                @endforeach
                                            </td>
                                            <th>
                                                @if ($dosen->ttd)
                                                    <img src="{{ asset($dosen->ttd) }}" height="50" style="max-width: 60px;" />
                                                @endif
                                            </th>
                                            <td>
                                                @if ($dosen->is_manual == 1)
                                                    <a href="{{ route('dosen.change.manual', $dosen->id) }}"
                                                        class="btn btn-secondary btn-sm shadow" onclick="return confirm('Yakin ingin disable?')">
                                                        <i class="bi bi-circle mr-1"></i> Disable Input Manual
                                                    </a>
                                                @else
                                                <a href="{{ route('dosen.change.manual', $dosen->id) }}"
                                                    class="btn btn-success btn-sm shadow" onclick="return confirm('Yakin ingin enable?')">
                                                    <i class="bi bi-check mr-1"></i> Enable Input Manual
                                                </a>
                                                @endif
                                                <a href="{{ route('dosen.edit', $dosen->id) }}"
                                                    class="btn btn-primary btn-sm shadow">
                                                    <i class="bi bi-gear mr-1"></i> Setting
                                                </a>
                                                <a href="{{ route('dosen.reset.password', $dosen->id) }}"
                                                    class="btn btn-danger btn-sm shadow"
                                                    onclick="return confirm('Yakin ingin reset password?')">
                                                    <i class="fas fa-history"></i> Reset Password
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>NIDN</th>
                                        <th>Nama Dosen</th>
                                        <th>Prodi</th>
                                        <th>TTD</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dosen.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Dosen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing"
                            class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download
                            Template File Import</a> <br><br>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File <br>
                                <small>Format file <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>

                            </div>
                            @error('file')
                                <small class="text-danger"
                                    style="position:relative;top:-15px;left:5px">{{ $message }}</small>
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

    <div class="modal fade" id="modal-import-penugasan">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dosen.prodi.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Penugasan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing"
                            class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download
                            Template File Import</a> <br><br>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File <br>
                                <small>Format file <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                        file</label>
                                </div>

                            </div>
                            @error('file')
                                <small class="text-danger"
                                    style="position:relative;top:-15px;left:5px">{{ $message }}</small>
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

    <!-- Modal Tambah Dosen -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('dosen.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Dosen</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIDN <span class="text-danger">*</span></label>
                                    <input type="text" name="nidn" class="form-control" required placeholder="Contoh: 0601018501">
                                </div>
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Dr. Ahmad Fauzi">
                                </div>
                                <div class="form-group">
                                    <label>Gelar</label>
                                    <input type="text" name="gelar" class="form-control" placeholder="Contoh: M.Kom">
                                </div>
                                <div class="form-group">
                                    <label>Kode Prodi</label>
                                    <input type="text" name="kodeprodi" class="form-control" placeholder="Contoh: TI">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" placeholder="16 digit NIK">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email@unsiq.ac.id">
                                </div>
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="hp" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>Mode Bimbingan</label>
                                    <select name="mode_bimbingan" class="form-control">
                                        <option value="both">Offline & Online</option>
                                        <option value="offline">Offline Saja</option>
                                        <option value="online">Online Saja</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span> <small class="text-muted">(default: NIDN)</small></label>
                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan untuk default NIDN">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection




