<?php

namespace App\Http\Controllers;

use App\Imports\BagiansImport;

// Import TA Models (default untuk master data bagian TA)
use App\Models\TA\Bagian;
use App\Models\TA\Bimbingan;
use App\Models\TA\Pendaftaran;

// Import Shared Models
use App\Models\Mahasiswa;
use App\Models\Prodi;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class BagianController extends Controller
{
    public function store(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        // $cekBagian = $prodi->bagians()->where('bagian', $request->bagian)->first();
        // if ($cekBagian) {
        //     return back()->with('warning', 'Nama bagian yang sama sudah dibuat');
        // }
        $validatedData = $request->validate([
            'bagian' => 'required',
            'tahun_masuk' => 'required',
        ]);
        $bagian = new Bagian;
        $bagian->bagian = $validatedData['bagian'];
        $bagian->tahun_masuk = $validatedData['tahun_masuk'];
        $prodi->bagians()->save($bagian);
        return back()->with('success', 'Bagian berhasil dibuat');
    }

    public function update(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);

        $validatedData = $request->validate([
            'bagian' => 'required',
            'tahun_masuk' => 'required',
        ]);

        $bagian->update([
            'bagian' => $validatedData['bagian'],
            'tahun_masuk' => $validatedData['tahun_masuk'],
            'is_seminar' => $request->is_seminar ? 1 : 0,
            'is_pendadaran' => $request->is_pendadaran ? 1 : 0,
        ]);

        return back()->with('success', 'Bagian berhasil diedit');
    }

    public function delete(Request $request)
    {
        $bagian = Bagian::findOrFail($request->id);

        // Cek apakah bagian sudah dipakai di bimbingan
        if (count($bagian->bimbingans) != 0) {
            return back()->with('warning', 'Tidak dapat menghapus bagian yang sudah digunakan di bimbingan');
        }

        $bagian->delete();
        return back()->with('success', 'Bagian berhasil dihapus');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new BagiansImport($request->prodi), $request->file('file'));
            return back()->with('success', 'Bagian berhasil diimport');
        } catch (Exception $e) {
            return back()->with('warning', 'Bagian gagal diimport');
        }
    }

    public function bagianActive(Request $request)
    {
        return back();
        $bagian = Bagian::findOrFail($request->id);
        $prodi = Prodi::findOrFail($request->prodi_id);
        $pendaftaransAcc = Pendaftaran::with(['mahasiswa'])
            ->where('status', 'diterima')
            ->whereHas('mahasiswa', function ($query) use ($prodi) {
                $query->where('prodi', $prodi->namaprodi);
            })
            ->get();
        if (count($pendaftaransAcc) == 0 || !$prodi) {
            return back()->with('warning', 'Pendaftaran mahasiswa tidak ditemukan');
        }
        foreach ($pendaftaransAcc as $pendaftaran) {
            $mahasiswa = $pendaftaran->mahasiswa()->where('prodi', $prodi->namaprodi)->first();
            if ($mahasiswa) {
                if ($mahasiswa->thmasuk == $bagian->tahun_masuk) {
                    foreach ($mahasiswa->dosens as $dosen) {
                        if ($dosen->pivot->status == 'utama') {
                            $bimbingan = Bimbingan::create([
                                'mahasiswa_id' => $mahasiswa->id,
                                'bagian_id' => $request->id,
                                'pembimbing' => 'utama',
                            ]);
                            $bimbingan->dosens()->attach([$dosen->id]);
                        } else if ($dosen->pivot->status == 'pendamping') {
                            $bimbingan = Bimbingan::create([
                                'mahasiswa_id' => $mahasiswa->id,
                                'bagian_id' => $request->id,
                                'pembimbing' => 'pendamping',
                            ]);
                            $bimbingan->dosens()->attach([$dosen->id]);
                        }
                    }
                }
            }
        }

        return back()->with('success', 'Bagian Bimbingan berhasil diaktifkan');
    }

    public function up($id)
    {
        $bagian = Bagian::with(['prodi'])->where('id', $id)->first();
        return $prodi = $bagian->prodi()->with(['bagians'])->get();
    }
}
