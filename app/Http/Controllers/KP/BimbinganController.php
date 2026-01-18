<?php

namespace App\Http\Controllers\KP;

use App\Models\KP\Bagian;
use App\Models\KP\Bimbingan;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\KP\RevisiBimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\AppHelper;
use App\Models\KP\BimbinganCanceled;
use App\Models\Dosen;
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\KP\SeminarCanceled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BimbinganController extends \App\Http\Controllers\Controller
{

    public function bimbinganProdi()
    {
        $mahasiswas = Mahasiswa::with(['bimbingans', 'pengajuans', 'pendaftarans', 'dosens', 'jilid', 'seminar', 'bimbingan_canceleds'])->where('prodi', Auth::guard('prodi')->user()->namaprodi)->get();

        return view('kp.pages.admin.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function bimbinganDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        return view('kp.pages.dosen.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'bimbingans' => $dosen->bimbingansKP()->where('status', 'review')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_diterima' => $dosen->bimbingansKP()->where('status', 'diterima')->orderBy('tanggal_bimbingan', 'desc')->get(),
            'bimbingans_revisi' => $dosen->bimbingansKP()->where('status', 'revisi')->orderBy('tanggal_bimbingan', 'desc')->get(),
        ]);
    }

    /**
     * Halaman Bimbingan KP untuk Mahasiswa
     * Durasi KP: 6 bulan sejak pendaftaran
     * Perpanjangan: maksimal 2 kali
     */
    public function bimbinganMahasiswa()
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('kp.profile');
        }

        // Cek tahapan: Pendaftaran harus ACC dulu
        if (!AppHelper::canAccessBimbingan($mahasiswa)) {
            return redirect()->route('kp.pendaftaran.mahasiswa')->with('warning', 'Selesaikan tahap Pendaftaran KP terlebih dahulu. Pendaftaran harus sudah di-ACC oleh Admin.');
        }

        // KP menggunakan single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama (utama/pendamping)
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', Auth::guard('mahasiswa')->user()->id)->where('status', 'diterima')->first();

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', Auth::guard('mahasiswa')->user()->prodi)
            ->orWhere('namaprodi', Auth::guard('mahasiswa')->user()->prodi)
            ->first();

        // Cek syarat seminar: semua bab yang diperlukan sudah di-ACC
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
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

        // Ambil semua bagian berdasarkan tahun masuk mahasiswa
        $bagians = $prodi->bagiansKP()
            ->where(function($query) use ($mahasiswa) {
                $query->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")
                      ->orWhereNull("tahun_masuk")
                      ->orWhere("tahun_masuk", "");
            })
            ->orderBy('id')
            ->get();

        // Buat array bimbingan per bagian untuk ditampilkan di view
        $bimbinganPerBagian = [];
        foreach ($bagians as $bagian) {
            // Ambil bimbingan terbaru untuk bagian ini
            $bimbingan = $mahasiswa->bimbingansKP()
                ->where('bagian_id', $bagian->id)
                ->where(function($q) {
                    $q->where('tipe', 'online')->orWhereNull('tipe');
                })
                ->orderBy('id', 'desc')
                ->first();
            $bimbinganPerBagian[] = [
                'bagian' => $bagian,
                'bimbingan' => $bimbingan,
            ];
        }

        // Ambil bimbingan offline
        $bimbinganOffline = $mahasiswa->bimbingansKP()
            ->where('tipe', 'offline')
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        return view('kp.pages.mahasiswa.bimbingan.bimbingan', [
            'title' => 'Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'bimbingans_utama' => $mahasiswa->bimbingansKP()->get(), // Semua bimbingan (KP: single pembimbing)
            'bimbingans_pendamping' => collect([]), // Kosong untuk KP
            'bimbingan_per_bagian' => $bimbinganPerBagian, // Data baru untuk tampilan per bagian
            'bimbingan_offline' => $bimbinganOffline, // Data bimbingan offline
            'dosen_utama' => $dosenPembimbing, // Untuk kompatibilitas view
            'dosen_pendamping' => null,
            'dosen_pembimbing' => $dosenPembimbing,
            'date_expired' => $date_expired,
            'is_seminar' => $is_seminar,
            'is_ujian' => false,
            'is_expired' => $is_expired,
            'pendaftaran_acc' => $pendaftaran_acc,
            'mahasiswa' => $mahasiswa,
            'jilid' => $mahasiswa->jilidKP,
            'check_ujian_has_done' => AppHelper::check_ujian_has_done(),
        ]);
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Ambil bagian_id dari URL
        $bagian_id = request()->get('bagian_id');
        if (!$bagian_id) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Pilih bagian bimbingan terlebih dahulu');
        }

        $bagian = Bagian::findOrFail($bagian_id);

        // Cek apakah bagian sudah di-ACC atau sedang review
        $existingBimbingan = $mahasiswa->bimbingansKP()->where('bagian_id', $bagian_id)->first();
        if ($existingBimbingan && $existingBimbingan->status == 'diterima') {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Bagian ini sudah di-ACC');
        }
        if ($existingBimbingan && in_array($existingBimbingan->status, ['review', 'revisi'])) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Bagian ini sedang dalam proses review');
        }

        return view('kp.pages.mahasiswa.bimbingan.create', [
            'title' => 'Form Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'bagian' => $bagian,
        ]);
    }

    /**
     * Store bimbingan KP
     * KP: single dosen pembimbing
     */
    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cek apakah bagian ini sudah pernah dibuat dan statusnya review/revisi
        $bimbinganPending = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('bagian_id', $request->bagian_id)
            ->whereIn('status', ['review', 'revisi'])
            ->first();

        // Cek apakah bagian ini sudah di-ACC
        $bimbinganAcc = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('bagian_id', $request->bagian_id)
            ->where('status', 'diterima')
            ->first();

        Bagian::findOrFail($request->bagian_id);

        // KP: single dosen pembimbing
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Jika bagian ini sudah di-ACC, tidak bisa submit lagi
        if ($bimbinganAcc) {
            return back()->with('warning', 'Bimbingan dengan bagian ini sudah di-ACC');
        }

        // Jika bagian ini sedang review/revisi, tidak bisa submit lagi
        if ($bimbinganPending) {
            return back()->with('warning', 'Bimbingan dengan bagian ini sedang dalam proses review. Silahkan tunggu review dari dosen pembimbing');
        }

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
        $bimbingan->tanggal_bimbingan = now();
        $bimbingan->status = 'review';

        $mahasiswa->bimbingansKP()->save($bimbingan);

        // Attach dosen pembimbing (single for KP)
        if ($dosenPembimbing) {
            $bimbingan->dosens()->attach([$dosenPembimbing->id]);
        }

        return redirect()->route('kp.bimbingan.mahasiswa')->with('success', 'Bimbingan berhasil dibuat. Silahkan tunggu review dari dosen pembimbing');
    }

    /**
     * Form input bimbingan manual (untuk dosen is_manual = true)
     * Mahasiswa langsung input bukti ACC dari dosen offline
     */
    public function createManual()
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cek apakah dosen is_manual
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        if (!$dosenPembimbing || !$dosenPembimbing->is_manual) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Fitur ini hanya untuk dosen dengan mode bimbingan offline');
        }

        // Ambil bagian_id dari URL
        $bagian_id = request()->get('bagian_id');
        if (!$bagian_id) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Pilih bagian bimbingan terlebih dahulu');
        }

        $bagian = Bagian::findOrFail($bagian_id);

        // Cek apakah bagian sudah di-ACC atau sedang review
        $existingBimbingan = $mahasiswa->bimbingansKP()->where('bagian_id', $bagian_id)->first();
        if ($existingBimbingan && $existingBimbingan->status == 'diterima') {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Bagian ini sudah di-ACC');
        }
        if ($existingBimbingan && in_array($existingBimbingan->status, ['review', 'revisi'])) {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Bagian ini sedang dalam proses review');
        }

        return view('kp.pages.mahasiswa.bimbingan.create-manual', [
            'title' => 'Input Acc Manual Bimbingan',
            'active' => 'bimbingan-kp',
            'bagian' => $bagian,
            'dosen' => $dosenPembimbing,
        ]);
    }

    /**
     * Store bimbingan manual (untuk dosen is_manual = true)
     * Status review dulu, nanti di-ACC oleh prodi
     */
    public function storeManual(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Cek apakah dosen is_manual
        $dosenPembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosenPembimbing) {
            $dosenPembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        if (!$dosenPembimbing || !$dosenPembimbing->is_manual) {
            return back()->with('warning', 'Fitur ini hanya untuk dosen dengan mode bimbingan offline');
        }

        // Cek apakah bagian ini sudah ada (review/diterima)
        $bimbinganExist = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('bagian_id', $request->bagian_id)
            ->whereIn('status', ['review', 'diterima'])
            ->first();

        if ($bimbinganExist) {
            return back()->with('warning', 'Bimbingan dengan bagian ini sudah ada');
        }

        $request->validate([
            'lampiran_acc' => 'required|mimes:pdf,jpg,jpeg,png|max:2000',
            'tanggal_acc' => 'required|date',
            'bagian_id' => 'required',
        ]);

        $bimbingan = new Bimbingan;
        $bimbingan->lampiran_acc = AppHelper::instance()->uploadLampiran($request->lampiran_acc, 'lampirans', $mahasiswa->nim, 'bimbingan-acc');
        $bimbingan->keterangan = $request->keterangan;
        $bimbingan->bagian_id = $request->bagian_id;
        $bimbingan->tanggal_bimbingan = $request->tanggal_acc;
        $bimbingan->tanggal_manual_acc = $request->tanggal_acc;
        $bimbingan->status = 'review'; // Review dulu, nanti di-ACC oleh prodi

        $mahasiswa->bimbingansKP()->save($bimbingan);

        // Attach dosen pembimbing
        if ($dosenPembimbing) {
            $bimbingan->dosens()->attach([$dosenPembimbing->id]);
        }

        return redirect()->route('kp.bimbingan.mahasiswa')->with('success', 'Bimbingan berhasil disubmit. Menunggu validasi dari Prodi.');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $pendaftaran = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        $bagians_is_seminar = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->where('is_seminar', 1)->get() : collect();
        $bimbingans_is_acc_seminar = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)
        ->whereHas('bagian', function($query) {
            $query->where('is_seminar', 1);
        })->get();

        if($pendaftaran){
            if (AppHelper::instance()->is_expired_in_one_year($pendaftaran->tanggal_acc)) {
                return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
            }
        }

        $bimbingan = Bimbingan::with(['dosens'])->findOrFail($id);

        if ($mahasiswa->id != $bimbingan->mahasiswa->id) {
            abort(404);
        } elseif ($bimbingan->status == 'review' || $bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan tidak dapat disubmit');
        } elseif(count($bimbingans_is_acc_seminar) / 2  < count($bagians_is_seminar) && $bimbingan->bagian->is_pendadaran == 1){
            return back();
        }

        return view('kp.pages.mahasiswa.bimbingan.edit', [
            'title' => 'Form Submit Bimbingan Kerja Praktek',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan-kp',
            'bagians' => $prodi->bagians,
            'dosen' => $bimbingan->dosens()->first(),
        ]);
    }

    public function bimbinganDetail($id)
    {
        $bimbingan = Bimbingan::with(['revisis','mahasiswa', 'bagian', 'dosens'])->findOrFail($id);
        if ($bimbingan->mahasiswa->id != Auth::guard('mahasiswa')->user()->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }
        if ($bimbingan->status == null) {
            return back()->with('warning', 'Harap edit Bimbingan terlebih dahulu');
        }

        $mahasiswa = $bimbingan->mahasiswa;

        // Ambil dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Ambil pengajuan untuk judul KP
        $pengajuan = Pengajuan::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'diterima')
            ->first();

        return view('kp.pages.mahasiswa.bimbingan.detail', [
            'title' => 'Detail Bimbingan Kerja Praktek',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan-kp',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'dosen_pembimbing' => $dosen_pembimbing,
            'pengajuan' => $pengajuan,
        ]);
    }

    public function bimbinganReview($id)
    {
        $bimbingan = Bimbingan::with(['revisis'])->findOrFail($id);
        $mahasiswa = Mahasiswa::with(['bimbingansKP','pengajuansKP'])->find($bimbingan->mahasiswa->id);
        // Cari prodi berdasarkan kode ATAU namaprodi
        $prodi = Prodi::with(['bagiansKP'])->where('namaprodi', $mahasiswa->prodi)
            ->orWhere('kode', $mahasiswa->prodi)
            ->first();
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();

        $bimbingans_acc = $mahasiswa->bimbingansKP()->where('status', 'diterima')->get();

        return view('kp.pages.dosen.bimbingan.review', [
            'title' => 'Review Bimbingan Kerja Praktek',
            'bimbingan' => $bimbingan,
            'active' => 'bimbingan-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(5),
            'bagians' => $prodi ? $prodi->bagiansKP : collect(),
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
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Masa aktif bimbingan anda sudah berakhir, silahkan lakukan pendaftaran ulang');
        }

        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'review') {
            return redirect()->route('kp.bimbingan.mahasiswa')->with('warning', 'Bimbingan tidak bisa diedit');
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
        return redirect()->route('kp.bimbingan.mahasiswa')->with('success', 'Bimbingan berhasil diupdate. Silahkan tunggu review dari dosen pembimbing');
    }

    public function delete(Request $request)
    {
        return back();
    }

    public function accBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima') {
            return redirect()->route('kp.bimbingan.dosen')->with('warning', 'Bimbingan sudah di Acc');
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
                'subject' => 'Bimbingan Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Selamat Bimbingan Kerja Praktek Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya. Catatan: '.$request->catatan,
            ]);
        }
        return redirect()->route('kp.bimbingan.dosen')->with('success', 'Bimbingan berhasil di Acc');
    }

    public function revisiBimbingan(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        if ($bimbingan->status == 'diterima' || $bimbingan->status == 'revisi') {
            return redirect()->route('kp.bimbingan.dosen')->with('warning', 'Bimbingan tidak bisa direvisi');
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
                'subject' => 'Bimbingan Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'From: <b>'.Auth::guard('dosen')->user()->nama.', '.Auth::guard('dosen')->user()->gelar.'</b><br>Bimbingan Kerja Praktek Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus REVISI. Silahkan perbaiki kemudian submit ulang. <br><br> Catatan revisi : '. $request->catatan,
            ]);
        }
        return redirect()->route('kp.bimbingan.dosen')->with('success', 'Bimbingan berhasil direvisi');
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
        $bimbingan = Bimbingan::with(['mahasiswa', 'bagian', 'revisis'])->findOrFail($id);
        $mahasiswa = $bimbingan->mahasiswa;
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $data = [
            'title' => 'Review Bimbingan KP',
            'active' => 'bimbingan-input-kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'bimbingan' => $bimbingan,
            'pengajuan' => $pengajuan,
            'dosen_pembimbing' => $dosen_pembimbing,
            'mahasiswa' => $mahasiswa,
            'revisis' => $bimbingan->revisis()->orderBy('created_at', 'desc')->paginate(10),
        ];

        return view('kp.pages.prodi.bimbingan.review', $data);
    }

    public function bimbinganAdmin()
    {
        $mahasiswas = Mahasiswa::with(['bimbingansKP', 'pendaftaransKP', 'dosens', 'seminarKP', 'pengajuansKP', 'jilidKP', 'bimbingan_canceledsKP'])->get();
        return view('kp.pages.admin.bimbingan.bimbingan', [
            'title' => 'Laporan Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function reviewAdmin($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        // KP: single dosen pembimbing
        $dosen_pembimbing = $pengajuan->mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $pengajuan->mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $data = [
            'title' => 'Detail Bimbingan Kerja Praktek',
            'active' => 'bimbingan-kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'pengajuan' => $pengajuan,
            'dosen_pembimbing' => $dosen_pembimbing,
            'mahasiswa' => $pengajuan->mahasiswa,
        ];

        return view('kp.pages.admin.bimbingan.detail', $data);
    }

    public function bimbinganDosenProgress()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        return view('kp.pages.dosen.bimbingan.bimbingan-progress', [
            'title' => 'Bimbingan Kerja Praktek',
            'active' => 'bimbingan-progress-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'mahasiswas' => $dosen->mahasiswas()->with(['bimbingansKP'])->get(),
        ]);
    }

    public function rekapDosen()
    {
        $prodi =  Prodi::with(['dosens'])->findOrFail(Auth::guard('prodi')->user()->id);
        $dosens = $prodi->dosens()->with(['mahasiswas'])->get();

        return view('kp.pages.prodi.bimbingan.rekap-dosen', [
            'title' => 'Rekap Bimbingan Dosen',
            'sidebar' => 'kp.partials.sidebarProdi',
            'module' => 'kp',
            'active' => 'dashboard',
            'dosens' => $dosens,
        ]);
    }

    public function bimbinganAdminInput()
    {
        // Cek berdasarkan route name untuk menentukan sidebar
        $routeName = request()->route()->getName();

        if(($routeName == 'kp.bimbingan.prodi.input' || $routeName == 'bimbingan.prodi.input') && Auth::guard('prodi')->check()){
            $prodi = Prodi::findOrFail(Auth::guard('prodi')->user()->id);
            $sidebar = 'partials.sidebarProdi';

            // Ambil bimbingan dari mahasiswa yang dosennya is_manual = 1
            $bimbingans_review = Bimbingan::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where('prodi', $prodi->namaprodi)
                  ->whereHas('dosens', function($q2) {
                      $q2->where('status', 'pembimbing')->where('is_manual', 1);
                  });
            })->where('status', 'review')->with(['mahasiswa', 'bagian'])->get();

            $bimbingans_diterima = Bimbingan::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where('prodi', $prodi->namaprodi)
                  ->whereHas('dosens', function($q2) {
                      $q2->where('status', 'pembimbing')->where('is_manual', 1);
                  });
            })->where('status', 'diterima')->with(['mahasiswa', 'bagian'])->orderBy('tanggal_acc', 'desc')->get();

            $bimbingans_revisi = Bimbingan::whereHas('mahasiswa', function($q) use ($prodi) {
                $q->where('prodi', $prodi->namaprodi)
                  ->whereHas('dosens', function($q2) {
                      $q2->where('status', 'pembimbing')->where('is_manual', 1);
                  });
            })->where('status', 'revisi')->with(['mahasiswa', 'bagian'])->get();
        } else {
            $sidebar = 'partials.sidebarAdmin';

            // Admin: semua bimbingan dari dosen manual
            $bimbingans_review = Bimbingan::whereHas('mahasiswa', function($q) {
                $q->whereHas('dosens', function($q2) {
                    $q2->where('status', 'pembimbing')->where('is_manual', 1);
                });
            })->where('status', 'review')->with(['mahasiswa', 'bagian'])->get();

            $bimbingans_diterima = Bimbingan::whereHas('mahasiswa', function($q) {
                $q->whereHas('dosens', function($q2) {
                    $q2->where('status', 'pembimbing')->where('is_manual', 1);
                });
            })->where('status', 'diterima')->with(['mahasiswa', 'bagian'])->orderBy('tanggal_acc', 'desc')->get();

            $bimbingans_revisi = Bimbingan::whereHas('mahasiswa', function($q) {
                $q->whereHas('dosens', function($q2) {
                    $q2->where('status', 'pembimbing')->where('is_manual', 1);
                });
            })->where('status', 'revisi')->with(['mahasiswa', 'bagian'])->get();
        }

        return view('kp.pages.admin.bimbingan.bimbingan-input', [
            'title' => 'Validasi Bimbingan KP',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input-kp',
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_revisi' => $bimbingans_revisi,
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

        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();
        $bagians = $prodi ? $prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->orderBy('id')->get() : collect();

        // Buat array bimbingan per bagian
        $bimbingan_per_bagian = [];
        foreach ($bagians as $bagian) {
            $bimbingan = $mahasiswa->bimbingansKP()->where('bagian_id', $bagian->id)->first();
            $bimbingan_per_bagian[] = [
                'bagian' => $bagian,
                'bimbingan' => $bimbingan,
            ];
        }

        return view('kp.pages.admin.bimbingan.bimbingan-store', [
            'title' => 'Validasi Bimbingan KP',
            'sidebar' => $sidebar,
            'active' => 'bimbingan-input-kp',
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
            'bimbingan_per_bagian' => $bimbingan_per_bagian,
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
        $dates = $request->dates ?? [];
        $ids = $request->ids ?? [];
        for ($i = 0; $i < count($dates); $i++) {
            if ($dates[$i] != null && isset($ids[$i]) && !empty($ids[$i])) {
                $bimbingan = Bimbingan::findOrFail($ids[$i]);
                // Hanya ACC jika status masih review
                if ($bimbingan->status == 'review') {
                    $bimbingan->update([
                        "status" => "diterima",
                        "tanggal_acc" => $dates[$i],
                    ]);
                    if ($bimbingan->mahasiswa->email != '-') {
                        AppHelper::instance()->send_mail([
                            'mail' => $bimbingan->mahasiswa->email,
                            'subject' => 'Bimbingan Kerja Praktek',
                            'title' => 'EKAPTA',
                            'message' => 'Selamat Bimbingan Kerja Praktek Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
                        ]);
                    }
                }
            }
        }
        return back()->with('success', 'Berhasil disimpan');
    }

    public function public($id)
    {
        $mahasiswa = Mahasiswa::with(['bimbingansKP'])->where('id', base64_decode($id))->first();
        if (!$mahasiswa) {
            abort(404);
        }
        $pengajuan = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        // Cari prodi berdasarkan kode ATAU namaprodi untuk backward compatibility
        $prodi = Prodi::where('kode', $mahasiswa->prodi)
            ->orWhere('namaprodi', $mahasiswa->prodi)
            ->first();

        // KP: single dosen pembimbing
        $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        // Fallback untuk data lama
        if (!$dosen_pembimbing) {
            $dosen_pembimbing = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        $pendaftaran_acc = Pendaftaran::orderBy('created_at', 'desc')->where('mahasiswa_id', $mahasiswa->id)->where('status', 'diterima')->first();
        return view('kp.pages.public.detail', [
            'title' => 'Riwayat Bimbingan Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi,
            'dosen_pembimbing' => $dosen_pembimbing,
            'date_expired' => $pendaftaran_acc ? Carbon::parse($pendaftaran_acc->tanggal_acc)->addMonthsNoOverflow(6) : null,
            'is_expired' => $pendaftaran_acc ? AppHelper::instance()->is_expired_in_one_year($pendaftaran_acc->tanggal_acc) : null,
            'pengajuan' => $pengajuan ? $pengajuan : null,
            'pendaftaran' => $pendaftaran_acc ? $pendaftaran_acc : null,
        ]);
    }

    public function bimbinganCanceled($id){
        return back()->with('warning', 'Mohon maaf, saat ini fitur sedang di non-aktifkan');
    }

    // ==================== BIMBINGAN OFFLINE ====================

    public function createOffline()
    {
        return view('kp.pages.mahasiswa.bimbingan.create-offline', [
            'title' => 'Input Bimbingan Offline',
            'active' => 'bimbingan-kp',
        ]);
    }

    public function storeOffline(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'keterangan' => 'required',
            'bukti_bimbingan_offline' => 'required|mimes:pdf,jpg,jpeg,png|max:5000',
        ]);

        $bimbingan = new Bimbingan;
        $bimbingan->tanggal_bimbingan = $request->tanggal_bimbingan;
        $bimbingan->keterangan = $request->keterangan;
        $bimbingan->bukti_bimbingan_offline = AppHelper::instance()->uploadLampiran($request->bukti_bimbingan_offline, 'lampirans', $mahasiswa->nim, 'bimbingan-offline');
        $bimbingan->tipe = 'offline';
        $bimbingan->status_offline = 'pending';
        $bimbingan->mahasiswa_id = $mahasiswa->id;

        $bimbingan->save();

        return redirect()->route('kp.bimbingan.mahasiswa')->with('success', 'Bimbingan offline berhasil disubmit. Menunggu verifikasi dari prodi.');
    }

    public function detailOffline($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        if ($bimbingan->mahasiswa_id != Auth::guard('mahasiswa')->user()->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        return view('kp.pages.mahasiswa.bimbingan.detail-offline', [
            'title' => 'Detail Bimbingan Offline',
            'active' => 'bimbingan-kp',
            'bimbingan' => $bimbingan,
        ]);
    }

    public function verifyOffline(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status_offline' => 'verified',
        ]);

        return back()->with('success', 'Bimbingan offline berhasil diverifikasi');
    }

    public function rejectOffline(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $bimbingan->update([
            'status_offline' => 'rejected',
        ]);

        return back()->with('success', 'Bimbingan offline ditolak');
    }

    // ==================== SUBMIT ACC MANUAL (untuk dosen is_manual) ====================

    /**
     * Form input acc manual untuk mahasiswa
     * Digunakan ketika dosen pembimbing is_manual = true
     */
    public function submitAccManual($id)
    {
        $bimbingan = Bimbingan::with(['bagian', 'mahasiswa'])->findOrFail($id);
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Pastikan bimbingan milik mahasiswa yang login
        if ($bimbingan->mahasiswa_id != $mahasiswa->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        // Pastikan status masih review
        if ($bimbingan->status != 'review') {
            return back()->with('warning', 'Bimbingan tidak dalam status review');
        }

        // Ambil dosen pembimbing
        $dosen = $mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosen) {
            $dosen = $mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Pastikan dosen is_manual
        if (!$dosen || !$dosen->is_manual) {
            return back()->with('warning', 'Fitur ini hanya untuk dosen dengan mode bimbingan offline');
        }

        return view('kp.pages.mahasiswa.bimbingan.submit-acc-manual', [
            'title' => 'Input Acc Manual Bimbingan',
            'active' => 'bimbingan-kp',
            'bimbingan' => $bimbingan,
            'dosen' => $dosen,
        ]);
    }

    /**
     * Store acc manual dari mahasiswa
     */
    public function submitAccManualStore(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);
        $mahasiswa = Mahasiswa::findOrFail(Auth::guard('mahasiswa')->user()->id);

        // Pastikan bimbingan milik mahasiswa yang login
        if ($bimbingan->mahasiswa_id != $mahasiswa->id) {
            return back()->with('warning', 'Bimbingan tidak ditemukan');
        }

        $request->validate([
            'lampiran_acc' => 'required|mimes:pdf,jpg,jpeg,png|max:1000',
            'tanggal_manual_acc' => 'required|date',
        ]);

        // Upload lampiran acc
        $lampiran_acc = AppHelper::instance()->uploadLampiran($request->lampiran_acc, 'lampirans', $mahasiswa->nim, 'bimbingan-acc');

        $bimbingan->update([
            'lampiran_acc' => $lampiran_acc,
            'tanggal_manual_acc' => $request->tanggal_manual_acc,
        ]);

        return redirect()->route('kp.bimbingan.mahasiswa')->with('success', 'Lembar acc bimbingan berhasil diupload. Silahkan tunggu validasi dari prodi.');
    }

    // ==================== ACC & REVISI BIMBINGAN UNTUK PRODI ====================

    /**
     * ACC Bimbingan oleh Prodi/Admin
     */
    public function accBimbinganProdi(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);

        if ($bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan sudah di ACC');
        }

        $request->validate([
            'tanggal_acc' => 'required|date',
        ]);

        $bimbingan->update([
            'status' => 'diterima',
            'tanggal_acc' => $request->tanggal_acc,
            'tanggal_bimbingan' => $bimbingan->tanggal_bimbingan ?? $request->tanggal_acc,
        ]);

        // Simpan catatan jika ada
        if ($request->catatan) {
            $revisi = new RevisiBimbingan;
            $revisi->catatan = $request->catatan;
            $revisi->lampiran = $bimbingan->lampiran;
            $bimbingan->revisis()->save($revisi);
        }

        // Kirim email notifikasi
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Selamat Bimbingan Kerja Praktek Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus DITERIMA. Silahkan lanjutkan ke bab berikutnya.',
            ]);
        }

        return back()->with('success', 'Bimbingan '.$bimbingan->bagian->bagian.' berhasil di ACC');
    }

    /**
     * Revisi Bimbingan oleh Prodi/Admin
     */
    public function revisiBimbinganProdi(Request $request)
    {
        $bimbingan = Bimbingan::findOrFail($request->id);

        if ($bimbingan->status == 'diterima') {
            return back()->with('warning', 'Bimbingan sudah di ACC, tidak bisa direvisi');
        }

        $request->validate([
            'catatan' => 'required',
        ]);

        // Ambil dosen pembimbing mahasiswa
        $dosen = $bimbingan->mahasiswa->dosens()->where('status', 'pembimbing')->first();
        if (!$dosen) {
            $dosen = $bimbingan->mahasiswa->dosens()->where('status', 'utama')->first();
        }

        // Pastikan ada dosen
        if (!$dosen) {
            return back()->with('warning', 'Mahasiswa belum memiliki dosen pembimbing');
        }

        // Simpan catatan revisi
        $revisi = new RevisiBimbingan;
        $revisi->catatan = $request->catatan;
        $revisi->lampiran = $bimbingan->lampiran;
        $revisi->dosen_id = $dosen->id;
        $revisi->reviewer_type = 'prodi';
        $revisi->prodi_id = auth('prodi')->id();
        $bimbingan->revisis()->save($revisi);

        $bimbingan->update([
            'status' => 'revisi',
        ]);

        // Kirim email notifikasi
        if ($bimbingan->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $bimbingan->mahasiswa->email,
                'subject' => 'Bimbingan Kerja Praktek',
                'title' => 'EKAPTA',
                'message' => 'Bimbingan Kerja Praktek Anda <b>'.$bimbingan->bagian->bagian.'</b> Berstatus REVISI. Silahkan perbaiki dan submit ulang. Catatan: '.$request->catatan,
            ]);
        }

        return back()->with('success', 'Bimbingan '.$bimbingan->bagian->bagian.' berhasil direvisi');
    }

}

