@extends('ta.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.himpunan') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Toggle Buka/Tutup Pendaftaran Seminar --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card {{ $is_pendaftaran_open ? 'bg-success' : 'bg-danger' }}">
                        <div class="card-body d-flex justify-content-between align-items-center py-2">
                            <span class="text-white">
                                <i class="fas {{ $is_pendaftaran_open ? 'fa-door-open' : 'fa-door-closed' }} mr-2"></i>
                                Pendaftaran Seminar KP: <strong>{{ $is_pendaftaran_open ? 'DIBUKA' : 'DITUTUP' }}</strong>
                            </span>
                            <form action="{{ route('ta.seminar.himpunan.toggle') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $is_pendaftaran_open ? 'btn-light' : 'btn-warning' }}">
                                    <i class="fas {{ $is_pendaftaran_open ? 'fa-lock' : 'fa-unlock' }} mr-1"></i>
                                    {{ $is_pendaftaran_open ? 'Tutup Pendaftaran' : 'Buka Pendaftaran' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_review" data-toggle="tab">
                                        Review <span class="badge bg-secondary">{{ count($seminars_review) }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_revisi" data-toggle="tab">
                                        Revisi <span class="badge bg-warning">{{ count($seminars_revisi) }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_diterima" data-toggle="tab">
                                        Diterima <span class="badge bg-success">{{ count($seminars_acc) }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Review -->
                                <div class="tab-pane active" id="tab_review">
                                    <table id="example1" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars_review as $seminar)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nim }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}</td>
                                                    <td>{{ $seminar->mahasiswa->prodi }}</td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->created_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('ta.seminar.himpunan.review', $seminar->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Revisi -->
                                <div class="tab-pane" id="tab_revisi">
                                    <table id="example2" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars_revisi as $seminar)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nim }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}</td>
                                                    <td>{{ $seminar->mahasiswa->prodi }}</td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->created_at->format('d M Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('ta.seminar.himpunan.review', $seminar->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye mr-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tab Diterima -->
                                <div class="tab-pane" id="tab_diterima">
                                    <table id="example3" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Prodi</th>
                                                <th>Judul KP</th>
                                                <th>Tanggal Seminar</th>
                                                <th>Tempat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach ($seminars_acc as $seminar)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $seminar->mahasiswa->nim }}</td>
                                                    <td>{{ $seminar->mahasiswa->nama }}</td>
                                                    <td>{{ $seminar->mahasiswa->prodi }}</td>
                                                    <td>{{ $seminar->pengajuan->judul }}</td>
                                                    <td>{{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : '-' }}</td>
                                                    <td>{{ $seminar->tempat_ujian ?? '-' }}</td>
                                                    <td>
                                                        <a href="{{ route('ta.seminar.himpunan.review', $seminar->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fas fa-eye mr-1"></i> Detail
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




