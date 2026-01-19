<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Models\KP\Bimbingan;
use App\Models\Dosen;
use App\Models\Himpunan;
use App\Models\Mahasiswa;
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\Prodi;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiSeminar;
use App\Models\KP\Seminar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SeminarController extends \App\Http\Controllers\Controller
{
    public function seminarMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP', 'seminarKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if ($mahasiswa->email == '-') {
            return redirect()->route('kp.profile');
        }

        // Cek tahapan: Bimbingan harus selesai dulu
        if (!AppHelper::canAccessSeminar($mahasiswa)) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Selesaikan tahap Bimbingan KP terlebih dahulu. Semua bagian bimbingan harus sudah di-ACC oleh Dosen Pembimbing.');
        }

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $seminar = $mahasiswa->seminarKP;

        // Cek status pendaftaran seminar
        $is_pendaftaran_open = \App\Models\Himpunan::isPendaftaranSeminarOpen();

        $data = [
            'title' => 'Seminar KP',
            'active' => 'seminar-kp',
            'dosen_utama' => $dosen_pembimbing,
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
            'dosens_penguji' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get() : collect(),
            'reviews_acc' => $seminar ? $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->where('status', ReviewSeminar::DITERIMA)->get() : collect(),
            'is_ujian' => false,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
            'reviews_has_acc' => false,
            'is_pendaftaran_open' => $is_pendaftaran_open,
        ];

        return view('kp.pages.mahasiswa.seminar.seminar', $data);
    }

    public function seminarAdmin()
    {
        $seminars_review = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVIEW)->get();
        $seminars_revisi = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVISI)->get();
        $seminars_acc = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->get();

        $data = [
            'title' => 'Validasi Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'kp',
            'seminars_review' => $seminars_review,
            'seminars_revisi' => $seminars_revisi,
            'seminars_acc' => $seminars_acc,
        ];

        return view('kp.pages.admin.seminar.seminar', $data);
    }

    public function seminarDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $data = [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'partials.sidebarDosen',
            'module' => 'kp',
            'seminars_review' => $dosen->seminars()->where('status', ReviewSeminar::REVIEW)->get(),
            'seminars_acc' => $dosen->seminars()->where('status', ReviewSeminar::DITERIMA)->get(),
            'seminars_revisi' => $dosen->seminars()->where('status', ReviewSeminar::REVISI)->get(),
        ];

        return view('kp.pages.dosen.seminar.seminar', $data);
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
            'active' => 'seminar-kp',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'kp',
            'seminars' => $seminars_prodi,
        ];

        return view('kp.pages.prodi.seminar.seminar', $data);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $pengajuan_acc = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function ($query) {
                $query->where('is_seminar', 1);
            })->get();

        $bagians = [];
        foreach ($bagians_is_seminar as $b) {
            array_push($bagians, $b->bagian);
        }

        // Cek apakah pendaftaran seminar dibuka oleh himpunan
        if (!Himpunan::isPendaftaranSeminarOpen()) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Pendaftaran Seminar KP sedang DITUTUP. Silahkan tunggu pengumuman dari Himpunan.');
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        } else if ($pengajuan_acc->seminar) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar KP');
        } else if (count($bimbingans_is_acc_seminar) < count($bagians_is_seminar)) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        // Ambil data himpunan untuk info pembayaran
        $himpunan = Himpunan::first();

        $data = [
            'title' => 'Form Pendaftaran Seminar KP',
            'active' => 'seminar-kp',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'himpunan' => $himpunan,
        ];

        return view('kp.pages.mahasiswa.seminar.create', $data);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if (AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        } else if ($pengajuan->seminar) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Sudah mendaftar seminar KP');
        }

        // Cek apakah pendaftaran seminar dibuka
        if (!\App\Models\Himpunan::isPendaftaranSeminarOpen()) {
            return redirect()->route('kp.seminar.mahasiswa')->with('warning', 'Pendaftaran Seminar KP belum dibuka.');
        }

        $validatedData = $request->validate([
            'no_wa' => ['required', 'string', 'max:20'],
            'file_laporan' => ['required', 'mimes:pdf', 'max:10240'],
            'file_pengesahan' => ['required', 'mimes:pdf', 'max:10240'],
            'lampiran_1' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 1
            'lampiran_2' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 2
            'lampiran_3' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 3
            'lampiran_4' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // Sertifikat 4
            'metode_bayar' => ['required', 'in:Cash,DANA,SeaBank,Transfer Bank'],
            'bukti_bayar' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:10240'],
        ]);

        // Ambil biaya seminar dari himpunan
        $himpunan = Himpunan::first();
        $validatedData['jumlah_bayar'] = $himpunan ? $himpunan->biaya_seminar : 25000;

        // Judul laporan otomatis dari pengajuan
        $validatedData['judul_laporan'] = $pengajuan->judul;

        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Upload semua file
        $validatedData['file_laporan'] = AppHelper::instance()->uploadLampiran($request->file('file_laporan'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['file_pengesahan'] = AppHelper::instance()->uploadLampiran($request->file('file_pengesahan'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampirans', $mahasiswa->nim, 'seminar');
        $validatedData['bukti_bayar'] = AppHelper::instance()->uploadLampiran($request->file('bukti_bayar'), 'lampirans', $mahasiswa->nim, 'seminar');

        $validatedData['mahasiswa_id'] = $mahasiswa->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;
        $validatedData['is_valid'] = Seminar::REVIEW;
        $validatedData['status_seminar'] = Seminar::STATUS_MENUNGGU_VERIFIKASI;

        Seminar::create($validatedData);

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Pendaftaran Seminar KP berhasil! Silahkan tunggu verifikasi dari Himpunan.');
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
            return redirect()->route('kp.seminar.mahasiswa');
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        }

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $data = [
            'title' => 'Submit Seminar KP',
            'active' => 'seminar-kp',
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $validatedData = $request->validate([
            'no_wa' => ['required', 'string', 'max:20'],
            'link_akses_produk' => ['required', 'url'],
            'metode_bayar' => ['required', 'in:Cash,DANA,SeaBank,Transfer Bank'],
            'file_laporan' => ['nullable', 'mimes:pdf', 'max:10240'],
            'file_pengesahan' => ['nullable', 'mimes:pdf', 'max:10240'],
            'lampiran_1' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_2' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_3' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'lampiran_4' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'bukti_bayar' => ['nullable', 'mimes:jpg,png,jpeg,pdf', 'max:10240'],
        ]);

        $mahasiswa = $seminar->mahasiswa;

        // Upload file jika ada yang baru
        if ($request->file('file_laporan')) {
            AppHelper::instance()->deleteLampiran($seminar->file_laporan);
            $validatedData['file_laporan'] = AppHelper::instance()->uploadLampiran($request->file_laporan, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('file_pengesahan')) {
            AppHelper::instance()->deleteLampiran($seminar->file_pengesahan);
            $validatedData['file_pengesahan'] = AppHelper::instance()->uploadLampiran($request->file_pengesahan, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('lampiran_4')) {
            AppHelper::instance()->deleteLampiran($seminar->lampiran_4);
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampirans', $mahasiswa->nim, 'seminar');
        }
        if ($request->file('bukti_bayar')) {
            AppHelper::instance()->deleteLampiran($seminar->bukti_bayar);
            $validatedData['bukti_bayar'] = AppHelper::instance()->uploadLampiran($request->bukti_bayar, 'lampirans', $mahasiswa->nim, 'seminar');
        }

        // Set status kembali ke review
        $validatedData['is_valid'] = Seminar::REVIEW;
        $validatedData['status_seminar'] = Seminar::STATUS_MENUNGGU_VERIFIKASI;

        $seminar->update($validatedData);

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Revisi Seminar KP berhasil disubmit, silahkan tunggu verifikasi dari Himpunan');
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

        if ($seminar->is_valid == 1 || count($seminar->reviews) >= 3) {
            return back();
        }

        $mahasiswa = $seminar->mahasiswa;

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        if ($dosen_pembimbing) {
            ReviewSeminar::create([
                'seminar_id' => $seminar->id,
                'dosen_id' => $dosen_pembimbing->id,
                'status' => ReviewSeminar::REVIEW,
                'dosen_status' => ReviewSeminar::DOSEN_PEMBIMBING,
            ]);
        }

        $seminar->update([
            'is_valid' => 1,
            'tanggal_acc' => now(),
        ]);
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Selamat Pendaftaran  Seminar Kerja Praktek Anda Berstatus DITERIMA.',
            ]);
        }
        return back()->with('success', 'Pendaftaran Seminar KP berhasil di Acc.');
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

        return back()->with('success', 'Acc Seminar KP berhasil dibatalkan.');
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
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        $dosens = $prodi ? $prodi->dosens : collect();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }
        $reviews_check = $seminar->reviews()->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])->where('dosen_status', 'penguji')->get();

        $data = [
            'title' => 'Review Pendaftaran Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => $sidebar,
            'seminar' => $seminar,
            'dosens' => $dosens,
            'dosen_utama' => $dosen_pembimbing, // Untuk kompatibilitas view
            'dosen_pembimbing' => $dosen_pembimbing,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosens_penguji' => $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get(),
            'reviews_check' => $reviews_check,
        ];

        return view('kp.pages.admin.seminar.review', $data);
    }

    public function seminarReviews($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'seminar' => $seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.reviews', $data);
    }

    public function revisiSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $revisi = new RevisiSeminar();
        $revisi->catatan = $request->catatan;
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
            $revisi->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $seminar->mahasiswa->nim, 'seminar');
        }
        if ($seminar->is_valid == Seminar::REVIEW) {
            $seminar->update([
                'is_valid' => Seminar::REVISI,
            ]);
            $seminar->revisis()->save($revisi);
            if ($seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'message' => 'Pendaftaran Seminar Kerja Praktek Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!.<br><br>Catatan revisi: ' . $request->catatan,
                ]);
            }
            return redirect()->route('kp.seminar.admin')->with('success', 'Seminar KP berhasil direvisi');
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
        $seminar = Seminar::with(['sesiSeminar.dosenPenguji', 'dosenPenguji'])->findOrFail($id);
        $mahasiswa = $seminar->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($seminar->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran_acc) {
            return redirect()->route('kp.pendaftaran.mahasiswa');
        }

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Get reviews untuk nilai dosen
        $review_pembimbing = $seminar->reviews()->where('dosen_status', 'pembimbing')->first();
        $review_penguji = $seminar->reviews()->where('dosen_status', 'penguji')->first();

        $data = [
            'title' => 'Detail Seminar KP',
            'active' => 'seminar-kp',
            'dosen_pembimbing' => $dosen_pembimbing,
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->orderBy('created_at', 'desc')->get(),
            'review_pembimbing' => $review_pembimbing,
            'review_penguji' => $review_penguji,
        ];

        return view('kp.pages.mahasiswa.seminar.detail', $data);
    }

    public function editProposal($id)
    {
        $seminar = Seminar::findOrFail($id);

        $data = [
            'title' => 'Submit Laporan Seminar Proposal',
            'active' => 'seminar-kp',
            'seminar' => $seminar,
        ];

        return view('kp.pages.mahasiswa.seminar.submit-proposal', $data);
    }

    public function updateProposal(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        $request->validate([
            'lampiran_proposal' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $seminar->update([
            'lampiran_proposal' => AppHelper::instance()->uploadLampiran($request->lampiran_proposal, 'lampirans', $seminar->mahasiswa->nim, 'seminar'),
        ]);

        return redirect()->route('kp.seminar.mahasiswa')->with('success', 'Laporan Seminar Proposal Berhasil di submit.');
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
                'message' => 'Selamat seminar Kerja Praktek anda sudah dijadwalkan. Berikut detail seminar Kerja Praktek Anda: <br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b>',
            ]);
        }
        // Kirim email ke dosen penguji
        foreach ($seminar->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review) {
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'message' => 'Kepada Yth Bapak/Ibu <b>' . $review->dosen->nama . ', ' . $review->dosen->gelar . '</b> anda di tunjuk sebagai penguji untuk seminar Kerja Praktek. Berikut detail dan jadwal seminar Kerja Praktek: <br>NIM/Nama Mahasiswa: <b>' . $seminar->mahasiswa->nim . '/' . $seminar->mahasiswa->nama . '</b><br>Judul KP: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b><br><br>Silahkan klik link berikut untuk melakukan penilaian: <a href="' . route('kp.review.seminar.public', $review->token) . '">Link Penilaian</a>',
                ]);
            }
        }
        // Kirim email ke dosen pembimbing
        foreach ($seminar->reviews()->where('dosen_status', 'pembimbing')->with(['dosen'])->get() as $review) {
            if ($review->dosen && $review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'message' => 'Kepada Yth Bapak/Ibu <b>' . $review->dosen->nama . ', ' . $review->dosen->gelar . '</b> sebagai dosen pembimbing untuk seminar Kerja Praktek. Berikut detail dan jadwal seminar Kerja Praktek: <br>NIM/Nama Mahasiswa: <b>' . $seminar->mahasiswa->nim . '/' . $seminar->mahasiswa->nama . '</b><br>Judul KP: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal Seminar: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat Seminar: <b>' . $request->tempat_ujian . '</b><br><br>Silahkan klik link berikut untuk melakukan penilaian: <a href="' . route('kp.review.seminar.public', $review->token) . '">Link Penilaian</a>',
                ]);
            }
        }
        return back()->with('success', 'Jadwal dan Tempat Seminar Kerja Praktek berhasil disimpan');
    }

    public function seminarProdiDetail($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'reviews', 'revisis'])->where('id', $id)->first();
        
        if (!$seminar) {
            return back()->with('warning', 'Seminar tidak ditemukan');
        }

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $seminar->mahasiswa->prodi)
            ->orWhere('namaprodi', $seminar->mahasiswa->prodi)
            ->first();
        
        // KP: Tidak menggunakan presentase_nilai seperti TA
        // Cek apakah tabel presentase_nilais ada dan prodi punya relasi
        $presentase_nilai = null;
        $nilai = null;
        $nilai_penguji = null;
        $nilai_pembimbing = null;
        
        try {
            if ($prodi && method_exists($prodi, 'presentase_nilai')) {
                $presentase_nilai = $prodi->presentase_nilai;
            }
            
            // Hitung nilai jika ada cukup review dan presentase nilai
            if (count($seminar->reviews) >= 4 && $presentase_nilai) {
                $nilaiData = AppHelper::hitung_nilai_mahasiswa($seminar);
                $nilai = $nilaiData['nilai'] ?? null;
                $nilai_penguji = $nilaiData['nilai_penguji'] ?? null;
                $nilai_pembimbing = $nilaiData['nilai_pembimbing'] ?? null;
            }
        } catch (\Exception $e) {
            // Abaikan error jika tabel tidak ada
        }

        $data = [
            'title' => 'Detail Seminar Mahasiswa',
            'active' => 'seminar-kp',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'kp',
            'seminar' => $seminar,
            'revisis' => $seminar->revisis()->paginate(5),
            'nilai' => $nilai,
            'nilai_dosen_penguji' => $nilai_penguji,
            'nilai_dosen_pembimbing' => $nilai_pembimbing,
        ];

        return view('kp.pages.prodi.seminar.detail', $data);
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
        return view('kp.pages.admin.seminar.rekap', [
            'title' => 'Rekap Pendaftaran Seminar Mahasiswa',
            'sidebar' => $sidebar,
            'active' => 'seminar-kp',
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

    /**
     * Upload nilai instansi oleh mahasiswa
     * Setelah seminar selesai, mahasiswa upload nilai dari instansi
     */
    public function uploadNilaiInstansi(Request $request, $id)
    {
        $seminar = Seminar::findOrFail($id);

        // Pastikan seminar milik mahasiswa yang login
        if ($seminar->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(403);
        }

        // Pastikan status seminar adalah selesai_seminar
        if ($seminar->status_seminar != Seminar::STATUS_SELESAI_SEMINAR) {
            return back()->with('warning', 'Anda belum bisa upload nilai instansi. Selesaikan seminar terlebih dahulu.');
        }

        $request->validate([
            'nilai_instansi' => ['required', 'numeric', 'min:0', 'max:100'],
            'file_nilai_instansi' => ['required', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $mahasiswa = $seminar->mahasiswa;

        $seminar->nilai_instansi = $request->nilai_instansi;
        $seminar->file_nilai_instansi = AppHelper::instance()->uploadLampiran($request->file('file_nilai_instansi'), 'lampirans', $mahasiswa->nim, 'seminar');

        // Hitung nilai akhir jika nilai seminar sudah ada
        if ($seminar->nilai_seminar) {
            $seminar->hitungNilaiAkhir();
            $seminar->status_seminar = Seminar::STATUS_SELESAI;
        }

        $seminar->save();

        return back()->with('success', 'Nilai instansi berhasil diupload. Nilai akhir KP: ' . $seminar->nilai_akhir);
    }
}

