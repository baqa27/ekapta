@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

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
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_1" data-toggle="tab">Menunggu Jadwal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_2" data-toggle="tab">Dijadwalkan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_3" data-toggle="tab">Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Menunggu Jadwal -->
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars as $seminar)
                                                @if (!$seminar->tanggal_ujian)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}<br><small class="text-muted">{{ $seminar->mahasiswa->nim }}</small></td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ date('d M Y', strtotime($seminar->created_at)) }}</td>
                                                    <td><span class="badge bg-secondary">Menunggu Jadwal</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.seminar.prodi.detail', $seminar->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Dijadwalkan -->
                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Tempat</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars as $seminar)
                                                @if ($seminar->tanggal_ujian && !$seminar->is_lulus)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}<br><small class="text-muted">{{ $seminar->mahasiswa->nim }}</small></td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) }}</td>
                                                    <td>{{ $seminar->tempat_ujian }}</td>
                                                    <td><span class="badge bg-warning">Dijadwalkan</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.seminar.prodi.detail', $seminar->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Selesai -->
                                <div class="tab-pane" id="tab_3">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars as $seminar)
                                                @if ($seminar->is_lulus)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}<br><small class="text-muted">{{ $seminar->mahasiswa->nim }}</small></td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-' }}</td>
                                                    <td><span class="badge bg-success">Selesai</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.seminar.prodi.detail', $seminar->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




