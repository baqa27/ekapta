@extends(Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN ? 'layouts.dashboard' : 'layouts.dashboardFotokopi')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Jilid KP</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (Auth::guard('admin')->user()->type != \App\Models\Admin::TYPE_SUPER_ADMIN)
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    Selamat datang, <strong>{{ Auth::guard('admin')->user()->nama }}</strong>
                    <a href="{{ route('logout.admin') }}" class="btn btn-danger btn-sm float-right">Logout</a>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                            @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                <ul class="nav nav-pills ml-auto float-right">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab_1" data-toggle="tab">Review</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_3" data-toggle="tab">Revisi</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_4" data-toggle="tab">Valid</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_2" data-toggle="tab">Selesai</a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                        <div class="card-body">
                            @if (Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN)
                                <div class="tab-content">
                                    {{-- Tab Review --}}
                                    <div class="tab-pane active" id="tab_1">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_REVIEW)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">Pemeriksaan</span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye mr-1"></i> Periksa
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Selesai --}}
                                    <div class="tab-pane" id="tab_2">
                                        <table id="example2" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">Selesai</span>
                                                                @if ($jilid->is_completed)
                                                                    <br><small class="text-primary">Sudah setor perpus</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (!$jilid->is_completed)
                                                                    <a href="{{ route('kp.pengumpulan-akhir.confirm.completed', $jilid->id) }}"
                                                                        class="btn btn-success btn-sm"
                                                                        onclick="return confirm('Yakin ingin konfirmasi bahwa mahasiswa sudah setor ke perpustakaan?')">
                                                                        <i class="fas fa-check mr-1"></i> Konfirmasi Setor
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Sudah dikonfirmasi</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Revisi --}}
                                    <div class="tab-pane" id="tab_3">
                                        <table id="example3" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_REVISI)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>-</td>
                                                            <td>
                                                                <span class="badge bg-warning">Revisi</span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Valid --}}
                                    <div class="tab-pane" id="tab_4">
                                        <table id="example4" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_VALID)
                                                        <tr>
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $jilid->mahasiswa->nim }}</td>
                                                            <td>{{ $jilid->mahasiswa->nama }}</td>
                                                            <td>
                                                                @if ($jilid->total_pembayaran)
                                                                    Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">Valid</span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye mr-1"></i> Dokumen
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                {{-- Tampilan untuk Fotokopian - Lihat Valid dan Selesai --}}
                                <ul class="nav nav-pills mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#tab_fotokopi_valid" data-toggle="tab">
                                            <i class="fas fa-clock mr-1"></i> Menunggu Jilid
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tab_fotokopi_selesai" data-toggle="tab">
                                            <i class="fas fa-check-circle mr-1"></i> Selesai Jilid
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    {{-- Tab Menunggu Jilid (Status VALID) --}}
                                    <div class="tab-pane active" id="tab_fotokopi_valid">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Tanggal Submit</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_VALID)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>{{ $jilid->mahasiswa->nama }}</td>
                                                        <td>{{ $jilid->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">Menunggu Jilid</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-book mr-1"></i> Proses Jilid
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Tab Selesai Jilid --}}
                                    <div class="tab-pane" id="tab_fotokopi_selesai">
                                        <table id="example5" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NIM</th>
                                                    <th>Nama Mahasiswa</th>
                                                    <th>Total Pembayaran</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $no = 1; @endphp
                                                @foreach ($jilids as $jilid)
                                                    @if ($jilid->status == \App\Models\KP\Jilid::JILID_SELESAI)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $jilid->mahasiswa->nim }}</td>
                                                        <td>{{ $jilid->mahasiswa->nama }}</td>
                                                        <td>
                                                            @if ($jilid->total_pembayaran)
                                                                <span class="text-success font-weight-bold">
                                                                    Rp {{ number_format($jilid->total_pembayaran, 0, ',', '.') }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Selesai</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('kp.pengumpulan-akhir.detail', $jilid->id) }}" class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye mr-1"></i> Detail
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




