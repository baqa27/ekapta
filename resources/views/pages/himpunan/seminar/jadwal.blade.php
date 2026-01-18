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
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.himpunan') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Mahasiswa Siap Dijadwalkan -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>Mahasiswa Siap Dijadwalkan
                        <span class="badge bg-success ml-2">{{ count($seminars_siap) }}</span>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-buat-sesi" {{ count($seminars_siap) == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-plus mr-1"></i> Buat Sesi Seminar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($seminars_siap) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th>Judul KP</th>
                                    <th>Tgl Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($seminars_siap as $index => $seminar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $seminar->mahasiswa->nim }}</td>
                                    <td>{{ $seminar->mahasiswa->nama }}</td>
                                    <td>{{ $seminar->mahasiswa->prodi }}</td>
                                    <td>{{ Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 50) }}</td>
                                    <td>{{ $seminar->tanggal_acc ? $seminar->tanggal_acc->format('d M Y') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Tidak ada mahasiswa yang siap dijadwalkan</p>
                    @endif
                </div>
            </div>

            <!-- Daftar Sesi Seminar -->
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Daftar Sesi Seminar</h3>
                </div>
                <div class="card-body">
                    @if(count($sesi_seminars) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableSesi">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Penguji</th>
                                    <th>Peserta</th>
                                    <th>Status Link</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sesi_seminars as $sesi)
                                <tr>
                                    <td>{{ $sesi->tanggal->translatedFormat('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }}</td>
                                    <td>{{ $sesi->tempat }}</td>
                                    <td>{{ $sesi->dosenPenguji->nama ?? '-' }}</td>
                                    <td><span class="badge bg-info">{{ count($sesi->seminars) }} mahasiswa</span></td>
                                    <td>
                                        @if($sesi->is_token_used)
                                            <span class="badge bg-secondary"><i class="fas fa-check"></i> Sudah Digunakan</span>
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-link"></i> Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('jadwal.himpunan.detail', $sesi->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$sesi->is_token_used)
                                        <button type="button" class="btn btn-warning btn-sm" onclick="copyLink('{{ $sesi->link_penilaian }}')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Belum ada sesi seminar</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Buat Sesi -->
    <div class="modal fade" id="modal-buat-sesi">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('jadwal.himpunan.create') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title">Buat Sesi Seminar Baru</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tanggal Seminar <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_mulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat / Link Online <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat" class="form-control" placeholder="Ruang A / Link Zoom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dosen Penguji <span class="text-danger">*</span></label>
                                    <select name="dosen_penguji_id" class="form-control select-1" required>
                                        <option value="">-- Pilih Dosen --</option>
                                        @foreach($dosens as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Mahasiswa per Sesi</label>
                                    <input type="number" name="jumlah_mahasiswa" class="form-control" value="8" min="1" max="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Catatan Teknis</label>
                                    <input type="text" name="catatan_teknis" class="form-control" placeholder="Catatan untuk penguji...">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pilih Mahasiswa <span class="text-danger">*</span></label>
                            <div class="border p-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach($seminars_siap as $seminar)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="seminars[]" value="{{ $seminar->id }}" id="mhs{{ $seminar->id }}">
                                    <label class="custom-control-label" for="mhs{{ $seminar->id }}">
                                        {{ $seminar->mahasiswa->nim }} - {{ $seminar->mahasiswa->nama }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Sesi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(function() {
                $(document).Toasts('create', {
                    class: 'bg-success mt-5 mr-3',
                    title: 'Berhasil',
                    body: 'Link penilaian berhasil disalin!'
                });
            });
        }
    </script>
@endsection
