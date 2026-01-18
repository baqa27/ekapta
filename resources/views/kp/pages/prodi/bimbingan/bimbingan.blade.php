@extends('kp.layouts.dashboard')

@section('content')
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">Tabel {{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#tab_1" data-toggle="tab">Sedang Bimbingan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#tab_2" data-toggle="tab">Selesai Bimbingan</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Tab Sedang Bimbingan -->
                                <div class="tab-pane active" id="tab_1">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama</th>
                                                    <th>Judul KP</th>
                                                    <th>Pembimbing</th>
                                                    <th>Progress</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($mahasiswas as $mhs)
                                                    @php
                                                        $peng = $mhs->pengajuans()->where('status', 'diterima')->first();
                                                        $pend = $mhs->pendaftarans()->where('status', 'diterima')->first();
                                                        $dsn = $mhs->dosens()->where('status', 'pembimbing')->first();
                                                        $done = \App\Helpers\AppHelper::check_bimbingan_is_complete($mhs);
                                                    @endphp
                                                    @if ($pend && !$done && !$mhs->jilid)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $mhs->nim }}</td>
                                                        <td>{{ $mhs->nama }}</td>
                                                        <td>{{ $peng ? $peng->judul : '-' }}</td>
                                                        <td>{{ $dsn ? $dsn->nama : '-' }}</td>
                                                        <td>
                                                            @foreach ($mhs->bimbingans as $b)
                                                                @if ($b->status == 'diterima')
                                                                    <span class="badge bg-success">{{ $b->bagian->bagian }}</span>
                                                                @elseif ($b->status == 'review')
                                                                    <span class="badge bg-secondary">{{ $b->bagian->bagian }}</span>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @if ($pend)
                                                                <a href="{{ url('cetak/surat-tugas-bimbingan/' . $pend->id) }}" target="_blank" class="btn btn-success btn-sm">
                                                                    <i class="fas fa-download"></i> Surat Tugas
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- Tab Selesai -->
                                <div class="tab-pane" id="tab_2">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama</th>
                                                    <th>Judul KP</th>
                                                    <th>Pembimbing</th>
                                                    <th>Status KP</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($mahasiswas as $mhs)
                                                    @php
                                                        $peng = $mhs->pengajuans()->where('status', 'diterima')->first();
                                                        $pend = $mhs->pendaftarans()->where('status', 'diterima')->first();
                                                        $dsn = $mhs->dosens()->where('status', 'pembimbing')->first();
                                                        $done = \App\Helpers\AppHelper::check_bimbingan_is_complete($mhs);
                                                    @endphp
                                                    @if ($done)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $mhs->nim }}</td>
                                                        <td>{{ $mhs->nama }}</td>
                                                        <td>{{ $peng ? $peng->judul : '-' }}</td>
                                                        <td>{{ $dsn ? $dsn->nama : '-' }}</td>
                                                        <td>
                                                            @if ($mhs->jilid && $mhs->jilid->status == 'selesai')
                                                                <span class="badge bg-success">Selesai KP</span>
                                                            @elseif ($mhs->seminar && $mhs->seminar->is_lulus)
                                                                <span class="badge bg-info">Lulus Seminar</span>
                                                            @else
                                                                <span class="badge bg-warning">Siap Seminar</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($pend)
                                                                <a href="{{ url('cetak/surat-tugas-bimbingan/' . $pend->id) }}" target="_blank" class="btn btn-success btn-sm">
                                                                    <i class="fas fa-download"></i> Surat Tugas
                                                                </a>
                                                            @endif
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
        </div>
    </section>
@endsection




