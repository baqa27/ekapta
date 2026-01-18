@extends('ta.layouts.dashboard')

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
                        <li class="breadcrumb-item"><a href="#">Semiar TA</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="ribbon-wrapper ribbon-lg">
                            <div
                                class="ribbon
                            @if ($seminar->is_valid == 0) bg-secondary
                            @elseif ($seminar->is_valid == 2)
                            bg-warning
                            @elseif ($seminar->is_valid == 1)
                            bg-success @endif
                            ">
                                @if ($seminar->is_valid == 0)
                                    TIDAK VALID
                                @elseif ($seminar->is_valid == 1)
                                    VALID
                                @elseif ($seminar->is_valid == 2)
                                    REVISI
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-5">
                                    NIM
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->nim }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Nama Lengkap
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->nama }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Prodi
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->mahasiswa->prodi }}</b>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-5">
                                    Judul TA
                                </div>
                                <div class="col-md-7">
                                    <b>{{ $seminar->pengajuan->judul }}</b>
                                </div>
                            </div>
                            <hr>
                            @if($seminar->lampiran_proposal)
                                <div class="row">
                                    <div class="col-md-5">
                                        Proposal Seminar TA
                                    </div>
                                    <div class="col-md-7">
                                        <b><a href="{{ asset($seminar->lampiran_proposal) }}" target="_blank"><i
                                                    class="fas fa-download"></i>
                                                {{ Str::substr($seminar->lampiran_proposal, 40) }}</a>
                                        </b>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            <div class="row">
                                <div class="col-md-5">
                                    Tanggal Ujian
                                </div>
                                <div class="col-md-7">
                                    <b class="text-danger">{{ \App\Helpers\AppHelper::parse_date_short($seminar->tanggal_ujian) }}</b>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-5">
                                    Tempat Ujian
                                </div>
                                <div class="col-md-7">
                                    <b class="text-danger">{{ $seminar->tempat_ujian }}</b>
                                </div>
                            </div>

                        </div>

                        @if ($seminar->is_valid == 1)
                            <div class="card-footer">
                                <div class="d-flex">
                                    <a href="{{ route('ta.cetak.berita.acara.ujian.proposal', $seminar->id) }}"
                                        class="btn btn-success" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Proposal
                                    </a>
                                    &nbsp;
                                    <a href="{{ route('ta.cetak.berita.acara.ujian.proposal.blank', [$seminar->id, 1]) }}"
                                        class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-download"></i> Berita Acara Ujian Proposal Kosong
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Revisi --}}
                    <div class="card card-primary card-outline mt-2">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Revisi</strong>
                                <span class="badge bg-danger rounded-pill">
                                    {{ count($seminar->revisis) }}
                                </span>
                            </h3>
                        </div>

                        <div class="card-body">
                            @foreach ($revisis as $revisi)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $revisi->created_at->format('d M Y H:m') }}
                                        <div class="float-right" onclick="confirmDelete()">
                                            <form action="{{ route('ta.seminar.revisi.delete') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $revisi->id }}">
                                                <button class="btn btn-danger btn-sm float-right" type="submit">
                                                    <i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {!! nl2br($revisi->catatan) !!}
                                    </div>

                                    @if ($revisi->lampiran)
                                        <div class="card-footer">
                                            Lampiran :
                                            @if ($revisi->lampiran)
                                                <a href="{{ asset($revisi->lampiran) }}" class="ml-3" target="_blank"><i
                                                        class="fas fa-paperclip"></i>
                                                    {{ Str::substr($revisi->lampiran, 40) }}</a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            {{ $revisis->links() }}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-3">
                @php
                    $no = 1;
                @endphp
                @foreach ($seminar->reviews as $review)
                    @if ($review->dosen_status == 'penguji')
                        <div class="col-md-4">
                            <div class="card card-primary card-outline">
                                <div class="ribbon-wrapper ribbon-lg">
                                    <div
                                        class="ribbon
                                @if ($review->status == 'diterima') bg-success
                                @elseif($review->status == 'revisi')
                                bg-warning
                                @elseif($review->status == 'review')
                                bg-secondary
                                @else
                                bg-danger @endif
                                ">
                                        @if ($review->status == 'diterima')
                                            Diterima
                                        @elseif($review->status == 'revisi')
                                            Revisi
                                        @elseif($review->status == 'review')
                                            Review
                                        @else
                                            Belum Submit
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    Dosen Penguji {{ $no++ }}: <br>
                                    <b>{{ $review->dosen->nama }}, {{ $review->dosen->gelar }}</b>
                                    <p>
                                        Lampiran Proposal:
                                        <a href="{{ asset($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3) }}"
                                            class="ml-3 text-primary" target="_blank"><i class="fas fa-paperclip mr-2"></i>
                                            {{ Str::substr($review->lampiran ? $review->lampiran : $review->seminar->lampiran_3, 40) }}</a>
                                    </p>
                                    @if ($review->status == 'diterima')
                                        <p>
                                            Tanggal Acc:
                                            <b
                                                class="text-success">{{ \App\Helpers\AppHelper::parse_date_short($review->tanggal_acc) }}</b>
                                        </p>
                                    @endif
                                    @if ($review->tanggal_acc_manual && $review->lampiran_lembar_revisi && $review->status == 'review')
                                        <p>
                                            Tanggal Acc Manual:
                                            <b>{{ \App\Helpers\AppHelper::parse_date_short($review->tanggal_acc_manual) }}</b>
                                        </p>
                                        <p>
                                            Lampiran Lembar Revisi:
                                            <a href="{{ asset($review->lampiran_lembar_revisi) }}"
                                                class="ml-3 text-primary" target="_blank"><i
                                                    class="fas fa-paperclip mr-2"></i>
                                                {{ Str::substr($review->lampiran_lembar_revisi, 40) }}</a>
                                        </p>
                                        <button type="button" class="btn btn-success col-12" data-toggle="modal"
                                            data-target="#modal-acc">
                                            <i class="fas fa-check"></i> Acc bimbingan
                                        </button>

                                        <div class="modal fade" id="modal-acc">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('review.seminar.acc.prodi') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $review->id }}">
                                                        <input type="hidden" name="type" value="input_manual">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Acc Bimbingan</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="" class="form-label">Catatan</label>
                                                                <textarea id="summernote" name="catatan" required></textarea>
                                                                @error('catatan')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="submit" class="btn btn-success">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    @endif
                                </div>

                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

            <div class="card card-primary card-outline col-md-12 mb-3">
                <div class="card-header">
                    Nilai Seminar TA
                </div>
                <div class="card-body table-responsive">
                    <div class="form-group mb-4">
                        <label for="exampleInputFile">Status</label>
                        <select class="form-control" name="is_lulus" id="is_lulus">
                            <option value="">-- pilih --</option>
                            <option value="1" {{ $review->seminar->is_lulus == 1 ? 'selected' : '' }}>Lulus</option>
                            <option value="2" {{ $review->seminar->is_lulus == 2 ? 'selected' : '' }}>Tidak Lulus
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Dosen</th>
                                <th>Status</th>
                                <th>Substansi / Isi Materi</th>
                                <th>Kompetensi Ilmu</th>
                                <th>Metodologi dan Redaksi TA</th>
                                <th>Presentasi</th>
                                <th>Rata-Rata</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($seminar->reviews as $review)
                                <tr>
                                    <td>{{ $review->dosen->nama . ', ' . $review->dosen->gelar }}</td>
                                    <td>{{ $review->dosen_status == \App\Models\ReviewSeminar::DOSEN_PENGUJI ? 'Penguji' : 'Pembimbing' }}
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_1"
                                            data-review-id="{{ $review->id }}" value="{{ $review->nilai_1 }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_2"
                                            data-review-id="{{ $review->id }}" value="{{ $review->nilai_2 }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_3"
                                            data-review-id="{{ $review->id }}" value="{{ $review->nilai_3 }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_4"
                                            data-review-id="{{ $review->id }}" value="{{ $review->nilai_4 }}">
                                    </td>
                                    <td>{{ round(\App\Helpers\AppHelper::instance()->hitung_nilai_mean($review->nilai_1, $review->nilai_2, $review->nilai_3, $review->nilai_4), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr bgcolor="#a9a9a9" class="text-white">
                                <th colspan="6">RATA - RATA NILAI DOSEN PEMBIMBING</th>
                                <th>{{ $nilai_dosen_pembimbing }}</th>
                            </tr>
                            <tr bgcolor="#a9a9a9" class="text-white">
                                <th colspan="6">RATA - RATA NILAI DOSEN PENGUJI</th>
                                <th>{{ $nilai_dosen_penguji }}</th>
                            </tr>
                            <tr bgcolor="#808080" class="text-white">
                                <th colspan="6">NILAI AKHIR</th>
                                <th>{{ $nilai }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#is_lulus').on('change', function() {
                var is_lulus = $(this).val();
                var seminar_id = {{ $review->seminar->id }};

                $.ajax({
                    url: '/seminar/update-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        seminar_id: seminar_id,
                        is_lulus: is_lulus
                    },
                    success: function(response) {
                        // alert(response.message);
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Success',
                            autohide: true,
                            delay: 3000,
                            body: response.message
                        })
                    },
                    error: function(xhr, status, error) {
                        // alert('An error occurred: ' + error);
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: 'Terjadi kesalahan: '+ error
                        })
                    }
                });
            });
        });

        $(document).ready(function() {
            $('input[type="number"]').on('change', function() {
                var input = $(this);
                var reviewId = input.data('review-id');
                var fieldName = input.attr('name');
                var fieldValue = input.val();

                $.ajax({
                    url: '/review/seminar/update-nilai',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        review_id: reviewId,
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function(response) {
                        // alert('Nilai berhasil diperbarui');
                        $(document).Toasts('create', {
                            class: 'bg-success mt-5 mr-3',
                            title: 'Success',
                            autohide: true,
                            delay: 3000,
                            body: 'Nilai berhasil diperbarui'
                        })
                        input.closest('tr').find('td:last').text(response.nilai_akhir);
                    },
                    error: function(xhr, status, error) {
                        // alert('Terjadi kesalahan: ' + error);
                        $(document).Toasts('create', {
                            class: 'bg-danger mt-5 mr-3',
                            title: 'Error',
                            autohide: true,
                            delay: 3000,
                            body: 'Terjadi kesalahan: '+ error
                        })
                    }
                });
            });
        });
    </script>
@endsection




