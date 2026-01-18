@extends('kp.layouts.dashboardFotokopi')

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
                        <li class="breadcrumb-item"><a href="#">Public</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                <div class="col-md-12">
                    {{-- Info Seminar --}}
                    <div class="card card-primary card-outline">
                         <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                            @if ($review_seminar->status == 'review') bg-secondary
                            @elseif ($review_seminar->status == 'revisi') bg-warning
                            @elseif ($review_seminar->status == 'diterima') bg-success
                            @elseif ($review_seminar->status == 'ditolak') bg-danger @endif">
                                {{ $review_seminar->status }}
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="20%"><b class="mr-3">Nim</b></td>
                                    <td>: {{ $review_seminar->seminar->mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Nama</b></td>
                                    <td>: {{ $review_seminar->seminar->mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Prodi</b></td>
                                    <td>: {{ $review_seminar->seminar->mahasiswa->prodi }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Judul KP</b></td>
                                    <td>: {{ $review_seminar->seminar->pengajuan->judul }}</td>
                                </tr>
                                <tr>
                                    <td><b class="mr-3">Tanggal Seminar</b></td>
                                    <td>: {{ App\Helpers\AppHelper::get_tanggal_indo($review_seminar->seminar->tanggal_ujian) }}</td>
                                </tr>
                            </table>
                            <hr>

                            <div class="mt-4">
                                <b>Dokumen Laporan Seminar KP : </b>
                                <a href="{{ asset($review_seminar->lampiran ? $review_seminar->lampiran : $review_seminar->seminar->lampiran_3) }}" 
                                   class="btn btn-sm btn-info ml-2" target="_blank">
                                   <i class="fas fa-paperclip mr-2"></i> Lihat Dokumen
                                </a>
                            </div>

                            @if ($review_seminar->status == 'diterima' && $review_seminar->tanggal_acc)
                                <div class="alert alert-success mt-3"><i class="fas fa-check-circle mr-2"></i>
                                    Telah di-ACC pada <b>{{ date('d M Y H:i', strtotime($review_seminar->tanggal_acc)) }}</b>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form Review --}}
                    @if($review_seminar->status != 'diterima')
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header font-weight-bold">Form Penilaian & Review</div>
                        <div class="card-body">
                            <form action="{{ route('kp.review.seminar.public.store', $review_seminar->token) }}" method="post">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-1"></i> Silahkan isi nilai (0-100) dan tentukan status hasil review.
                                        </div>
                                    </div>

                                    {{-- Input Nilai --}}
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Substansi / Isi Materi (0-100) <span class="text-danger">*</span></label>
                                            <input type="number" name="nilai_1" class="form-control @error('nilai_1') is-invalid @enderror" value="{{ old('nilai_1', $review_seminar->nilai_1) }}" min="0" max="100" required>
                                            @error('nilai_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Kompetensi Ilmu (0-100) <span class="text-danger">*</span></label>
                                            <input type="number" name="nilai_2" class="form-control @error('nilai_2') is-invalid @enderror" value="{{ old('nilai_2', $review_seminar->nilai_2) }}" min="0" max="100" required>
                                            @error('nilai_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Metodologi & Redaksi (0-100) <span class="text-danger">*</span></label>
                                            <input type="number" name="nilai_3" class="form-control @error('nilai_3') is-invalid @enderror" value="{{ old('nilai_3', $review_seminar->nilai_3) }}" min="0" max="100" required>
                                            @error('nilai_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Presentasi (0-100) <span class="text-danger">*</span></label>
                                            <input type="number" name="nilai_4" class="form-control @error('nilai_4') is-invalid @enderror" value="{{ old('nilai_4', $review_seminar->nilai_4) }}" min="0" max="100" required>
                                            @error('nilai_4') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    {{-- Keterangan / Catatan Revisi --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Catatan / Keterangan Revisi </label>
                                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="4" placeholder="Tuliskan catatan atau revisi disini...">{{ old('keterangan', $review_seminar->keterangan) }}</textarea>
                                            <small class="text-muted">Wajib diisi jika status Revisi.</small>
                                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status Review <span class="text-danger">*</span></label>
                                            <select class="form-control" name="status" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="diterima" {{ old('status') == 'diterima' ? 'selected' : '' }}>DITERIMA (ACC)</option>
                                                <option value="revisi" {{ old('status') == 'revisi' ? 'selected' : '' }}>REVISI (Perlu Perbaikan)</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Status Kelulusan (Hanya Dosen Penguji Utama) --}}
                                    @if($form_status == 1)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Keputusan Akhir Seminar</label>
                                                <select class="form-control" name="is_lulus">
                                                    <option value="">-- Belum Diputuskan --</option>
                                                    <option value="1" {{ $review_seminar->seminar->is_lulus == 1 ? 'selected' :'' }}>Lulus</option>
                                                    <option value="2" {{ $review_seminar->seminar->is_lulus == 2 ? 'selected' :'' }}>Tidak Lulus</option>
                                                </select>
                                                <small class="text-muted">Hanya untuk Dosen Penguji Utama.</small>
                                            </div>
                                        </div>
                                    @endif

                                </div>

                                <div class="form-group mt-3 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane mr-2"></i> Kirim Review</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- Riwayat Revisi --}}
                    @if(count($revisis) > 0)
                    <div class="card card-outline card-secondary mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Revisi</h3>
                        </div>
                        <div class="card-body">
                            @foreach ($revisis as $revisi)
                                <div class="callout callout-warning">
                                    <h5>Review {{ $revisi->created_at->format('d M Y') }}</h5>
                                    <p>{!! nl2br(e($revisi->catatan)) !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection




