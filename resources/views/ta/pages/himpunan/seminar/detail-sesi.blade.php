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
                        <li class="breadcrumb-item"><a href="{{ route('ta.jadwal.himpunan') }}">Penjadwalan</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <!-- Info Sesi -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Sesi</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td><i class="fas fa-calendar mr-2"></i>Tanggal</td>
                                    <td>: <strong>{{ $sesi->tanggal->translatedFormat('l, d F Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-clock mr-2"></i>Waktu</td>
                                    <td>: <strong>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-map-marker-alt mr-2"></i>Tempat</td>
                                    <td>: <strong>{{ $sesi->tempat }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-user-tie mr-2"></i>Penguji</td>
                                    <td>: <strong>{{ $sesi->dosenPenguji->nama ?? '-' }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-users mr-2"></i>Peserta</td>
                                    <td>: <strong>{{ count($sesi->seminars) }} mahasiswa</strong></td>
                                </tr>
                            </table>
                            
                            @if($sesi->catatan_teknis)
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle mr-1"></i> {{ $sesi->catatan_teknis }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Link Penilaian -->
                    <div class="card {{ $sesi->is_token_used ? 'card-secondary' : 'card-success' }} card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Link Penilaian Dosen</h3>
                        </div>
                        <div class="card-body">
                            @if($sesi->is_token_used)
                                <div class="alert alert-secondary">
                                    <i class="fas fa-check-circle mr-1"></i> Link sudah digunakan
                                    <br><small>{{ $sesi->token_used_at->translatedFormat('d F Y, H:i') }}</small>
                                </div>
                            @else
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" value="{{ $sesi->link_penilaian }}" id="linkPenilaian" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-success btn-sm" onclick="copyLink()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Kirim link ini ke dosen penguji</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Daftar Peserta -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Peserta Seminar</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60">Urutan</th>
                                        <th>NIM</th>
                                        <th>Nama</th>
                                        <th>Judul</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sesi->seminars->sortBy('urutan_presentasi') as $seminar)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary" style="font-size: 1rem;">{{ $seminar->urutan_presentasi }}</span>
                                        </td>
                                        <td>{{ $seminar->mahasiswa->nim }}</td>
                                        <td>{{ $seminar->mahasiswa->nama }}</td>
                                        <td>{{ Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 40) }}</td>
                                        <td>
                                            @if($seminar->nilai_seminar)
                                                <strong class="text-success">{{ $seminar->nilai_seminar }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($seminar->status_seminar)
                                                @case('dijadwalkan')
                                                    <span class="badge bg-info">Dijadwalkan</span>
                                                    @break
                                                @case('selesai_seminar')
                                                    <span class="badge bg-success">Selesai</span>
                                                    @break
                                                @case('revisi_pasca')
                                                    <span class="badge bg-warning">Revisi</span>
                                                    @break
                                                @case('revisi_disetujui')
                                                    <span class="badge bg-primary">Revisi OK</span>
                                                    @break
                                                @case('selesai')
                                                    <span class="badge bg-success">Selesai KP</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $seminar->status_label }}</span>
                                            @endswitch
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
    </section>

    <script>
        function copyLink() {
            var link = document.getElementById('linkPenilaian');
            link.select();
            document.execCommand('copy');
            $(document).Toasts('create', {
                class: 'bg-success mt-5 mr-3',
                title: 'Berhasil',
                body: 'Link berhasil disalin!'
            });
        }
    </script>
@endsection




