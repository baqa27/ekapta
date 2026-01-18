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

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="bi bi-credit-card mr-2"></i>Info Pembayaran Seminar KP</h3>
                        </div>
                        <form action="{{ route('kp.payment.himpunan.update') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Pengaturan info pembayaran akan ditampilkan di form pendaftaran seminar mahasiswa.
                                </div>
                                
                                <div class="form-group">
                                    <label>Biaya Seminar <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="biaya_seminar" class="form-control" required min="0" value="{{ $himpunan->biaya_seminar ?? 25000 }}">
                                    </div>
                                </div>

                                <hr>
                                <h6 class="text-muted mb-3"><i class="fas fa-university mr-2"></i>Transfer Bank</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Bank</label>
                                            <input type="text" name="bank" class="form-control" placeholder="Contoh: BRI, BCA, Mandiri" value="{{ $himpunan->bank }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor Rekening</label>
                                            <input type="text" name="nomor_rekening" class="form-control" placeholder="Contoh: 1234567890" value="{{ $himpunan->nomor_rekening }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nama Pemilik Rekening</label>
                                    <input type="text" name="nama_rekening" class="form-control" placeholder="Contoh: HIMATIF UNSIQ" value="{{ $himpunan->nama_rekening }}">
                                </div>

                                <hr>
                                <h6 class="text-muted mb-3"><i class="fas fa-wallet mr-2"></i>E-Wallet</h6>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor DANA</label>
                                            <input type="text" name="nomor_dana" class="form-control" placeholder="Contoh: 081234567890" value="{{ $himpunan->nomor_dana }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor SeaBank</label>
                                            <input type="text" name="nomor_seabank" class="form-control" placeholder="Contoh: 081234567890" value="{{ $himpunan->nomor_seabank }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning"><i class="bi bi-save mr-2"></i>Simpan Info Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-eye mr-2"></i>Preview</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Info ini akan ditampilkan kepada mahasiswa:</p>
                            <hr>
                            <p><strong>Biaya Seminar:</strong> <br>Rp {{ number_format($himpunan->biaya_seminar ?? 25000, 0, ',', '.') }}</p>
                            @if($himpunan->bank && $himpunan->nomor_rekening)
                            <p><strong>Transfer Bank:</strong> <br>{{ $himpunan->bank }} - {{ $himpunan->nomor_rekening }} <br>a.n. {{ $himpunan->nama_rekening }}</p>
                            @endif
                            @if($himpunan->nomor_dana)
                            <p><strong>DANA:</strong> <br>{{ $himpunan->nomor_dana }}</p>
                            @endif
                            @if($himpunan->nomor_seabank)
                            <p><strong>SeaBank:</strong> <br>{{ $himpunan->nomor_seabank }}</p>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection
