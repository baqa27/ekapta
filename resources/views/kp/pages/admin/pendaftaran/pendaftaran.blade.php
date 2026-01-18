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
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pendaftaran
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pendaftaran
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Pendaftaran
                                        Revisi</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">

                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pendaftarans as $pendaftaran)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->nama }} - {{ $pendaftaran->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->prodi }}
                                                    </td>
                                                    <td>{{ $pendaftaran->pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pendaftaran->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pendaftaran->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pendaftaran->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('kp.pendaftaran.review', $pendaftaran->id) }}"
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
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_2">

                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pendaftarans_acc as $pendaftaran)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->nama }}
                                                        {{  $pendaftaran->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->prodi }}
                                                    </td>
                                                    <td>{{ $pendaftaran->pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pendaftaran->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pendaftaran->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pendaftaran->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ route('kp.pendaftaran.review', $pendaftaran->id) }}"
                                                                class="btn btn-primary btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>

                                                            @php
                                                                $cekBimbinganIsActive = \App\Helpers\AppHelper::instance()
                                                                    ->getMahasiswa($pendaftaran->mahasiswa->nim)
                                                                    ->bimbingans()
                                                                    ->whereIn('status', ['review', 'revisi', 'diterima'])
                                                                    ->get();
                                                            @endphp
                                                            @if (count($cekBimbinganIsActive) == 0)
                                                                <div onclick="confirmCancel()">
                                                                    <form action="{{ route('kp.pendaftaran.cancel.acc') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $pendaftaran->id }}">
                                                                        <button class="btn btn-danger btn-sm shadow">
                                                                            <i class="bi bi-x-circle mr-1"></i>Batalkan
                                                                        </button>
                                                                    </form>
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
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="tab_3">

                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($pendaftarans_revisi as $pendaftaran)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->nama }} -
                                                        {{  $pendaftaran->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{  $pendaftaran->mahasiswa->prodi }}
                                                    </td>
                                                    <td>{{ $pendaftaran->pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pendaftaran->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pendaftaran->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pendaftaran->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('kp.pendaftaran.review', $pendaftaran->id) }}"
                                                            class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
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
                                                <th>Judul</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>

    </section>
    <!-- /.content -->
@endsection




