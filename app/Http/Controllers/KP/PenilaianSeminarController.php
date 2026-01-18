<?php

namespace App\Http\Controllers\KP;

// Import KP Models
use App\Models\KP\SesiSeminar;
use App\Models\KP\Seminar;
use App\Models\KP\ReviewSeminar;

use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class PenilaianSeminarController extends \App\Http\Controllers\Controller
{
    /**
     * Halaman penilaian seminar untuk dosen penguji (tanpa login)
     */
    public function index($token)
    {
        $sesi = SesiSeminar::where('token_penilaian', $token)->first();

        if (!$sesi) {
            return view('kp.pages.public.penilaian-seminar.not-found', [
                'title' => 'Link Tidak Valid',
                'message' => 'Link penilaian tidak ditemukan atau tidak valid.',
            ]);
        }

        if ($sesi->is_token_used) {
            return view('kp.pages.public.penilaian-seminar.expired', [
                'title' => 'Link Kadaluarsa',
                'message' => 'Link penilaian ini sudah digunakan dan tidak bisa dipakai lagi.',
                'used_at' => $sesi->token_used_at,
            ]);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('urutan_presentasi')
            ->get();

        return view('kp.pages.public.penilaian-seminar.index', [
            'title' => 'Penilaian Seminar KP',
            'sesi' => $sesi,
            'seminars' => $seminars,
        ]);
    }

    /**
     * Submit semua penilaian
     * KP: Hanya nilai, tidak ada status revisi dari penguji
     * Setelah dinilai, mahasiswa langsung upload nilai instansi
     */
    public function submit(Request $request, $token)
    {
        $sesi = SesiSeminar::where('token_penilaian', $token)->first();

        if (!$sesi || $sesi->is_token_used) {
            return response()->json([
                'success' => false,
                'message' => 'Link tidak valid atau sudah kadaluarsa.'
            ], 400);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)->get();
        $penilaian = $request->input('penilaian', []);

        // Validasi semua mahasiswa sudah dinilai
        foreach ($seminars as $seminar) {
            if (!isset($penilaian[$seminar->id]) || 
                !isset($penilaian[$seminar->id]['nilai']) ||
                $penilaian[$seminar->id]['nilai'] === '' ||
                $penilaian[$seminar->id]['nilai'] < 0 ||
                $penilaian[$seminar->id]['nilai'] > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua mahasiswa harus dinilai dengan nilai 0-100 sebelum submit.'
                ], 400);
            }
        }

        // Simpan penilaian
        foreach ($seminars as $seminar) {
            $data = $penilaian[$seminar->id];
            
            // Update nilai seminar - langsung selesai seminar (tidak ada revisi)
            $seminar->nilai_seminar = $data['nilai'];
            $seminar->status_seminar = Seminar::STATUS_SELESAI_SEMINAR;
            $seminar->is_lulus = 1;
            $seminar->catatan_penguji = $data['catatan'] ?? null;
            $seminar->save();

            // Simpan review dari penguji
            ReviewSeminar::create([
                'seminar_id' => $seminar->id,
                'dosen_id' => $sesi->dosen_penguji_id,
                'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                'status' => ReviewSeminar::DITERIMA,
                'status_hasil' => 'diterima',
                'nilai_angka' => $data['nilai'],
                'catatan_penguji' => $data['catatan'] ?? null,
                'is_dinilai' => true,
                'tanggal_acc' => now(),
            ]);

            // Kirim notifikasi ke mahasiswa
            if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'message' => "Seminar KP Anda telah selesai!<br>Nilai Seminar: <b>{$data['nilai']}</b><br><br>Silahkan login ke sistem untuk upload <b>Nilai KP dari Instansi</b> untuk menyelesaikan proses KP.",
                ]);
            }
        }

        // Invalidate token
        $sesi->invalidateToken();

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil disimpan. Terima kasih.'
        ]);
    }
}

