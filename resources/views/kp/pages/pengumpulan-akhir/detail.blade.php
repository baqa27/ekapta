@extends($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi') : 'layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route($is_admin ? 'pengumpulan-akhir.index' : 'pengumpulan-akhir.mahasiswa') }}">Jilid KP</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
            <div class="row">
                <div class="col-md-12">
                    {{-- Card Info --}}
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon
                                @if ($jilid->status == 1) bg-secondary
                                @elseif ($jilid->status == 2) bg-warning
                                @elseif ($jilid->status == 3) bg-primary
                                @elseif ($jilid->status == 4) bg-success @endif">
                                @if ($jilid->status == 1)
                                    REVIEW
                                @elseif ($jilid->status == 2)
                                    REVISI
                                @elseif ($jilid->status == 3)
                                    VALID
                                @elseif ($jilid->status == 4)
                                    SELESAI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">NIM</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nim }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Nama Lengkap</div>
                                <div class="col-md-8"><b>{{ $mahasiswa->nama }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Prodi</div>
                                <div class="col-md-8"><b>{{ $prodi ? $prodi->namaprodi : $mahasiswa->prodi }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Judul KP</div>
                                <div class="col-md-8"><b>{{ $jilid->mahasiswa->pengajuansKP()->where('status', 'diterima')->first()->judul ?? '-' }}</b></div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-4">Tanggal Submit</div>
                                <div class="col-md-8"><b>{{ $jilid->created_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>

                            @if($jilid->updated_at && $jilid->updated_at != $jilid->created_at)
                            <div class="row">
                                <div class="col-md-4">Terakhir Update</div>
                                <div class="col-md-8"><b>{{ $jilid->updated_at->format('d M Y H:i') }}</b></div>
                            </div>
                            <hr>
                            @endif

                            {{-- Nilai KP --}}
                            {{-- Nilai Pembimbing dari jilid.nilai_pembimbing (via form Penilaian Pembimbing) --}}
                            @if($jilid->nilai_pembimbing)
                            <div class="row">
                                <div class="col-md-4">Nilai Dosen Pembimbing</div>
                                <div class="col-md-8">
                                    <b>{{ number_format($jilid->nilai_pembimbing, 2) }}</b>
                                    @php
                                        $dosen_pembimbing = $mahasiswa->dosens()->whereIn('status', ['pembimbing', 'utama'])->first();
                                    @endphp
                                    @if($dosen_pembimbing)
                                    <small class="text-muted">({{ $dosen_pembimbing->nama }})</small>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Nilai Seminar dari form public himpunan (seminar.nilai_seminar) --}}
                            @if($mahasiswa->seminar && $mahasiswa->seminar->nilai_seminar)
                            <div class="row">
                                <div class="col-md-4">Nilai Seminar</div>
                                <div class="col-md-8">
                                    <b>{{ number_format($mahasiswa->seminar->nilai_seminar, 2) }}</b>
                                    @if($mahasiswa->seminar->sesiSeminar && $mahasiswa->seminar->sesiSeminar->dosenPenguji)
                                    <small class="text-muted">(Penguji: {{ $mahasiswa->seminar->sesiSeminar->dosenPenguji->nama ?? '-' }})</small>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Nilai Instansi --}}
                            @if($mahasiswa->seminar && $mahasiswa->seminar->nilai_instansi)
                            <div class="row">
                                <div class="col-md-4">Nilai Instansi</div>
                                <div class="col-md-8">
                                    <b>{{ number_format($mahasiswa->seminar->nilai_instansi, 2) }}</b>
                                </div>
                            </div>
                            <hr>
                            @endif

                            {{-- Dokumen --}}
                            @if($jilid->lembar_keaslian)
                            <div class="row">
                                <div class="col-md-4">Lembar Keaslian</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_keaslian) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_keaslian) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_persetujuan_pembimbing)
                            <div class="row">
                                <div class="col-md-4">Lembar Persetujuan Pembimbing</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_persetujuan_pembimbing) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_persetujuan_penguji)
                            <div class="row">
                                <div class="col-md-4">Lembar Persetujuan Penguji</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_persetujuan_penguji) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_pengesahan)
                            <div class="row">
                                <div class="col-md-4">Lembar Pengesahan</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_pengesahan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_pengesahan) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_bimbingan)
                            <div class="row">
                                <div class="col-md-4">Lembar Bimbingan</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_bimbingan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_bimbingan) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lembar_revisi)
                            <div class="row">
                                <div class="col-md-4">Lembar Revisi</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lembar_revisi) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lembar_revisi) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->laporan_pdf)
                            <div class="row">
                                <div class="col-md-4">Laporan KP (PDF)</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->laporan_pdf) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_pdf) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->laporan_word)
                            <div class="row">
                                <div class="col-md-4">Laporan KP (Word)</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->laporan_word) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->laporan_word) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->artikel)
                            <div class="row">
                                <div class="col-md-4">Artikel KP (Word)</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->artikel) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->artikel) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->bukti_nilai_instansi)
                            <div class="row">
                                <div class="col-md-4">Bukti Nilai Instansi</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->bukti_nilai_instansi) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->bukti_nilai_instansi) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->berita_acara)
                            <div class="row">
                                <div class="col-md-4">Berita Acara</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->berita_acara) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->berita_acara) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->panduan)
                            <div class="row">
                                <div class="col-md-4">Panduan Penggunaan</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->panduan) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->panduan) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->lampiran)
                            <div class="row">
                                <div class="col-md-4">Dokumen Lampiran</div>
                                <div class="col-md-8">
                                    <a href="{{ asset($jilid->lampiran) }}" target="_blank">
                                        <i class="fas fa-paperclip"></i> {{ basename($jilid->lampiran) }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if($jilid->link_project)
                            <div class="row">
                                <div class="col-md-4">Link Project</div>
                                <div class="col-md-8">
                                    <a href="{{ $jilid->link_project }}" target="_blank">
                                        <i class="fas fa-external-link-alt"></i> {{ $jilid->link_project }}
                                    </a>
                                </div>
                            </div>
                            <hr>
                            @endif

                            @if ($is_admin && ($jilid->status == 1 || $jilid->status == 3))
                            <form action="{{ route('kp.pengumpulan-akhir.acc', $jilid->id) }}" method="post">
                                @method('put')
                                @csrf
                                @if ($jilid->status == 3)
                                    <input type="hidden" name="status" value="4">
                                    <div class="form-group">
                                        <label>Jumlah Pembayaran (Opsional)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran jilid KP"
                                                value="{{ $jilid->total_pembayaran }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check mr-1"></i> Selesaikan Proses Jilid
                                    </button>
                                @elseif ($jilid->status == 1)
                                    <div class="form-group">
                                        <label>Status Verifikasi <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="3">VALID - Dokumen lengkap dan benar</option>
                                            <option value="2">TIDAK VALID - Perlu revisi</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan <span class="text-danger">*</span></label>
                                        <textarea name="catatan" class="form-control" rows="3" placeholder="Berikan catatan untuk mahasiswa..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save mr-1"></i> Simpan Verifikasi
                                    </button>
                                @endif
                            </form>
                            @endif
                        </div>
                    </div>

                    {{-- Card Revisi --}}
                    <div class="card card-outline card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b>Revisi</b>
                                <span class="badge bg-danger rounded-pill">{{ count($revisis) }}</span>
                            </h3>
                            <div class="card-tools">
                                {{ $revisis->links() }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="p-2">
                                @forelse ($revisis as $revisi)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left">Admin/Fotokopi</span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:i a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br(e($revisi->catatan)) !!}
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
    </div>
    <!-- /.content -->
@endsection




