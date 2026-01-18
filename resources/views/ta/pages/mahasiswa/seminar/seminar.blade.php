@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Seminar Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar TA</a></li>
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

            @if ($is_ujian)
                <div class="mb-3 bg-success rounded p-2">
                    Selamat anda sudah bisa melakukan pendaftaran Ujian Pendadaran TA. Silahkan lakukan pendaftaran
                    <a href="{{ route('ta.ujian.create') }}"><b><u>Ujian Pendadaran TA!</u></b></a>
                </div>
            @endif

            @if (count($reviews_acc) < 2)
                <div class="mb-3 bg-secondary rounded p-2">
                    Silahkan tunggu review dan penilaian dari dosen pembimbing dan penguji!
                </div>
            @else
                <div class="mb-3 bg-primary rounded p-2">
                    Selamat bimbingan Seminar TA anda sudah selesai.
                </div>
            @endif

            @if (!$seminar)
                <a href="{{ route('ta.seminar.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Pendaftaran Seminar Proposal</a>
            @else
                @if ($seminar->is_valid == 0)
                    <div class="mb-3 bg-primary rounded p-2">
                        Anda sudah melakukan pendaftaran Seminar TA, silahkan tunggu validasi dari Admin.
                    </div>
                @elseif($seminar->is_valid == 2)
                    <div class="mb-3 bg-warning rounded p-2">
                        Silahkan revisi pendaftaran Seminar TA anda sesuai instruksi dari admin, kemudian submit ulang!
                    </div>
                @endif
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Seminar Proposal Anda</h3>
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
                                            <span>{{ $no++  }}. <b>{{$dosen->dosen->nama}}, {{$dosen->dosen->gelar}}</b></span><br>
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

                                    @if ($seminar)
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <a
                                                    href="{{ route('ta.seminar.detail', $seminar->id) }}">{{ $seminar->pengajuan->judul }}</a>
                                            </td>
                                            <td>
                                                {{ $seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) : null }}
                                            </td>
                                            <td>{{ $seminar->tempat_ujian }}</td>
                                            <td>
                                                @if ($seminar->is_lulus == 1)
                                                    <span class="badge bg-success">LULUS</span>
                                                @else
                                                    @if ($seminar->is_valid == 0)
                                                        <span class="badge bg-secondary">REVIEW</span>
                                                    @elseif ($seminar->is_valid == 1)
                                                        <span class="badge bg-success">VALID</span>
                                                    @elseif ($seminar->is_valid == 2)
                                                        <span class="badge bg-warning">TIDAK VALID</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($seminar->is_valid == 0)
                                                    <a href="{{ route('ta.seminar.detail', $seminar->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-info-circle"></i> Detail
                                                    </a>
                                                @elseif ($seminar->is_valid == 1)
                                                    @if ($check_ujian_has_done)
                                                        <a href="{{ route('ta.seminar.reviews', $seminar->id) }}"
                                                            class="btn btn-info btn-sm mb-1">
                                                            <i class="bi bi-star"></i> Lihat Review
                                                        </a>
                                                        @if ($reviews_has_acc)
                                                            {{-- <a href="{{ route('seminar.edit.proposal', $seminar->id) }}"
                                                                class="btn btn-primary btn-sm mb-1">
                                                                <i class="bi bi-upload"></i> Submit Laporan Proposal
                                                            </a> --}}
                                                        @endif
                                                    @endif
                                                @elseif ($seminar->is_valid == 2)
                                                    <a href="{{ route('ta.seminar.edit', $seminar->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="bi bi-upload"></i> Submit
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
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




