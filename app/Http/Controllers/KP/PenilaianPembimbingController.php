<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;

// Import KP Models
use App\Models\KP\Jilid;

// Import Shared Models
use App\Models\Dosen;
use App\Models\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianPembimbingController extends \App\Http\Controllers\Controller
{
    /**
     * Halaman daftar mahasiswa yang perlu dinilai oleh dosen pembimbing
     * Syarat: Seminar KP sudah selesai (is_lulus = 1) dan revisi seminar sudah beres
     */
    public function index()
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);

        // Ambil mahasiswa bimbingan yang seminarnya sudah selesai
        $mahasiswas = $dosen->mahasiswas()
            ->whereHas('seminar', function($q) {
                $q->where('is_lulus', 1);
            })
            ->with(['seminar', 'jilid', 'pengajuans' => function($q) {
                $q->where('status', 'diterima');
            }])
            ->get();

        return view('kp.pages.dosen.penilaian.index', [
            'title' => 'Penilaian Pembimbing',
            'active' => 'penilaian-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    /**
     * Form penilaian untuk mahasiswa tertentu
     */
    public function create($mahasiswa_id)
    {
        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $mahasiswa = Mahasiswa::with(['seminar', 'jilid', 'pengajuans', 'dosens'])->findOrFail($mahasiswa_id);

        // Cek apakah dosen ini adalah pembimbing mahasiswa
        $isPembimbing = $mahasiswa->dosens()
            ->where('dosen_id', $dosen->id)
            ->whereIn('status', ['pembimbing', 'utama'])
            ->exists();

        if (!$isPembimbing) {
            return back()->with('warning', 'Anda bukan dosen pembimbing mahasiswa ini');
        }

        // Cek apakah seminar sudah selesai
        if (!$mahasiswa->seminar || $mahasiswa->seminar->is_lulus != 1) {
            return back()->with('warning', 'Mahasiswa belum menyelesaikan seminar KP');
        }

        $pengajuan = $mahasiswa->pengajuansKP()->where('status', 'diterima')->first();

        return view('kp.pages.dosen.penilaian.create', [
            'title' => 'Form Penilaian Pembimbing',
            'active' => 'penilaian-kp',
            'sidebar' => 'kp.partials.sidebarDosen',
            'module' => 'kp',
            'mahasiswa' => $mahasiswa,
            'pengajuan' => $pengajuan,
            'jilid' => $mahasiswa->jilidKP,
        ]);
    }

    /**
     * Simpan nilai pembimbing
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'nilai_pembimbing' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $dosen = Dosen::findOrFail(Auth::guard('dosen')->user()->id);
        $mahasiswa = Mahasiswa::with(['jilidKP'])->findOrFail($request->mahasiswa_id);

        // Cek apakah dosen ini adalah pembimbing mahasiswa
        $isPembimbing = $mahasiswa->dosens()
            ->where('dosen_id', $dosen->id)
            ->whereIn('status', ['pembimbing', 'utama'])
            ->exists();

        if (!$isPembimbing) {
            return back()->with('warning', 'Anda bukan dosen pembimbing mahasiswa ini');
        }

        // Update atau buat jilid jika belum ada
        if ($mahasiswa->jilidKP) {
            $mahasiswa->jilidKP->update([
                'nilai_pembimbing' => $request->nilai_pembimbing,
                'catatan' => $request->catatan,
            ]);

            // Hitung nilai akhir jika semua nilai sudah ada
            $mahasiswa->jilidKP->hitungNilaiAkhir();
        }
        // Tidak otomatis buat jilid - mahasiswa harus submit sendiri di menu Jilid KP

        // Kirim email notifikasi ke mahasiswa
        if ($mahasiswa->email && $mahasiswa->email != '-') {
            AppHelper::instance()->send_mail([
                'mail' => $mahasiswa->email,
                'subject' => 'Nilai Pembimbing KP',
                'title' => 'EKAPTA',
                'message' => 'Dosen pembimbing telah memberikan nilai untuk Kerja Praktek Anda. Nilai: ' . $request->nilai_pembimbing,
            ]);
        }

        return redirect()->route('kp.penilaian.pembimbing.index')->with('success', 'Nilai pembimbing berhasil disimpan');
    }
}

