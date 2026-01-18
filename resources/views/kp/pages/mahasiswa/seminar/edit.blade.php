@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit mr-2"></i>{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Revisi:</strong> Perbaiki data yang diminta, lalu submit ulang.
                            </div>

                            <form action="{{ route('kp.seminar.update', $seminar->id) }}" method="post" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                {{-- BAGIAN 1: INFORMASI DIRI --}}
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">1. Informasi Diri</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>NIM</label>
                                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->nim }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->nama }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Prodi</label>
                                                    <input type="text" class="form-control" value="{{ $seminar->mahasiswa->prodi }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor WA Aktif <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('no_wa') is-invalid @enderror"
                                                        name="no_wa" placeholder="08xxxxxxxxxx" value="{{ old('no_wa', $seminar->no_wa) }}" required>
                                                    @error('no_wa')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN 2: INFORMASI LAPORAN --}}
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">2. Informasi Laporan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Judul Kerja Praktek</label>
                                            <input type="text" class="form-control" value="{{ $seminar->pengajuan->judul }}" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Laporan Final PDF</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('file_laporan') is-invalid @enderror"
                                                            name="file_laporan" accept=".pdf">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @error('file_laporan')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    @if($seminar->file_laporan)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->file_laporan) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->file_laporan) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Lembar Pengesahan PDF</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('file_pengesahan') is-invalid @enderror"
                                                            name="file_pengesahan" accept=".pdf">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @error('file_pengesahan')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    @if($seminar->file_pengesahan)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->file_pengesahan) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->file_pengesahan) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN 3: SERTIFIKAT --}}
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">3. Syarat Sertifikat Seminar/Pelatihan (4 Sertifikat)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 1</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_1" accept=".pdf,.jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @if($seminar->lampiran_1)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->lampiran_1) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_1) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 2</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_2" accept=".pdf,.jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @if($seminar->lampiran_2)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->lampiran_2) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_2) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 3</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_3" accept=".pdf,.jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @if($seminar->lampiran_3)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->lampiran_3) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_3) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 4</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_4" accept=".pdf,.jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @if($seminar->lampiran_4)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->lampiran_4) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->lampiran_4) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Format: PDF/JPG/PNG, maks 10 MB per file. Kosongkan jika tidak ingin mengubah.</small>
                                    </div>
                                </div>

                                {{-- BAGIAN 4: LINK PRODUK --}}
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">4. Link Akses Produk KP</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Link Akses Produk <span class="text-danger">*</span></label>
                                            <input type="url" class="form-control @error('link_akses_produk') is-invalid @enderror"
                                                name="link_akses_produk" placeholder="https://..."
                                                value="{{ old('link_akses_produk', $seminar->link_akses_produk) }}" required>
                                            <small class="text-muted">Masukkan link untuk mengakses produk KP (Google Drive, GitHub, dll)</small>
                                            @error('link_akses_produk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Dokumen Penilaian <small class="text-muted">(Opsional)</small></label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('dokumen_penilaian') is-invalid @enderror"
                                                    name="dokumen_penilaian" accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label">Pilih file baru (opsional)</label>
                                            </div>
                                            @if($seminar->dokumen_penilaian)
                                            <div class="mt-2 bg-light p-2 rounded">
                                                <small>File sebelumnya:
                                                    <a href="{{ storage_url($seminar->dokumen_penilaian) }}" target="_blank">
                                                        <i class="fas fa-paperclip"></i> {{ basename($seminar->dokumen_penilaian) }}
                                                    </a>
                                                </small>
                                            </div>
                                            @endif
                                            <small class="text-muted">Upload dokumen penilaian tambahan jika ada</small>
                                            @error('dokumen_penilaian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- BAGIAN 5: PEMBAYARAN --}}
                                <div class="card card-secondary">
                                    <div class="card-header py-2">
                                        <h5 class="card-title mb-0">5. Pembayaran Seminar</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-light border mb-3">
                                            <i class="fas fa-info-circle text-primary mr-2"></i>
                                            Nominal pembayaran: <strong>Rp {{ number_format($seminar->jumlah_bayar ?? 25000, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                    <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                                                        <option value="">-- Pilih Metode --</option>
                                                        <option value="Cash" {{ old('metode_bayar', $seminar->metode_bayar) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                        <option value="DANA" {{ old('metode_bayar', $seminar->metode_bayar) == 'DANA' ? 'selected' : '' }}>DANA</option>
                                                        <option value="SeaBank" {{ old('metode_bayar', $seminar->metode_bayar) == 'SeaBank' ? 'selected' : '' }}>SeaBank</option>
                                                        <option value="Transfer Bank" {{ old('metode_bayar', $seminar->metode_bayar) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                                    </select>
                                                    @error('metode_bayar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Bukti Pembayaran</label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('bukti_bayar') is-invalid @enderror"
                                                            name="bukti_bayar" accept=".pdf,.jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih file baru (opsional)</label>
                                                    </div>
                                                    @error('bukti_bayar')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                    @if($seminar->bukti_bayar)
                                                    <div class="mt-2 bg-light p-2 rounded">
                                                        <small>File sebelumnya:
                                                            <a href="{{ storage_url($seminar->bukti_bayar) }}" target="_blank">
                                                                <i class="fas fa-paperclip"></i> {{ basename($seminar->bukti_bayar) }}
                                                            </a>
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <a href="{{ route('kp.seminar.mahasiswa') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Revisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




