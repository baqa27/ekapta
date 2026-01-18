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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Presentase Nilai Prodi {{ $prodi->namaprodi }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ta.prodi.presentase.nilai.store') }}" method="post">
                                @csrf
                                <input type="hidden" name="prodi_id" value="{{ $prodi->id }}">
                                <div class="row border mb-3">
                                    <div class="col-md-12">
                                        <p>Total Presentase ke 4 Nilai Harus 100</p>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Substansi / Isi Materi %</label>
                                            <input type="number" name="presentase_1"
                                                   class="form-control @error('presentase_1') is-invalid @enderror"
                                                   value="{{ $presentase_nilai ? $presentase_nilai->presentase_1 : '' }}"
                                                   required>
                                            @error('presentase_1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Kompetensi Ilmu %</label>
                                            <input type="number" name="presentase_2"
                                                   class="form-control @error('presentase_2') is-invalid @enderror"
                                                   value="{{ $presentase_nilai ? $presentase_nilai->presentase_2 : '' }}"
                                                   required>
                                            @error('presentase_2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Metodologi dan Redaksi TA %</label>
                                            <input type="number" name="presentase_3"
                                                   class="form-control @error('presentase_3') is-invalid @enderror"
                                                   value="{{ $presentase_nilai ? $presentase_nilai->presentase_3 : '' }}"
                                                   required>
                                            @error('presentase_3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Presentasi %</label>
                                            <input type="number" name="presentase_4"
                                                   class="form-control @error('presentase_4') is-invalid @enderror"
                                                   value="{{ $presentase_nilai ? $presentase_nilai->presentase_4 : '' }}"
                                                   required>
                                            @error('presentase_4')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row border">
                                        <div class="col-md-12">
                                            <p>Total Presentase ke 2 Nilai Harus 100</p>
                                        </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Bobot Nilai Dosen Penguji</label>
                                                <input type="number" name="bobot_penguji"
                                                       class="form-control @error('bobot_penguji') is-invalid @enderror"
                                                       value="{{ $presentase_nilai ? $presentase_nilai->bobot_penguji : '' }}"
                                                       required>
                                                @error('bobot_penguji')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Bobot Nilai Dosen Pembimbing</label>
                                                <input type="number" name="bobot_pembimbing"
                                                       class="form-control @error('bobot_pembimbing') is-invalid @enderror"
                                                       value="{{ $presentase_nilai ? $presentase_nilai->bobot_pembimbing : '' }}"
                                                       required>
                                                @error('bobot_pembimbing')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection




