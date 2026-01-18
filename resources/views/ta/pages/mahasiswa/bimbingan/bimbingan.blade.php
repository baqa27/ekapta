@extends('ta.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bimbingan Tugas Akhir</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengajuan TA</a></li>
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
            <div class="row">
                <div class="col-md-10">
                    @if (\App\Helpers\AppHelper::check_bimbingan_is_complete($mahasiswa))
                        <a href="{{ route('ta.cetak.riwayat.bimbingan.mahasiswa') }}" class="btn btn-warning mb-3"
                            target="_blank"><i class="fas fa-download"></i> DOWNLOAD LEMBAR BIMBINGAN SKRIPSI</a>
                        {{-- @if ($jilid)
                            @if ($jilid->status == 3)
                                <a href="#" class="btn btn-primary shadow mb-3"><i class="bi bi-check-circle "></i>
                                    PENJILIDAN SUDAH DI KONFIRMASI OLEH ADMIN. SILAHKAN TUNGGU JILID SELESAI</a>
                            @elseif ($jilid->status == 4)
                                <a href="#" class="btn btn-success shadow mb-3"><i class="bi bi-check-circle "></i>
                                    PENJILIDAN SUDAH SELESAI. SILAHKAN AMBIL KE FOTOKOPIAN DAN BAYAR KE FOTOKOPIAN SEBESAR
                                    <b>Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}</b></b></a>
                            @elseif ($jilid->status == 2)
                                <a href="{{ route('ta.jilid.edit', $jilid->id) }}" class="btn btn-primary shadow mb-3"><i
                                        class="bi bi-info-circle"></i> PENJILIDAN BERSTATUS REVISI. SILAHKAN SUBMIT
                                    ULANG</b></a>
                            @elseif ($jilid->status == 1)
                                <a href="#" class="btn btn-secondary shadow mb-3"><i
                                        class="bi bi-hourglass-bottom "></i> SUDAH PENGAJUAN JILID KE FOTOKOPIAN. SILAHKAN
                                    TUNGGU KONFIRMASI DARI ADMIN!</a>
                            @endif
                        @else
                            <a href="{{ route('ta.jilid.create') }}" class="btn btn-primary shadow mb-3"><i
                                    class="fas fa-book"></i> AJUKAN PENJILIDAN SKRIPSI KE FOTOKOPIAN</a>
                        @endif --}}
                    @endif

                    @if ($is_expired)
                        <div class="mb-3 bg-danger rounded p-2">
                            Masa bimbingan anda sudah habis, silahkan lakukan <a
                                href="{{ route('ta.pendaftaran.disable', $pendaftaran_acc->id) }}"><u><b>Perpanjangan
                                        TA!</b></u></a>
                        </div>
                    @else
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Tanggal Berakhir Bimbingan : <b>{{ \App\Helpers\AppHelper::parse_date_short($date_expired) }}
                            </b>
                            @if ($is_seminar)
                                , Selamat anda sudah bisa melakukan
                                <b><a href="{{ route('ta.seminar.create') }}">Pendaftaran Seminar TA</a></b>
                            @endif
                            @if ($is_ujian)
                                , <b><a href="{{ route('ta.ujian.create') }}">Pendaftaran Ujian Pendadaran</a></b>
                            @endif
                            @if ($check_ujian_has_done)
                                , <b><a href="{{ route('ta.jilid.create') }}"> Ajukan Penjilidan Tugas Akhir</a></b>
                            @endif
                        </div>

                        <div class="d-flex justify-content-center mb-3 bg-primary rounded p-2 countdown"
                            data-expire="{{ \Carbon\Carbon::parse($date_expired)->format('Y/m/d h:i:s') }}">
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex p-0">
                                    <h3 class="card-title p-3">Bimbingan Anda</h3>
                                    <ul class="nav nav-pills ml-auto p-2">
                                        <li class="nav-item"><a class="nav-link active" href="#tab_1"
                                                data-toggle="tab">Pembimbing
                                                Utama</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Pembimbing
                                                Pendamping</a>
                                        </li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">

                                            Dosen Pembimbing : <strong>
                                                @if ($dosen_utama)
                                                    {{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}
                                                @endif
                                            </strong>

                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($bimbingans_utama as $bimbingan)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $bimbingan->bagian->bagian }}</td>
                                                            <td>
                                                                @if ($bimbingan->tanggal_bimbingan)
                                                                    {{ date('d M Y H:m', strtotime($bimbingan->tanggal_bimbingan)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bimbingan->tanggal_acc)
                                                                    {{ date('d M Y H:m', strtotime($bimbingan->tanggal_acc)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bimbingan->status == 'review')
                                                                    <span class="badge bg-secondary">Review</span>
                                                                @elseif ($bimbingan->status == 'revisi')
                                                                    <span class="badge bg-warning">Revisi</span>
                                                                @elseif ($bimbingan->status == 'diterima')
                                                                    <span class="badge bg-success">Diterima</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!$is_expired)
                                                                    @if ($bimbingan->status == 'review')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                    @elseif ($bimbingan->status == 'revisi')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                            class="btn btn-primary btn-sm shadow"
                                                                            type="submit"><i
                                                                                class="fas fa-upload mr-1"></i>Submit</a>
                                                                    @elseif ($bimbingan->status == 'diterima')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                        {{-- @elseif($bimbingan->bagian->bagian == 'BAB I') --}}
                                                                    @elseif($no - 1 == 1)
                                                                        @if ($bimbingan->status == null)
                                                                            <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                                class="btn btn-primary btn-sm shadow"
                                                                                type="submit"><i
                                                                                    class="fas fa-upload mr-1"></i>Submit</a>
                                                                        @endif
                                                                    @endif

                                                                    @if (count(\App\Helpers\AppHelper::instance()->getBimbinganIsAcc($bimbingan->mahasiswa->id)) > 1)
                                                                        @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->mahasiswa->nim) == false)
                                                                            @if ($bimbingan->status == null)
                                                                                <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                                    class="btn btn-primary btn-sm shadow"
                                                                                    type="submit"><i
                                                                                        class="fas fa-upload mr-1"></i>Submit</a>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_2">

                                            Dosen Pembimbing : <strong>
                                                @if ($dosen_pendamping)
                                                    {{ $dosen_pendamping->nama . ', ' . $dosen_pendamping->gelar }}
                                                @endif
                                            </strong>

                                            <table id="example2" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no = 1;
                                                    @endphp
                                                    @foreach ($bimbingans_pendamping as $bimbingan)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $bimbingan->bagian->bagian }}</td>
                                                            <td>
                                                                @if ($bimbingan->tanggal_bimbingan)
                                                                    {{ date('d M Y H:m', strtotime($bimbingan->tanggal_bimbingan)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bimbingan->tanggal_acc)
                                                                    {{ date('d M Y H:m', strtotime($bimbingan->tanggal_acc)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($bimbingan->status == 'review')
                                                                    <span class="badge bg-secondary">Review</span>
                                                                @elseif ($bimbingan->status == 'revisi')
                                                                    <span class="badge bg-warning">Revisi</span>
                                                                @elseif ($bimbingan->status == 'diterima')
                                                                    <span class="badge bg-success">Diterima</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!$is_expired)
                                                                    @if ($bimbingan->status == 'review')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow mr-2">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                    @elseif ($bimbingan->status == 'revisi')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>

                                                                        <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                            class="btn btn-primary btn-sm shadow"
                                                                            type="submit"><i
                                                                                class="fas fa-upload mr-1"></i>Submit</a>
                                                                    @elseif ($bimbingan->status == 'diterima')
                                                                        <a href="{{ url('/bimbingan/detail/' . $bimbingan->id) }}"
                                                                            class="btn btn-info btn-sm shadow">
                                                                            <i class="fas fa-info-circle mr-1"></i> Detail
                                                                        </a>
                                                                        {{-- @elseif($bimbingan->bagian->bagian == 'BAB I') --}}
                                                                    @elseif($no - 1 == 1)
                                                                        @if ($bimbingan->status == null)
                                                                            <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                                class="btn btn-primary btn-sm shadow"
                                                                                type="submit"><i
                                                                                    class="fas fa-upload mr-1"></i>Submit</a>
                                                                        @endif
                                                                    @endif

                                                                    @if (count(\App\Helpers\AppHelper::instance()->getBimbinganIsAcc($bimbingan->mahasiswa->id)) > 1)
                                                                        @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->mahasiswa->nim) == false)
                                                                            @if ($bimbingan->status == null)
                                                                                <a href="{{ url('/bimbingan/edit/' . $bimbingan->id) }}"
                                                                                    class="btn btn-primary btn-sm shadow"
                                                                                    type="submit"><i
                                                                                        class="fas fa-upload mr-1"></i>Submit</a>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Bagian Bimbingan</th>
                                                        <th>Tanggal Bimbingan</th>
                                                        <th>Tanggal ACC</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>

                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center mb-3 card" style="max-height: 300px;">
                    <div class="text-center mt-3">
                        <a href="{{ url('public/riwayat-bimbingan/' . base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid()) }}"
                            class="btn btn-secondary btn-sm shadow mb-2" target="_blank">
                            Tracking Bimbingan <small><i class="bi bi-chevron-right"></i></small>
                        </a>
                        <p class="text-secondary">Atau scan QRCODE dibawah:</p>
                        <div class="mb-3">
                            {!! QrCode::size(150)->generate(
                                url('public/riwayat-bimbingan/' . base64_encode(Auth::guard('mahasiswa')->user()->id) . uniqid()),
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->

@endsection




