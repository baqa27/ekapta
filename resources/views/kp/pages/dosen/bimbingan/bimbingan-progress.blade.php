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
                            <h3 class="card-title">Tabel {{ $title }}</h3>
                            <div class="card-tools">
                                <span class="badge badge-success mr-2"><i class="fas fa-check-circle mr-1"></i> Diterima/Acc</span>
                                <span class="badge badge-secondary"><i class="fas fa-circle mr-1"></i> Review/Belum Di Acc</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Kontak</th>
                                        <th>Prodi</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Bagian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($mahasiswas as $mahasiswa)
                                        {{-- @if (count($mahasiswa->bimbingans) != 0) --}}
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $mahasiswa->nama }}
                                                {{ '(' . $mahasiswa->nim . ')' }}
                                            </td>
                                            <td>
                                                @if(substr($mahasiswa->hp, 0, 2) === "62")
                                                    <a href="https://api.whatsapp.com/send?phone={{ $mahasiswa->hp}}" class="btn btn-success btn-sm rounded-pill" target="_blank"><i class="fab fa-whatsapp"></i></a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $mahasiswa->prodi }}
                                            </td>
                                            <td>
                                                @if ($mahasiswa->pengajuans()->where('status', 'diterima')->first())
                                                    {{ $mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul }}
                                                @else
                                                    <span class="badge bg-secondary">BELUM PENGAJUAN JUDUL</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (count(
                                                        $mahasiswa->bimbingans()->whereIn('status', ['revisi', 'review', 'diterima'])->get()) != 0)
                                                    <span class="badge bg-success">AKTIF</span>
                                                @else
                                                    {{--<span class="badge bg-danger">TIDAK AKTIF</span>--}}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($mahasiswa->bimbingans()->orderBy('created_at', 'desc')->whereIn('status', ['revisi', 'review', 'diterima'])->first())
                                                    {{ \App\Helpers\AppHelper::parse_date($mahasiswa->bimbingans()->orderBy('tanggal_bimbingan', 'desc')->first()->tanggal_bimbingan) }}
                                                @endif
                                            </td>
                                            {{-- <td>
                                                @if (count($mahasiswa->bimbingans) != 0)
                                                    <a href="{{ route('kp.bimbingan.review.admin', $mahasiswa->pengajuans()->where('status', 'diterima')->first()->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="bi bi-info-circle"></i>
                                                        Detail Bimbingan</a>
                                                @else
                                                <span class="badge bg-secondary">BELUM ADA BIMBINGAN</span>
                                                @endif
                                            </td> --}}

                                            <td>
                                                @if (count($mahasiswa->bimbingans) != 0)
                                                    <div>
                                                        @php
                                                            // Support both old (utama/pendamping) and new (pembimbing) status
                                                            $pivotStatus = $mahasiswa->pivot->status;
                                                            $pembimbingLabel = match($pivotStatus) {
                                                                'utama' => '1',
                                                                'pendamping' => '2',
                                                                'pembimbing' => '',
                                                                default => ''
                                                            };
                                                            
                                                            // Group bimbingan by bagian_id to avoid duplicates
                                                            // For each bagian, get the latest/best status (diterima > review > revisi)
                                                            $bimbinganByBagian = $mahasiswa->bimbingans->groupBy('bagian_id')->map(function($items) use ($pivotStatus) {
                                                                // Filter by pembimbing status for old system
                                                                if ($pivotStatus != 'pembimbing') {
                                                                    $items = $items->where('pembimbing', $pivotStatus);
                                                                }
                                                                // Get the one with best status (prioritize diterima)
                                                                return $items->sortByDesc(function($item) {
                                                                    return $item->status == 'diterima' ? 2 : ($item->status == 'review' ? 1 : 0);
                                                                })->first();
                                                            })->filter();
                                                        @endphp
                                                        <span>Sebagai Pembimbing {{ $pembimbingLabel }}</span> <br>
                                                        @foreach ($bimbinganByBagian as $bimbingan)
                                                            @if ($bimbingan)
                                                                @if ($bimbingan->status == 'diterima')
                                                                    <a href="{{ storage_url($bimbingan->lampiran) }}" target="_blank">
                                                                        <span class="badge badge-success">
                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                            {{ $bimbingan->bagian->bagian }}
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    <span class="badge badge-secondary">
                                                                        <i class="fas fa-circle mr-1"></i>
                                                                        {{ $bimbingan->bagian->bagian }}
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                <span class="badge badge-secondary">BELUM ADA BIMBINGAN</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($mahasiswa->pengajuans()->where('status', 'diterima')->first())
                                                    <a href="{{ route('kp.bimbingan.review.admin', $mahasiswa->pengajuans()->where('status', 'diterima')->first()->id) }}"
                                                        class="btn btn-primary btn-sm shadow">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail Bimbingan
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Kontak</th>
                                        <th>Prodi</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Status Bimbingan</th>
                                        <th>Terakhir Bimbingan</th>
                                        <th>Bagian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- ./card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection




