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
                                <i class="bi bi-plus-circle mr-1"></i> Tambah Prodi
                            </button>

                            <button type="button" class="btn btn-primary btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-1"></i> Import Data Prodi
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
                                        <th>Kode Prodi</th>
                                        <th>Nama Prodi</th>
                                        <th>Jenjang</th>
                                        <th>Bagian Bimbingan</th>
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
                                            <td>{{ $prodi->kode }}</td>
                                            <td>{{ $prodi->namaprodi }}</td>
                                            <td>{{ $prodi->jenjang }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <span
                                                        class="badge {{ count($prodi->bagians) == 0 ? 'bg-danger' : 'bg-success' }}">{{ count($prodi->bagians) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ url('/prodi/' . $prodi->id) }}"
                                                    class="btn btn-primary btn-sm shadow">
                                                    <i class="fas fa-plus mr-1"></i> Bagian Bimbingan
                                                </a>
                                                <a href="{{ route('prodi.reset.password' , $prodi->id) }}"
                                                    class="btn btn-danger btn-sm shadow" onclick="return confirm('Yakin ingin reset password?')">
                                                    <i class="fas fa-history"></i> Reset Password
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Prodi</th>
                                        <th>Nama Prodi</th>
                                        <th>Jenjang</th>
                                        <th>Bagian Bimbingan</th>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('prodi.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Prodi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Prodi <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control" required placeholder="Contoh: TI">
                        </div>
                        <div class="form-group">
                            <label>Nama Prodi <span class="text-danger">*</span></label>
                            <input type="text" name="namaprodi" class="form-control" required placeholder="Contoh: Teknik Informatika">
                        </div>
                        <div class="form-group">
                            <label>Jenjang <span class="text-danger">*</span></label>
                            <select name="jenjang" class="form-control" required>
                                <option value="">-- Pilih Jenjang --</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>NIDN Kaprodi</label>
                            <input type="text" name="kodekaprodi" class="form-control" placeholder="Masukkan NIDN Kaprodi (misal: 0601018501)">
                            <small class="text-muted">NIDN dosen yang menjadi Kaprodi untuk ditampilkan di surat</small>
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Fakultas</label>
                            <select name="fakultas_id" class="form-control">
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach(\App\Models\Fakultas::all() as $fakultas)
                                <option value="{{ $fakultas->id }}">{{ $fakultas->namafakultas }}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('prodi.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Prodi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
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
@endsection




