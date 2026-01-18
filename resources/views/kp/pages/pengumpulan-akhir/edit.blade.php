@extends('kp.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Revisi</li>
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
                    @if (count($revisis) != 0)
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Pengajuan Jilid KP Anda berstatus <strong>REVISI</strong>. Silahkan submit ulang dokumen yang diperlukan.
                            <br>
                            <a href="{{ route('kp.pengumpulan-akhir.detail.mahasiswa', $jilid->id) }}" class="btn btn-sm btn-outline-dark mt-2">
                                <i class="fas fa-eye mr-1"></i> Lihat Catatan Revisi
                            </a>
                        </div>
                    @endif

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('kp.pengumpulan-akhir.update', $jilid->id) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @csrf

                                <h5><strong>Dokumen Utama</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>Lembar Keaslian</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_keaslian')is-invalid @enderror"
                                            name="lembar_keaslian" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_keaslian')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_keaslian) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Persetujuan Pembimbing (TTD)</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_persetujuan_pembimbing')is-invalid @enderror"
                                            name="lembar_persetujuan_pembimbing" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_persetujuan_pembimbing')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Persetujuan Penguji (TTD)</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_persetujuan_penguji')is-invalid @enderror"
                                            name="lembar_persetujuan_penguji" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_persetujuan_penguji')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Pengesahan (TTD)</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_pengesahan')is-invalid @enderror"
                                            name="lembar_pengesahan" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_pengesahan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_pengesahan) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Lembar Bimbingan KP
                                        <br><small><a href="{{ route('kp.cetak.riwayat.bimbingan.mahasiswa') }}" target="_blank" class="text-primary"><i class="fas fa-download"></i> Download Lembar Bimbingan</a></small>
                                    </label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_bimbingan')is-invalid @enderror"
                                            name="lembar_bimbingan" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_bimbingan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_bimbingan) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>Lembar Revisi (ACC Penguji, 1 file)</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lembar_revisi')is-invalid @enderror"
                                            name="lembar_revisi" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lembar_revisi')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->lembar_revisi) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <h5 class="mt-4"><strong>Laporan Kerja Praktek</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format PDF
                                        <br><small class="text-muted">Digabung dengan Lembar Pengesahan TTD</small>
                                    </label>
                                    <select name="type_laporan_pdf" id="type_laporan_pdf" class="form-control mb-2" onchange="toggleInputFieldsPdf()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field_pdf" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                name="laporan_pdf" accept=".pdf" id="laporan_pdf">
                                            <label class="custom-file-label">Pilih file PDF...</label>
                                        </div>
                                    </div>
                                    <div id="link_field_pdf" style="display:none;">
                                        <input type="url" name="laporan_link_pdf" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link_pdf">
                                    </div>
                                    @error('laporan_pdf')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->laporan_pdf) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Laporan KP Format Word
                                        <br><small class="text-muted">Format .docx</small>
                                    </label>
                                    <select name="type_laporan" id="type_laporan" class="form-control mb-2" onchange="toggleInputFields()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                name="laporan_word" accept=".docx" id="laporan_word">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="link_field" style="display:none;">
                                        <input type="url" name="laporan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="laporan_link">
                                    </div>
                                    @error('laporan_word')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small>File saat ini: <a href="{{ asset($jilid->laporan_word) }}" target="_blank">Lihat Lampiran</a></small>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Artikel KP Format Word
                                        <br><small class="text-muted">Format .docx (Opsional)</small>
                                    </label>
                                    <select name="type_artikel" id="type_artikel" class="form-control mb-2" onchange="toggleInputFieldsArtikel()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="artikel_upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('artikel')is-invalid @enderror"
                                                name="artikel" id="artikel" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="artikel_link_field" style="display:none;">
                                        <input type="url" name="artikel_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="artikel_link">
                                    </div>
                                    @error('artikel')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if ($jilid->artikel)
                                    <small>File saat ini: <a href="{{ asset($jilid->artikel) }}" target="_blank">Lihat Lampiran</a></small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>
                                        Link Produk KP
                                        <br><small class="text-muted">Opsional - Upload ke Google Drive</small>
                                    </label>
                                    <input type="url" class="form-control" name="link_project" value="{{ $jilid->link_project }}" placeholder="https://drive.google.com/...">
                                </div>

                                <h5 class="mt-4"><strong>Dokumen Pendukung</strong></h5>
                                <hr>

                                <div class="form-group">
                                    <label>Berita Acara Serah Terima Produk KP</label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('berita_acara')is-invalid @enderror"
                                            name="berita_acara" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('berita_acara')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if ($jilid->berita_acara)
                                    <small>File saat ini: <a href="{{ asset($jilid->berita_acara) }}" target="_blank">Lihat Lampiran</a></small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>
                                        Dokumen Lampiran
                                        <br><small class="text-muted">Opsional - Surat ijin KP, data KP, dll</small>
                                    </label>
                                    <div class="custom-file mb-2">
                                        <input type="file" class="custom-file-input @error('lampiran')is-invalid @enderror"
                                            name="lampiran" accept=".pdf">
                                        <label class="custom-file-label">Pilih file baru (opsional)...</label>
                                    </div>
                                    @error('lampiran')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if ($jilid->lampiran)
                                    <small>File saat ini: <a href="{{ storage_url($jilid->lampiran) }}" target="_blank">Lihat Lampiran</a></small>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>
                                        Panduan Penggunaan Produk KP
                                        <br><small class="text-muted">Format .docx atau Link Google Drive</small>
                                    </label>
                                    <select name="type_panduan" id="type_panduan" class="form-control mb-2" onchange="toggleInputFieldsPanduan()">
                                        <option value="">-- Pilih Metode Upload --</option>
                                        <option value="upload">Upload File Langsung</option>
                                        <option value="link">Link Google Drive</option>
                                    </select>
                                    <div id="panduan_upload_field" style="display:none;">
                                        <div class="custom-file mb-2">
                                            <input type="file" class="custom-file-input @error('panduan')is-invalid @enderror"
                                                name="panduan" id="panduan" accept=".docx">
                                            <label class="custom-file-label">Pilih file Word...</label>
                                        </div>
                                    </div>
                                    <div id="panduan_link_field" style="display:none;">
                                        <input type="url" name="panduan_link" class="form-control mb-2" placeholder="https://drive.google.com/..." id="panduan_link">
                                    </div>
                                    @error('panduan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    @if ($jilid->panduan)
                                    <small>File saat ini: <a href="{{ asset($jilid->panduan) }}" target="_blank">Lihat Lampiran</a></small>
                                    @endif
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('kp.pengumpulan-akhir.mahasiswa') }}" class="btn btn-secondary">
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
    <!-- /.content -->
@endsection
@section('script')
    <script>
        function toggleInputFields() {
            var typeLaporan = document.getElementById('type_laporan').value;
            var uploadField = document.getElementById('upload_field');
            var linkField = document.getElementById('link_field');
            const laporan_word = document.getElementById('laporan_word');
            const laporan_link = document.getElementById('laporan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_word.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_word.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                laporan_word.required = true;
                laporan_link.required = false;
                linkField.style.display = 'none';
            }
        }

        function toggleInputFieldsPdf() {
            var typeLaporan = document.getElementById('type_laporan_pdf').value;
            var uploadField = document.getElementById('upload_field_pdf');
            var linkField = document.getElementById('link_field_pdf');
            const laporan_pdf = document.getElementById('laporan_pdf');
            const laporan_link = document.getElementById('laporan_link_pdf');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                laporan_pdf.required = true;
                laporan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                laporan_pdf.required = false;
                laporan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                laporan_pdf.required = true;
                laporan_link.required = false;
                linkField.style.display = 'none';
            }
        }

        function toggleInputFieldsArtikel() {
            var typeArtikel = document.getElementById('type_artikel').value;
            var uploadField = document.getElementById('artikel_upload_field');
            var linkField = document.getElementById('artikel_link_field');
            const artikel = document.getElementById('artikel');
            const artikel_link = document.getElementById('artikel_link');

            if (typeArtikel === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                artikel.required = true;
                artikel_link.required = false;
            } else if (typeArtikel === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                artikel.required = false;
                artikel_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                artikel.required = false;
                artikel_link.required = false;
            }
        }

        function toggleInputFieldsPanduan() {
            var typeLaporan = document.getElementById('type_panduan').value;
            var uploadField = document.getElementById('panduan_upload_field');
            var linkField = document.getElementById('panduan_link_field');
            const panduan = document.getElementById('panduan');
            const panduan_link = document.getElementById('panduan_link');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
                panduan.required = true;
                panduan_link.required = false;
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
                panduan.required = false;
                panduan_link.required = true;
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
                panduan.required = false;
                panduan_link.required = false;
            }
        }
    </script>
@endsection




