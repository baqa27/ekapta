@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 fw-bold">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            
            {{-- Alerts --}}
            @if(Auth::guard('mahasiswa')->user()->email == '-')
                <div class="alert alert-warning border-0 shadow-sm mb-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Silahkan update Email utama Anda untuk notifikasi!
                </div>
            @endif
            @if(substr(Auth::guard('mahasiswa')->user()->hp,0,2) !== '62')
                <div class="alert alert-danger border-0 shadow-sm mb-3">
                    <i class="fas fa-phone-slash mr-2"></i> Nomor WhatsApp Wajib diawali <b>62</b> (bukan 08).
                </div>
            @endif

            <div class="row">
                <!-- Left Column: Profile Card -->
                <div class="col-md-4">
                    <div class="card card-primary card-outline shadow-sm">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama) }}&background=random"
                                    alt="User profile picture">
                            </div>
                            <h3 class="profile-username text-center mt-3">{{ $mahasiswa->nama }}</h3>
                            <p class="text-muted text-center">{{ $mahasiswa->nim }}</p>
                            <p class="text-muted text-center mb-1">{{ $mahasiswa->prodi }}</p>
                            <hr>
                            <strong><i class="fas fa-book mr-1"></i> Data Akademik</strong>
                            <p class="text-muted small mt-2">
                                Semester: {{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->semester ?? '-' }}<br>
                                Status: {{ \App\Helpers\AppHelper::instance()->getMahasiswaDetail($mahasiswa->nim)->status ?? '-' }}<br>
                                Dosen Wali: {{ \App\Helpers\AppHelper::instance()->getDosen($mahasiswa->kodedosenwali)->nama ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings Form -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white p-3 border-bottom-0">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Edit Kontak</a></li>
                                <li class="nav-item"><a class="nav-link" href="#biodata" data-toggle="tab">Detail Biodata</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Settings Tab -->
                                <div class="active tab-pane" id="settings">
                                    <form class="form-horizontal" action="{{ route('kp.profile.update') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $mahasiswa->id }}">
                                        
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email Utama</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                    name="email" value="{{ $mahasiswa->email }}" placeholder="Email">
                                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">WhatsApp (62..)</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control @if(substr($mahasiswa->hp,0,2) !== '62') is-invalid @endif" 
                                                    name="hp" value="{{ $mahasiswa->hp }}" placeholder="628xxx">
                                                <small class="text-muted">Contoh: 6281234567890</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Alamat</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="alamat" rows="3">{{ $mahasiswa->alamat }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-3 col-sm-9">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Biodata Tab (Read Only) -->
                                <div class="tab-pane" id="biodata">
                                    <dl class="row">
                                        <dt class="col-sm-4">Tempat, Tgl Lahir</dt>
                                        <dd class="col-sm-8">{{ $mahasiswa->tptlahir }}, {{ $mahasiswa->tgllahir }}</dd>

                                        <dt class="col-sm-4">Jenis Kelamin</dt>
                                        <dd class="col-sm-8">{{ $mahasiswa->jeniskelamin }}</dd>

                                        <dt class="col-sm-4">NIK</dt>
                                        <dd class="col-sm-8">{{ $mahasiswa->nik }}</dd>

                                        <dt class="col-sm-4">Kelas</dt>
                                        <dd class="col-sm-8">{{ $mahasiswa->kelas }}</dd>

                                        <dt class="col-sm-4">Tahun Masuk</dt>
                                        <dd class="col-sm-8">{{ $mahasiswa->thmasuk }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection




