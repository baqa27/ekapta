<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Seminar;
use App\Models\KP\SesiSeminar;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\RevisiSeminar;

// Import Shared Models
use App\Models\Himpunan;
use App\Models\Dosen;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HimpunanController extends \App\Http\Controllers\Controller
{
    /**
     * Menampilkan daftar seminar untuk verifikasi himpunan
     */
    public function seminarIndex()
    {
        $seminars_review = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVIEW)->get();
        $seminars_revisi = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::REVISI)->get();
        $seminars_acc = Seminar::orderBy('created_at', 'desc')->where('is_valid', Seminar::DITERIMA)->get();
        
        $himpunan = Auth::guard('himpunan')->user();
        $is_pendaftaran_open = $himpunan ? $himpunan->is_pendaftaran_seminar_open : true;

        return view('kp.pages.himpunan.seminar.index', [
            'title' => 'Verifikasi Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars_review' => $seminars_review,
            'seminars_revisi' => $seminars_revisi,
            'seminars_acc' => $seminars_acc,
            'is_pendaftaran_open' => $is_pendaftaran_open,
        ]);
    }

    /**
     * Toggle buka/tutup pendaftaran seminar
     */
    public function togglePendaftaranSeminar()
    {
        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        $himpunan->is_pendaftaran_seminar_open = !$himpunan->is_pendaftaran_seminar_open;
        $himpunan->save();

        $status = $himpunan->is_pendaftaran_seminar_open ? 'DIBUKA' : 'DITUTUP';
        return back()->with('success', "Pendaftaran Seminar KP sekarang $status");
    }

    /**
     * Menampilkan detail seminar untuk review
     */
    public function seminarReview($id)
    {
        $seminar = Seminar::with(['mahasiswa', 'pengajuan', 'revisis'])->findOrFail($id);
        $revisis = $seminar->revisis()->orderBy('created_at', 'desc')->paginate(5);

        return view('kp.pages.himpunan.seminar.review', [
            'title' => 'Review Seminar KP',
            'active' => 'seminar-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminar' => $seminar,
            'revisis' => $revisis,
        ]);
    }

    /**
     * ACC/Validasi seminar oleh himpunan
     */
    public function seminarAcc(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([
            'is_valid' => Seminar::DITERIMA,
            'status_seminar' => Seminar::STATUS_DITERIMA,
            'tanggal_acc' => now(),
        ]);

        // Kirim email notifikasi ke mahasiswa
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Selamat pendaftaran Seminar Kerja Praktek anda sudah divalidasi oleh Himpunan. Silahkan tunggu jadwal seminar.',
            ]);
        }

        return back()->with('success', 'Seminar berhasil divalidasi');
    }

    /**
     * Revisi seminar oleh himpunan
     */
    public function seminarRevisi(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([
            'is_valid' => Seminar::REVISI,
            'status_seminar' => Seminar::STATUS_REVISI,
        ]);

        RevisiSeminar::create([
            'seminar_id' => $seminar->id,
            'catatan' => $request->catatan,
        ]);

        // Kirim email notifikasi ke mahasiswa
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Pendaftaran Seminar Kerja Praktek anda perlu direvisi. Silahkan cek catatan revisi di sistem.',
            ]);
        }

        return back()->with('success', 'Revisi berhasil disimpan');
    }

    /**
     * Set jadwal seminar (tanggal, tempat, penguji)
     */
    public function setJadwalSeminar(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        $validatedData = $request->validate([
            'tanggal_ujian' => 'required',
            'tempat_ujian' => 'required',
        ]);
        
        $validatedData['tanggal_ujian'] = Carbon::parse($request->tanggal_ujian);
        $seminar->update($validatedData);

        // Kirim email notifikasi ke mahasiswa
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Jadwal Seminar Kerja Praktek anda sudah ditentukan. <br>Tanggal: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat: <b>' . $request->tempat_ujian . '</b>',
            ]);
        }

        // Kirim email ke dosen penguji
        foreach ($seminar->reviews()->where('dosen_status', 'penguji')->with(['dosen'])->get() as $review) {
            if ($review->dosen->email) {
                AppHelper::instance()->send_mail([
                    'mail' => $review->dosen->email,
                    'message' => 'Anda ditunjuk sebagai penguji Seminar KP. <br>Mahasiswa: <b>' . $seminar->mahasiswa->nama . '</b><br>Judul: <b>' . $seminar->pengajuan->judul . '</b><br>Tanggal: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat: <b>' . $request->tempat_ujian . '</b>',
                ]);
            }
        }

        return back()->with('success', 'Jadwal seminar berhasil disimpan');
    }

    /**
     * Ploting dosen penguji seminar
     */
    public function plotingPenguji(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        // Cek apakah sudah ada penguji yang sudah memberikan nilai
        $reviews_check = $seminar->reviews()
            ->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])
            ->where('dosen_status', 'penguji')
            ->get();

        if (count($reviews_check) != 0) {
            return back()->with('warning', 'Dosen penguji sudah memberikan penilaian, tidak bisa diubah');
        }

        // Hapus penguji lama
        $dosens_penguji = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get();
        foreach ($dosens_penguji as $dosen) {
            $dosen->delete();
        }

        // Tambah penguji baru
        for ($i = 0; $i < count($request->dosen_penguji); $i++) {
            if ($request->dosen_penguji[$i] != null) {
                ReviewSeminar::create([
                    'seminar_id' => $seminar->id,
                    'dosen_id' => $request->dosen_penguji[$i],
                    'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                    'status' => ReviewSeminar::REVIEW,
                ]);
            }
        }

        return back()->with('success', 'Ploting dosen penguji berhasil');
    }

    /**
     * Rekap seminar untuk himpunan
     */
    public function rekapSeminar()
    {
        $seminars = Seminar::with(['mahasiswa', 'pengajuan'])->orderBy('created_at', 'desc')->get();

        return view('kp.pages.himpunan.seminar.rekap', [
            'title' => 'Rekap Seminar KP',
            'active' => 'rekap-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars' => $seminars,
        ]);
    }

    /**
     * Halaman penjadwalan sesi seminar
     */
    public function jadwalIndex()
    {
        $sesi_seminars = SesiSeminar::with(['seminars.mahasiswa', 'dosenPenguji'])
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // Mahasiswa yang siap dijadwalkan (diterima tapi belum ada sesi)
        $seminars_siap = Seminar::where('is_valid', Seminar::DITERIMA)
            ->whereNull('sesi_seminar_id')
            ->with(['mahasiswa', 'pengajuan'])
            ->get();

        $dosens = Dosen::orderBy('nama')->get();

        return view('kp.pages.himpunan.seminar.jadwal', [
            'title' => 'Penjadwalan Seminar KP',
            'active' => 'jadwal-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'sesi_seminars' => $sesi_seminars,
            'seminars_siap' => $seminars_siap,
            'dosens' => $dosens,
        ]);
    }

    /**
     * Buat sesi seminar baru
     */
    public function createSesi(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tempat' => 'required|string',
            'dosen_penguji_id' => 'required|exists:dosens,id',
            'jumlah_mahasiswa' => 'required|integer|min:1|max:20',
            'seminars' => 'required|array|min:1',
        ]);

        // Buat sesi seminar (token_penilaian auto-generated di model boot)
        $sesi = SesiSeminar::create([
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tempat' => $request->tempat,
            'dosen_penguji_id' => $request->dosen_penguji_id,
            'jumlah_mahasiswa' => $request->jumlah_mahasiswa,
            'catatan_teknis' => $request->catatan_teknis,
        ]);

        // Assign mahasiswa ke sesi dan set urutan presentasi
        $urutan = 1;
        foreach ($request->seminars as $seminar_id) {
            $seminar = Seminar::find($seminar_id);
            if ($seminar) {
                $seminar->update([
                    'sesi_seminar_id' => $sesi->id,
                    'urutan_presentasi' => $urutan,
                    'tanggal_ujian' => $request->tanggal . ' ' . $request->jam_mulai,
                    'tempat_ujian' => $request->tempat,
                    'status_seminar' => Seminar::STATUS_DIJADWALKAN,
                ]);

                // Kirim notifikasi ke mahasiswa
                if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                    AppHelper::instance()->send_mail([
                        'mail' => $seminar->mahasiswa->email,
                        'message' => "Jadwal Seminar KP Anda sudah ditentukan.<br>
                            Tanggal: <b>" . Carbon::parse($request->tanggal)->translatedFormat('l, d F Y') . "</b><br>
                            Waktu: <b>{$request->jam_mulai} - {$request->jam_selesai} WIB</b><br>
                            Tempat: <b>{$request->tempat}</b><br>
                            Urutan Presentasi: <b>{$urutan}</b>",
                    ]);
                }

                $urutan++;
            }
        }

        return back()->with('success', 'Sesi seminar berhasil dibuat. Link penilaian: ' . $sesi->link_penilaian);
    }

    /**
     * Detail sesi seminar
     */
    public function detailSesi($id)
    {
        $sesi = SesiSeminar::with(['seminars.mahasiswa', 'seminars.pengajuan', 'dosenPenguji'])->findOrFail($id);

        return view('kp.pages.himpunan.seminar.detail-sesi', [
            'title' => 'Detail Sesi Seminar',
            'active' => 'jadwal-kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'sesi' => $sesi,
        ]);
    }

    /**
     * Hapus sesi seminar (hanya jika belum digunakan)
     */
    public function deleteSesi($id)
    {
        $sesi = SesiSeminar::findOrFail($id);

        // Cek apakah token sudah digunakan
        if ($sesi->is_token_used) {
            return back()->with('error', 'Sesi seminar tidak bisa dihapus karena sudah digunakan untuk penilaian.');
        }

        // Reset seminar yang terkait ke status sebelumnya
        foreach ($sesi->seminars as $seminar) {
            $seminar->update([
                'sesi_seminar_id' => null,
                'urutan_presentasi' => null,
                'tanggal_ujian' => null,
                'tempat_ujian' => null,
                'status_seminar' => Seminar::STATUS_DITERIMA,
            ]);
        }

        // Hapus sesi
        $sesi->delete();

        return back()->with('success', 'Sesi seminar berhasil dihapus.');
    }

    /**
     * Validasi revisi pasca seminar
     */
    public function validasiRevisiPasca(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        if ($request->action === 'setuju') {
            $seminar->update([
                'status_seminar' => Seminar::STATUS_REVISI_DISETUJUI,
            ]);
            $message = 'Revisi pasca seminar disetujui';
        } else {
            RevisiSeminar::create([
                'seminar_id' => $seminar->id,
                'catatan' => $request->catatan,
            ]);
            $message = 'Catatan revisi berhasil dikirim';
        }

        return back()->with('success', $message);
    }

    /**
     * Finalisasi nilai akhir
     */
    public function finalisasiNilai(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);
        
        $seminar->hitungNilaiAkhir();
        $seminar->update([
            'status_seminar' => Seminar::STATUS_SELESAI,
            'tanggal_selesai' => now(),
        ]);

        return back()->with('success', 'Nilai akhir berhasil ditetapkan: ' . $seminar->nilai_akhir);
    }

    /**
     * Halaman pengaturan pembayaran seminar
     */
    public function paymentSettings()
    {
        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);

        return view('kp.pages.himpunan.payment', [
            'title' => 'Pengaturan Pembayaran',
            'active' => 'payment',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'himpunan' => $himpunan,
        ]);
    }

    /**
     * Update info pembayaran seminar
     */
    public function updatePayment(Request $request)
    {
        $request->validate([
            'biaya_seminar' => 'required|integer|min:0',
        ]);

        $himpunan = Himpunan::findOrFail(Auth::guard('himpunan')->user()->id);
        $himpunan->update([
            'biaya_seminar' => $request->biaya_seminar,
            'nama_rekening' => $request->nama_rekening,
            'nomor_rekening' => $request->nomor_rekening,
            'bank' => $request->bank,
            'nomor_dana' => $request->nomor_dana,
            'nomor_seabank' => $request->nomor_seabank,
        ]);

        return redirect()->route('kp.payment.himpunan')->with('success', 'Info pembayaran berhasil diupdate');
    }
}

