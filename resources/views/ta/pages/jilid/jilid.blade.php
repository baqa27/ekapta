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

            @if (count($jilids) == 0)
                <a href="{{ route('ta.jilid.create') }}" class="btn btn-primary mb-4"><i class="fas fa-plus mr-2"></i>
                    Ajukan Jilid Tugas Akhir</a>
            @endif

            @if ($jilid)
                @if ($jilid->status == 3)
                    <div class="alert alert-primary">
                        <i class="fas fa-info-circle"></i> Dokumen Tugas Akhir sudah dikonfirmasi oleh admin dan siap untuk
                        dijilid. Silahkan konfirmasi dan melakukan pembayaran ke Fotocopy fastikom dengan membawa
                        dokumen-dokumen asli yang akan disertakan dalam penjilidan TA seperti lembar keaslian TA, lembar
                        pengesahan, lembar bimbingan, lampiran-lampiran, dll.
                        <br>Ket: DIBUAT JILID RANGKAP 2
                    </div>
                @elseif ($jilid->status == 4)
                    <a href="#" class="btn btn-success shadow mb-3"><i class="bi bi-check-circle "></i>
                        PENJILIDAN SUDAH SELESAI. SILAHKAN AMBIL KE FOTOKOPIAN DAN BAYAR KE FOTOKOPIAN SEBESAR
                        <b>Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}</b></b></a>
                @elseif ($jilid->status == 2)
                    <a href="{{ route('ta.jilid.edit', $jilid->id) }}" class="btn btn-primary shadow mb-3"><i
                            class="bi bi-info-circle"></i> PENJILIDAN BERSTATUS REVISI. SILAHKAN SUBMIT ULANG</b></a>
                @elseif ($jilid->status == 1)
                    <a href="#" class="btn btn-secondary shadow mb-3"><i class="bi bi-hourglass-bottom "></i> SUDAH
                        PENGAJUAN JILID KE FOTOKOPIAN. SILAHKAN
                        TUNGGU KONFIRMASI DARI ADMIN!</a>
                    <div class="p-2 rounded border border-warning mb-3" style="background-color: #fff5a6;">
                        <i class="fas fa-info-circle"></i> PASTIKAN FILE UPLOAD GOOGLE DRIVE AKSES DIBUAT PUBLIC!
                    </div>
                @endif
            @else
                <a href="{{ route('ta.jilid.create') }}" class="btn btn-primary shadow mb-3"><i class="fas fa-book"></i> AJUKAN
                    PENJILIDAN SKRIPSI KE FOTOKOPIAN</a>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>JUDUL TUGAS AKHIR</th>
                                        <th>TOTAL PEMBAYARAN</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($jilids as $jilid)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <a
                                                    href="{{ route('ta.jilid.detail.mahasiswa', $jilid->id) }}">{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}</a>
                                            </td>
                                            <td>
                                                @if ($jilid->total_pembayaran)
                                                    <span class="text-success">
                                                        Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($jilid->status == 1)
                                                    <span class="badge bg-secondary">REVIEW</span>
                                                @elseif ($jilid->status == 2)
                                                    <span class="badge bg-warning">REVISI</span>
                                                @elseif ($jilid->status == 3)
                                                    <span class="badge bg-primary">VALID</span>
                                                @elseif ($jilid->status == 4)
                                                    <span class="badge bg-success">SELESAI</span>
                                                    @if ($jilid->is_completed)
                                                        <br>
                                                        <span class="badge bg-primary"><i class="fas fa-check-circle"></i> Sudah
                                                            disetorkan ke perpus</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($jilid->status == 2)
                                                    <a href="{{ route('ta.jilid.edit', $jilid->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-upload"></i>
                                                        Submit</a>
                                                @else
                                                    <a href="{{ route('ta.jilid.detail.mahasiswa', $jilid->id) }}"
                                                        class="btn btn-info btn-sm"><i class="fas fa-info-circle"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>JUDUL TUGAS AKHIR</th>
                                        <th>TOTAL PEMBAYARAN</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
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




