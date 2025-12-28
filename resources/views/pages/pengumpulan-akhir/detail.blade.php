@extends($is_admin ? (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi') : 'layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Pengumpulan Akhir</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="{{ $is_admin ? 'container-fluid' : 'container' }}">
            <div class="mb-3 d-flex">
                <div class="flex-shrink-1">
                    <a href="{{ route($is_admin ? 'pengumpulan-akhir.index' : 'pengumpulan-akhir.mahasiswa') }}"
                        class="btn btn-secondary float-end"><i class="bi bi-arrow-left ml-2"></i> Kembali</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="p-3 rounded border">
                                <table>
                                    <tr>
                                        <td>NIM/NAMA MAHASISWA</td>
                                        <td>: <b>{{ $mahasiswa->nim . '/' . $mahasiswa->nama }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>PRODI</td>
                                        <td>: <b>{{ $prodi ? $prodi->namaprodi : $mahasiswa->prodi }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>JUDUL KERJA PRAKTEK</td>
                                        <td>: <b>{{ $jilid->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul ?? '-' }}</b></td>
                                    </tr>
                                </table>
                            </div>

                            @if ($is_admin)
                                {{-- Tampilan untuk Admin --}}
                                @if ($jilid->status == 1)
                                    {{-- Status Review - Tampilkan semua dokumen untuk diperiksa --}}
                                    <div class="mt-3">
                                        <a href="{{ asset($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR KEASLIAN
                                        </a>
                                        <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR PERSETUJUAN PEMBIMBING
                                        </a>
                                        <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR PERSETUJUAN PENGUJI
                                        </a>
                                        <a href="{{ asset($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR PENGESAHAN
                                        </a>
                                        <a href="{{ asset($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR BIMBINGAN
                                        </a>
                                        <a href="{{ asset($jilid->lembar_revisi) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LEMBAR REVISI
                                        </a>
                                        <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LAPORAN FORMAT PDF
                                        </a>
                                        <a href="{{ asset($jilid->laporan_word) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> LAPORAN FORMAT WORD
                                        </a>
                                        @if ($jilid->artikel)
                                        <a href="{{ asset($jilid->artikel) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> ARTIKEL FORMAT WORD
                                        </a>
                                        @endif
                                        @if ($jilid->bukti_nilai_instansi)
                                        <a href="{{ asset($jilid->bukti_nilai_instansi) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> BUKTI NILAI INSTANSI
                                        </a>
                                        @endif
                                        @if ($jilid->berita_acara)
                                        <a href="{{ asset($jilid->berita_acara) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> BERITA ACARA
                                        </a>
                                        @endif
                                        @if ($jilid->panduan)
                                        <a href="{{ asset($jilid->panduan) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> PANDUAN PENGGUNAAN
                                        </a>
                                        @endif
                                        @if ($jilid->lampiran)
                                        <a href="{{ asset($jilid->lampiran) }}" class="btn btn-primary mb-3" target="_blank">
                                            <i class="fas fa-download"></i> DOKUMEN LAMPIRAN
                                        </a>
                                        @endif
                                        @if ($jilid->link_project)
                                        <a href="{{ $jilid->link_project }}" class="btn btn-secondary mb-3" target="_blank">
                                            <i class="fas fa-paper-plane"></i> LINK PROJECT
                                        </a>
                                        @endif
                                    </div>
                                @elseif ($jilid->status == 3)
                                    {{-- Status Valid - Untuk Fotokopian --}}
                                    <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3 mt-4" target="_blank">
                                        <i class="fas fa-download"></i> LAPORAN PDF
                                    </a>
                                @endif

                                {{-- Form Aksi Admin --}}
                                <form action="{{ route('pengumpulan-akhir.acc', $jilid->id) }}" method="post">
                                    @method('put')
                                    @csrf
                                    @if ($jilid->status == 3)
                                        {{-- Fotokopian: Input pembayaran dan selesaikan --}}
                                        <input type="hidden" name="status" value="4">
                                        <div class="mt-4">
                                            <label>JUMLAH PEMBAYARAN (Opsional)</label>
                                            <input type="number" name="total_pembayaran" class="form-control"
                                                placeholder="Nominal pembayaran jilid KP"
                                                value="{{ $jilid->total_pembayaran }}">
                                        </div>
                                    @elseif ($jilid->status == 1)
                                        {{-- Admin: Review dokumen --}}
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="">--pilih--</option>
                                                <option value="3">VALID</option>
                                                <option value="2">TIDAK VALID</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Catatan</label>
                                            <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan..." required></textarea>
                                        </div>
                                    @endif
                                    @if ($jilid->status == 1 || $jilid->status == 3)
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check"></i>
                                            @if ($jilid->status == 1)
                                                SIMPAN
                                            @elseif ($jilid->status == 3)
                                                SELESAI
                                            @endif
                                        </button>
                                    </div>
                                    @endif
                                </form>
                            @else
                                {{-- Tampilan untuk Mahasiswa --}}
                                <div class="mt-3">
                                    @if($jilid->lembar_keaslian)
                                    <a href="{{ asset($jilid->lembar_keaslian) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR KEASLIAN
                                    </a>
                                    @endif
                                    @if($jilid->lembar_persetujuan_pembimbing)
                                    <a href="{{ asset($jilid->lembar_persetujuan_pembimbing) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR PERSETUJUAN PEMBIMBING
                                    </a>
                                    @endif
                                    @if($jilid->lembar_persetujuan_penguji)
                                    <a href="{{ asset($jilid->lembar_persetujuan_penguji) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR PERSETUJUAN PENGUJI
                                    </a>
                                    @endif
                                    @if($jilid->lembar_pengesahan)
                                    <a href="{{ asset($jilid->lembar_pengesahan) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR PENGESAHAN
                                    </a>
                                    @endif
                                    @if($jilid->lembar_bimbingan)
                                    <a href="{{ asset($jilid->lembar_bimbingan) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR BIMBINGAN
                                    </a>
                                    @endif
                                    @if($jilid->lembar_revisi)
                                    <a href="{{ asset($jilid->lembar_revisi) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LEMBAR REVISI
                                    </a>
                                    @endif
                                    @if($jilid->laporan_pdf)
                                    <a href="{{ asset($jilid->laporan_pdf) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LAPORAN FORMAT PDF
                                    </a>
                                    @endif
                                    @if($jilid->laporan_word)
                                    <a href="{{ asset($jilid->laporan_word) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> LAPORAN FORMAT WORD
                                    </a>
                                    @endif
                                    @if ($jilid->artikel)
                                    <a href="{{ asset($jilid->artikel) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> ARTIKEL FORMAT WORD
                                    </a>
                                    @endif
                                    @if ($jilid->bukti_nilai_instansi)
                                    <a href="{{ asset($jilid->bukti_nilai_instansi) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> BUKTI NILAI INSTANSI
                                    </a>
                                    @endif
                                    @if ($jilid->berita_acara)
                                    <a href="{{ asset($jilid->berita_acara) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> BERITA ACARA
                                    </a>
                                    @endif
                                    @if ($jilid->panduan)
                                    <a href="{{ asset($jilid->panduan) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> PANDUAN PENGGUNAAN
                                    </a>
                                    @endif
                                    @if ($jilid->lampiran)
                                    <a href="{{ asset($jilid->lampiran) }}" class="btn btn-primary mb-3" target="_blank">
                                        <i class="fas fa-download"></i> DOKUMEN LAMPIRAN
                                    </a>
                                    @endif
                                    @if ($jilid->link_project)
                                    <a href="{{ $jilid->link_project }}" class="btn btn-secondary mb-3" target="_blank">
                                        <i class="fas fa-paper-plane"></i> LINK PROJECT
                                    </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Revisi --}}
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
                                            <span class="direct-chat-name float-left">Admin Ekapta</span>
                                            <span class="direct-chat-timestamp float-right">
                                                {{ $revisi->created_at->format('d M Y H:i a') }}
                                            </span>
                                        </div>
                                        <img class="direct-chat-img"
                                            src="{{ asset('ekapta/adminLTE/dist/img/default-profile.png') }}"
                                            alt="message user image">
                                        <div class="direct-chat-text p-2">
                                            {!! nl2br($revisi->catatan) !!}
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
@endsection
