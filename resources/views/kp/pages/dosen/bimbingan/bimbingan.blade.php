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
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Bimbingan
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Bimbingan
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Bimbingan
                                        Revisi</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($bimbingans as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $bimbingan->mahasiswa->nama }}
                                                        {{ '(' . $bimbingan->mahasiswa->nim . ')' }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->prodi->namaprodi }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->bagian }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $bimbingan->status }}</span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('kp.bimbingan.review', $bimbingan->id) }}"
                                                            class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-check-circle mr-1"></i> Review
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($bimbingans_diterima as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $bimbingan->mahasiswa->nama }}
                                                        {{ '(' . $bimbingan->mahasiswa->nim . ')' }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->prodi->namaprodi }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->bagian }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">{{ $bimbingan->status }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('kp.bimbingan.review', $bimbingan->id) }}"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            @if(!$bimbingan->mahasiswa->seminar()->where('is_valid', 1)->first())
                                                            <form action="{{ route('kp.bimbingan.cancel.acc') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $bimbingan->id }}">
                                                                <button class="btn btn-danger btn-sm shadow" type="submit">
                                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_3">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($bimbingans_revisi as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $bimbingan->mahasiswa->nama }}
                                                        {{ '(' . $bimbingan->mahasiswa->nim . ')' }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->prodi->namaprodi }}
                                                    </td>
                                                    <td>
                                                        {{ $bimbingan->bagian->bagian }}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning">{{ $bimbingan->status }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('kp.bimbingan.review', $bimbingan->id) }}"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            <form action="{{ route('kp.bimbingan.cancel.revisi') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="id"
                                                                    value="{{ $bimbingan->id }}">
                                                                <button class="btn btn-danger btn-sm shadow" type="submit">
                                                                    <i class="bi bi-x-circle"></i> Batalkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Status</th>
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
    </section>
    <!-- /.content -->
@endsection




