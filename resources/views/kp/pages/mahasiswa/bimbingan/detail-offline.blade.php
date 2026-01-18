@extends('kp.layouts.dashboardMahasiswa')

@section('content')

<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kp.bimbingan.mahasiswa') }}">Bimbingan KP</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Detail Bimbingan Offline</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Tanggal Bimbingan</th>
                                <td>{{ date('d M Y', strtotime($bimbingan->tanggal_bimbingan)) }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{!! nl2br($bimbingan->keterangan) !!}</td>
                            </tr>
                            <tr>
                                <th>Bukti Bimbingan</th>
                                <td>
                                    @if ($bimbingan->bukti_bimbingan_offline)
                                        <a href="{{ storage_url($bimbingan->bukti_bimbingan_offline) }}" target="_blank" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-file"></i> Lihat Bukti
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Verifikasi</th>
                                <td>
                                    @if ($bimbingan->status_offline == 'pending')
                                        <span class="badge bg-secondary">Menunggu Verifikasi</span>
                                    @elseif ($bimbingan->status_offline == 'verified')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif ($bimbingan->status_offline == 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('kp.bimbingan.mahasiswa') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection




