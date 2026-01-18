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

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                        </div>
                        <div class="card-body">

                            <button type="button" class="btn btn-success col-md-3 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-tambah">
                                <i class="bi bi-plus-circle mr-2"></i> Tambah Himpunan
                            </button>

                            <button type="button" class="btn btn-primary col-md-3 col-sm-12 mb-2" data-toggle="modal"
                                data-target="#modal-import">
                                <i class="bi bi-upload mr-2"></i> Import Data Himpunan
                            </button>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Himpunan</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Pendaftaran Seminar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($himpunans as $himpunan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td><code>{{ $himpunan->username }}</code></td>
                                            <td>{{ $himpunan->nama }}</td>
                                            <td>{{ $himpunan->email ?? '-' }}</td>
                                            <td>{{ $himpunan->prodi->namaprodi ?? '-' }}</td>
                                            <td>
                                                @if($himpunan->is_pendaftaran_seminar_open)
                                                <span class="badge bg-success">Dibuka</span>
                                                @else
                                                <span class="badge bg-secondary">Ditutup</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm shadow" 
                                                    data-toggle="modal" data-target="#modal-edit"
                                                    data-id="{{ $himpunan->id }}"
                                                    data-nama="{{ $himpunan->nama }}"
                                                    data-username="{{ $himpunan->username }}"
                                                    data-email="{{ $himpunan->email }}"
                                                    data-prodi_id="{{ $himpunan->prodi_id }}">
                                                    <i class="bi bi-gear mr-1"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm shadow"
                                                    data-toggle="modal" data-target="#modal-hapus"
                                                    data-id="{{ $himpunan->id }}"
                                                    data-nama="{{ $himpunan->nama }}">
                                                    <i class="bi bi-trash mr-1"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Nama Himpunan</th>
                                        <th>Email</th>
                                        <th>Prodi</th>
                                        <th>Pendaftaran Seminar</th>
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
                <form action="{{ route('himpunan.store') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Himpunan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Contoh: HIMATIF - Himpunan Mahasiswa Teknik Informatika">
                        </div>
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required placeholder="Contoh: himatif">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Contoh: himatif@unsiq.ac.id">
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->namaprodi }}</option>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('himpunan.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Himpunan <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="edit-nama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="edit-username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="edit-email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                            <input type="password" name="password" class="form-control" minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" id="edit-prodi_id" class="form-control" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->namaprodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div class="modal fade" id="modal-hapus">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('himpunan.delete') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="hapus-id">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus himpunan <strong id="hapus-nama"></strong>?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('himpunan.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Data Himpunan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong>Format CSV:</strong> nama, username, email, password, kode_prodi
                        </div>
                        <div class="form-group">
                            <label for="" class="form-label">Pilih File <br>
                                <small>Format file <b>.csv / .xlsx </b></small></label>
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file')is-invalid @enderror"
                                        name="file" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                
                            </div>
                            @error('file')
                                <small class="text-danger" style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Edit modal
    $('#modal-edit').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        $('#edit-id').val(button.data('id'));
        $('#edit-nama').val(button.data('nama'));
        $('#edit-username').val(button.data('username'));
        $('#edit-email').val(button.data('email'));
        $('#edit-prodi_id').val(button.data('prodi_id'));
    });

    // Hapus modal
    $('#modal-hapus').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        $('#hapus-id').val(button.data('id'));
        $('#hapus-nama').text(button.data('nama'));
    });

});
</script>
@endpush




