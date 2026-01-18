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
                        <li class="breadcrumb-item"><a href="#">Bimbingan KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="mb-3">
                <a href="{{ route($route) }}" class="btn btn-secondary shadow">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- Info Mahasiswa -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i>Informasi Mahasiswa</h3>
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
                                    <td><b>Dosen Pembimbing</b></td>
                                    <td>{{ $dosen->nama }}, {{ $dosen->gelar }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Progress Bimbingan -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tasks mr-2"></i>Progress Bimbingan</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Bagian Bimbingan</th>
                                            <th>Tanggal Bimbingan</th>
                                            <th>File Bimbingan</th>
                                            <th width="150">Status</th>
                                            <th width="200">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($bimbingan_per_bagian as $item)
                                            @php 
                                                $bagian = $item['bagian']; 
                                                $bimbingan = $item['bimbingan']; 
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $no++ }}</td>
                                                <td><b>{{ $bagian->bagian }}</b></td>
                                                <td>
                                                    @if ($bimbingan && $bimbingan->tanggal_bimbingan)
                                                        {{ date('d M Y', strtotime($bimbingan->tanggal_bimbingan)) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bimbingan && $bimbingan->lampiran)
                                                        <a href="{{ storage_url($bimbingan->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-pdf mr-1"></i> Lihat File
                                                        </a>
                                                    @elseif ($bimbingan && $bimbingan->lampiran_acc)
                                                        <a href="{{ storage_url($bimbingan->lampiran_acc) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-pdf mr-1"></i> Lihat File
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Belum ada file</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bimbingan && $bimbingan->status == 'diterima')
                                                        <span class="badge bg-success p-2">
                                                            <i class="fas fa-check mr-1"></i> Diterima
                                                        </span>
                                                        @if ($bimbingan->tanggal_acc)
                                                            <br><small class="text-success">{{ date('d M Y', strtotime($bimbingan->tanggal_acc)) }}</small>
                                                        @endif
                                                    @elseif ($bimbingan && $bimbingan->status == 'review')
                                                        <span class="badge bg-warning p-2">
                                                            <i class="fas fa-clock mr-1"></i> Review
                                                        </span>
                                                    @elseif ($bimbingan && $bimbingan->status == 'revisi')
                                                        <span class="badge bg-info p-2">
                                                            <i class="fas fa-edit mr-1"></i> Revisi
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary p-2">
                                                            <i class="fas fa-minus mr-1"></i> Belum Submit
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($bimbingan && $bimbingan->status == 'review')
                                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-acc-{{ $bimbingan->id }}">
                                                            <i class="fas fa-check mr-1"></i> ACC
                                                        </button>
                                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-revisi-{{ $bimbingan->id }}">
                                                            <i class="fas fa-edit mr-1"></i> Revisi
                                                        </button>
                                                    @elseif ($bimbingan && $bimbingan->status == 'diterima')
                                                        <span class="text-success"><i class="fas fa-check-circle"></i> Selesai</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
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
        </div>
    </section>

    <!-- Modal ACC untuk setiap bimbingan -->
    @foreach ($bimbingan_per_bagian as $item)
        @php $bimbingan = $item['bimbingan']; $bagian = $item['bagian']; @endphp
        @if ($bimbingan && $bimbingan->status == 'review')
            <div class="modal fade" id="modal-acc-{{ $bimbingan->id }}">
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
                                    ACC bimbingan <b>{{ $bagian->bagian }}</b> untuk mahasiswa <b>{{ $mahasiswa->nama }}</b>
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

            <div class="modal fade" id="modal-revisi-{{ $bimbingan->id }}">
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
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Revisi bimbingan <b>{{ $bagian->bagian }}</b> untuk mahasiswa <b>{{ $mahasiswa->nama }}</b>
                                </div>
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
    @endforeach
@endsection




