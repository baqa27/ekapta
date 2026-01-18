<?php

namespace App\Http\Controllers\KP;

use App\Imports\DosenProdisImport;
use App\Models\Dosen;
use App\Models\DosenProdi;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DosenProdiController extends \App\Http\Controllers\Controller
{

    public function import(Request $request)
    {
        try {
            Excel::import(new DosenProdisImport, $request->file('file'));
            return back()->with('success', 'Data penugasan berhasil di Import');
        }catch (Exception $e){
            return back()->with('warning', 'Data penugasan gagal di Import');
        }
    }

    public function update(Request $request, $dosen)
    {
        $dosen = Dosen::findOrFail($dosen);

        $dosen_prodis = DosenProdi::where('dosen_id', $dosen->id)->get();

        if (count($dosen_prodis) != 0){
            foreach ($dosen_prodis as $dosen_prodi){
                $query = 'DELETE FROM dosen_prodis where dosen_id = ?';
                DB::delete($query, [$dosen_prodi->dosen_id]);
            }
        }

        if ($request->prodis){
            foreach ($request->prodis as $prodi_id){
                $prodi = Prodi::findOrfail($prodi_id);
                $dosen->prodis()->attach([
                    $prodi->id => [
                        'kode' => $prodi->kode,
                        'nidn' => $dosen->nidn,
                        'updated_at' => now(),
                    ]
                ]);
            }
        }

        return back()->with('success', 'Penugasan Dosen Berhasil Di update');
    }

}

