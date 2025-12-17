<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Himpunan;
use App\Models\Seminar;
use App\Models\SesiSeminar;
use App\Models\Dosen;
use App\Models\ReviewSeminar;
use App\Models\RevisiSeminar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HimpunanController extends Controller
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

        return view('pages.himpunan.seminar.index', [
            'title' => 'Verifikasi Seminar KP',
            'active' => 'seminar',
            'sidebar' => 'partials.sidebarHimpunan',
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

        return view('pages.himpunan.seminar.review', [
            'title' => 'Review Seminar KP',
            'active' => 'seminar',
            'sidebar' => 'partials.sidebarHimpunan',
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
            'tanggal_acc' => now(),
        ]);

        // Kirim email notifikasi ke mahasiswa
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Selamat pendaftaran Seminar Kerja Praktik anda sudah divalidasi oleh Himpunan. Silahkan tunggu jadwal seminar.',
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
        $seminar->update(['is_valid' => Seminar::REVISI]);

        RevisiSeminar::create([
            'seminar_id' => $seminar->id,
            'catatan' => $request->catatan,
        ]);

        // Kirim email notifikasi ke mahasiswa
        if ($seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Pendaftaran Seminar Kerja Praktik anda perlu direvisi. Silahkan cek catatan revisi di sistem.',
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
                'message' => 'Jadwal Seminar Kerja Praktik anda sudah ditentukan. <br>Tanggal: <b>' . AppHelper::parse_date($request->tanggal_ujian) . '</b><br>Tempat: <b>' . $request->tempat_ujian . '</b>',
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

        return view('pages.himpunan.seminar.rekap', [
            'title' => 'Rekap Seminar KP',
            'active' => 'seminar',
            'sidebar' => 'partials.sidebarHimpunan',
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

        return view('pages.himpunan.seminar.jadwal', [
            'title' => 'Penjadwalan Seminar KP',
            'active' => 'jadwal',
            'sidebar' => 'partials.sidebarHimpunan',
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

        // Buat sesi seminar
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

        return view('pages.himpunan.seminar.detail-sesi', [
            'title' => 'Detail Sesi Seminar',
            'active' => 'jadwal',
            'sidebar' => 'partials.sidebarHimpunan',
            'sesi' => $sesi,
        ]);
    }

    /**
     * Tolak pendaftaran seminar
     */
    public function seminarTolak(Request $request)
    {
        $seminar = Seminar::findOrFail($request->id);
        $seminar->update([
            'is_valid' => Seminar::DITOLAK,
            'status_seminar' => Seminar::STATUS_DITOLAK,
        ]);

        RevisiSeminar::create([
            'seminar_id' => $seminar->id,
            'catatan' => $request->catatan ?? 'Pendaftaran ditolak',
        ]);

        if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $seminar->mahasiswa->email,
                'message' => 'Pendaftaran Seminar KP Anda ditolak. Silahkan daftar ulang dengan melengkapi persyaratan.',
            ]);
        }

        return back()->with('success', 'Pendaftaran seminar ditolak');
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
}
