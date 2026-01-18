@extends('layouts.dashboard')

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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h3 class="card-title flex-grow-1">Tabel {{ $title }}</h3>
                            {{-- <div class="card-title flex-shrink-0">
                                <a href="" class="btn btn-success btn-sm">
                                    <i class="fas fa-download"></i> Download Laporan Excel
                                </a>
                            </div> --}}
                        </div>
                        <div class="card-body table-responsive">

                            {{-- <span class="badge badge-success"> <i class="fas fa-check-circle mr-1"></i>
                                Diterima/Acc
                            </span>
                            <span class="badge badge-secondary"> <i class="fas fa-circle mr-1"></i>
                                Review/Belum Di Acc
                            </span> --}}

                            <table id="examplebutton" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Kontak</th>
                                        <th>Prodi</th>
                                        <th>Judul Tugas Akhir</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Tanggal Pendaftaran TA</th>
                                        <th>Pembimbing 1</th>
                                        <th>Pembimbing 2</th>
                                        <th>Penguji Seminar 1</th>
                                        <th>Penguji Seminar 2</th>
                                        <th>Penguji Seminar 3</th>
                                        <th>Penguji Ujian 1</th>
                                        <th>Penguji Ujian 2</th>
                                        <th>Penguji Ujian 3</th>
                                        <th>Tanggal Ujian Seminar</th>
                                        <th>Tanggal Ujian Pendadaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($mahasiswas as $mahasiswa)
                                        @php
                                            $ujian = $mahasiswa
                                                ->ujians()
                                                ->where('is_valid', \App\Models\TA\Ujian::VALID_LULUS)
                                                ->first();
                                        @endphp
                                        @if (!$mahasiswa->jilid)
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
                                                            class="btn btn-success btn-sm rounded-pill" target="_blank"><i
                                                                class="fab fa-whatsapp"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $mahasiswa->prodi }}
                                                </td>
                                                <td>
                                                    @if ($mahasiswa->pengajuans()->where('status', 'diterima')->first())
                                                        {{ $mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}
                                                    @else
                                                        <span class="badge bg-secondary">BELUM PENGAJUAN JUDUL</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (count(
                                                            $mahasiswa->bimbingans()->whereIn('status', ['revisi', 'review', 'diterima'])->get()) != 0)
                                                        <span class="badge bg-success">AKTIF</span>
                                                    @else
                                                        {{-- <span class="badge bg-danger">TIDAK AKTIF</span> --}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($mahasiswa->bimbingans()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first())
                                                        {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->bimbingans()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan) }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($mahasiswa->pendaftarans()->where('status', 'diterima')->first())
                                                        {{ \App\Helpers\AppHelper::parse_date_export($mahasiswa->pendaftarans()->where('status', 'diterima')->first()->created_at) }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if (count($mahasiswa->bimbingans) != 0)
                                                        @php
                                                            $dosen_utama = $mahasiswa
                                                                ->dosens()
                                                                ->where('status', 'utama')
                                                                ->first();
                                                        @endphp
                                                        <small><b>{{ $dosen_utama->nama . ',' . $dosen_utama->gelar }}</b></small>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'utama')
                                                                <small>
                                                                    @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                        <span
                                                                            class="text-success">{{ $bimbingan->bagian->bagian }}(ACC)
                                                                        </span>
                                                                    @else
                                                                        <span>{{ $bimbingan->bagian->bagian }}
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-secondary">BELUM ADA BIMBINGAN</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (count($mahasiswa->bimbingans) != 0)
                                                        @php
                                                            $dosen_pendamping = $mahasiswa
                                                                ->dosens()
                                                                ->where('status', 'pendamping')
                                                                ->first();
                                                        @endphp
                                                        <small>
                                                            <b>{{ $dosen_pendamping->nama . ',' . $dosen_pendamping->gelar }}</b>
                                                        </small>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'pendamping')
                                                                <small>
                                                                    @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                        <span
                                                                            class="text-success">{{ $bimbingan->bagian->bagian }}(ACC)
                                                                        </span>
                                                                    @else
                                                                        <span>{{ $bimbingan->bagian->bagian }}
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-secondary">BELUM ADA BIMBINGAN</span>
                                                    @endif
                                                </td>

                                                @if ($mahasiswa->seminar)
                                                    @if (count($mahasiswa->seminar->reviews) > 3)
                                                        @foreach ($mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get() as $review)
                                                            <td>
                                                                {{ $review->dosen->nama . ',' . $review->dosen->gelar }}
                                                            </td>
                                                        @endforeach
                                                        @if (count($mahasiswa->seminar->reviews()->where('dosen_status', 'penguji')->get()) < 3)
                                                            <td></td>
                                                        @endif
                                                    @else
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif

                                                @if ($ujian)
                                                    @if (count($ujian->reviews) > 3)
                                                        @foreach ($ujian->reviews()->where('dosen_status', 'penguji')->get() as $review)
                                                            <td>
                                                                {{ $review->dosen->nama . ',' . $review->dosen->gelar }}
                                                            </td>
                                                        @endforeach
                                                        @if (count($ujian->reviews()->where('dosen_status', 'penguji')->get()) < 3)
                                                            <td></td>
                                                        @endif
                                                    @else
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif

                                                <td>
                                                    @if ($mahasiswa->seminar)
                                                        {{ $mahasiswa->seminar->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($mahasiswa->seminar->tanggal_ujian) : '' }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($ujian)
                                                        {{ $ujian->tanggal_ujian ? \App\Helpers\AppHelper::parse_date_export($ujian->tanggal_ujian) : '' }}
                                                    @endif
                                                </td>

                                                <td>
                                                    {{-- @if (count($mahasiswa->bimbingans) != 0)
                                                    <a href="{{ route('bimbingan.review.admin', $mahasiswa->pengajuans()->where('status', 'diterima')->first()->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="bi bi-info-circle"></i>
                                                        Detail Bimbingan</a>
                                                @endif --}}
                                                    @if ($mahasiswa->pendaftarans()->where('status', 'diterima')->first())
                                                        <a href="{{ url('cetak/surat-tugas-bimbingan/' . $mahasiswa->pendaftarans()->where('status', 'diterima')->first()->id) }}"
                                                            target="_blank" class="btn btn-success btn-sm "><i
                                                                class="fas fa-download"></i> Surat Tugas Bimbingan TA</a>
                                                    @endif
                                                </td>

                                                {{-- <td>
                                                    @if (count($mahasiswa->bimbingans) != 0)
                                                    @php
                                                        $dosen_utama = $mahasiswa
                                                            ->dosens()
                                                            ->where('status', 'utama')
                                                            ->first();
                                                        $dosen_pendamping = $mahasiswa
                                                            ->dosens()
                                                            ->where('status', 'pendamping')
                                                            ->first();
                                                    @endphp
                                                    <div class="mt-2 border p-2 rounded">
                                                        <small>
                                                            <b>{{ $dosen_utama->nama.', '.$dosen_utama->gelar }}</b>
                                                        </small>
                                                        <br>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'utama')
                                                                @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                    <span class="badge badge-success">
                                                                        <i class="fas fa-check-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-2 border p-2 rounded">
                                                        <small><b>{{ $dosen_pendamping->nama.','.$dosen_pendamping->gelar }}</b>
                                                        </small>
                                                        <br>
                                                        @foreach ($mahasiswa->bimbingans as $bimbingan)
                                                            @if ($bimbingan->pembimbing == 'pendamping')
                                                                @if (\App\Helpers\AppHelper::instance()->cekBagianIsAcc($bimbingan->id))
                                                                    <a
                                                                        href="{{ route('bimbingan.review.prodi', $bimbingan->id) }}">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                            {{ $bimbingan->bagian->bagian }}
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    @else

                                                    @endif
                                                </td> --}}
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
                                        <th>Judul Tugas Akhir</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Tanggal Pendaftaran TA</th>
                                        <th>Pembimbing 1</th>
                                        <th>Pembimbing 2</th>
                                        <th>Penguji Seminar 1</th>
                                        <th>Penguji Seminar 2</th>
                                        <th>Penguji Seminar 3</th>
                                        <th>Penguji Ujian 1</th>
                                        <th>Penguji Ujian 2</th>
                                        <th>Penguji Ujian 3</th>
                                        <th>Tanggal Ujian Seminar</th>
                                        <th>Tanggal Ujian Pendadaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
@endsection
