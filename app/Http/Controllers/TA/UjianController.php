<?php

namespace App\Http\Controllers\TA;

use App\Helpers\AppHelper;
use App\Models\TA\Bimbingan;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TA\Pendaftaran;
use App\Models\TA\Pengajuan;
use App\Models\Prodi;
use App\Models\TA\ReviewUjian;
use App\Models\TA\RevisiUjian;
use App\Models\TA\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UjianController extends \App\Http\Controllers\Controller
{
    public function ujianMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans','ujians'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }
        $ujians = $mahasiswa->ujians()->orderBy('created_at','desc')->get();
        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();
        $bagians_is_ujian = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_pendadaran', 1)->get();

        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function($query) {
                $query->where('is_pendadaran', 1);
            })->get();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        $bagians = [];
        foreach($bagians_is_ujian as $b){
            array_push($bagians, $b->bagian);
        }

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        if (count($bimbingans_is_acc) - count($bagians_is_ujian) < count($bagians_is_ujian)) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        if (count($ujians) == 0) {
            return redirect('ujian/create');
        }

        // $ujians_acc = $ujians->reviews()->where('status', ReviewUjian::DITERIMA)->get();

        // $ujian_is_completed = false;
        // if (count($ujians_acc) == 5){
        //     $ujian_is_completed = true;
        // }

        $ujian_not_lulus = $mahasiswa->ujians()->where('is_lulus', Ujian::NOT_VALID_LULUS)->first();
        $ujian_has_ready = $mahasiswa->ujians()->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->first();
        $ujian_has_complete = $mahasiswa->ujians()->where('is_lulus', Ujian::VALID_LULUS)->first();
        $reviews_has_acc = 0;
        if($ujian_has_complete){
            $reviews_has_acc = count($ujian_has_complete->reviews()->where('status', 'diterima')->where('dosen_status', Dosen::PENGUJI)->get());
        }

        $data = [
            'title' => 'Ujian Pendadaran TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
            'ujians' => $ujians,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            // 'dosens_penguji' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get(),
            // 'reviews_acc' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->where('status', ReviewUjian::DITERIMA)->get(),
            // 'ujian_is_completed' => $ujian_is_completed,
            'ujian_not_lulus' => $ujian_not_lulus,
            'ujian_has_ready' => $ujian_has_ready,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
            'reviews_has_acc' => $reviews_has_acc == 3 ? true : false,
        ];

        return view('ta.pages.mahasiswa.ujian.ujian', $data);
    }

    public function ujianAdmin()
    {
        $ujians_review = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::REVIEW)
        ->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->get();
        $ujians_revisi = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::REVISI)
        ->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->get();
        $ujians_acc = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::DITERIMA)->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->get();

        $data = [
            'title' => 'Validasi Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'ujians_review' => $ujians_review,
            'ujians_revisi' => $ujians_revisi,
            'ujians_acc' => $ujians_acc,
        ];

        return view('ta.pages.admin.ujian.ujian', $data);
    }

    public function ujianDosen()
    {
        $dosen = Dosen::with(['ujians'])->findOrFail(Auth::guard('dosen')->user()->id);
        // return $dosen->ujians;
        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarDosen',
            'module' => 'ta',
            'ujians_review' => $dosen->ujians()->where('status', ReviewUjian::REVIEW)
            ->whereHas('ujian', function($query) {
                $query->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS]);
            })->get(),
            'ujians_acc' => $dosen->ujians()->where('status', ReviewUjian::DITERIMA)
            ->whereHas('ujian', function($query) {
                $query->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS]);
            })->get(),
            'ujians_revisi' => $dosen->ujians()->where('status', ReviewUjian::REVISI)
            ->whereHas('ujian', function($query) {
                $query->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS]);
            })->get(),
        ];

        return view('ta.pages.dosen.ujian.ujian', $data);
    }

public function ujianProdi()
    {
        $prodi = Auth::guard('prodi')->user();
        $ujians = Ujian::orderBy('created_at', 'desc')->where('is_valid', Ujian::DITERIMA)->with(['mahasiswa'])->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->get();

        $ujians_prodi = [];
        foreach ($ujians as $ujian) {
            if ($ujian->mahasiswa->prodi == $prodi->namaprodi) {
                $ujians_prodi[] = $ujian;
            }
        }

        $data = [
            'title' => 'Daftar Ujian Pendadaran Mahasiswa',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'ta',
            'ujians' => $ujians_prodi,
        ];

        return view('ta.pages.prodi.ujian.ujian', $data);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans','ujians'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        $bagians_is_ujian = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_pendadaran', 1)->get();
        $bimbingans_is_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function($query) {
                $query->where('is_pendadaran', 1);
            })->get();

        $bagians = [];
        foreach($bagians_is_ujian as $b){
            array_push($bagians, $b->bagian);
        }

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        } else if ($pengajuan_acc->ujians()->whereNotIn('is_lulus', [Ujian::NOT_VALID_LULUS])->first()) {
            return redirect('ujian-mahasiswa')->with('warning', 'Sudah mendaftar ujian pendadaran TA');
        } else if (count($bimbingans_is_acc) - count($bagians_is_ujian) < count($bagians_is_ujian)) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Selesaikan bimbingan: ' . implode(',', $bagians));
        }

        $data = [
            'title' => 'Form Pendaftaran Ujian Pendadaran TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
        ];

        return view('ta.pages.mahasiswa.ujian.create', $data);
    }

    public function store(Request $request)
    {
        $pengajuan = Pengajuan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', Pengajuan::DITERIMA)->first();

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $pengajuan->mahasiswa->id)->where('status', 'diterima')->first();

        if (AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc)) {
            return redirect('pedaftaran-mahasiswa');
        } else if ($pengajuan->ujian) {
            return redirect('ujian-mahasiswa')->with('warning', 'Sudah mendaftar ujian pendadaran');
        }

        $validatedData = $request->validate([
            'lampiran_1' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_2' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_3' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_4' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_5' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_6' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_7' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_8' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
            'lampiran_laporan' => ['required', 'mimes:jpg,png,jpeg,pdf', 'max:5000'],
        ]);

        $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_1'), 'lampirans');
        $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_2'), 'lampirans');
        $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_3'), 'lampirans');
        $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_4'), 'lampirans');
        $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_5'), 'lampirans');
        $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_6'), 'lampirans');
        $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_7'), 'lampirans');
        $validatedData['lampiran_8'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_8'), 'lampirans');
        $validatedData['lampiran_laporan'] = AppHelper::instance()->uploadLampiran($request->file('lampiran_laporan'), 'lampirans');

        $validatedData['mahasiswa_id'] = Auth::guard('mahasiswa')->user()->id;
        $validatedData['pengajuan_id'] = $pengajuan->id;
        $validatedData['status'] = Ujian::REVIEW;

        Ujian::create($validatedData);

        return redirect('ujian-mahasiswa')->with('success', 'Pendaftaran Ujian Pendadaran TA berhasil, selihkan tunggu validasi dari admin.');
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($ujian->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if ($ujian->is_valid == Ujian::REVIEW || $ujian->is_valid == Ujian::DITERIMA) {
            return redirect('ujian-mahasiswa');
        }

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $data = [
            'title' => 'Submit Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'ujian' => $ujian,
        ];

        return view('ta.pages.mahasiswa.ujian.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        $validatedData = $request->validate([
            'lampiran_1' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_1)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_2' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_2)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_3' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_3)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_4' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_4)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_5' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_6' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_7' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_8' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf,png,jpg,jpeg', 'max:5000'
            ],
            'lampiran_laporan' => [
                Rule::requiredIf(function () use($request) {
                    if (empty($request->lampiran_5)) {
                        return false;
                    }
                    return true;
                }),
                'mimes:pdf', 'max:5000'
            ],
        ]);

        if ($request->file('lampiran_1')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_1);
            $validatedData['lampiran_1'] = AppHelper::instance()->uploadLampiran($request->lampiran_1, 'lampirans');
        }
        if ($request->file('lampiran_2')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_2);
            $validatedData['lampiran_2'] = AppHelper::instance()->uploadLampiran($request->lampiran_2, 'lampirans');
        }
        if ($request->file('lampiran_3')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_3);
            $validatedData['lampiran_3'] = AppHelper::instance()->uploadLampiran($request->lampiran_3, 'lampirans');
        }
        if ($request->file('lampiran_4')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_4);
            $validatedData['lampiran_4'] = AppHelper::instance()->uploadLampiran($request->lampiran_4, 'lampirans');
        }
        if ($request->file('lampiran_5')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_5);
            $validatedData['lampiran_5'] = AppHelper::instance()->uploadLampiran($request->lampiran_5, 'lampirans');
        }
        if ($request->file('lampiran_6')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_6);
            $validatedData['lampiran_6'] = AppHelper::instance()->uploadLampiran($request->lampiran_6, 'lampirans');
        }
        if ($request->file('lampiran_7')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_7);
            $validatedData['lampiran_7'] = AppHelper::instance()->uploadLampiran($request->lampiran_7, 'lampirans');
        }
        if ($request->file('lampiran_8')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_8);
            $validatedData['lampiran_8'] = AppHelper::instance()->uploadLampiran($request->lampiran_8, 'lampirans');
        }
        if ($request->file('lampiran_laporan')) {
            AppHelper::instance()->deleteLampiran($ujian->lampiran_laporan);
            $validatedData['lampiran_laporan'] = AppHelper::instance()->uploadLampiran($request->lampiran_laporan, 'lampirans');
        }

        $validatedData['is_valid'] = Ujian::REVIEW;

        $ujian->update($validatedData);

        return redirect('ujian-mahasiswa')->with('success', 'Pendaftaran Ujian TA berhasil diupdate, silahkan tunggu review dari Admin');
    }

    public function detail($id)
    {
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();

        if ($ujian->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            abort(404);
        }

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa');
        }

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'ujian' => $ujian,
            'revisis' => $ujian->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ];

        return view('ta.pages.mahasiswa.ujian.detail', $data);
    }

    public function ujianReviewAdmin($id)
    {
        // Check admin FIRST
        if(Auth::guard('admin')->user()){
            $sidebar = 'partials.sidebarAdmin';
        }else{
            $sidebar = 'partials.sidebarProdi';
        }
        $ujian = Ujian::findOrFail($id);
        $mahasiswa = $ujian->mahasiswa;
        $prodi = Prodi::where('kode', $mahasiswa->prodi)->first();

        $dosens = $prodi->dosens;

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        $reviews_check = $ujian->reviews()->whereIn('status', [ReviewUjian::DITERIMA, ReviewUjian::REVISI])->where('dosen_status','penguji')->get();

        $data = [
            'title' => 'Review Pendaftaran Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => $sidebar,
            'ujian' => $ujian,
            'dosens' => $dosens,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'revisis' => $ujian->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosens_penguji' => $ujian->reviews()->where('dosen_status', ReviewUjian::DOSEN_PENGUJI)->get(),
            'reviews_check' => $reviews_check,
        ];

        return view('ta.pages.admin.ujian.review', $data);
    }

    public function revisiUjian(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);
        $revisi = new RevisiUjian();
        $revisi->keterangan = $request->catatan; // Database uses 'keterangan' column
        $request->validate([
            'lampiran' => [
                Rule::requiredIf(function () use($request) {
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
        if ($ujian->is_valid == Ujian::REVIEW) {
            $ujian->update([
                'is_valid' => Ujian::REVISI,
            ]);
            $ujian->revisis()->save($revisi);
            if ($ujian->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $ujian->mahasiswa->email,
                    'subject' => 'Pendaftaran Ujian Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Pendaftaran Ujian Tugas Akhir Anda Berstatus REVISI. Silahkan perbaiki kemudian lakukan submit ulang!. <br><br>Catatan revisi: '.$request->catatan,
                ]);
            }
            return redirect('ujian-admin')->with('success', 'Ujian TA berhasil direvisi');
        } elseif ($ujian->is_valid == Ujian::REVISI) {
            $ujian->revisis()->save($revisi);
            return back()->with('success', 'Revisi berhasil ditambahkan');
        }
    }

    public function accUjian(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);

        if ($ujian->is_valid == Ujian::VALID_LULUS || count($ujian->reviews) == 5) {
            return back();
        }

        $mahasiswa = $ujian->mahasiswa;

        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();

        if($request->catatan){
            $revisi = new RevisiUjian();
            $revisi->keterangan = $request->catatan; // Database uses 'keterangan' column
            $ujian->revisis()->save($revisi);
        }

        ReviewUjian::create([
            'ujian_id' => $ujian->id,
            'dosen_id' => $dosen_utama->id,
            'status' => ReviewUjian::REVIEW,
            'dosen_status' => ReviewUjian::DOSEN_PEMBIMBING,
        ]);

        ReviewUjian::create([
            'ujian_id' => $ujian->id,
            'dosen_id' => $dosen_pendamping->id,
            'status' => ReviewUjian::REVIEW,
            'dosen_status' => ReviewUjian::DOSEN_PEMBIMBING,
        ]);

        $ujian->update([
            'is_valid' => Ujian::VALID_LULUS,
            'tanggal_acc' => now(),
        ]);

        if ($ujian->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $ujian->mahasiswa->email,
                'subject' => 'Pendaftaran Ujian Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat Pendaftaran Ujian Tugas Akhir Anda Berstatus DITERIMA.',
            ]);
        }
        return back()->with('success', 'Pendaftaran Ujian TA berhasil di Acc.');
    }

    public function cancelAcc(Request $request)
    {
        $ujian = Ujian::findOrFail($request->id);

        if (count($ujian->reviews) == 5) {
            return back();
        }

        foreach ($ujian->reviews as $review) {
            $review->delete();
        }

        $ujian->update([
            'is_valid' => 0,
        ]);

        return back()->with('success', 'Acc Ujian TA berhasil dibatalkan.');
    }

    public function deleteRevisi(Request $request)
    {
        $revisi = RevisiUjian::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function setDateExam(Request $request)
    {
        $ujian = Ujian::findOrFail($request->ujian_id);
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required',
        ]);
        $validatedData['tanggal_ujian'] = Carbon::parse($request->tanggal_ujian);
        $ujian->update($validatedData);
        if ($ujian->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $ujian->mahasiswa->email,
                'subject' => 'Ujian Tugas Ahir',
                'title' => 'EKAPTA',
                'message' => 'Selamat ujian Tugas Akhir anda sudah dijadwalkan. Berikut detail ujian Tugas Akhir Anda: <br>Tanggal ujian: <b>'.AppHelper::parse_date($request->tanggal_ujian).'</b><br>Tempat ujian: <b>'.$request->tempat_ujian.'</b>',
            ]);
        }
        foreach($ujian->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review){
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'subject' => 'Penguji Ujian Tugas Ahir',
                    'title' => 'EKAPTA',
                    'message' => 'Kepada Yth Bapak/Ibu <b>'.$review->dosen->nama.', '.$review->dosen->gelar.'</b> anda di tunjuk sebagai penguji untuk ujian Tugas Akhir. Berikut detail dan jadwal ujian Tugas Akhir: <br>NIM/Nama Mahasiswa: <b>'.$ujian->mahasiswa->nim.'/'.$ujian->mahasiswa->nama.'</b><br>Judul Skripsi: <b>'.$ujian->pengajuan->judul.'</b><br>Tanggal ujian: <b>'.AppHelper::parse_date($request->tanggal_ujian).'</b><br>Tempat ujian: <b>'.$request->tempat_ujian.'</b>',
                ]);
            }
        }
        return back()->with('success', 'Jadwal dan Tempat Ujian Tugas Akhir berhasil disimpan');
    }

    public function ujianReviews($id)
    {
        $ujian = Ujian::findOrFail($id);

        $data = [
            'title' => 'Review Ujian TA',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'ujian' => $ujian,
        ];

        return view('ta.pages.mahasiswa.ujian.reviews', $data);
    }

    public function editProposal($id)
    {
        $ujian = Ujian::findOrFail($id);

        $data = [
            'title' => 'Submit Laporan Ujian Pendadaran',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'ujian' => $ujian,
        ];

        return view('ta.pages.mahasiswa.ujian.submit-proposal', $data);
    }

    public function updateProposal(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);

        $request->validate([
            'lampiran_proposal' => ['required', 'mimes:pdf, docx', 'max:5000'],
        ]);

        $ujian->update([
            'lampiran_proposal' => AppHelper::instance()->uploadLampiran($request->lampiran_proposal, 'lampirans'),
        ]);

        return redirect('ujian-mahasiswa')->with('success', 'Laporan Ujian Proposal Berhasil di submit.');
    }

    public function ujianProdiDetail($id)
    {
        $ujian = Ujian::findOrFail($id);

        $prodi = Prodi::where('kode', $ujian->mahasiswa->prodi)->first();
        $presentase_nilai = $prodi->presentase_nilai;

        if (count($ujian->reviews) != 5 || !$presentase_nilai) {
            return back();
        }

        $nilai = AppHelper::hitung_nilai_mahasiswa($ujian);

        $data = [
            'title' => 'Detail Ujian Mahasiswa',
            'active' => 'ujian-ta',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'ta',
            'ujian' => $ujian,
            'revisis' => $ujian->revisis()->paginate(5),
            'nilai' => $nilai['nilai'],
            'nilai_dosen_penguji' => $nilai['nilai_penguji'],
            'nilai_dosen_pembimbing' => $nilai['nilai_pembimbing'],
            'prodi' => $prodi,
        ];

        return view('ta.pages.prodi.ujian.detail', $data);
    }

    public function rekapUjian(){
        // Check admin FIRST
        if(Auth::guard('admin')->user()){
            $sidebar = 'partials.sidebarAdmin';
        }else{
            $sidebar = 'partials.sidebarProdi';
        }
        $ujians = Ujian::where('is_valid', Ujian::VALID_LULUS)
            ->where('tanggal_ujian', null)
            ->get();
        return view('ta.pages.admin.ujian.rekap',[
            'title' => 'Rekap Pendaftaran Ujian Pendadaran Mahasiswa',
            'sidebar' => $sidebar,
            'active' => 'ujian-ta',
            'module' => 'ta',
            'ujians' => $ujians,
        ]);
    }

    public function updateStatus(Request $request)
    {
        $ujian = Ujian::findOrFail($request->ujian_id);
        if ($ujian->is_valid == Ujian::NOT_VALID_LULUS) {
            return back();
        }
        $ujian->update([
            'is_lulus' => $request->is_lulus,
        ]);
        return response()->json(['message' => 'Status ujian berhasil disimpan']);
    }
}


