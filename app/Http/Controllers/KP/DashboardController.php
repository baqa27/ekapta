<?php

namespace App\Http\Controllers\KP;

// Import KP Models
use App\Models\KP\Bimbingan;
use App\Models\KP\Pendaftaran;
use App\Models\KP\Pengajuan;
use App\Models\KP\ReviewSeminar;
use App\Models\KP\Seminar;
use App\Models\KP\Jilid;

// Import Shared Models
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends \App\Http\Controllers\Controller
{

    public function dashboardMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => config('app.name'),
            'mahasiswa' => $mahasiswa,
            'active' => ''
        ];

        return view('kp.pages.mahasiswa.home', $data);
    }

    public function dashboardProdi()
    {
        $pengajuans = Pengajuan::all();
        $pengajuans_diterima = Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = Pengajuan::where('status', 'ditolak')->get();

        $bimbingans = Bimbingan::all();
        $bimbingans_diterima = Bimbingan::where('status', 'diterima')->get();
        $bimbingans_review = Bimbingan::where('status', 'review')->get();
        $bimbingans_revisi = Bimbingan::where('status', 'revisi')->get();

        $seminars = Seminar::all();
        $seminars_diterima = Seminar::where('is_valid', '1')->get();
        $seminars_review = Seminar::where('is_valid', '0')->get();
        $seminars_revisi = Seminar::where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (menggunakan model Jilid)
        $pengumpulan_akhir = Jilid::all();
        $pengumpulan_akhir_diterima = Jilid::where('status', Jilid::JILID_VALID)->orWhere('status', Jilid::JILID_SELESAI)->get();
        $pengumpulan_akhir_review = Jilid::where('status', Jilid::JILID_REVIEW)->get();
        $pengumpulan_akhir_revisi = Jilid::where('status', Jilid::JILID_REVISI)->get();

        return view('kp.pages.prodi.dashboard.home', [
            'title' => 'Dashboard KP - Prodi',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarProdi',
            'pengajuans' => $pengajuans,
            'pengajuans_diterima' => $pengajuans_diterima,
            'pengajuans_review' => $pengajuans_review,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
            'pengumpulan_akhir' => $pengumpulan_akhir,
            'pengumpulan_akhir_diterima' => $pengumpulan_akhir_diterima,
            'pengumpulan_akhir_revisi' => $pengumpulan_akhir_revisi,
            'pengumpulan_akhir_review' => $pengumpulan_akhir_review,
        ]);
    }

    public function dashboardAdmin()
    {
        $pengajuans = Pengajuan::all();
        $pengajuans_diterima = Pengajuan::where('status', 'diterima')->get();
        $pengajuans_revisi = Pengajuan::where('status', 'revisi')->get();
        $pengajuans_review = Pengajuan::where('status', 'review')->get();
        $pengajuans_ditolak = Pengajuan::where('status', 'ditolak')->get();

        $pendaftarans = Pendaftaran::all();
        $pendaftarans_diterima = Pendaftaran::where('status', 'diterima')->get();
        $pendaftarans_review = Pendaftaran::where('status', 'review')->get();
        $pendaftarans_revisi = Pendaftaran::where('status', 'revisi')->get();

        $seminars = Seminar::all();
        $seminars_diterima = Seminar::where('is_valid', '1')->get();
        $seminars_review = Seminar::where('is_valid', '0')->get();
        $seminars_revisi = Seminar::where('is_valid', '2')->get();

        // Pengumpulan Akhir KP (menggunakan model Jilid)
        $pengumpulan_akhir = Jilid::all();
        $pengumpulan_akhir_diterima = Jilid::where('status', Jilid::JILID_VALID)->orWhere('status', Jilid::JILID_SELESAI)->get();
        $pengumpulan_akhir_review = Jilid::where('status', Jilid::JILID_REVIEW)->get();
        $pengumpulan_akhir_revisi = Jilid::where('status', Jilid::JILID_REVISI)->get();

        $mahasiswas = Mahasiswa::all();
        $prodis = Prodi::all();
        $dosens = Dosen::all();
        $fakultas = Fakultas::all();

        return view('kp.pages.admin.dashboard.home', [
            'title' => 'Dashboard KP - Admin',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'pengajuans' => $pengajuans,
            'pengajuans_diterima' => $pengajuans_diterima,
            'pengajuans_review' => $pengajuans_review,
            'pengajuans_revisi' => $pengajuans_revisi,
            'pengajuans_ditolak' => $pengajuans_ditolak,
            'pendaftarans' => $pendaftarans,
            'pendaftarans_diterima' => $pendaftarans_diterima,
            'pendaftarans_review' => $pendaftarans_review,
            'pendaftarans_revisi' => $pendaftarans_revisi,
            'mahasiswas' => $mahasiswas,
            'dosens' => $dosens,
            'prodis' => $prodis,
            'fakultas' => $fakultas,
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
            'pengumpulan_akhir' => $pengumpulan_akhir,
            'pengumpulan_akhir_diterima' => $pengumpulan_akhir_diterima,
            'pengumpulan_akhir_revisi' => $pengumpulan_akhir_revisi,
            'pengumpulan_akhir_review' => $pengumpulan_akhir_review,
        ]);
    }

    public function dashboardDosen()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $bimbingans = $dosen->bimbingansKP;
        $bimbingans_diterima = $dosen->bimbingansKP()->where('status', 'diterima')->get();
        $bimbingans_review = $dosen->bimbingansKP()->where('status', 'review')->get();
        $bimbingans_revisi = $dosen->bimbingansKP()->where('status', 'revisi')->get();

        return view('kp.pages.dosen.dashboard.home', [
            'title' => 'Dashboard KP - Dosen',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
        ]);
    }

    /**
     * Dashboard Mahasiswa untuk Sistem Kerja Praktek (KP)
     * Alur: Pengajuan -> Pendaftaran -> Bimbingan -> Seminar KP -> Pengumpulan Akhir
     */
    public function dashboardMahasiswaKP()
    {
        // Set context session ke KP
        session(['ekapta_context' => 'kp']);
        
        // Pakai relationship KP (tabel dengan suffix _kp)
        $mahasiswa = Mahasiswa::with(['bimbingansKP','pengajuansKP','pendaftaransKP','seminarKP','jilid'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('kp.profile');
        }elseif (substr($mahasiswa->hp, 0, 2) !== '62') {
            return redirect()->route('kp.profile');
        }
        // Cari prodi berdasarkan kode atau nama prodi
        $prodiValue = Auth::guard('mahasiswa')->user()->prodi;
        $prodi = Prodi::where('kode', $prodiValue)->orWhere('namaprodi', $prodiValue)->first();
        
        $pengajuan_acc = $mahasiswa->pengajuansKP()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran_acc = $pengajuan_acc ? $pengajuan_acc->pendaftaran()->where('status', Pendaftaran::DITERIMA)->first() : null;

        $bimbingans_acc = $mahasiswa->bimbingansKP()->where('status', Bimbingan::DITERIMA)->get();
        $seminars_acc = $mahasiswa->seminarKP ? $mahasiswa->seminarKP->reviews()->where('status', ReviewSeminar::DITERIMA)->get() : null;

        // Cek bimbingan completed (semua bab sudah ACC)
        $is_bimbingan_completed = false;
        $bagians_count = 0;
        if ($prodi) {
            $bagians_count = count($prodi->bagiansKP()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get());
        }
        if ($bagians_count > 0 && count($bimbingans_acc) >= $bagians_count) {
            $is_bimbingan_completed = true;
        }

        // Cek seminar completed (is_lulus = true setelah seminar selesai)
        $is_seminar_completed = false;
        if ($mahasiswa->seminarKP){
            if($mahasiswa->seminarKP->is_lulus){
                $is_seminar_completed = true;
            }
        }

        // Cek pengumpulan akhir completed
        $is_pengumpulan_akhir_completed = false;
        if ($mahasiswa->jilidKP){
            if($mahasiswa->jilidKP->status == Jilid::JILID_SELESAI || $mahasiswa->jilidKP->is_completed == Jilid::JILID_COMPLETED){
                $is_pengumpulan_akhir_completed = true;
            }
        }

        return view('kp.pages.mahasiswa.dashboard.home-kp', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'pendaftaran_acc' => $pendaftaran_acc,
            'is_bimbingan_completed' => count($mahasiswa->bimbingansKP) != 0 ? $is_bimbingan_completed : false,
            'is_seminar_completed' => $mahasiswa->seminarKP ? $is_seminar_completed : false,
            'is_pengumpulan_akhir_completed' => $mahasiswa->jilidKP ? $is_pengumpulan_akhir_completed : false,
        ]);
    }

    public function dashboardMahasiswaJilid()
    {
        return "<center><h1>SEDANG DALAM TAHAP PENGEMBANGAN</h1></center>";
    }

    public function dashboardHimpunan()
    {
        $seminars = Seminar::all();
        $seminars_diterima = Seminar::where('is_valid', '1')->get();
        $seminars_review = Seminar::where('is_valid', '0')->get();
        $seminars_revisi = Seminar::where('is_valid', '2')->get();

        return view('kp.pages.himpunan.dashboard', [
            'title' => 'Dashboard Himpunan',
            'active' => 'dashboard',
            'module' => 'kp',
            'sidebar' => 'kp.partials.sidebarHimpunan',
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
        ]);
    }
}

