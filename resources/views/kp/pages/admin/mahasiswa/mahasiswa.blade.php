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
                                <i class="bi bi-plus-circle mr-1"></i> Tambah Mahasiswa
                            </button>

                            <button type="button" class="btn btn-primary btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import-1">
                                <i class="bi bi-upload mr-1"></i> Import Data Mahasiswa
                            </button>

                            <button type="button" class="btn btn-info btn-sm shadow mb-2 mr-1" data-toggle="modal"
                                data-target="#modal-import-2">
                                <i class="bi bi-upload mr-1"></i> Import Data Mahasiswa Detail
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
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Tahun Masuk</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($mahasiswas as $mahasiswa)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $mahasiswa->nim }}</td>
                                            <td>{{ $mahasiswa->nama }}</td>
                                            <td>{{ $mahasiswa->prodi }}</td>
                                            <td>{{ $mahasiswa->thmasuk }}</td>
                                            <td>
                                                {{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->semester : '' }}
                                            </td>
                                            <td>
                                                {{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->status : '' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('kp.mahasiswa.reset.password' , $mahasiswa->id) }}"
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
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Prodi</th>
                                        <th>Tahun Masuk</th>
                                        <th>Semester</th>
                                        <th>Status</th>
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

    <!-- Modal Import Mahasiswa-->
    <div class="modal fade" id="modal-import-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.mahasiswa.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Mahasiswa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
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

    <!-- Modal Import Mahasiswa Detail-->
    <div class="modal fade" id="modal-import-2">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('kp.mahasiswa.detail.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Mahasiswa Detail</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="https://drive.google.com/drive/folders/1AD3y7NZGUvjkoyQAdyegVzVXWNB_XJqQ?usp=sharing" class="btn btn-warning btn-sm shadow" target="_blank"><i class="fas fa-download"></i> Download Template File Import</a> <br><br>
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

    <!-- Modal Tambah Mahasiswa -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('kp.mahasiswa.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Mahasiswa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIM <span class="text-danger">*</span></label>
                                    <input type="text" name="nim" class="form-control" required placeholder="Contoh: 20210001">
                                </div>
                                <div class="form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control" required placeholder="Contoh: Andi Pratama">
                                </div>
                                <div class="form-group">
                                    <label>Prodi <span class="text-danger">*</span></label>
                                    <select name="prodi" class="form-control" required>
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach(\App\Models\Prodi::all() as $prodi)
                                        <option value="{{ $prodi->namaprodi }}">{{ $prodi->namaprodi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tahun Masuk <span class="text-danger">*</span></label>
                                    <input type="text" name="thmasuk" class="form-control" required placeholder="Contoh: 2021">
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jeniskelamin" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" placeholder="16 digit NIK">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="email@mhs.unsiq.ac.id">
                                </div>
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="hp" class="form-control" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span> <small class="text-muted">(default: NIM)</small></label>
                                    <input type="password" name="password" class="form-control" placeholder="Kosongkan untuk default NIM">
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




