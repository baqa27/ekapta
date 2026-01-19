<?php

namespace App\Http\Controllers\TA;

use App\Helpers\AppHelper;
use App\Models\TA\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TA\Pendaftaran;
use App\Models\TA\Pengajuan;
use App\Models\Prodi;
use App\Models\TA\ReviewSeminar;
use App\Models\TA\RevisiSeminar;
use App\Models\TA\Seminar;
use App\Models\TA\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeminarController extends \App\Http\Controllers\Controller
{
    public function seminarMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans', 'ujians', 'seminar'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if ($mahasiswa->email == '-') {
            return redirect()->route('profile');
        }
        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bagians_is_ujian = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_pendadaran', 1)->get();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();
        $bimbingans_is_acc_ujian = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_pendadaran', 1);
            })->get();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect()->route('ta.pendaftaran.mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        if (count($bimbingans_is_acc_seminar) - count($bagians_is_seminar) < count($bagians_is_seminar)) {
            return redirect()->route('ta.bimbingan.mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        $seminar = $mahasiswa->seminar;

        $is_ujian = false;
        if (count($bimbingans_is_acc_ujian) >= count($bagians_is_ujian) * 2) {
            $is_ujian = true;
        }

        $ujian_has_complete = $mahasiswa->ujians()->where('is_lulus', Ujian::VALID_LULUS)->first();
        $reviews_has_acc = 0;
        if ($ujian_has_complete) {
            $reviews_has_acc = count($ujian_has_complete->reviews()->where('status', 'diterima')->where('dosen_status', Dosen::PENGUJI)->get());
        }

        $data = [
            'title' => 'Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'seminar' => $seminar,
            'dosens_penguji' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get() : [],
            'reviews_acc' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->where('status', ReviewSeminar::DITERIMA)->get() : [],
            'is_ujian' => $is_ujian,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
            'reviews_has_acc' => $reviews_has_acc == 3 ? true : false,
        ];

        return view('ta.pages.mahasiswa.seminar.seminar', $data);
    }

    public function seminarAdmin()
    {
        $seminars_review = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVIEW)->get();
        $seminars_revisi = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVISI)->get();
        $seminars_acc = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->get();

        $data = [
            'title' => 'Validasi Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'seminars_review' => $seminars_review,
            'seminars_revisi' => $seminars_revisi,
            'seminars_acc' => $seminars_acc,
        ];

        return view('ta.pages.admin.seminar.seminar', $data);
    }

    public function seminarDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $data = [
            'title' => 'Review Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarDosen',
            'module' => 'ta',
            'seminars_review' => $dosen->seminars()->where('status', ReviewSeminar::REVIEW)->get(),
            'seminars_acc' => $dosen->seminars()->where('status', ReviewSeminar::DITERIMA)->get(),
            'seminars_revisi' => $dosen->seminars()->where('status', ReviewSeminar::REVISI)->get(),
        ];

        return view('ta.pages.dosen.seminar.seminar', $data);
    }

    public function seminarProdi()
    {
        $prodi = Auth::guard('prodi')->user();
        $seminars = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->with(['mahasiswa'])->get();

        $seminars_prodi = [];
        foreach ($seminars as $seminar) {
            if ($seminar->mahasiswa->prodi == $prodi->namaprodi) {
                $seminars_prodi[] = $seminar;
            }
        }

        $data = [
            'title' => 'Daftar Seminar Mahasiswa',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'ta',
            'seminars' => $seminars_prodi,
        ];

        return view('ta.pages.prodi.seminar.seminar', $data);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('ta.pendaftaran.mahasiswa');
        } else if ($pengajuan_acc->seminar) {
            return redirect()->route('ta.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar proposal');
        } else if (count($bimbingans_is_acc_seminar) - count($bagians_is_seminar) < count($bagians_is_seminar)) {
            return redirect()->route('ta.bimbingan.mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        $data = [
            'title' => 'Form Pendaftaran Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
        ];

        return view('ta.pages.mahasiswa.seminar.create', $data);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if (AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)) {
            return redirect()->route('ta.pendaftaran.mahasiswa');
        } else if ($pengajuan->seminar) {
            return redirect()->route('ta.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar proposal');
        }

        $validatedData = $request->validate([
            'lampiran_1' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'nomor_pembayaran' => ['required'],
            'jumlah_bayar' => ['required'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            //            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            //            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
        ]);

        $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampirans');
        $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampirans');
        $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampirans');
        //        $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampirans');
        //        $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampirans');

        $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;
        $validatedData['status'] = Seminar::REVIEW;

        Seminar::create($validatedData);

        return redirect()->route('ta.seminar.mahasiswa')->with('success', 'Pendaftaran Seminar TA berhasil, selihkan tunggu validasi dari admin.');
    }

    public function edit($id)
    {
        $seminar = Seminar::findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($pendaftaran_acc->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($seminar->is_valid == Seminar::REVIEW) {
            return redirect()->route('ta.seminar.mahasiswa');
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('ta.pendaftaran.mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Submit Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'seminar' => $seminar,
        ];

        return view('ta.pages.mahasiswa.seminar.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $validatedData = $request->validate([
            'lampiran_1' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran_1)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_2' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran_2)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'nomor_pembayaran' => ['required'],
            'jumlah_bayar' => ['required'],
            'lampiran_3' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran_3)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            //    'lampiran_4' => [
            //        Rule::requiredIf(function () {
            //            if (empty($this->request->lampiran_4)) {
            //                return false;
            //            }
            //            return true;
            //        }),
            //        'mimes:pdf,png,jpg,jpeg', 'max:5000'
            //    ],
            //    'lampiran_5' => [
            //        Rule::requiredIf(function () {
            //            if (empty($this->request->lampiran_5)) {
            //                return false;
            //            }
            //            return true;
            //        }),
            //        'mimes:pdf,png,jpg,jpeg', 'max:5000'
            //    ],
        ]);

        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampirans');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampirans');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampirans');
        }
        //        if ($request->file('lampiran_4')) {
        //            AppHelper::instance()->deleteLampiran($seminar->lampiran_4);
        //            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampirans');
        //        }
        //        if ($request->file('lampiran_5')) {
        //            AppHelper::instance()->deleteLampiran($seminar->lampiran_5);
        //            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        //        }

        $validatedData['is_valid'] = 0;

        $seminar->update($validatedData);

        return redirect()->route('ta.seminar.mahasiswa')->with('success', 'Pendaftaran Seminar TA berhasil diupdate, silahkan tunggu review dari Admin');
    }

    public function delete(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->delete();
        return 'Seminar has been deleted';
    }

    public function accSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);

        if ($seminar->is_valid == 1 || count($seminar->reviews) == 5) {
            return back();
        }

        $mahasiswa = $seminar->mahasiswa;

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        ReviewSeminar::create([
            'seminar_id' => $seminar->id,
            'dosen_id' => $dosen_utama->id,
            'status' => ReviewSeminar::REVIEW,
            'dosen_status' => ReviewSeminar::DOSEN_PEMBIMBING,
        ]);

        ReviewSeminar::create([
            'seminar_id' => $seminar->id,
            'dosen_id' => $dosen_pendamping->id,
            'status' => ReviewSeminar::REVIEW,
            'dosen_status' => ReviewSeminar::DOSEN_PEMBIMBING,
        ]);

        $seminar->update([
            'is_valid' => 1,
            'tanggal_acc' => now(),
        ]);
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'subject' => 'Pendaftaran Seminar Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat Pendaftaran Seminar Tugas Akhir Anda Berstatus DITERIMA.',
            ]);
        }
        return back()->with('success', 'Pendaftaran Seminar TA berhasil di Acc.');
    }

    public function cancelAcc(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);

        if (count($seminar->reviews) == 5) {
            return back();
        }

        foreach ($seminar->reviews as $review) {
            $review->delete();
        }

        $seminar->update([
            'is_valid' => 0,
        ]);

        return back()->with('success', 'Acc Seminar TA berhasil dibatalkan.');
    }

    public function seminarReviewAdmin($id)
    {
        if (Auth::guard('prodi')->user()) {
            $sidebar = 'partials.sidebarProdi';
        } else {
            $sidebar = 'partials.sidebarAdmin';
        }
        $seminar = Seminar::findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;
        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();

        //$dosens = Dosen::where('kodeprodi', $prodi->kode)->get();
        $dosens = $prodi->dosens;

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        // return $seminar->reviews;
        $reviews_check = $seminar->reviews()->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])->where('dosen_status', 'penguji')->get();

        $data = [
            'title' => 'Review Pendaftaran Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => $sidebar,
            'seminar' => $seminar,
            'dosens' => $dosens,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosens_penguji' => $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get(),
            'reviews_check' => $reviews_check,
        ];

        return view('ta.pages.admin.seminar.review', $data);
    }

    public function seminarReviews($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Review Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'seminar' => $seminar,
        ];

        return view('ta.pages.mahasiswa.seminar.reviews', $data);
    }

    public function revisiSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $revisi = new RevisiSeminar();
        $revisi->keterangan = $request->catatan; // Database uses 'keterangan' column
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () use ($request) {
                    if (empty($request->lampiran)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,docx', 'max:5000'
            ]
        ]);
        if ($request->file('lampiran')) {
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans');
        }
        if ($seminar->is_valid == Seminar::REVIEW) {
            $seminar->update([
                'is_valid' => Seminar::REVISI,
            ]);
            $seminar->revisis()->save($revisi);
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'subject' => 'Pendaftaran Seminar Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Pendaftaran Seminar Tugas Akhir Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!.<br><br>Catatan revisi: ' . $request->catatan,
                ]);
            }
            return redirect()->route('ta.seminar.admin')->with('success', 'Seminar TA berhasil direvisi');
        } elseif ($seminar->is_valid == Seminar::REVISI) {
            $seminar->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function deleteRevisi(Request $request)
    {
        $revisi = RevisiSeminar::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function detail($id)
    {
        $seminar = Seminar::findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($seminar->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('ta.pendaftaran.mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Seminar TA',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ];

        return view('ta.pages.mahasiswa.seminar.detail', $data);
    }

    public function editProposal($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Submit Laporan Seminar Proposal',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'seminar' => $seminar,
        ];

        return view('ta.pages.mahasiswa.seminar.submit-proposal', $data);
    }

    public function updateProposal(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $request->validate([
            'lampiran_proposal' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $seminar->update([
            'lampiran_proposal' => AppHelper::instance()->uploadLampiran($request->lampiran_proposal, 'lampirans'),
        ]);

        return redirect()->route('ta.seminar.mahasiswa')->with('success', 'Laporan Seminar Proposal Berhasil di submit.');
    }

    public function setDateExam(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required',
        ]);
        $validatedData['tanggal_ujian'] = Carbon::parse($request->tanggal_ujian);
        $seminar->update($validatedData);
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'subject' => 'Seminar Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat seminar Tugas Akhir anda sudah dijadwalkan. Berikut detail seminar Tugas Akhir Anda: <br>Tanggal ujian: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat ujian: <b>' . $request->tempat_ujian . '</b>',
            ]);
        }
        foreach ($seminar->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review) {
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'subject' => 'Penguji Seminar Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Kepada Yth Bapak/Ibu <b>' . $review->dosen->nama . ', ' . $review->dosen->gelar . '</b> anda di tunjuk sebagai penguji untuk seminar Tugas Akhir. Berikut detail dan jadwal seminar Tugas Akhir: <br>NIM/Nama Mahasiswa: <b>' . $seminar->mahasiswa->nim . '/' . $seminar->mahasiswa->nama . '</b><br>Judul Skripsi: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal ujian: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat ujian: <b>' . $request->tempat_ujian . '</b>',
                ]);
            }
        }
        return back()->with('success', 'Jadwal dan Tempat Seminar Tugas Akhir berhasil disimpan');
    }

    public function seminarProdiDetail($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'reviews'])->where('id', $id)->first();

        $prodi = Prodi::where('kode', $seminar->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        if (count($seminar->reviews) < 4 || !$presentase_nilai) {
            return back();
        }

        $nilai = AppHelper::hitung_nilai_mahasiswa($seminar);

        $data = [
            'title' => 'Detail Seminar Mahasiswa',
            'active' => 'seminar-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'ta',
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->paginate(5),
            'nilai' => $nilai['nilai'],
            'nilai_dosen_penguji' => $nilai['nilai_penguji'],
            'nilai_dosen_pembimbing' => $nilai['nilai_pembimbing'],
        ];

        return view('ta.pages.prodi.seminar.detail', $data);
    }

    public function rekapSeminar()
    {
        if (Auth::guard('prodi')->user()) {
            $sidebar = 'partials.sidebarProdi';
        } else {
            $sidebar = 'partials.sidebarAdmin';
        }
        $seminars = Seminar::where('is_valid', Seminar::VALID)
            ->where('tanggal_ujian', null)
            ->get();
        return view('ta.pages.admin.seminar.rekap', [
            'title' => 'Rekap Pendaftaran Seminar Mahasiswa',
            'sidebar' => $sidebar,
            'active' => 'seminar-ta',
            'module' => 'ta',
            'seminars' => $seminars,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        if ($seminar->is_valid == 0) {
            return back();
        }
        $seminar->update([
            'is_lulus' => $request->is_lulus,
        ]);
        return response()->json(['message' => 'Status seminar berhasil disimpan']);
    }
}


