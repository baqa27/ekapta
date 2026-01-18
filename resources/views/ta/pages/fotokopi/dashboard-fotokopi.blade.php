@extends(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi')

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
                        <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            @if (Auth::guard('admin')->user()->type != \App\Models\Admin::TYPE_SUPER_ADMIN)
                <div class="mb-3 d-flex">
                    <h4 class="flex-grow-1">Selamat datang {{ Auth::guard('admin')->user()->nama }}</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('logout.admin') }}" class="btn btn-danger float-end">Logout <i
                                class="bi bi-box-arrow-right ml-2"></i></a>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">{{ $title }}</h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Jilid
                                            Review</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Jilid
                                            Revisi</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab_4" data-toggle="tab">Jilid
                                            Valid</a>
                                    </li>
                                     <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Jilid
                                            Selesai</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="card-body">
                            @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <table id="example1" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\Jilid::JILID_REVIEW)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}
                                                            </td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    <span class="text-success">
                                                                        Rp
                                                                        {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">PEMERIKSAAN
                                                                    DOKUMEN</span>
                                                            </td>
                                                            <td>
                                                                @if ($jilid->status == 1)
                                                                    <a href="{{ route('ta.jilid.detail', $jilid->id) }}"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fas fa-eye"></i>
                                                                        Periksa Dokumen</a>
                                                                @elseif ($jilid->status == 3)
                                                                    <a href="{{ route('ta.jilid.detail', $jilid->id) }}"
                                                                        class="btn btn-primary btn-sm"><i
                                                                            class="fas fa-book"></i> JILID
                                                                        SKRIPSI</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <table id="example2" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\Jilid::JILID_SELESAI)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}
                                                            </td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    <span class="text-success">
                                                                        Rp
                                                                        {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">SELESAI</span>
                                                                @if ($jilid->is_completed)
                                                                    <br>
                                                                    <span class="badge bg-primary"><i
                                                                            class="fas fa-check-circle"></i> Sudah
                                                                        disetorkan ke perpus</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if (!$jilid->is_completed)
                                                                    <a href="{{ route('ta.jilid.confirm.completed', $jilid->id) }}"
                                                                        class="btn btn-primary btn-sm"
                                                                        onclick="return confirm('Yakin ingin konfirmasi?')"><i
                                                                            class="fas fa-check-circle"></i> Konfirmasi
                                                                        Sudah Setor ke Perpus</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_3">
                                        <table id="example3" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\Jilid::JILID_REVISI)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <span class="badge bg-warning">REVISI</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <table id="example4" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no = 1;
                                                @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\Jilid::JILID_VALID)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                <span class="badge bg-primary">VALID</span>
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM/NAMA MAHASISWA</th>
                                                    <th>TOTAL PEMBAYARAN</th>
                                                    <th>STATUS</th>
                                                    <th>AKSI</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM/NAMA MAHASISWA</th>
                                            <th>TOTAL PEMBAYARAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($jilids as $jilid)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $jilid->mahasiswa->nim . '/' . $jilid->mahasiswa->nama }}
                                                </td>
                                                <td>
                                                    @if ($jilid->total_pembayaran)
                                                        <span class="text-success">
                                                            Rp
                                                            {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">VALID</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('ta.jilid.detail', $jilid->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fas fa-book"></i> JILID
                                                        SKRIPSI</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>No</th>
                                            <th>NIM/NAMA MAHASISWA</th>
                                            <th>TOTAL PEMBAYARAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            @endif
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




