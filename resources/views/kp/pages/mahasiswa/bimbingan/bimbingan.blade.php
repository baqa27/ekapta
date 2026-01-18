@extends('kp.layouts.dashboardMahasiswa')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Bimbingan Kerja Praktek</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Bimbingan Kerja Praktek</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            @if (\App\Helpers\AppHelper::check_bimbingan_kp_is_complete($mahasiswa))
                <a href="{{ route('kp.cetak.riwayat.bimbingan.mahasiswa') }}" class="btn btn-success mb-3"
                    target="_blank"><i class="fas fa-download mr-1"></i> DOWNLOAD LEMBAR BIMBINGAN KP</a>
            @endif

            @if ($is_expired)
                <div class="mb-3 bg-danger rounded p-2 text-white">
                    Masa bimbingan anda sudah habis, silahkan lakukan <a
                        href="{{ route('kp.pendaftaran.disable', $pendaftaran_acc->id) }}" class="text-white"><u><b>Perpanjangan KP!</b></u></a>
                </div>
            @else
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Tanggal Berakhir Bimbingan : <b>{{ \App\Helpers\AppHelper::parse_date_short($date_expired) }}</b>
                    @if ($is_seminar)
                        , Selamat anda sudah bisa melakukan
                        <b><a href="{{ route('kp.seminar.create') }}">Pendaftaran Seminar KP</a></b>
                    @endif
                    @if ($check_ujian_has_done)
                        , <b><a href="{{ route('kp.pengumpulan-akhir.create') }}">Jilid KP</a></b>
                    @endif
                </div>

                <div class="d-flex justify-content-center mb-3 bg-primary rounded p-2 countdown"
                    data-expire="{{ \Carbon\Carbon::parse($date_expired)->format('Y/m/d h:i:s') }}">
                </div>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Bimbingan Anda</h3>
                </div>
                <div class="card-body">
                    <p>Dosen Pembimbing : <strong>
                        @if ($dosen_utama)
                            {{ $dosen_utama->nama . ', ' . $dosen_utama->gelar }}
                        @else
                            -
                        @endif
                    </strong></p>

                    <div class="table-responsive">
                        <table id="table-bimbingan" class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Bagian Bimbingan</th>
                                    <th>Tanggal Bimbingan</th>
                                    <th>Tanggal ACC</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $no = 1; @endphp
                            @foreach ($bimbingan_per_bagian as $item)
                                @php $bagian = $item['bagian']; $bimbingan = $item['bimbingan']; @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $bagian->bagian }}</td>
                                    <td>
                                        @if ($bimbingan && $bimbingan->tanggal_bimbingan)
                                            {{ date('d M Y H:i', strtotime($bimbingan->tanggal_bimbingan)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($bimbingan && $bimbingan->tanggal_acc)
                                            {{ date('d M Y H:i', strtotime($bimbingan->tanggal_acc)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($bimbingan && $bimbingan->status)
                                            @if ($bimbingan->status == 'review')
                                                <span class="badge bg-secondary">Review</span>
                                            @elseif ($bimbingan->status == 'revisi')
                                                <span class="badge bg-warning">Revisi</span>
                                            @elseif ($bimbingan->status == 'diterima')
                                                <span class="badge bg-success">Diterima</span>
                                            @endif
                                        @else
                                            <span class="badge bg-light text-dark">Belum Submit</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$is_expired)
                                            @if ($bimbingan && $bimbingan->status)
                                                {{-- Sudah ada bimbingan --}}
                                                @if ($bimbingan->status == 'review')
                                                    {{-- Status review: tampilkan tombol Detail saja (baik dosen manual maupun online) --}}
                                                    <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                @elseif ($bimbingan->status == 'revisi')
                                                    <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('kp.bimbingan.edit', $bimbingan->id) }}" class="btn btn-success btn-sm shadow">
                                                        <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                    </a>
                                                @elseif ($bimbingan->status == 'diterima')
                                                    <a href="{{ route('kp.bimbingan.detail', $bimbingan->id) }}" class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                @endif
                                            @else
                                                {{-- Belum submit - cek apakah bisa submit --}}
                                                @php
                                                    $canSubmit = false;
                                                    $currentIndex = $no - 1; // Index bagian saat ini (1-based)
                                                    
                                                    if ($currentIndex == 1) {
                                                        // Bab pertama selalu bisa submit
                                                        $canSubmit = true;
                                                    } else {
                                                        // Cek apakah bagian sebelumnya sudah di-ACC
                                                        // Ambil bagian sebelumnya dari $bimbingan_per_bagian
                                                        $prevItem = $bimbingan_per_bagian[$currentIndex - 2] ?? null;
                                                        if ($prevItem && $prevItem['bimbingan'] && $prevItem['bimbingan']->status == 'diterima') {
                                                            $canSubmit = true;
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if ($canSubmit)
                                                    @if ($dosen_utama && $dosen_utama->is_manual)
                                                        {{-- Dosen offline: langsung input acc manual --}}
                                                        <a href="{{ route('kp.bimbingan.create.manual') }}?bagian_id={{ $bagian->id }}" class="btn btn-warning btn-sm shadow">
                                                            <i class="fas fa-upload mr-1"></i> Input Acc Manual
                                                        </a>
                                                    @else
                                                        {{-- Dosen online: submit file ke sistem --}}
                                                        <a href="{{ route('kp.bimbingan.create') }}?bagian_id={{ $bagian->id }}" class="btn btn-success btn-sm shadow">
                                                            <i class="fas fa-upload mr-1"></i> Submit
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Bagian Bimbingan</th>
                                <th>Tanggal Bimbingan</th>
                                <th>Tanggal ACC</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#table-bimbingan').DataTable({
        "paging": true, "lengthChange": false, "searching": true, "ordering": true, "info": true, "autoWidth": false, "responsive": true
    });
});
</script>
@endpush




