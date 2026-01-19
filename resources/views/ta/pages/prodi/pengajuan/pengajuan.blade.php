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
                <div class="col-12">
                    <!-- Custom Tabs -->
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Pengajuan
                                        Review</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pengajuan
                                        Diterima</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Pengajuan
                                        Revisi</a></li>
                                <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Pengajuan
                                        Ditolak</a></li>
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
                                            @foreach ($pengajuans as $pengajuan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $pengajuan->mahasiswa->nama }} - {{ $pengajuan->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{ $pengajuan->prodi->namaprodi }}
                                                    </td>
                                                    <td>{{ $pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pengajuan->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pengajuan->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pengajuan->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif ($pengajuan->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('/pengajuan/review/' . $pengajuan->id) }}"
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
                                            @foreach ($pengajuans_acc as $pengajuan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $pengajuan->mahasiswa->nama }} - {{ $pengajuan->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{ $pengajuan->prodi->namaprodi }}
                                                    </td>
                                                    <td>{{ $pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pengajuan->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pengajuan->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pengajuan->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif ($pengajuan->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ url('/pengajuan/review/' . $pengajuan->id) }}"
                                                                class="btn btn-info btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            @if ($pengajuan->pendaftaran == null)
                                                                @if (count($pengajuan->mahasiswa->dosens) == 0)
                                                                    <div onclick="confirmCancel()">
                                                                        <form action="{{ route('ta.pengajuan.cancel.acc') }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="id"
                                                                                value="{{ $pengajuan->id }}">
                                                                            <button
                                                                                class="btn btn-danger btn-sm mr-2 shadow"
                                                                                type="submit">
                                                                                <i class="bi bi-x-circle mr-1"></i> Batalkan
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endif
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
                                            @foreach ($pengajuans_revisi as $pengajuan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $pengajuan->mahasiswa->nama }} - {{ $pengajuan->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{ $pengajuan->prodi->namaprodi }}
                                                    </td>
                                                    <td>{{ $pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pengajuan->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pengajuan->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pengajuan->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif ($pengajuan->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('/pengajuan/review/' . $pengajuan->id) }}"
                                                            class="btn btn-info btn-sm shadow">
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
                                <div class="tab-pane" id="tab_4">

                                    <table id="example4" class="table table-bordered">
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
                                            @foreach ($pengajuans_ditolak as $pengajuan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>
                                                        {{ $pengajuan->mahasiswa->nama }} - {{ $pengajuan->mahasiswa->nim }}
                                                    </td>
                                                    <td>
                                                        {{ $pengajuan->prodi->namaprodi }}
                                                    </td>
                                                    <td>{{ $pengajuan->judul }}</td>
                                                    <td>
                                                        @if ($pengajuan->status == 'review')
                                                            <span class="badge bg-secondary">Review</span>
                                                        @elseif ($pengajuan->status == 'revisi')
                                                            <span class="badge bg-warning">Revisi</span>
                                                        @elseif ($pengajuan->status == 'diterima')
                                                            <span class="badge bg-success">Diterima</span>
                                                        @elseif ($pengajuan->status == 'ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <a href="{{ url('/pengajuan/review/' . $pengajuan->id) }}"
                                                                class="btn btn-info btn-sm shadow mr-2">
                                                                <i class="fas fa-info-circle mr-1"></i> Detail
                                                            </a>
                                                            @if ($pengajuan->status != 'diterima')
                                                                <div onclick="confirmCancel()">
                                                                    <form action="{{ route('ta.pengajuan.cancel.tolak') }}"
                                                                        method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="id"
                                                                            value="{{ $pengajuan->id }}">
                                                                        <input type="hidden" name="mahasiswa_id"
                                                                            value="{{ $pengajuan->mahasiswa->id }}">
                                                                        <button class="btn btn-danger btn-sm mr-2 shadow"
                                                                            type="submit">
                                                                            <i class="bi bi-x-circle mr-1"></i> Batalkan
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




