<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="shortcut icon" href="https://unsiq.ac.id/img/UNSIQ-bunder.ico" type="image/x-icon">
    <style>
        body { background: #f4f6f9; min-height: 100vh; }
        .card-mahasiswa { transition: all 0.3s; border-left: 4px solid #007bff; }
        .card-mahasiswa.dinilai { border-left-color: #28a745; background: #f8fff8; }
        .card-mahasiswa:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .badge-urutan { font-size: 1.2rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="mb-0"><i class="fas fa-clipboard-check mr-2 text-primary"></i>Penilaian Seminar Kerja Praktek</h2>
        </div>

        <!-- Info Sesi -->
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title"><strong>Informasi Sesi Seminar</strong></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-calendar mr-2 text-primary"></i>Tanggal</td>
                                <td>: <strong>{{ $sesi->tanggal->translatedFormat('l, d F Y') }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock mr-2 text-primary"></i>Waktu</td>
                                <td>: <strong>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Lokasi</td>
                                <td>: <strong>{{ $sesi->tempat }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-users mr-2 text-primary"></i>Jumlah Peserta</td>
                                <td>: <strong>{{ count($seminars) }} Mahasiswa</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie mr-2 text-primary"></i>Penguji</td>
                                <td>: <strong>{{ $sesi->dosenPenguji->nama ?? '-' }}</strong></td>
                            </tr>
                        </table>
                        @if($sesi->catatan_teknis)
                        <div class="alert alert-info mb-0 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> {{ $sesi->catatan_teknis }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Penilaian -->
        <form id="formPenilaian" enctype="multipart/form-data">
            <!-- Card Daftar Mahasiswa -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title"><strong>Daftar Mahasiswa</strong></h3>
                </div>
                <div class="card-body">
                    @foreach($seminars as $index => $seminar)
                    <div class="card card-mahasiswa mb-3" id="card-{{ $seminar->id }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge badge-primary badge-urutan rounded-circle">{{ $seminar->urutan_presentasi ?? ($index + 1) }}</span>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">{{ $seminar->mahasiswa->nama }}</h5>
                                    <p class="text-muted mb-1">NIM: {{ $seminar->mahasiswa->nim }}</p>
                                    <small class="text-secondary">{{ Str::limit($seminar->judul_laporan ?? $seminar->pengajuan->judul, 60) }}</small>
                                </div>
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="small text-muted">Nilai (0-100) <span class="text-danger">*</span></label>
                                            <input type="number" name="penilaian[{{ $seminar->id }}][nilai]" 
                                                   class="form-control nilai-input" min="0" max="100" step="0.01"
                                                   data-id="{{ $seminar->id }}" required placeholder="0-100">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted">Catatan (opsional)</label>
                                            <input type="text" name="penilaian[{{ $seminar->id }}][catatan]" 
                                                   class="form-control" placeholder="Catatan...">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Dokumen (opsional)</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="penilaian[{{ $seminar->id }}][dokumen]" accept=".pdf,.jpg,.jpeg,.png">
                                                <label class="custom-file-label" style="font-size: 0.85rem;">Pilih file...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-secondary status-badge" id="badge-{{ $seminar->id }}">
                                        <i class="fas fa-hourglass-half"></i> Belum Dinilai
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="card card-primary card-outline">
                <div class="card-body text-center">
                    <p class="text-muted mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                        Pastikan semua mahasiswa sudah dinilai. Setelah submit, link ini tidak bisa digunakan lagi.
                    </p>
                    <button type="submit" class="btn btn-success btn-lg px-5" id="btnSubmit">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Semua Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script src="{{ asset('ekapta') }}/adminLTE/plugins/jquery/jquery.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('ekapta') }}/adminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            // Update badge saat input berubah
            function updateBadge(id) {
                var nilai = $('input[name="penilaian['+id+'][nilai]"]').val();
                var badge = $('#badge-' + id);
                var card = $('#card-' + id);
                
                if (nilai && parseFloat(nilai) >= 0 && parseFloat(nilai) <= 100) {
                    badge.removeClass('badge-secondary badge-warning').addClass('badge-success');
                    badge.html('<i class="fas fa-check"></i> Nilai: ' + nilai);
                    card.addClass('dinilai');
                } else {
                    badge.removeClass('badge-success').addClass('badge-secondary');
                    badge.html('<i class="fas fa-hourglass-half"></i> Belum Dinilai');
                    card.removeClass('dinilai');
                }
            }

            $('.nilai-input').on('change keyup', function() {
                updateBadge($(this).data('id'));
            });

            // Submit form
            $('#formPenilaian').on('submit', function(e) {
                e.preventDefault();
                
                // Cek semua sudah dinilai
                var belumDinilai = [];
                @foreach($seminars as $seminar)
                var nilai{{ $seminar->id }} = $('input[name="penilaian[{{ $seminar->id }}][nilai]"]').val();
                if (!nilai{{ $seminar->id }} || parseFloat(nilai{{ $seminar->id }}) < 0 || parseFloat(nilai{{ $seminar->id }}) > 100) {
                    belumDinilai.push('{{ $seminar->mahasiswa->nama }}');
                }
                @endforeach

                if (belumDinilai.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Lengkap',
                        html: 'Mahasiswa berikut belum dinilai atau nilai tidak valid:<br><b>' + belumDinilai.join(', ') + '</b>',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Submit',
                    text: 'Setelah submit, link ini tidak bisa digunakan lagi. Lanjutkan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Submit',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#btnSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
                        
                        var formData = new FormData($('#formPenilaian')[0]);
                        
                        $.ajax({
                            url: '{{ route("kp.penilaian.seminar.submit", $sesi->token_penilaian) }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    allowOutsideClick: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan';
                                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                                $('#btnSubmit').prop('disabled', false).html('<i class="fas fa-paper-plane mr-2"></i>Submit Semua Penilaian');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>




