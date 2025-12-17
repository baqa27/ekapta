<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\RevisiBimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\BimbinganCanceled;
use App\Models\Dosen;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\SeminarCanceled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BimbinganController extends Controller
{

    public function bimbinganProdi()
    {
        $mahasiswas = Mahasiswa::with(['bimbingans','pengajuans','pendaftarans','ujians','dosens','jilid','bimbingan_canceleds'])->where('prodi', Auth::guard('prodi')->user()->namaprodi)->with(['bimbingans'])->get();

        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function bimbinganDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        return view('pages.dosen.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'bimbingans' => $dosen->bimbingans()->where('status', 'review')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_diterima' => $dosen->bimbingans()->where('status', 'diterima')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_revisi' => $dosen->bimbingans()->where('status', 'revisi')->orderBy('tanggal_bimbingan', 'desc')->get(),
        ]);
    }

    /**
     * Halaman Bimbingan KP untuk Mahasiswa
     * Durasi KP: 6 bulan sejak pendaftaran
     * Perpanjangan: maksimal 2 kali
     */
    public function bimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }
        // KP menggunakan single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama (utama/pendamping)
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if (!$pendaftaran_acc) {
            return redirect('pendaftaran-mahasiswa')->with('warning', 'Silahkan melakukan Pendaftaran Kerja Praktik terlebih dahulu');
        }

        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();

        // Cek syarat seminar: semua bab yang diperlukan sudah di-ACC
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
            ->whereHas('bagian', function($query) {
                $query->where('is_seminar', 1);
            })->get();

        // Syarat seminar terpenuhi jika semua bagian sudah di-ACC
        $is_seminar = false;
        if (count($bimbingans_is_acc_seminar) >= count($bagians_is_seminar)) {
            $is_seminar = true;
        }

        // Durasi KP: 6 bulan sejak pendaftaran di-ACC
        $date_expired = Carbon::parse($pendaftaran_acc->tanggal_acc)->addMonthsNoOverflow(6);
        $is_expired = Carbon::now()->greaterThan($date_expired);

        return view('pages.mahasiswa.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'bimbingans_utama' => $mahasiswa->bimbingans()->get(), // Semua bimbingan (KP: single pembimbing)
            'bimbingans_pendamping' => collect([]), // Kosong untuk KP
            'dosen_utama' => $dosenPembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosenPembimbing,
            'date_expired' => $date_expired,
            'is_seminar' => $is_seminar,
            'is_ujian' => false, // KP tidak ada ujian pendadaran
            'is_expired' => $is_expired,
            'pendaftaran_acc' => $pendaftaran_acc,
            'mahasiswa' => $mahasiswa,
            'jilid' => $mahasiswa->jilid,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
        ]);
    }

    public function create()
    {

        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        return view('pages.mahasiswa.bimbingan.create', [
            'title' => 'Form Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
        ]);
    }

    /**
     * Store bimbingan KP
     * KP: single dosen pembimbing
     */
    public function store(Request $request)
    {
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)
            ->whereIn('status', ['review', 'revisi'])
            ->get();
        $bimbinganIfExists = Bimbingan::where(['mahasiswa_id' => Auth::guard('mahasiswa')->user()->id, 'bagian_id' => $request->bagian_id, 'status' => 'diterima'])
            ->get();
        Bagian::findOrFail($request->bagian_id);
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // KP: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        if ($cekBimbingan->isEmpty()) {
            if ($bimbinganIfExists->isEmpty()) {
                $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
                $request->validate([
                    'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
                    'bukti_bimbingan_offline' => ['nullable', 'mimes:pdf,jpg,jpeg,png', 'max:5000'],
                    'bagian_id' => 'required',
                ]);
                $bimbingan = new Bimbingan;
                $bimbingan->lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'bimbingan');
                if ($request->bukti_bimbingan_offline) {
                    $bimbingan->bukti_bimbingan_offline = AppHelper::instance()->uploadLampiran($request->bukti_bimbingan_offline, 'lampirans', $mahasiswa->nim, 'bimbingan');
                }
                $bimbingan->keterangan = $request->keterangan;
                $bimbingan->bagian_id = $request->bagian_id;

                $mahasiswa->bimbingans()->save($bimbingan);

                // Attach dosen pembimbing (single for KP)
                if ($dosenPembimbing) {
                    $bimbingan->dosens()->attach([$dosenPembimbing->id]);
                }

                return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil dibuat. Silahkan tunggu review dari dosen pembimbing');
            } else {
                return back()->with('warning', 'Bimbingan dengan bagian yang sama sudah di Acc');
            }
        } else {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan dengan bagian yang sama sudah dibuat. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $pendaftaran = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $bagians_is_seminar = $prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)
        ->whereHas('bagian', function($query) {
            $query->where('is_seminar', 1);
        })->get();

        if($pendaftaran){
            if (AppHelper::instance()->is_expired_in_one_year($pendaftaran->tanggal_acc)) {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
            }
        }

        $bimbingan = Bimbingan::with(['dosens'])->findOrFail($id);

        if ($mahasiswa->id != $bimbingan->mahasiswa->id) {
            abort(404);
        } elseif ($bimbingan->status == 'review' || $bimbingan->status == 'ditolak' ||    $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak dapat disubmit');
        } elseif (count($mahasiswa->bimbingans()->where('status', 'review')->get()) >= 2) {
            return back()->with('warning', 'Tunggu sampai bimbingan di Acc oleh dosen');
        } elseif(count($bimbingans_is_acc_seminar) / 2  < count($bagians_is_seminar) && $bimbingan->bagian->is_pendadaran == 1){
            return back();
        }

        return view('pages.mahasiswa.bimbingan.edit', [
            'title' => 'Form Submit Bimbingan Kerja Praktik',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'bagians' => $prodi->bagians,
            'dosen' => $bimbingan->dosens()->first(),
        ]);
    }

    public function bimbinganDetail($id)
    {
        $bimbingan = Bimbingan::with(['revisis','mahasiswa'])->findOrFail($id);
        if ($bimbingan->mahasiswa->id != Auth::guard('mahasiswa')->user()->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }
        if ($bimbingan->status == null) {
            return back()->with('warning', 'Harap edit Bimbingan terlebih dahulu');
        }
        return view('pages.mahasiswa.bimbingan.detail', [
            'title' => 'Detail Bimbingan Kerja Praktik',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
        ]);
    }

    public function bimbinganReview($id)
    {
        $bimbingan = Bimbingan::with(['revisis'])->findOrFail($id);
        $mahasiswa = Mahasiswa::with(['bimbingans','pengajuans'])->find($bimbingan->mahasiswa->id);
        $prodi = Prodi::with(['bagians'])->where('namaprodi', $mahasiswa->prodi)->first();
        $pengajuan = $mahasiswa->pengajuans()->where('status', 'diterima')->first();

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', 'diterima')->get();

        return view('pages.dosen.bimbingan.review', [
            'title' => 'Review Bimbingan Kerja Praktik',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarDosen',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'bagians' => $prodi->bagians,
            'bimbingans_acc' => $bimbingans_acc,
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function update(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $cekBimbingan = Bimbingan::where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'review')->get();

        $pendaftaran = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        if (AppHelper::instance()->is_expired_in_one_year($pendaftaran->tanggal_acc)) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
        }

        if (count($cekBimbingan) >= 2) {
            return redirect('bimbingan-mahasiswa')->with('warning', 'Harap menunggu Acc bimbingan dari dosen Pembimbing');
        } else {
            if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
                return redirect('bimbingan-mahasiswa')->with('warning', 'Bimbingan tidak bisa diedit');
            }
            $validatedData = $request->validate([
                'lampiran' => ['required', 'mimes:pdf', 'max:5000'],
            ]);
            if ($request->file('lampiran')) {

                $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);
                $validatedData['lampiran'] = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'bimbingan');
            }
            $validatedData['keterangan'] = $request->keterangan;
            if ($bimbingan->status == null) {
                $validatedData['tanggal_bimbingan'] = now();
            }
            $validatedData['status'] = 'review';
            $validatedData['tanggal_bimbingan'] = Carbon::now();
            // return $validatedData;
            $bimbingan->update($validatedData);
            return redirect('bimbingan-mahasiswa')->with('success', 'Bimbingan berhasil diupdate. Silahkan tunggu review dari dosen pembimbing');
        }
    }

    public function delete(Request $request)
    {
        return back();
    }

    public function accBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan sudah di Acc');
        }
        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $bimbingan->lampiran;
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        $bimbingan->update([
            'status' => 'diterima',
            'tanggal_acc' => now(),
        ]);
        $bimbingan->revisis()->save($revisi);
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Kerja Praktik',
                'title' => 'EKAPTA',
                'message' => 'Selamat Bimbingan Kerja Praktik Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya. Catatan: '.$request->catatan,
            ]);
        }
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil di Acc');
    }

    public function revisiBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'revisi') {
            return redirect('bimbingan-dosen')->with('warning', 'Bimbingan tidak bisa direvisi');
        }

        $revisi = new RevisiBimbingan;
        $request->validate([
            'catatan' => 'required',
            'lampiran' => [Rule::requiredIf(function () use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }), 'mimes:pdf,docx', 'max:5000']
        ]);

        if($request->lampiran){
            $revisi->lampiran_revisi = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $bimbingan->mahasiswa->nim, 'bimbingan');
        }
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $bimbingan->lampiran;
        $revisi->dosen_id = Auth::guard('dosen')->user()->id;
        $bimbingan->revisis()->save($revisi);
        $bimbingan->update([
            'status' => 'revisi',
        ]);
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Kerja Praktik',
                'title' => 'EKAPTA',
                'message' => 'From: <b>'.Auth::guard('dosen')->user()->nama.', '.Auth::guard('dosen')->user()->gelar.'</b><br>Bimbingan Kerja Praktik Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan revisi : '. $request->catatan,
            ]);
        }
        return redirect('bimbingan-dosen')->with('success', 'Bimbingan berhasil direvisi');
    }

    public function deleteRevisiBimbingan(Request $request)
    {
        $revisi = RevisiBimbingan::findOrFail($request->id);
        AppHelper::instance()->deleteLampiran($revisi->lampiran);
        $revisi->delete();
        return back()->with('success', 'Revisi berhasil dihapus');
    }

    public function cancelAcc(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
            'tanggal_acc' => null,
        ]);
        return back()->with('success', 'Acc bimbingan berhasil dibatalkan');
    }

    public function cancelRevisi(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status' => 'review',
        ]);
        return back()->with('success', 'Revisi bimbingan berhasil dibatalkan');
    }

    public function reviewProdi($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarProdi',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.prodi.bimbingan.detail', $data);
    }

    public function bimbinganAdmin()
    {
        $mahasiswas = Mahasiswa::with(['bimbingans', 'pendaftarans', 'dosens', 'ujians', 'seminar','pengajuans', 'jilid', 'bimbingan_canceleds'])->get();
        return view('pages.admin.bimbingan.bimbingan', [
            'title' => 'Laporan Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function reviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        $dosen_utama = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $pengajuan->mahasiswa->dosens()->where('status', 'pendamping')->first();

        $data = [
            'title' => 'Detail Bimbingan Kerja Praktik',
            'active' => 'bimbingan',
            'sidebar' => 'partials.sidebarAdmin',
            'pengajuan' => $pengajuan,
            'dosen_utama' => $dosen_utama,
            'dosen_pendamping' => $dosen_pendamping,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('pages.admin.bimbingan.detail', $data);
    }

    public function bimbinganDosenProgress()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        return view('pages.dosen.bimbingan.bimbingan-progress', [
            'title' => 'Bimbingan Kerja Praktik',
            'active' => 'bimbingan-progress',
            'sidebar' => 'partials.sidebarDosen',
            'mahasiswas' => $dosen->mahasiswas()->with(['bimbingans'])->get(),
        ]);
    }

    public function rekapDosen()
    {
        $prodi =  Prodi::with(['dosens'])->findOrFail(Auth::guard('prodi')->user()->id);
        $dosens = $prodi->dosens()->with(['mahasiswas'])->get();

        return view('pages.prodi.bimbingan.rekap-dosen', [
            'title' => 'Rekap Bimbingan Dosen',
            'sidebar' => 'partials.sidebarProdi',
            'active' => 'dashboard',
            'dosens' => $dosens,
        ]);
    }

    public function bimbinganAdminInput()
    {
        // Cek berdasarkan route name untuk menentukan sidebar
        $routeName = request()->route()->getName();
        
        if($routeName == 'bimbingan.prodi.input' && Auth::guard('prodi')->user()){
            $prodi = Prodi::with(['dosens'])->findOrFail(Auth::guard('prodi')->user()->id);
            $dosens = $prodi->dosens()->with(['mahasiswas'])->where('is_manual', 1)->get();
            $sidebar = 'partials.sidebarProdi';
        }else{
            $dosens = Dosen::with(['mahasiswas'])->where('is_manual', 1)->get();
            $sidebar = 'partials.sidebarAdmin';
        }
        return view('pages.admin.bimbingan.bimbingan-input', [
            'title' => 'Bimbingan Dosen',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input',
            'dosens' => $dosens,
        ]);
    }

    public function bimbinganAdminInputCreate($dosen_id, $mahasiswa_id)
    {
        // Cek berdasarkan guard yang aktif via middleware
        if(Auth::guard('prodi')->check()){
            $sidebar = 'partials.sidebarProdi';
            $route = 'bimbingan.prodi.input';
        }else{
            $sidebar = 'partials.sidebarAdmin';
            $route = 'bimbingan.admin.input';
        }
        $dosen = Dosen::findOrFail($dosen_id);
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);
        $bimbingans = $dosen->bimbingans()->where('mahasiswa_id', $mahasiswa->id)->get();
        return view('pages.admin.bimbingan.bimbingan-store', [
            'title' => 'Input Manual Bimbingan Dosen',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input',
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'bimbingans' => $bimbingans,
            'dosen_mahasiswa' => DB::table('dosen_mahasiswas')->where('mahasiswa_id', $mahasiswa->id)->where('dosen_id', $dosen->id)->first(),
            'route' => $route,
        ]);
    }

    public function bimbinganAdminInputStore(Request $request)
    {
        $request->validate([
            'lampiran' => [Rule::requiredIf(function() use($request) {
                if (empty($request->lampiran)) {
                    return false;
                }
                return true;
            }) ,'mimes:pdf', 'max:1000'],
        ]);
        if($request->lampiran){
            $mahasiswa = Mahasiswa::findOrFail($request->mahasiswa_id);
            $lampiran = AppHelper::instance()->uploadLampiran($request->lampiran, 'lampirans', $mahasiswa->nim, 'bimbingan');
            DB::table('dosen_mahasiswas')->where('mahasiswa_id', $request->mahasiswa_id)->where('dosen_id', $request->dosen_id)->update(['lampiran' => $lampiran]);
        }
        $dates = $request->dates;
        $ids = $request->ids;
        for ($i = 0; $i < count($request->dates); $i++) {
            if ($dates[$i] != null) {
                $bimbingan = Bimbingan::findOrFail($ids[$i]);
                $bimbingan->update([
                    "status" => "diterima",
                    "tanggal_acc" => $dates[$i],
                ]);
                if ($bimbingan->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $bimbingan->mahasiswa->email,
                        'subject' => 'Bimbingan Kerja Praktik',
                        'title' => 'EKAPTA',
                        'message' => 'Selamat Bimbingan Kerja Praktik Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
                    ]);
                }
            }
        }
        return back()->with('success', 'Berhasil disimpan');
    }

    public function public($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingans'])->where('id', base64_decode($id))->first();
        if (!$mahasiswa) {
            abort(404);
        }
        $pengajuan = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $prodi = Prodi::where('namaprodi', $mahasiswa->prodi)->first();
        $dosen_utama = $mahasiswa->dosens()->where('status', 'utama')->first();
        $dosen_pendamping = $mahasiswa->dosens()->where('status', 'pendamping')->first();
        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        return view('pages.public.detail', [
            'title' => 'Riwayat Bimbingan Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'dosen_utama' => $dosen_utama ? $dosen_utama : null,
            'dosen_pendamping' => $dosen_pendamping ? $dosen_pendamping : null,
            'date_expired' => $pendaftaran_acc ? Carbon::parse($pendaftaran_acc->tanggal_acc)->addMonthsNoOverflow(12) : null,
            'is_expired' => $pendaftaran_acc ? AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc) : null,
            'pengajuan' => $pengajuan ? $pengajuan : null,
            'pendaftaran' => $pendaftaran_acc ? $pendaftaran_acc : null,
        ]);
    }

    public function bimbinganCanceled($id){
        return back()->with('warning', 'Mohon maaf, saat ini fitur sedang di non-aktifkan');
    }

}
