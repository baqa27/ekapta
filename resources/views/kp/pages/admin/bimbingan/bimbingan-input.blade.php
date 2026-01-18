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
                                    <a class="nav-link active" href="#tab_1" data-toggle="tab">
                                        Bimbingan Review <span class="badge bg-secondary">{{ count($bimbingans_review) }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_2" data-toggle="tab">
                                        Bimbingan Diterima <span class="badge bg-success">{{ count($bimbingans_diterima) }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_3" data-toggle="tab">
                                        Bimbingan Revisi <span class="badge bg-warning">{{ count($bimbingans_revisi) }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Review -->
                                <div class="tab-pane active" id="tab_1">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Tanggal Submit</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($bimbingans_review as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->nama }} - {{ $bimbingan->mahasiswa->nim }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->prodi }}</td>
                                                    <td>{{ $bimbingan->bagian->bagian }}</td>
                                                    <td>{{ date('d M Y H:i', strtotime($bimbingan->created_at)) }}</td>
                                                    <td><span class="badge bg-secondary">Review</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.bimbingan.review.prodi', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Diterima -->
                                <div class="tab-pane" id="tab_2">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Tanggal ACC</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($bimbingans_diterima as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->nama }} - {{ $bimbingan->mahasiswa->nim }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->prodi }}</td>
                                                    <td>{{ $bimbingan->bagian->bagian }}</td>
                                                    <td>{{ $bimbingan->tanggal_acc ? date('d M Y H:i', strtotime($bimbingan->tanggal_acc)) : '-' }}</td>
                                                    <td><span class="badge bg-success">Diterima</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.bimbingan.review.prodi', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Revisi -->
                                <div class="tab-pane" id="tab_3">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Mahasiswa</th>
                                                <th>Prodi</th>
                                                <th>Bagian Bimbingan</th>
                                                <th>Tanggal Submit</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($bimbingans_revisi as $bimbingan)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->nama }} - {{ $bimbingan->mahasiswa->nim }}</td>
                                                    <td>{{ $bimbingan->mahasiswa->prodi }}</td>
                                                    <td>{{ $bimbingan->bagian->bagian }}</td>
                                                    <td>{{ date('d M Y H:i', strtotime($bimbingan->created_at)) }}</td>
                                                    <td><span class="badge bg-warning">Revisi</span></td>
                                                    <td>
                                                        <a href="{{ route('kp.bimbingan.review.prodi', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
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




