@extends('ta.layouts.dashboardMahasiswa')

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
                        <li class="breadcrumb-item"><a href="#">Jilid TA</a></li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('ta.jilid.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Keaslian<br> <small><a
                                                href="{{ asset($pendaftaran->lampiran_2) }}" target="_blank"><i
                                                    class="fas fa-download"></i> Download Lembar
                                                Keaslian</a></small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_keaslian')is-invalid @enderror"
                                                name="lembar_keaslian" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_keaslian')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Persetujuan Pembimbing (Dengan TTD)
                                        {{-- <br> <small><a href="{{ route('ta.cetak.lembar.persetujuan', 1) }}" target="_blank"><i class="fas fa-download"></i> Download Lembar Persetujuan Pembimbing</a></small> --}}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_persetujuan_pembimbing')is-invalid @enderror"
                                                name="lembar_persetujuan_pembimbing" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_persetujuan_pembimbing')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Persetujuan Penguji (Dengan TTD)
                                        {{-- <br> <small><a href="{{ route('ta.cetak.lembar.persetujuan', 2) }}" target="_blank"><i class="fas fa-download"></i> Download Lembar Persetujuan Penguji</a></small> --}}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_persetujuan_penguji')is-invalid @enderror"
                                                name="lembar_persetujuan_penguji" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_persetujuan_penguji')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Pengesahan (Dengan TTD)
                                        {{-- <br> <small><a href="{{ route('ta.cetak.lembar.pengesahan') }}" target="_blank"><i class="fas fa-download"></i> Download Lembar Pengesahan</a></small> --}}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_pengesahan')is-invalid @enderror"
                                                name="lembar_pengesahan" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_pengesahan')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Bimbingan Pembimbing 1 dan 2 (Dijadikan 1 file)<br>
                                        <small><a href="{{ route('ta.cetak.riwayat.bimbingan.mahasiswa') }}" target="_blank"><i
                                                    class="fas fa-download"></i> Download Lembar
                                                Bimbingan</a></small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_bimbingan')is-invalid @enderror"
                                                name="lembar_bimbingan" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_bimbingan')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Lembar Revisi Yang Sudah di ACC Semua Dosen Penguji
                                        (Dijadikan 1 file)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lembar_revisi')is-invalid @enderror"
                                                name="lembar_revisi" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lembar_revisi')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <label for="exampleInputFile">Laporan Tugas Akhir Format PDF (Digabung Dengan Lembar
                                        Pengesahan TTD)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                name="laporan_pdf" accept=".pdf" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('laporan_pdf')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="exampleInputFile">Laporan Skripsi Format PDF (Digabung Dengan Lembar
                                        Pengesahan TTD)<br><small>Jika terjadi error, silahan upload file PDF ke Google
                                            Drive dan isikan URL file PDF yang diupload ke Google Drive</small></label>
                                    <select name="type_laporan_pdf" id="type_laporan_pdf" class="form-control"
                                        onchange="toggleInputFieldsPdf()">
                                        <option value="">--pilih--</option>
                                        <option value="upload">Upload File</option>
                                        <option value="link">Link File (Upload Google Drive)</option>
                                    </select>

                                    <div id="upload_field_pdf" class="mb-3" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('laporan_pdf')is-invalid @enderror"
                                                name="laporan_pdf" accept=".pdf" id="laporan_pdf">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>

                                    <div id="link_field_pdf" style="display:none;">
                                        <input type="url" name="laporan_link_pdf" class="form-control"
                                            placeholder="Enter Google Drive link" id="laporan_link_pdf">
                                    </div>
                                    @error('laporan_pdf')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <label for="exampleInputFile">Laporan Tugas Akhir Format WORD</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                   class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                   name="laporan_word" accept=".docx" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('laporan_word')
                                    <small class="text-danger"
                                           style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="exampleInputFile">Laporan Tugas Akhir Format Word<br><small>Jika terjadi
                                            error, silahan upload file WORD ke Google Drive dan isikan URL file WORD yang
                                            diupload ke Google Drive</small></label>
                                    <select name="type_laporan" id="type_laporan" class="form-control"
                                        onchange="toggleInputFields()">
                                        <option value="">--pilih--</option>
                                        <option value="upload">Upload File</option>
                                        <option value="link">Link File (Upload Google Drive)</option>
                                    </select>

                                    <div id="upload_field" class="mb-3" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('laporan_word')is-invalid @enderror"
                                                name="laporan_word" accept=".docx" id="laporan_word">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>

                                    <div id="link_field" style="display:none;">
                                        <input type="url" name="laporan_link" class="form-control"
                                            placeholder="Enter Google Drive link" id="laporan_link">
                                    </div>
                                    @error('laporan_word')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <label for="exampleInputFile">Artikel Tugas Akhir Format WORD <br><small>JIka error
                                            silahkan kompres file WORD anda terlebih dahulu. Berikut rekomendasi situs
                                            compress : <a href="https://www.docucompress.com/word/"
                                                target="_blank">Compress Document</a></small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('artikel')is-invalid @enderror"
                                                name="artikel" accept=".docx" required>
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('artikel')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="exampleInputFile">Artikel Tugas Akhir Format WORD <br><small>Jika terjadi
                                            error, silahan upload file WORD ke Google Drive dan isikan URL file WORD yang
                                            diupload ke Google Drive</small></label>
                                    <select name="type_artikel" id="type_artikel" class="form-control"
                                        onchange="toggleInputFieldsArtikel()">
                                        <option value="">--pilih--</option>
                                        <option value="upload">Upload File</option>
                                        <option value="link">Link File (Upload Google Drive)</option>
                                    </select>

                                    <div id="artikel_upload_field" class="mb-3" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('artikel')is-invalid @enderror"
                                                name="artikel" id="artikel" accept=".docx">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>

                                    <div id="artikel_link_field" style="display:none;">
                                        <input type="url" name="artikel_link" class="form-control"
                                            placeholder="Enter Google Drive link" id="artikel_link">
                                    </div>
                                    @error('artikel')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Link Produk Tugas Akhir (Opsional)<br>
                                        <small>Upload ke google drive, kemudian inputkan link project</small></label>
                                    <input type="url" class="form-control" name="link_project"
                                        value="{{ old('link_project') }}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Berita acara serah terima produk TA (Opsional)</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('berita_acara')is-invalid @enderror"
                                                name="berita_acara" accept=".pdf">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('berita_acara')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Dokumen Lampiran (Opsional) <br><small>Upload dokumen lampiran pendukung penelitian seperti surat ijin penelitian, data penelitian, dokumen validasi, instrumen penelitian, dll</small></label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('lampiran')is-invalid @enderror"
                                                name="lampiran" accept=".pdf">
                                            <label class="custom-file-label" for="exampleInputFile">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Dokumen</span>
                                        </div>
                                    </div>
                                    @error('lampiran')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Panduan Penggunaan Produk TA (Opsional) <br><small>Jika terjadi
                                            error, silahan upload file WORD ke Google Drive dan isikan URL file WORD yang
                                            diupload ke Google Drive</small></label>
                                    <select name="type_panduan" id="type_panduan" class="form-control"
                                        onchange="toggleInputFieldsPanduan()">
                                        <option value="">--pilih--</option>
                                        <option value="upload">Upload File</option>
                                        <option value="link">Link File (Upload Google Drive)</option>
                                    </select>

                                    <div id="panduan_upload_field" class="mb-3" style="display:none;">
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input @error('panduan')is-invalid @enderror"
                                                name="panduan" id="panduan" accept=".docx">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>

                                    <div id="panduan_link_field" style="display:none;">
                                        <input type="url" name="panduan_link" class="form-control"
                                            placeholder="Enter Google Drive link" id="panduan_link">
                                    </div>
                                    @error('panduan')
                                        <small class="text-danger"
                                            style="position:relative;top:-15px;left:5px">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
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

        function toggleInputFieldsPanduan() {
            var typeLaporan = document.getElementById('type_panduan').value;
            var uploadField = document.getElementById('panduan_upload_field');
            var linkField = document.getElementById('panduan_link_field');

            if (typeLaporan === 'upload') {
                uploadField.style.display = 'block';
                linkField.style.display = 'none';
            } else if (typeLaporan === 'link') {
                uploadField.style.display = 'none';
                linkField.style.display = 'block';
            } else {
                uploadField.style.display = 'none';
                linkField.style.display = 'none';
            }
        }
    </script>
@endsection




