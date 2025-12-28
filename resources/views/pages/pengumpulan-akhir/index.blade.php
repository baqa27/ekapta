@extends('layouts.dashboardMahasiswa')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengumpulan Akhir KP</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">

            @if (!$jilid)
                <a href="{{ route('pengumpulan-akhir.create') }}" class="btn btn-primary mb-4">
                    <i class="fas fa-plus mr-2"></i> Submit Pengumpulan Akhir KP
                </a>
            @else
                @if ($jilid->status == 4)
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i> Selamat! Pengumpulan Akhir KP sudah <strong>SELESAI</strong>.
                    </div>
                @elseif ($jilid->status == 3)
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-check-circle mr-2"></i> Dokumen sudah <strong>VALID</strong>. Menunggu verifikasi akhir.
                    </div>
                @elseif ($jilid->status == 2)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-exclamation-triangle mr-2"></i> Dokumen perlu <strong>REVISI</strong>. Silakan submit ulang.
                    </div>
                @elseif ($jilid->status == 1)
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <i class="fas fa-hourglass-half mr-2"></i> Dokumen sedang <strong>direview</strong> oleh Admin.
                    </div>
                @endif
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Pengumpulan Akhir Anda</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Tanggal Submit</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($jilids as $j)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <a href="{{ route('pengumpulan-akhir.detail.mahasiswa', $j->id) }}">
                                                    {{ $j->mahasiswa->pengajuans()->where('status', 'diterima')->first()->judul ?? '-' }}
                                                </a>
                                            </td>
                                            <td>{{ $j->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if ($j->status == 1)
                                                    <span class="badge bg-secondary">Review</span>
                                                @elseif ($j->status == 2)
                                                    <span class="badge bg-warning">Revisi</span>
                                                @elseif ($j->status == 3)
                                                    <span class="badge bg-primary">Valid</span>
                                                @elseif ($j->status == 4)
                                                    <span class="badge bg-success">Selesai</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('pengumpulan-akhir.detail.mahasiswa', $j->id) }}" class="btn btn-primary btn-sm shadow mr-2">
                                                        <i class="fas fa-info-circle mr-1"></i> Detail
                                                    </a>
                                                    @if ($j->status == 2)
                                                        <a href="{{ route('pengumpulan-akhir.edit', $j->id) }}" class="btn btn-success btn-sm shadow">
                                                            <i class="fas fa-upload mr-1"></i> Submit Revisi
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Kerja Praktek</th>
                                        <th>Tanggal Submit</th>
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
    </div>
@endsection
