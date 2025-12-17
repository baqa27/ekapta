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
                        <li class="breadcrumb-item"><a href="{{ route('seminar.himpunan') }}">Seminar KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <!-- Data Mahasiswa -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Data Pendaftaran Seminar KP</h3>
                            <div class="card-tools">
                                @if ($seminar->is_valid == 0)
                                    <span class="badge bg-secondary">REVIEW</span>
                                @elseif ($seminar->is_valid == 1)
                                    <span class="badge bg-success">DITERIMA</span>
                                @elseif ($seminar->is_valid == 2)
                                    <span class="badge bg-warning">REVISI</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200">NIM</td>
                                    <td width="10">:</td>
                                    <td><b>{{ $seminar->mahasiswa->nim }}</b></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td><b>{{ $seminar->mahasiswa->nama }}</b></td>
                                </tr>
                                <tr>
                                    <td>Prodi</td>
                                    <td>:</td>
                                    <td><b>{{ $seminar->mahasiswa->prodi }}</b></td>
                                </tr>
                                <tr>
                                    <td>Judul KP</td>
                                    <td>:</td>
                                    <td><b>{{ $seminar->pengajuan->judul }}</b></td>
                                </tr>
                                <tr>
                                    <td>Jumlah Bayar</td>
                                    <td>:</td>
                                    <td><b>Rp {{ number_format($seminar->jumlah_bayar, 0, ',', '.') }}</b></td>
                                </tr>
                                <tr>
                                    <td>Nomor Pembayaran</td>
                                    <td>:</td>
                                    <td><b>{{ $seminar->nomor_pembayaran }}</b></td>
                                </tr>
                            </table>
                            <hr>
                            <h6>Lampiran:</h6>
                            <ul>
                                @if($seminar->file_laporan)
                                <li><a href="{{ asset('storage/' . $seminar->file_laporan) }}" target="_blank">Laporan PDF</a></li>
                                @endif
                                @if($seminar->file_pengesahan)
                                <li><a href="{{ asset('storage/' . $seminar->file_pengesahan) }}" target="_blank">Lembar Pengesahan</a></li>
                                @endif
                                @if($seminar->bukti_bayar)
                                <li><a href="{{ asset('storage/' . $seminar->bukti_bayar) }}" target="_blank">Bukti Pembayaran</a></li>
                                @endif
                                @if($seminar->lampiran_1)
                                <li><a href="{{ asset('storage/' . $seminar->lampiran_1) }}" target="_blank">Sertifikat 1</a></li>
                                @endif
                                @if($seminar->lampiran_2)
                                <li><a href="{{ asset('storage/' . $seminar->lampiran_2) }}" target="_blank">Sertifikat 2</a></li>
                                @endif
                                @if($seminar->lampiran_3)
                                <li><a href="{{ asset('storage/' . $seminar->lampiran_3) }}" target="_blank">Sertifikat 3</a></li>
                                @endif
                                @if($seminar->lampiran_4)
                                <li><a href="{{ asset('storage/' . $seminar->lampiran_4) }}" target="_blank">Sertifikat 4</a></li>
                                @endif
                            </ul>
                        </div>

                        @if ($seminar->is_valid == 0 || $seminar->is_valid == 2)
                        <div class="card-footer">
                            <div class="d-flex">
                                <form action="{{ route('seminar.himpunan.acc') }}" method="post" class="mr-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $seminar->id }}">
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Validasi pendaftaran seminar ini?')">
                                        <i class="fas fa-check mr-1"></i> Validasi
                                    </button>
                                </form>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-revisi">
                                    <i class="fas fa-redo mr-1"></i> Revisi
                                </button>
                            </div>
                        </div>
                        @endif

                        @if ($seminar->is_valid == 1)
                        <div class="card-footer">
                            <a href="{{ route('jadwal.himpunan') }}" class="btn btn-primary">
                                <i class="fas fa-calendar mr-1"></i> Lihat Penjadwalan Seminar
                            </a>
                        </div>
                        @endif
                    </div>


                </div>

                <div class="col-md-4">
                    <!-- Riwayat Revisi -->
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Revisi</h3>
                            <span class="badge bg-warning float-right">{{ count($seminar->revisis) }}</span>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @forelse ($revisis as $revisi)
                            <div class="card bg-light mb-2">
                                <div class="card-body p-2">
                                    <small class="text-muted">{{ $revisi->created_at->format('d M Y H:i') }}</small>
                                    <p class="mb-0">{{ $revisi->catatan }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">Belum ada revisi</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Revisi -->
    <div class="modal fade" id="modal-revisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('seminar.himpunan.revisi') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $seminar->id }}">
                    <div class="modal-header">
                        <h4 class="modal-title">Revisi Pendaftaran Seminar</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Catatan Revisi</label>
                            <textarea name="catatan" class="form-control" rows="4" required placeholder="Tuliskan catatan revisi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Simpan Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
