@extends('kp.layouts.dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Mahasiswa Bimbingan</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Berikan nilai untuk mahasiswa yang sudah menyelesaikan seminar KP dan revisi seminar sudah beres.
                            </div>
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIM/Nama</th>
                                        <th>Judul KP</th>
                                        <th>Nilai Pembimbing</th>
                                        <th>Status Seminar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($mahasiswas as $mhs)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $mhs->nim }} / {{ $mhs->nama }}</td>
                                            <td>{{ $mhs->pengajuans->first()->judul ?? '-' }}</td>
                                            <td>
                                                @if ($mhs->jilid && $mhs->jilid->nilai_pembimbing)
                                                    <span class="badge bg-success">{{ $mhs->jilid->nilai_pembimbing }}</span>
                                                @else
                                                    <span class="badge bg-warning">Belum dinilai</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($mhs->seminar && $mhs->seminar->is_lulus)
                                                    <span class="badge bg-success">Lulus Seminar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm shadow" 
                                                        data-toggle="modal" 
                                                        data-target="#modalNilai{{ $mhs->id }}">
                                                    <i class="fas fa-edit mr-1"></i> 
                                                    {{ $mhs->jilid && $mhs->jilid->nilai_pembimbing ? 'Edit Nilai' : 'Beri Nilai' }}
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Penilaian -->
                                        <div class="modal fade" id="modalNilai{{ $mhs->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white">
                                                            <i class="fas fa-star"></i> Penilaian Pembimbing
                                                        </h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('kp.penilaian.pembimbing.store') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="mahasiswa_id" value="{{ $mhs->id }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <strong>Mahasiswa:</strong> {{ $mhs->nim }} / {{ $mhs->nama }}<br>
                                                                <strong>Judul KP:</strong> {{ $mhs->pengajuans->first()->judul ?? '-' }}
                                                            </div>
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="nilai_pembimbing">Nilai Pembimbing <span class="text-danger">*</span></label>
                                                                <input type="number" 
                                                                       name="nilai_pembimbing" 
                                                                       class="form-control"
                                                                       value="{{ $mhs->jilid->nilai_pembimbing ?? '' }}"
                                                                       min="0" 
                                                                       max="100" 
                                                                       step="0.01"
                                                                       placeholder="Masukkan nilai (0-100)"
                                                                       required>
                                                                <small class="text-muted">Nilai dalam skala 0-100</small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="catatan">Catatan Akhir Pembimbing</label>
                                                                <textarea name="catatan" 
                                                                          class="form-control"
                                                                          rows="3"
                                                                          placeholder="Catatan akhir (opsional)">{{ $mhs->jilid->catatan ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="dokumen_penilaian">Dokumen Penilaian (Opsional)</label>
                                                                <div class="custom-file">
                                                                    <input type="file" 
                                                                           class="custom-file-input" 
                                                                           name="dokumen_penilaian"
                                                                           accept=".pdf">
                                                                    <label class="custom-file-label">Pilih file PDF</label>
                                                                </div>
                                                                <small class="text-muted">Upload dokumen pendukung penilaian jika ada</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-save"></i> Simpan Nilai
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




