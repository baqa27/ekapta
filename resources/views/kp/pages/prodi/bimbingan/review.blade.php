@extends('kp.layouts.dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('kp.bimbingan.prodi.input') }}">Validasi Bimbingan</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="{{ route('kp.bimbingan.prodi.input') }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Card Detail Bimbingan -->
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon 
                                @if ($bimbingan->status == 'review') bg-secondary
                                @elseif ($bimbingan->status == 'revisi') bg-warning
                                @elseif ($bimbingan->status == 'diterima') bg-success
                                @endif">
                                {{ ucfirst($bimbingan->status) }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><b>NIM</b></td>
                                    <td>{{ $mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b>Nama</b></td>
                                    <td>{{ $mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b>Prodi</b></td>
                                    <td>{{ $mahasiswa->prodi }}</td>
                                </tr>
                                <tr>
                                    <td><b>Judul KP</b></td>
                                    <td>{{ $pengajuan ? $pengajuan->judul : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><b>Dosen Pembimbing</b></td>
                                    <td>{{ $dosen_pembimbing ? $dosen_pembimbing->nama . ', ' . $dosen_pembimbing->gelar : '-' }}</td>
                                </tr>
                            </table>
                            
                            <hr>
                            
                            <table class="table table-borderless">
                                <tr>
                                    <td width="200"><b>Bagian Bimbingan</b></td>
                                    <td><span class="badge bg-primary p-2">{{ $bimbingan->bagian->bagian }}</span></td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal Bimbingan</b></td>
                                    <td>{{ $bimbingan->tanggal_bimbingan ? date('d M Y H:i', strtotime($bimbingan->tanggal_bimbingan)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><b>Tanggal ACC</b></td>
                                    <td>
                                        @if ($bimbingan->tanggal_acc)
                                            <span class="text-success">{{ date('d M Y H:i', strtotime($bimbingan->tanggal_acc)) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>File Bimbingan</b></td>
                                    <td>
                                        @if ($bimbingan->lampiran)
                                            <a href="{{ storage_url($bimbingan->lampiran) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($bimbingan->lampiran) }}
                                            </a>
                                        @elseif ($bimbingan->lampiran_acc)
                                            <a href="{{ storage_url($bimbingan->lampiran_acc) }}" target="_blank">
                                                <i class="fas fa-paperclip"></i> {{ basename($bimbingan->lampiran_acc) }}
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($bimbingan->keterangan)
                                <tr>
                                    <td><b>Keterangan</b></td>
                                    <td>{!! nl2br($bimbingan->keterangan) !!}</td>
                                </tr>
                                @endif
                            </table>

                            <div class="mt-3 text-secondary">
                                <i class="fas fa-calendar mr-2"></i> Disubmit: {{ $bimbingan->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <a href="{{ route('kp.bimbingan.prodi.input') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                
                                @if ($bimbingan->status == 'review')
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modal-revisi">
                                        <i class="fas fa-edit mr-2"></i> Revisi Bimbingan
                                    </button>
                                    <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#modal-acc">
                                        <i class="fas fa-check mr-2"></i> ACC Bimbingan
                                    </button>
                                @elseif ($bimbingan->status == 'revisi')
                                    <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modal-revisi">
                                        <i class="fas fa-plus mr-2"></i> Tambah Revisi
                                    </button>
                                @elseif ($bimbingan->status == 'diterima')
                                    <form action="{{ route('kp.bimbingan.cancel.acc') }}" method="post" class="mr-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan ACC?')">
                                            <i class="fas fa-times mr-2"></i> Batalkan ACC
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Riwayat Revisi -->
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Riwayat Revisi</strong>
                                <span class="badge bg-danger rounded-pill ml-2">{{ count($bimbingan->revisis) }}</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse ($revisis as $revisi)
                                <div class="card bg-light mb-2">
                                    <div class="card-header d-flex justify-content-between">
                                        <span><b>Prodi</b></span>
                                        <span><i class="fas fa-calendar mr-2"></i>{{ $revisi->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>
                                    @if ($revisi->lampiran || $revisi->lampiran_revisi)
                                        <div class="card-footer">
                                            <small>
                                                Lampiran:
                                                @if ($revisi->lampiran)
                                                    <a href="{{ storage_url($revisi->lampiran) }}" target="_blank" class="ml-2">
                                                        <i class="fas fa-paperclip"></i> {{ basename($revisi->lampiran) }}
                                                    </a>
                                                @endif
                                                @if ($revisi->lampiran_revisi)
                                                    <a href="{{ storage_url($revisi->lampiran_revisi) }}" target="_blank" class="ml-2">
                                                        <i class="fas fa-paperclip"></i> {{ basename($revisi->lampiran_revisi) }}
                                                    </a>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada riwayat revisi</p>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal ACC -->
    @if ($bimbingan->status == 'review')
        <div class="modal fade" id="modal-acc">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kp.bimbingan.acc.prodi') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                        <div class="modal-header bg-success text-white">
                            <h4 class="modal-title"><i class="fas fa-check mr-2"></i>ACC Bimbingan</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                ACC bimbingan <b>{{ $bimbingan->bagian->bagian }}</b> untuk mahasiswa <b>{{ $mahasiswa->nama }}</b>
                            </div>
                            <div class="form-group">
                                <label>Tanggal ACC <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_acc" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Catatan (Opsional)</label>
                                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan untuk mahasiswa..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check mr-1"></i> ACC Bimbingan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Revisi -->
    @if ($bimbingan->status == 'review' || $bimbingan->status == 'revisi')
        <div class="modal fade" id="modal-revisi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('kp.bimbingan.revisi.prodi') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $bimbingan->id }}">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title"><i class="fas fa-edit mr-2"></i>Revisi Bimbingan</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Catatan Revisi <span class="text-danger">*</span></label>
                                <textarea name="catatan" class="form-control" rows="4" placeholder="Tuliskan catatan revisi..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Kirim Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection




