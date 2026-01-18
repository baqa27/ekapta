<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Mahasiswa - Halaman pilih sistem (KP atau TA)
     * HANYA MAHASISWA yang masuk ke halaman pilihan
     */
    public function dashboardMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        return view('pages.mahasiswa.pilih-sistem', [
            'title' => 'Pilih Sistem - EKAPTA',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Dashboard Prodi - Menampilkan ringkasan kedua sistem (TA dan KP)
     * UI sama persis dengan dashboard asli ekapta-ta dan ekapta-kp
     */
    public function dashboardProdi()
    {
        // ============================================
        // DATA TUGAS AKHIR (TA)
        // ============================================
        $ta_pengajuans = \App\Models\TA\Pengajuan::all();
        $ta_pengajuans_diterima = \App\Models\TA\Pengajuan::where('status', 'diterima')->get();
        $ta_pengajuans_revisi = \App\Models\TA\Pengajuan::where('status', 'revisi')->get();
        $ta_pengajuans_review = \App\Models\TA\Pengajuan::where('status', 'review')->get();
        $ta_pengajuans_ditolak = \App\Models\TA\Pengajuan::where('status', 'ditolak')->get();

        $ta_bimbingans = \App\Models\TA\Bimbingan::all();
        $ta_bimbingans_diterima = \App\Models\TA\Bimbingan::where('status', 'diterima')->get();
        $ta_bimbingans_review = \App\Models\TA\Bimbingan::where('status', 'review')->get();
        $ta_bimbingans_revisi = \App\Models\TA\Bimbingan::where('status', 'revisi')->get();

        $ta_seminars = \App\Models\TA\Seminar::all();
        $ta_seminars_diterima = \App\Models\TA\Seminar::where('is_valid', '1')->get();
        $ta_seminars_review = \App\Models\TA\Seminar::where('is_valid', '0')->get();
        $ta_seminars_revisi = \App\Models\TA\Seminar::where('is_valid', '2')->get();

        $ta_ujians = \App\Models\TA\Ujian::all();
        $ta_ujians_diterima = \App\Models\TA\Ujian::where('is_valid', '1')->get();
        $ta_ujians_review = \App\Models\TA\Ujian::where('is_valid', '3')->get();
        $ta_ujians_revisi = \App\Models\TA\Ujian::where('is_valid', '2')->get();

        // ============================================
        // DATA KERJA PRAKTIK (KP)
        // ============================================
        $kp_pengajuans = \App\Models\KP\Pengajuan::all();
        $kp_pengajuans_diterima = \App\Models\KP\Pengajuan::where('status', 'diterima')->get();
        $kp_pengajuans_revisi = \App\Models\KP\Pengajuan::where('status', 'revisi')->get();
        $kp_pengajuans_review = \App\Models\KP\Pengajuan::where('status', 'review')->get();
        $kp_pengajuans_ditolak = \App\Models\KP\Pengajuan::where('status', 'ditolak')->get();

        $kp_bimbingans = \App\Models\KP\Bimbingan::all();
        $kp_bimbingans_diterima = \App\Models\KP\Bimbingan::where('status', 'diterima')->get();
        $kp_bimbingans_review = \App\Models\KP\Bimbingan::where('status', 'review')->get();
        $kp_bimbingans_revisi = \App\Models\KP\Bimbingan::where('status', 'revisi')->get();

        $kp_seminars = \App\Models\KP\Seminar::all();
        $kp_seminars_diterima = \App\Models\KP\Seminar::where('is_valid', '1')->get();
        $kp_seminars_review = \App\Models\KP\Seminar::where('is_valid', '0')->get();
        $kp_seminars_revisi = \App\Models\KP\Seminar::where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (Jilid)
        $kp_pengumpulan_akhir = \App\Models\KP\Jilid::all();
        $kp_pengumpulan_akhir_diterima = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_VALID)
            ->orWhere('status', \App\Models\KP\Jilid::JILID_SELESAI)->get();
        $kp_pengumpulan_akhir_review = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_REVIEW)->get();
        $kp_pengumpulan_akhir_revisi = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_REVISI)->get();

        return view('pages.prodi.dashboard.home', [
            'title' => 'Dashboard Prodi - EKAPTA',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarProdi',
            // TA Data
            'ta_pengajuans' => $ta_pengajuans,
            'ta_pengajuans_diterima' => $ta_pengajuans_diterima,
            'ta_pengajuans_review' => $ta_pengajuans_review,
            'ta_pengajuans_revisi' => $ta_pengajuans_revisi,
            'ta_pengajuans_ditolak' => $ta_pengajuans_ditolak,
            'ta_bimbingans' => $ta_bimbingans,
            'ta_bimbingans_diterima' => $ta_bimbingans_diterima,
            'ta_bimbingans_review' => $ta_bimbingans_review,
            'ta_bimbingans_revisi' => $ta_bimbingans_revisi,
            'ta_seminars' => $ta_seminars,
            'ta_seminars_diterima' => $ta_seminars_diterima,
            'ta_seminars_review' => $ta_seminars_review,
            'ta_seminars_revisi' => $ta_seminars_revisi,
            'ta_ujians' => $ta_ujians,
            'ta_ujians_diterima' => $ta_ujians_diterima,
            'ta_ujians_review' => $ta_ujians_review,
            'ta_ujians_revisi' => $ta_ujians_revisi,
            // KP Data
            'kp_pengajuans' => $kp_pengajuans,
            'kp_pengajuans_diterima' => $kp_pengajuans_diterima,
            'kp_pengajuans_review' => $kp_pengajuans_review,
            'kp_pengajuans_revisi' => $kp_pengajuans_revisi,
            'kp_pengajuans_ditolak' => $kp_pengajuans_ditolak,
            'kp_bimbingans' => $kp_bimbingans,
            'kp_bimbingans_diterima' => $kp_bimbingans_diterima,
            'kp_bimbingans_review' => $kp_bimbingans_review,
            'kp_bimbingans_revisi' => $kp_bimbingans_revisi,
            'kp_seminars' => $kp_seminars,
            'kp_seminars_diterima' => $kp_seminars_diterima,
            'kp_seminars_review' => $kp_seminars_review,
            'kp_seminars_revisi' => $kp_seminars_revisi,
            'kp_pengumpulan_akhir' => $kp_pengumpulan_akhir,
            'kp_pengumpulan_akhir_diterima' => $kp_pengumpulan_akhir_diterima,
            'kp_pengumpulan_akhir_review' => $kp_pengumpulan_akhir_review,
            'kp_pengumpulan_akhir_revisi' => $kp_pengumpulan_akhir_revisi,
        ]);
    }

    /**
     * Dashboard Admin - Menampilkan ringkasan kedua sistem (TA dan KP)
     * UI sama persis dengan dashboard asli ekapta-ta dan ekapta-kp
     */
    public function dashboardAdmin()
    {
        // Master Data
        $mahasiswas = \App\Models\Mahasiswa::all();
        $dosens = \App\Models\Dosen::all();
        $prodis = \App\Models\Prodi::all();
        $fakultas = \App\Models\Fakultas::all();

        // ============================================
        // DATA TUGAS AKHIR (TA)
        // ============================================
        $ta_pengajuans = \App\Models\TA\Pengajuan::all();
        $ta_pengajuans_diterima = \App\Models\TA\Pengajuan::where('status', 'diterima')->get();
        $ta_pengajuans_revisi = \App\Models\TA\Pengajuan::where('status', 'revisi')->get();
        $ta_pengajuans_review = \App\Models\TA\Pengajuan::where('status', 'review')->get();
        $ta_pengajuans_ditolak = \App\Models\TA\Pengajuan::where('status', 'ditolak')->get();

        $ta_pendaftarans = \App\Models\TA\Pendaftaran::all();
        $ta_pendaftarans_diterima = \App\Models\TA\Pendaftaran::where('status', 'diterima')->get();
        $ta_pendaftarans_review = \App\Models\TA\Pendaftaran::where('status', 'review')->get();
        $ta_pendaftarans_revisi = \App\Models\TA\Pendaftaran::where('status', 'revisi')->get();

        $ta_seminars = \App\Models\TA\Seminar::all();
        $ta_seminars_diterima = \App\Models\TA\Seminar::where('is_valid', '1')->get();
        $ta_seminars_review = \App\Models\TA\Seminar::where('is_valid', '0')->get();
        $ta_seminars_revisi = \App\Models\TA\Seminar::where('is_valid', '2')->get();

        $ta_ujians = \App\Models\TA\Ujian::all();
        $ta_ujians_diterima = \App\Models\TA\Ujian::where('is_valid', '1')->get();
        $ta_ujians_review = \App\Models\TA\Ujian::where('is_valid', '3')->get();
        $ta_ujians_revisi = \App\Models\TA\Ujian::where('is_valid', '2')->get();

        // ============================================
        // DATA KERJA PRAKTIK (KP)
        // ============================================
        $kp_pengajuans = \App\Models\KP\Pengajuan::all();
        $kp_pengajuans_diterima = \App\Models\KP\Pengajuan::where('status', 'diterima')->get();
        $kp_pengajuans_revisi = \App\Models\KP\Pengajuan::where('status', 'revisi')->get();
        $kp_pengajuans_review = \App\Models\KP\Pengajuan::where('status', 'review')->get();
        $kp_pengajuans_ditolak = \App\Models\KP\Pengajuan::where('status', 'ditolak')->get();

        $kp_pendaftarans = \App\Models\KP\Pendaftaran::all();
        $kp_pendaftarans_diterima = \App\Models\KP\Pendaftaran::where('status', 'diterima')->get();
        $kp_pendaftarans_review = \App\Models\KP\Pendaftaran::where('status', 'review')->get();
        $kp_pendaftarans_revisi = \App\Models\KP\Pendaftaran::where('status', 'revisi')->get();

        $kp_seminars = \App\Models\KP\Seminar::all();
        $kp_seminars_diterima = \App\Models\KP\Seminar::where('is_valid', '1')->get();
        $kp_seminars_review = \App\Models\KP\Seminar::where('is_valid', '0')->get();
        $kp_seminars_revisi = \App\Models\KP\Seminar::where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (Jilid)
        $kp_pengumpulan_akhir = \App\Models\KP\Jilid::all();
        $kp_pengumpulan_akhir_diterima = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_VALID)
            ->orWhere('status', \App\Models\KP\Jilid::JILID_SELESAI)->get();
        $kp_pengumpulan_akhir_review = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_REVIEW)->get();
        $kp_pengumpulan_akhir_revisi = \App\Models\KP\Jilid::where('status', \App\Models\KP\Jilid::JILID_REVISI)->get();

        return view('pages.admin.dashboard.home', [
            'title' => 'Dashboard Admin - EKAPTA',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarAdmin',
            // Master Data
            'mahasiswas' => $mahasiswas,
            'dosens' => $dosens,
            'prodis' => $prodis,
            'fakultas' => $fakultas,
            // TA Data
            'ta_pengajuans' => $ta_pengajuans,
            'ta_pengajuans_diterima' => $ta_pengajuans_diterima,
            'ta_pengajuans_review' => $ta_pengajuans_review,
            'ta_pengajuans_revisi' => $ta_pengajuans_revisi,
            'ta_pengajuans_ditolak' => $ta_pengajuans_ditolak,
            'ta_pendaftarans' => $ta_pendaftarans,
            'ta_pendaftarans_diterima' => $ta_pendaftarans_diterima,
            'ta_pendaftarans_review' => $ta_pendaftarans_review,
            'ta_pendaftarans_revisi' => $ta_pendaftarans_revisi,
            'ta_seminars' => $ta_seminars,
            'ta_seminars_diterima' => $ta_seminars_diterima,
            'ta_seminars_review' => $ta_seminars_review,
            'ta_seminars_revisi' => $ta_seminars_revisi,
            'ta_ujians' => $ta_ujians,
            'ta_ujians_diterima' => $ta_ujians_diterima,
            'ta_ujians_review' => $ta_ujians_review,
            'ta_ujians_revisi' => $ta_ujians_revisi,
            // KP Data
            'kp_pengajuans' => $kp_pengajuans,
            'kp_pengajuans_diterima' => $kp_pengajuans_diterima,
            'kp_pengajuans_review' => $kp_pengajuans_review,
            'kp_pengajuans_revisi' => $kp_pengajuans_revisi,
            'kp_pengajuans_ditolak' => $kp_pengajuans_ditolak,
            'kp_pendaftarans' => $kp_pendaftarans,
            'kp_pendaftarans_diterima' => $kp_pendaftarans_diterima,
            'kp_pendaftarans_review' => $kp_pendaftarans_review,
            'kp_pendaftarans_revisi' => $kp_pendaftarans_revisi,
            'kp_seminars' => $kp_seminars,
            'kp_seminars_diterima' => $kp_seminars_diterima,
            'kp_seminars_review' => $kp_seminars_review,
            'kp_seminars_revisi' => $kp_seminars_revisi,
            'kp_pengumpulan_akhir' => $kp_pengumpulan_akhir,
            'kp_pengumpulan_akhir_diterima' => $kp_pengumpulan_akhir_diterima,
            'kp_pengumpulan_akhir_review' => $kp_pengumpulan_akhir_review,
            'kp_pengumpulan_akhir_revisi' => $kp_pengumpulan_akhir_revisi,
        ]);
    }

    /**
     * Dashboard Dosen - Menampilkan ringkasan bimbingan kedua sistem (TA dan KP)
     * UI sama persis dengan dashboard asli ekapta-ta dan ekapta-kp
     */
    public function dashboardDosen()
    {
        $dosen = \Illuminate\Support\Facades\Auth::guard('dosen')->user();

        // ============================================
        // DATA BIMBINGAN TUGAS AKHIR (TA)
        // Bimbingan TA terhubung ke mahasiswa, dosen pembimbing via pivot dosen_mahasiswas
        // ============================================
        // Ambil mahasiswa yang dibimbing dosen ini (TA)
        $mahasiswaIds = $dosen->mahasiswas()
            ->whereIn('dosen_mahasiswas.status', ['utama', 'pendamping'])
            ->pluck('mahasiswas.id')
            ->toArray();
        
        $ta_bimbingans = \App\Models\TA\Bimbingan::whereIn('mahasiswa_id', $mahasiswaIds)->get();
        $ta_bimbingans_review = \App\Models\TA\Bimbingan::whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('status', 'review')->get();
        $ta_bimbingans_diterima = \App\Models\TA\Bimbingan::whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('status', 'diterima')->get();
        $ta_bimbingans_revisi = \App\Models\TA\Bimbingan::whereIn('mahasiswa_id', $mahasiswaIds)
            ->where('status', 'revisi')->get();

        // ============================================
        // DATA BIMBINGAN KERJA PRAKTIK (KP)
        // Bimbingan KP terhubung ke mahasiswa, dosen pembimbing via pivot dosen_mahasiswas (status = pembimbing)
        // ============================================
        // Ambil mahasiswa yang dibimbing dosen ini (KP) - status 'pembimbing'
        $mahasiswaKpIds = $dosen->mahasiswasKP()
            ->whereIn('dosen_mahasiswas.status', ['pembimbing'])
            ->pluck('mahasiswas.id')
            ->toArray();
        
        $kp_bimbingans = \App\Models\KP\Bimbingan::whereIn('mahasiswa_id', $mahasiswaKpIds)->get();
        $kp_bimbingans_review = \App\Models\KP\Bimbingan::whereIn('mahasiswa_id', $mahasiswaKpIds)
            ->where('status', 'review')->get();
        $kp_bimbingans_diterima = \App\Models\KP\Bimbingan::whereIn('mahasiswa_id', $mahasiswaKpIds)
            ->where('status', 'diterima')->get();
        $kp_bimbingans_revisi = \App\Models\KP\Bimbingan::whereIn('mahasiswa_id', $mahasiswaKpIds)
            ->where('status', 'revisi')->get();

        return view('pages.dosen.dashboard.home', [
            'title' => 'Dashboard Dosen - EKAPTA',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarDosen',
            // TA Data
            'ta_bimbingans' => $ta_bimbingans,
            'ta_bimbingans_review' => $ta_bimbingans_review,
            'ta_bimbingans_diterima' => $ta_bimbingans_diterima,
            'ta_bimbingans_revisi' => $ta_bimbingans_revisi,
            // KP Data
            'kp_bimbingans' => $kp_bimbingans,
            'kp_bimbingans_review' => $kp_bimbingans_review,
            'kp_bimbingans_diterima' => $kp_bimbingans_diterima,
            'kp_bimbingans_revisi' => $kp_bimbingans_revisi,
        ]);
    }

    /**
     * Dashboard Himpunan - Langsung ke KP (Himpunan hanya untuk KP)
     */
    public function dashboardHimpunan()
    {
        // Himpunan HANYA untuk KP, set context langsung
        session(['ekapta_context' => 'kp']);

        return redirect()->route('kp.dashboard.himpunan');
    }

    /**
     * Switch context TA/KP via AJAX atau redirect
     */
    public function switchContext(Request $request, string $context)
    {
        if (!in_array($context, ['ta', 'kp'])) {
            return back()->with('error', 'Context tidak valid');
        }

        session(['ekapta_context' => $context]);

        // Redirect ke dashboard sesuai role dan context
        if (Auth::guard('mahasiswa')->check()) {
            return $context === 'kp'
                ? redirect()->route('kp.dashboard.mahasiswa')
                : redirect()->route('ta.dashboard.mahasiswa');
        }

        if (Auth::guard('dosen')->check()) {
            return $context === 'kp'
                ? redirect()->route('kp.dashboard.dosen')
                : redirect()->route('ta.dashboard.dosen');
        }

        if (Auth::guard('prodi')->check()) {
            return $context === 'kp'
                ? redirect()->route('kp.dashboard.prodi')
                : redirect()->route('ta.dashboard.prodi');
        }

        if (Auth::guard('admin')->check()) {
            return $context === 'kp'
                ? redirect()->route('kp.dashboard.admin')
                : redirect()->route('ta.dashboard.admin');
        }

        if (Auth::guard('himpunan')->check()) {
            // Himpunan hanya KP
            return redirect()->route('kp.dashboard.himpunan');
        }

        return redirect()->route('home');
    }
}
