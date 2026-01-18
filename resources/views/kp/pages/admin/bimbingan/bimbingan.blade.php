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
                                <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Bimbingan
                                        Aktif</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Bimbingan
                                        Selesai</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <table id="examplebutton" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($mahasiswas as $mahasiswa)
                                                @if (!$mahasiswa->jilidKP)
                                                    @php
                                                        $pendaftaran_acc = \App\Models\KP\Pendaftaran::where(
                                                            'mahasiswa_id',
                                                            $mahasiswa->id,
                                                        )
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                        $is_expired = $pendaftaran_acc
                                                            ? \App\Helpers\AppHelper::instance()->is_expired_in_one_year(
                                                                $pendaftaran_acc->tanggal_acc,
                                                            )
                                                            : null;
                                                        $pengajuan = $mahasiswa
                                                            ->pengajuansKP()
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                    @endphp

                                                    @if($pengajuan)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>
                                                                {{ $mahasiswa->nim }}
                                                            </td>
                                                            <td>
                                                                {{ $mahasiswa->nama }}
                                                            </td>
                                                            <td>
                                                                @if (substr($mahasiswa->hp, 0, 2) === '62')
                                                                    <a href="https://api.whatsapp.com/send?phone={{ $mahasiswa->hp }}"
                                                                        class="btn btn-success btn-sm rounded-pill"
                                                                        target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $mahasiswa->prodi }}
                                                            </td>
                                                            <td>
                                                                {{ $pengajuan->judul }}
                                                            </td>
                                                            <td>
                                                                @if (count($mahasiswa->bimbingansKP) != 0)
                                                                    @if ($pendaftaran_acc)
                                                                        @if ($is_expired)
                                                                            {{-- <span class="badge bg-danger">TIDAK AKTIF</span> --}}
                                                                        @else
                                                                            <span class="badge bg-success">AKTIF</span>
                                                                        @endif
                                                                    @else
                                                                        {{-- <span class="badge bg-danger">TIDAK AKTIF</span> --}}
                                                                    @endif
                                                                @else
                                                                    {{-- <span class="badge bg-danger">TIDAK AKTIF</span> --}}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($mahasiswa->bimbingansKP()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first())
                                                                    {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingansKP()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan) }}
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if ($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first())
                                                                    {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->created_at) }}
                                                                @endif
                                                            </td>

                                                            {{-- Dosen Pembimbing KP (1 dosen) --}}
                                                            <td>
                                                                @php
                                                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                                                @endphp
                                                                @if($dosen_pembimbing)
                                                                    <small><b>{{ $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar }}</b></small>
                                                                    @foreach ($mahasiswa->bimbingansKP as $bimbingan)
                                                                        <small>
                                                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                                <span class="text-success">{{ $bimbingan->bagian->bagian }}(ACC)</span>
                                                                            @else
                                                                                <span>{{ $bimbingan->bagian->bagian }}</span>
                                                                            @endif
                                                                        </small>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary">BELUM ADA PEMBIMBING</span>
                                                                @endif
                                                            </td>

                                                            {{-- Penguji Seminar KP --}}
                                                            <td>
                                                                @if ($mahasiswa->seminarKP && $mahasiswa->seminarKP->reviews)
                                                                    @foreach ($mahasiswa->seminarKP->reviews()->where('dosen_status', 'penguji')->get() as $review)
                                                                        <small>{{ $review->dosen->nama }}</small><br>
                                                                    @endforeach
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if ($mahasiswa->seminarKP)
                                                                    {{ $mahasiswa->seminarKP->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminarKP->tanggal_ujian) : '' }}
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if ($mahasiswa->jilidKP)
                                                                    {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->jilidKP->created_at) }}
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if (count($mahasiswa->bimbingansKP) != 0)
                                                                    <a href="{{ route('kp.bimbingan.review.admin', $mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->id) }}"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="bi bi-info-circle"></i>
                                                                        Detail Bimbingan</a>
                                                                    <a href="{{ route('kp.bimbingan.canceled', $mahasiswa->id) }}"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Yakin ingin membatalkan bimbingan?')"><i
                                                                            class="bi bi-x-circle"></i>
                                                                        Batalkan Bimbingan</a>
                                                                @endif
                                                                @if ($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first())
                                                                    <a href="{{ url('cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->id) }}"
                                                                        target="_blank" class="btn btn-success btn-sm "><i
                                                                            class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                        KP</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_2">
                                    <table id="examplebutton2" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($mahasiswas as $mahasiswa)
                                                @if ($mahasiswa->jilidKP || count($mahasiswa->bimbingan_canceledsKP) != 0)
                                                    @php
                                                        $pendaftaran_acc = \App\Models\KP\Pendaftaran::where(
                                                            'mahasiswa_id',
                                                            $mahasiswa->id,
                                                        )
                                                            ->where('status', 'diterima')
                                                            ->first();
                                                        $is_expired = $pendaftaran_acc
                                                            ? \App\Helpers\AppHelper::instance()->is_expired_in_one_year(
                                                                $pendaftaran_acc->tanggal_acc,
                                                            )
                                                            : null;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>
                                                            {{ $mahasiswa->nim }}
                                                        </td>
                                                        <td>
                                                            {{ $mahasiswa->nama }}
                                                        </td>
                                                        <td>
                                                            @if (substr($mahasiswa->hp, 0, 2) === '62')
                                                                <a href="https://api.whatsapp.com/send?phone={{ $mahasiswa->hp }}"
                                                                    class="btn btn-success btn-sm rounded-pill"
                                                                    target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ $mahasiswa->prodi }}
                                                        </td>
                                                        <td>
                                                            @if ($mahasiswa->jilidKP)
                                                                {{ $mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul }}
                                                            @else
                                                                {{ $mahasiswa->bimbingan_canceledsKP()->orderBy('created_at', 'desc')->first()->pengajuan->judul }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (count($mahasiswa->bimbingan_canceledsKP) != 0 && $mahasiswa->jilidKP)
                                                                <span class="badge bg-success">SELESAI</span>
                                                            @elseif (count($mahasiswa->bimbingan_canceledsKP) != 0)
                                                                <span class="badge bg-danger">DIBATALKAN</span>
                                                            @else
                                                                <span class="badge bg-success">SELESAI</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($mahasiswa->bimbingansKP()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first())
                                                                {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingansKP()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan) }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first())
                                                                {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->created_at) }}
                                                            @endif
                                                        </td>

                                                        {{-- Dosen Pembimbing KP (1 dosen) --}}
                                                        <td>
                                                            @if ($mahasiswa->jilidKP)
                                                                @php
                                                                    $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                                                @endphp
                                                                @if($dosen_pembimbing)
                                                                    <small><b>{{ $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar }}</b></small>
                                                                    @foreach ($mahasiswa->bimbingansKP as $bimbingan)
                                                                        <small>
                                                                            @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                                <span class="text-success">{{ $bimbingan->bagian->bagian }}(ACC)</span>
                                                                            @else
                                                                                <span>{{ $bimbingan->bagian->bagian }}</span>
                                                                            @endif
                                                                        </small>
                                                                    @endforeach
                                                                @else
                                                                    <span class="badge bg-secondary">BELUM ADA PEMBIMBING</span>
                                                                @endif
                                                            @else
                                                                @php
                                                                    $canceled = $mahasiswa->bimbingan_canceledsKP()->first();
                                                                @endphp
                                                                @if($canceled && $canceled->dosen)
                                                                    {{ $canceled->dosen->nama . ', ' . $canceled->dosen->gelar }}
                                                                @endif
                                                            @endif
                                                        </td>

                                                        {{-- Penguji Seminar KP --}}
                                                        <td>
                                                            @if ($mahasiswa->seminarKP && $mahasiswa->seminarKP->reviews)
                                                                @foreach ($mahasiswa->seminarKP->reviews()->where('dosen_status', 'penguji')->get() as $review)
                                                                    <small>{{ $review->dosen->nama }}</small><br>
                                                                @endforeach
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($mahasiswa->seminarKP)
                                                                {{ $mahasiswa->seminarKP->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminarKP->tanggal_ujian) : '' }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($mahasiswa->jilidKP)
                                                                {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->jilidKP->created_at) }}
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if (count($mahasiswa->bimbingansKP) != 0)
                                                                <a href="{{ route('kp.bimbingan.review.admin', $mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->id) }}"
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="bi bi-info-circle"></i>
                                                                    Detail Bimbingan</a>
                                                            @endif
                                                            @if ($mahasiswa->pendaftaransKP()->where('status', 'diterima')->first())
                                                                <a href="{{ url('cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftaransKP()->where('status', 'diterima')->first()->id) }}"
                                                                    target="_blank" class="btn btn-success btn-sm "><i
                                                                        class="fas fa-download"></i> Surat Tugas Bimbingan
                                                                    KP</a>
                                                            @endif
                                                        </td>


                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIM</th>
                                                <th>Nama Mahasiswa</th>
                                                <th>Kontak</th>
                                                <th>Prodi</th>
                                                <th>Judul Kerja Praktek</th>
                                                <th>Status Bimbingan</th>
                                                <th>Terakhir Bimbingan</th>
                                                <th>Tanggal Pendaftaran KP</th>
                                                <th>Dosen Pembimbing</th>
                                                <th>Penguji Seminar</th>
                                                <th>Tanggal Seminar KP</th>
                                                <th>Tanggal Jilid KP</th>
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
    </section>
    <!-- /.content -->
@endsection




