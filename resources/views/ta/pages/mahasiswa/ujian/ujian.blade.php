@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
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
    <div class="content">
        <div class="container">

            @if ($ujian_not_lulus && !$ujian_has_ready)
                <a href="{{ route('ta.ujian.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Pendaftaran Ujian TA</a>
            @endif

            @if ($check_ujian_has_done)
                <div class="mb-3 bg-success rounded p-2">
                    SELAMAT! PROSES PENGAJUAN TA, PENDAFTARAN TA, BIMBINGAN TA, SEMINAR TA, DAN UJIAN TA SUDAH SELESAI,
                    silahkan lakukan <a href="{{ route('ta.jilid.create') }}"><b><u>PENJILIDAN TUGAS AKHIR !</u></b></a>
                </div>
            @endif

            @if ($reviews_has_acc)
                <div class="mb-3 bg-primary rounded p-2">
                    Selamat bimbingan Ujian Pendadaran anda sudah selesai.
                </div>
            @else
                <div class="mb-3 bg-secondary rounded p-2">
                    Silahkan tunggu review dan penilaian dari dosen pembimbing dan penguji!
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">ujian Proposal Anda</h3>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    Dosen Pembimbing
                                </div>
                                <div class="col-md-10">
                                    1.
                                    <strong>{{ $dosen_utama ? $dosen_utama->nama . ' ,' . $dosen_utama->gelar : '' }}</strong>
                                    <br>
                                    2.
                                    <strong>{{ $dosen_pendamping ? $dosen_pendamping->nama . ' ,' . $dosen_pendamping->gelar : '' }}</strong>
                                </div>
                            </div>

                            {{-- <div class="row mb-5">
                            <div class="col-md-2">
                                Dosen Penguji
                            </div>
                            <div class="col-md-10">
                                @if (count($dosens_penguji) != 0)
                                    @php $no = 1; @endphp
                                    @foreach ($dosens_penguji as $dosen)
                                        <span>{{ $no++ }}. <b>{{ $dosen->dosen->nama }},
                                                {{ $dosen->dosen->gelar }}</b></span>
                                        <br>
                                    @endforeach
                                @endif
                            </div>
                        </div> --}}

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Tanggal Ujian</th>
                                        <th>Tempat Ujian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($ujians as $ujian)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('ta.ujian.detail', $ujian->id) }}">{{ $ujian->pengajuan->judul }}</a>
                                            </td>
                                            <td>
                                                {{ $ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($ujian->tanggal_ujian) : null }}
                                            </td>
                                            <td>{{ $ujian->tempat_ujian }} </td>
                                            <td>
                                                @if ($ujian->is_lulus == \App\Models\TA\Ujian::VALID_LULUS)
                                                    <span class="badge bg-success">LULUS</span>
                                                @elseif($ujian->is_lulus == \App\Models\TA\Ujian::NOT_VALID_LULUS)
                                                    <span class="badge bg-danger">TIDAK LULUS</span>
                                                @else
                                                    @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW)
                                                        <span class="badge bg-secondary">REVIEW</span>
                                                    @elseif ($ujian->is_valid == \App\Models\TA\Ujian::VALID_LULUS)
                                                        <span class="badge bg-success">VALID</span>
                                                    @elseif ($ujian->is_valid == \App\Models\TA\Ujian::NOT_VALID_LULUS)
                                                        <span class="badge bg-warning">TIDAK VALID</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ujian->is_valid == \App\Models\TA\Ujian::REVIEW)
                                                    <a href="{{ route('ta.ujian.detail', $ujian->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle"></i> Detail
                                                    </a>
                                                @elseif ($ujian->is_valid == \App\Models\TA\Ujian::NOT_VALID_LULUS)
                                                    <a href="{{ route('ta.ujian.edit', $ujian->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-upload"></i> Submit
                                                    </a>
                                                @endif
                                                @if ($check_ujian_has_done && $ujian->is_lulus != \App\Models\TA\Ujian::NOT_VALID_LULUS)
                                                    <a href="{{ route('ta.ujian.reviews', $ujian->id) }}"
                                                        class="btn btn-info btn-sm mb-1">
                                                        <i class="bi bi-star"></i> Lihat Review
                                                    </a>
                                                    @if ($reviews_has_acc)
                                                        {{-- &nbsp;
                                                        <a href="{{ route('ujian.edit.proposal', $ujian->id) }}"
                                                            class="btn btn-primary btn-sm mb-1">
                                                            <i class="bi bi-upload"></i> Submit Laporan TA
                                                        </a> --}}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Pendaftaran</th>
                                        <th>Tanggal Ujian</th>
                                        <th>Tempat Ujian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
@endsection




