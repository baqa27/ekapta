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
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .card-mahasiswa { transition: all 0.3s; border-left: 4px solid #007bff; }
        .card-mahasiswa.dinilai { border-left-color: #28a745; background: #f8fff8; }
        .card-mahasiswa:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .badge-urutan { font-size: 1.2rem; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header Info Sesi -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-clipboard-check mr-2"></i>Penilaian Seminar Kerja Praktik</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-calendar mr-2"></i>Tanggal</td>
                                <td>: <strong>{{ $sesi->tanggal->translatedFormat('l, d F Y') }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock mr-2"></i>Waktu</td>
                                <td>: <strong>{{ \Carbon\Carbon::parse($sesi->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($sesi->jam_selesai)->format('H:i') }} WIB</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-map-marker-alt mr-2"></i>Lokasi</td>
                                <td>: <strong>{{ $sesi->tempat }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="150"><i class="fas fa-users mr-2"></i>Jumlah Peserta</td>
                                <td>: <strong>{{ count($seminars) }} Mahasiswa</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user-tie mr-2"></i>Penguji</td>
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
        <form id="formPenilaian">
            @foreach($seminars as $index => $seminar)
            <div class="card card-mahasiswa mb-3" id="card-{{ $seminar->id }}">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="badge badge-primary badge-urutan rounded-circle">{{ $seminar->urutan_presentasi ?? ($index + 1) }}</span>
                        </div>
                        <div class="col-md-4">
                            <h5 class="mb-1">{{ $seminar->mahasiswa->nama }}</h5>
                            <p class="text-muted mb-1">NIM: {{ $seminar->mahasiswa->nim }}</p>
                            <small class="text-secondary">{{ $seminar->judul_laporan ?? $seminar->pengajuan->judul }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="small text-muted">Nilai (0-100)</label>
                                    <input type="number" name="penilaian[{{ $seminar->id }}][nilai]" 
                                           class="form-control nilai-input" min="0" max="100" step="0.01"
                                           data-id="{{ $seminar->id }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Status</label>
                                    <select name="penilaian[{{ $seminar->id }}][status]" 
                                            class="form-control status-input" data-id="{{ $seminar->id }}" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="diterima">Diterima</option>
                                        <option value="revisi">Revisi</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Catatan (opsional)</label>
                                    <input type="text" name="penilaian[{{ $seminar->id }}][catatan]" 
                                           class="form-control" placeholder="Catatan...">
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

            <!-- Submit Button -->
            <div class="card">
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
    <script>
        $(document).ready(function() {
            // Update badge saat input berubah
            function updateBadge(id) {
                var nilai = $('input[name="penilaian['+id+'][nilai]"]').val();
                var status = $('select[name="penilaian['+id+'][status]"]').val();
                var badge = $('#badge-' + id);
                var card = $('#card-' + id);
                
                if (nilai && status) {
                    badge.removeClass('badge-secondary badge-warning').addClass('badge-success');
                    badge.html('<i class="fas fa-check"></i> Selesai');
                    card.addClass('dinilai');
                } else {
                    badge.removeClass('badge-success').addClass('badge-secondary');
                    badge.html('<i class="fas fa-hourglass-half"></i> Belum Dinilai');
                    card.removeClass('dinilai');
                }
            }

            $('.nilai-input, .status-input').on('change keyup', function() {
                updateBadge($(this).data('id'));
            });

            // Submit form
            $('#formPenilaian').on('submit', function(e) {
                e.preventDefault();
                
                // Cek semua sudah dinilai
                var belumDinilai = [];
                @foreach($seminars as $seminar)
                var nilai{{ $seminar->id }} = $('input[name="penilaian[{{ $seminar->id }}][nilai]"]').val();
                var status{{ $seminar->id }} = $('select[name="penilaian[{{ $seminar->id }}][status]"]').val();
                if (!nilai{{ $seminar->id }} || !status{{ $seminar->id }}) {
                    belumDinilai.push('{{ $seminar->mahasiswa->nama }}');
                }
                @endforeach

                if (belumDinilai.length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum Lengkap',
                        html: 'Mahasiswa berikut belum dinilai:<br><b>' + belumDinilai.join(', ') + '</b>',
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
                        
                        $.ajax({
                            url: '{{ url("/penilaian-seminar/" . $sesi->token_penilaian . "/submit") }}',
                            method: 'POST',
                            data: $(this).serialize(),
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
