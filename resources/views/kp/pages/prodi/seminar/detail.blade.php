@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.seminar.prodi') }}">Seminar KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="mb-3">
                <a href="{{ route('kp.seminar.prodi') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($seminar->is_valid == 0) bg-secondary
                                @elseif ($seminar->is_valid == 2) bg-warning
                                @elseif ($seminar->is_valid == 1) bg-success @endif">
                                @if ($seminar->is_valid == 0)
                                    REVIEW
                                @elseif ($seminar->is_valid == 1)
                                    DITERIMA
                                @elseif ($seminar->is_valid == 2)
                                    REVISI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>NIM</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->nim }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Nama Lengkap</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->nama }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Prodi</strong></div>
                                <div class="col-md-8">{{ $seminar->mahasiswa->prodi }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Judul KP</strong></div>
                                <div class="col-md-8">{{ $seminar->pengajuan->judul ?? '-' }}</div>
                            </div>

                            @php
                                $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'pembimbing')->first();
                                if (!$dosen_pembimbing) {
                                    $dosen_pembimbing = $seminar->mahasiswa->dosens()->where('status', 'utama')->first();
                                }
                                $dosen_penguji = $seminar->dosenPenguji ?? null;
                            @endphp

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Dosen Pembimbing</strong></div>
                                <div class="col-md-8">{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Dosen Penguji</strong></div>
                                <div class="col-md-8">{{ $dosen_penguji ? $dosen_penguji->nama . ', ' . $dosen_penguji->gelar : '-' }}</div>
                            </div>

                            <hr>

                            @if($seminar->file_laporan)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>File Laporan KP</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->file_laporan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->file_laporan) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($seminar->file_pengesahan)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Lembar Pengesahan</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->file_pengesahan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->file_pengesahan) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            @if($seminar->bukti_bayar)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Bukti Pembayaran</strong></div>
                                <div class="col-md-8">
                                    <a href="{{ storage_url($seminar->bukti_bayar) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->bukti_bayar) }}
                                    </a>
                                </div>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Pendaftaran</strong></div>
                                <div class="col-md-8">{{ $seminar->created_at->format('d M Y H:i') }}</div>
                            </div>

                            @if($seminar->tanggal_acc)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Validasi</strong></div>
                                <div class="col-md-8"><span class="text-success">{{ date('d M Y H:i', strtotime($seminar->tanggal_acc)) }}</span></div>
                            </div>
                            @endif

                            @if($seminar->tanggal_ujian)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tanggal Seminar</strong></div>
                                <div class="col-md-8"><span class="text-primary">{{ $seminar->tanggal_ujian->format('d M Y H:i') }}</span></div>
                            </div>
                            @endif

                            @if($seminar->tempat_ujian)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Tempat Seminar</strong></div>
                                <div class="col-md-8">{{ $seminar->tempat_ujian }}</div>
                            </div>
                            @endif

                            @if($seminar->nilai_seminar)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Nilai Seminar</strong></div>
                                <div class="col-md-8"><span class="text-success font-weight-bold">{{ number_format($seminar->nilai_seminar, 2) }}</span></div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                Pendaftaran seminar KP dikelola oleh <strong>Himpunan</strong>. Prodi hanya dapat melihat data.
                            </div>
                        </div>
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">{{ count($seminar->revisis) }}</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse ($revisis as $revisi)
                                <div class="card bg-light mb-2">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:i') }}
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            <a href="{{ storage_url($revisi->lampiran) }}" class="ml-3" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($revisi->lampiran) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada revisi</p>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




