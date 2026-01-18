@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Pendaftaran Seminar KP</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Seminar KP</a></li>
                        <li class="breadcrumb-item active">Pendaftaran</li>
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
                            <h3 class="card-title"><i class="fas fa-file-alt mr-2"></i>Form Pendaftaran Seminar KP</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Informasi:</strong> Setelah submit, pendaftaran akan diverifikasi oleh <strong>Himpunan</strong>.
                                Pastikan semua dokumen lengkap dan pembayaran sudah sesuai (Rp 25.000).
                            </div>

                            <form action="{{ route('kp.seminar.store') }}" method="post" enctype="multipart/form-data">
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
                                                    <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Lengkap</label>
                                                    <input type="text" class="form-control" value="{{ $mahasiswa->nama }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Prodi</label>
                                                    <input type="text" class="form-control" value="{{ $mahasiswa->prodi }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nomor WA Aktif <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('no_wa') is-invalid @enderror"
                                                        name="no_wa" placeholder="08xxxxxxxxxx" value="{{ old('no_wa', $mahasiswa->hp) }}" required>
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
                                            <input type="text" class="form-control" value="{{ $pengajuan_acc->judul }}" disabled>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Laporan Final PDF <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('file_laporan') is-invalid @enderror"
                                                            name="file_laporan" accept=".pdf" required>
                                                        <label class="custom-file-label">Pilih file (maks 10 MB)</label>
                                                    </div>
                                                    @error('file_laporan')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Lembar Pengesahan PDF <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('file_pengesahan') is-invalid @enderror"
                                                            name="file_pengesahan" accept=".pdf" required>
                                                        <label class="custom-file-label">Pilih file (maks 10 MB)</label>
                                                    </div>
                                                    @error('file_pengesahan')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
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
                                                    <label>Sertifikat 1 <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_1" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <label class="custom-file-label">Pilih file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 2 <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_2" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <label class="custom-file-label">Pilih file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 3 <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_3" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <label class="custom-file-label">Pilih file</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Sertifikat 4 <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="lampiran_4" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <label class="custom-file-label">Pilih file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Format: PDF/JPG/PNG, maks 10 MB per file</small>
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
                                                value="{{ old('link_akses_produk') }}" required>
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
                                                <label class="custom-file-label">Pilih file (opsional)</label>
                                            </div>
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
                                            Nominal pembayaran: <strong>Rp {{ number_format($himpunan->biaya_seminar ?? 25000, 0, ',', '.') }}</strong>
                                        </div>

                                        @if($himpunan && ($himpunan->bank || $himpunan->nomor_dana || $himpunan->nomor_seabank))
                                        <div class="card bg-light mb-3">
                                            <div class="card-body py-2">
                                                <h6 class="mb-2"><i class="fas fa-wallet mr-2"></i>Informasi Rekening Pembayaran:</h6>
                                                <ul class="mb-0 pl-3">
                                                    @if($himpunan->bank && $himpunan->nomor_rekening)
                                                    <li><strong>{{ $himpunan->bank }}</strong>: {{ $himpunan->nomor_rekening }} a.n. {{ $himpunan->nama_rekening ?? '-' }}</li>
                                                    @endif
                                                    @if($himpunan->nomor_dana)
                                                    <li><strong>DANA</strong>: {{ $himpunan->nomor_dana }}</li>
                                                    @endif
                                                    @if($himpunan->nomor_seabank)
                                                    <li><strong>SeaBank</strong>: {{ $himpunan->nomor_seabank }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Metode Pembayaran <span class="text-danger">*</span></label>
                                                    <select name="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" required>
                                                        <option value="">-- Pilih Metode --</option>
                                                        <option value="Cash" {{ old('metode_bayar') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                        <option value="DANA" {{ old('metode_bayar') == 'DANA' ? 'selected' : '' }}>DANA</option>
                                                        <option value="SeaBank" {{ old('metode_bayar') == 'SeaBank' ? 'selected' : '' }}>SeaBank</option>
                                                        @if($himpunan && $himpunan->bank)
                                                        <option value="Transfer Bank" {{ old('metode_bayar') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank ({{ $himpunan->bank }})</option>
                                                        @endif
                                                    </select>
                                                    @error('metode_bayar')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input @error('bukti_bayar') is-invalid @enderror"
                                                            name="bukti_bayar" accept=".pdf,.jpg,.jpeg,.png" required>
                                                        <label class="custom-file-label">Pilih file (maks 10 MB)</label>
                                                    </div>
                                                    @error('bukti_bayar')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
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
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Pendaftaran
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




