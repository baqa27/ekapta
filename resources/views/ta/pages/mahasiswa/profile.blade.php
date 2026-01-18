@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0"> Hai! {{ Auth::guard('mahasiswa')->user()->nama }}</h1> --}}
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Profile</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            @if(Auth::guard('mahasiswa')->user()->email == '-')
                <div class="alert alert-warning mb-2" style="text-transform: uppercase;">
                    Silahkan update Email anda dengan email aktif untuk mendapatkan notifikasi!
                </div>
            @endif
            @if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62')
                <div class="alert alert-warning mb-2" style="text-transform: uppercase;">
                    Silahkan update Nomor WhatsApp anda dengan awalan kode negara <b>62</b>!
                </div>
            @endif
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Kontak Mahasiswa</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ta.profile.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $mahasiswa->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email (Hapus tanda - )</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ $mahasiswa->email }}" name="email" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">No. WhatsApp (Inputan diawali 62, contoh: 6281234567890)</label>
                                            <input type="text" class="form-control @if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62') is-invalid @endif"
                                                value="{{ $mahasiswa->hp }}" name="hp" required>
                                            @if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62')
                                                <div class="invalid-feedback">
                                                        Nomor WhatsApp belum diawali dengan <b>62</b>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Alamat</label>
                                            <input type="text"
                                                class="form-control @error('alamat') is-invalid @enderror"
                                                value="{{ $mahasiswa->alamat }}" name="alamat" required>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-success" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">NIM</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->nim }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nama Lengkap</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->nama }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tahun Masuk</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->thmasuk }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Prodi</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->prodi }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tempat Lahir</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->tptlahir }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tanggal Lahir</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->tgllahir }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Jenis Kelamin</label>
                                        <input type="text" class="form-control"
                                            value="{{ $mahasiswa->jeniskelamin }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Dosen Wali</label>
                                            @php
                                            $dosen_wali = \App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali) ? \App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali) : null
                                        @endphp
                                        <input type="text" class="form-control"
                                            value="{{ $dosen_wali ? $dosen_wali->nama.', '.$dosen_wali->gelar : '' }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">NIK</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->nik }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Kelas</label>
                                        <input type="text" class="form-control" value="{{ $mahasiswa->kelas }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Semester</label>
                                        <input type="text" class="form-control"
                                            value="{{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->semester : '' }}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Status</label>
                                        <input type="text" class="form-control"
                                            value="{{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim) != null ? \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->status : '' }}"
                                            disabled>
                                    </div>
                                </div>
                            </div>
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




