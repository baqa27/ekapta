@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seminar Kerja Praktek</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">

            {{-- Tombol Pendaftaran --}}
            @if (!$seminar)
                @if ($is_pendaftaran_open)
                    <a href="{{ route('kp.seminar.create') }}" class="btn btn-primary mb-4">
                        <i class="fas fa-plus mr-2"></i> Pendaftaran Seminar KP
                    </a>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-door-closed mr-2"></i>
                        <strong>Pendaftaran Seminar KP belum dibuka.</strong> Silahkan tunggu pengumuman dari Himpunan.
                    </div>
                @endif
            @else
                {{-- Alert Status --}}
                @if ($seminar->status_seminar == 'selesai' || $seminar->status_seminar == 'selesai_seminar')
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Selamat! Seminar KP anda sudah selesai. Silahkan lanjut ke <b><a href="{{ route('kp.pengumpulan-akhir.create') }}">Jilid KP.</a></b>
                    </div>
                @elseif ($seminar->status_seminar == 'revisi')
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Pendaftaran perlu <strong>direvisi</strong>. Silahkan perbaiki sesuai catatan dari Himpunan.
                    </div>
                @endif
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar KP Anda</h3>
                        </div>
                        <div class="card-body">

                            {{-- Tabel Seminar --}}
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Laporan</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jadwal Seminar</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($seminar)
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <a href="{{ route('kp.seminar.detail', $seminar->id) }}">
                                                    {{ Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 50) }}
                                                </a>
                                            </td>
                                            <td>{{ $seminar->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if ($seminar->tanggal_ujian)
                                                    <strong>{{ $seminar->tanggal_ujian->format('d M Y') }}</strong><br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i> {{ $seminar->tanggal_ujian->format('H:i') }} WIB
                                                        @if($seminar->tempat_ujian)
                                                            <br><i class="fas fa-map-marker-alt"></i> {{ $seminar->tempat_ujian }}
                                                        @endif
                                                        @if($seminar->urutan_presentasi)
                                                            <br><i class="fas fa-list-ol"></i> Urutan: {{ $seminar->urutan_presentasi }}
                                                        @endif
                                                    </small>
                                                @else
                                                    <span class="text-muted"><i class="fas fa-hourglass-half"></i> Menunggu jadwal</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($seminar->status_seminar)
                                                    @case('menunggu_verifikasi')
                                                        <span class="badge bg-secondary">Review</span>
                                                        @break
                                                    @case('diterima')
                                                        <span class="badge bg-success">Diterima</span>
                                                        @break
                                                    @case('revisi')
                                                        <span class="badge bg-warning">Revisi Berkas</span>
                                                        @break
                                                    @case('dijadwalkan')
                                                        <span class="badge bg-info">Dijadwalkan</span>
                                                        @break
                                                    @case('selesai_seminar')
                                                    @case('selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $seminar->status_label ?? 'Review' }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('kp.seminar.detail', $seminar->id) }}" class="btn btn-primary btn-sm mb-1">
                                                    <i class="fas fa-info-circle mr-1"></i> Detail
                                                </a>

                                                @if ($seminar->status_seminar == 'revisi' || $seminar->is_valid == 2)
                                                    <a href="{{ route('kp.seminar.edit', $seminar->id) }}" class="btn btn-success btn-sm mb-1">
                                                        <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Laporan</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Jadwal Seminar</th>
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
@endsection




