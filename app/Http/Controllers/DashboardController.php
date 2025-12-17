<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Pendaftaran;
use App\Models\Pengajuan;
use App\Models\Prodi;
use App\Models\ReviewSeminar;
use App\Models\Seminar;
use App\Models\Jilid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardMahasiswa()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => config('app.name'),
            'mahasiswa' => $mahasiswa,
            'active' => ''
        ];

        return view('pages.mahasiswa.home', $data);
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

        return view('pages.prodi.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarProdi',
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

        return view('pages.admin.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarAdmin',
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
        $bimbingans = $dosen->bimbingans;
        $bimbingans_diterima = $dosen->bimbingans()->where('status', 'diterima')->get();
        $bimbingans_review = $dosen->bimbingans()->where('status', 'review')->get();
        $bimbingans_revisi = $dosen->bimbingans()->where('status', 'revisi')->get();

        return view('pages.dosen.dashboard.home', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarDosen',
            'bimbingans' => $bimbingans,
            'bimbingans_diterima' => $bimbingans_diterima,
            'bimbingans_review' => $bimbingans_review,
            'bimbingans_revisi' => $bimbingans_revisi,
        ]);
    }

    /**
     * Dashboard Mahasiswa untuk Sistem Kerja Praktik (KP)
     * Alur: Pengajuan -> Pendaftaran -> Bimbingan -> Seminar KP -> Pengumpulan Akhir
     */
    public function dashboardMahasiswaTA()
    {
        $mahasiswa = Mahasiswa::with(['bimbingans','pengajuans','pendaftarans','seminar','jilid'])->findOrFail(Auth::guard('mahasiswa')->user()->id);
        if($mahasiswa->email == '-'){
            return redirect()->route('profile');
        }elseif (substr($mahasiswa->hp, 0, 2) !== '62') {
            return redirect()->route('profile');
        }
        $prodi = Prodi::where('namaprodi', Auth::guard('mahasiswa')->user()->prodi)->first();
        $pengajuan_acc = $mahasiswa->pengajuans()->where('status', Pengajuan::DITERIMA)->first();
        $pendaftaran_acc = $pengajuan_acc ? $pengajuan_acc->pendaftaran()->where('status', Pendaftaran::DITERIMA)->first() : null;

        $bimbingans_acc = $mahasiswa->bimbingans()->where('status', Bimbingan::DITERIMA)->get();
        $seminars_acc = $mahasiswa->seminar ? $mahasiswa->seminar->reviews()->where('status', ReviewSeminar::DITERIMA)->get() : null;

        // Cek bimbingan completed (semua bab sudah ACC)
        $is_bimbingan_completed = false;
        $bagians_count = count($prodi->bagians()->where("tahun_masuk", "LIKE", "%" . $mahasiswa->thmasuk . "%")->get());
        if ($bagians_count > 0 && count($bimbingans_acc) >= $bagians_count) {
            $is_bimbingan_completed = true;
        }

        // Cek seminar completed (is_lulus = true setelah seminar selesai)
        $is_seminar_completed = false;
        if ($mahasiswa->seminar){
            if($mahasiswa->seminar->is_lulus){
                $is_seminar_completed = true;
            }
        }

        // Cek pengumpulan akhir completed (untuk KP, ini menggantikan ujian pendadaran)
        $is_pengumpulan_akhir_completed = false;
        if ($mahasiswa->jilid){
            if($mahasiswa->jilid->status == Jilid::JILID_SELESAI || $mahasiswa->jilid->is_completed == Jilid::JILID_COMPLETED){
                $is_pengumpulan_akhir_completed = true;
            }
        }

        return view('pages.mahasiswa.dashboard.home-kp', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'mahasiswa' => $mahasiswa,
            'pengajuan_acc' => $pengajuan_acc,
            'pendaftaran_acc' => $pendaftaran_acc,
            'is_bimbingan_completed' => count($mahasiswa->bimbingans) != 0 ? $is_bimbingan_completed : false,
            'is_seminar_completed' => $mahasiswa->seminar ? $is_seminar_completed : false,
            'is_pengumpulan_akhir_completed' => $mahasiswa->jilid ? $is_pengumpulan_akhir_completed : false,
        ]);
    }

    public function dashboardMahasiswaKP()
    {
        return "<center><h1>SEDANG DALAM TAHAP PENGEMBANGAN</h1></center>";
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

        return view('pages.himpunan.dashboard', [
            'title' => 'Dashboard Himpunan',
            'active' => 'dashboard',
            'sidebar' => 'partials.sidebarHimpunan',
            'seminars' => $seminars,
            'seminars_diterima' => $seminars_diterima,
            'seminars_revisi' => $seminars_revisi,
            'seminars_review' => $seminars_review,
        ]);
    }
}
