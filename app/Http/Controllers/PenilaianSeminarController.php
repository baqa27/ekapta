<?php

namespace App\Http\Controllers;

use App\Models\SesiSeminar;
use App\Models\Seminar;
use App\Models\ReviewSeminar;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class PenilaianSeminarController extends Controller
{
    /**
     * Halaman penilaian seminar untuk dosen penguji (tanpa login)
     */
    public function index($token)
    {
        $sesi = SesiSeminar::where('token_penilaian', $token)->first();

        if (!$sesi) {
            return view('pages.public.penilaian-seminar.not-found', [
                'title' => 'Link Tidak Valid',
                'message' => 'Link penilaian tidak ditemukan atau tidak valid.',
            ]);
        }

        if ($sesi->is_token_used) {
            return view('pages.public.penilaian-seminar.expired', [
                'title' => 'Link Kadaluarsa',
                'message' => 'Link penilaian ini sudah digunakan dan tidak bisa dipakai lagi.',
                'used_at' => $sesi->token_used_at,
            ]);
        }

        $seminars = Seminar::where('sesi_seminar_id', $sesi->id)
            ->with(['mahasiswa', 'pengajuan'])
            ->orderBy('urutan_presentasi')
            ->get();

        return view('pages.public.penilaian-seminar.index', [
            'title' => 'Penilaian Seminar KP',
            'sesi' => $sesi,
            'seminars' => $seminars,
        ]);
    }

    /**
     * Submit semua penilaian
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
                !isset($penilaian[$seminar->id]['status'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua mahasiswa harus dinilai sebelum submit.'
                ], 400);
            }
        }

        // Simpan penilaian
        foreach ($seminars as $seminar) {
            $data = $penilaian[$seminar->id];
            
            // Update nilai seminar
            $seminar->nilai_seminar = $data['nilai'];
            
            if ($data['status'] === 'diterima') {
                $seminar->status_seminar = Seminar::STATUS_SELESAI_SEMINAR;
                $seminar->is_lulus = 1;
            } else {
                $seminar->status_seminar = Seminar::STATUS_REVISI_PASCA;
                $seminar->is_lulus = 0;
            }
            
            $seminar->save();

            // Simpan review dari penguji
            ReviewSeminar::create([
                'seminar_id' => $seminar->id,
                'dosen_id' => $sesi->dosen_penguji_id,
                'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                'status' => $data['status'] === 'diterima' ? ReviewSeminar::DITERIMA : ReviewSeminar::REVISI,
                'status_hasil' => $data['status'],
                'nilai_angka' => $data['nilai'],
                'catatan_penguji' => $data['catatan'] ?? null,
                'is_dinilai' => true,
                'tanggal_acc' => now(),
            ]);

            // Kirim notifikasi ke mahasiswa
            if ($seminar->mahasiswa->email && $seminar->mahasiswa->email != '-') {
                $statusText = $data['status'] === 'diterima' ? 'DITERIMA' : 'REVISI';
                AppHelper::instance()->send_mail([
                    'mail' => $seminar->mahasiswa->email,
                    'message' => "Hasil Seminar KP Anda: <b>$statusText</b><br>Nilai: <b>{$data['nilai']}</b><br>Silahkan cek dashboard untuk detail lebih lanjut.",
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
