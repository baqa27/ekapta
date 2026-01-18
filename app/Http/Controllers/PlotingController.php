<?php

namespace App\Http\Controllers;

// Import TA Models (default untuk ploting TA)
use App\Models\TA\ReviewSeminar;
use App\Models\TA\Seminar;

// Import Shared Models
use App\Models\Dosen;
use App\Models\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlotingController extends Controller
{

    /*
    STATUS DOSEN
        pembimbing = Dosen pembimbing KP (1 dosen)
        penguji = Dosen penguji
    */

    public function plotingPembimbing(Request $request)
    {
        // Validate: KP hanya butuh 1 dosen pembimbing
        $request->validate([
            'dosen_pembimbing' => 'required|exists:dosens,id',
            'nim' => 'required',
        ], [
            'dosen_pembimbing.required' => 'Pilih dosen pembimbing',
        ]);

        Dosen::findOrFail($request->dosen_pembimbing);
        $mahasiswa = Mahasiswa::where(['nim' => $request->nim])->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Mahasiswa tidak ditemukan');
        }

        if (count($mahasiswa->bimbingans) == 0) {
            // Clear existing pembimbing if any
            DB::table('dosen_mahasiswas')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->whereIn('status', ['utama', 'pendamping', 'pembimbing'])
                ->delete();

            // Attach new pembimbing (single dosen for KP)
            $mahasiswa->dosens()->attach([
                $request->dosen_pembimbing => ['status' => 'pembimbing'],
            ]);

            return back()->with('success', 'Ploting dosen pembimbing berhasil');
        } else {
            return back()->with('warning', 'Mahasiswa sudah memiliki bimbingan aktif');
        }
    }

    public function plotingPenguji(Request $request)
    {
        $seminar = Seminar::findOrFail($request->seminar_id);

        $reviews_check = $seminar->reviews()->whereIn('status', [ReviewSeminar::DITERIMA, ReviewSeminar::REVISI])->where('dosen_status','penguji')->get();

        if (count($reviews_check) != 0){
            return back()->with('warning', 'Dosen penguji sudah di ploting');
        }

        $dosens_penguji = $seminar->reviews()->where('dosen_status', ReviewSeminar::DOSEN_PENGUJI)->get();

        if ($dosens_penguji){
            foreach ($dosens_penguji as $dosen){
                $dosen->delete();
            }
        }

        for($i = 0; $i < count($request->dosen_penguji); $i++){
           if ($request->dosen_penguji[$i] != null) {
                ReviewSeminar::create([
                    'seminar_id' => $seminar->id,
                    'dosen_id' => $request->dosen_penguji[$i],
                    'dosen_status' => ReviewSeminar::DOSEN_PENGUJI,
                    'status' => ReviewSeminar::REVIEW,
                ]);
           }
        }

        return back()->with('success', 'Ploting dosen penguji berhasil.');
    }
}
