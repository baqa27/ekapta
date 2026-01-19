<?php

namespace App\Http\Controllers\TA;

use App\Imports\ProdisImport;
use App\Models\PresentaseNilai;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProdiController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $prodis = Prodi::orderBy('created_at', 'desc')->get();
        return view('ta.pages.admin.prodi.prodi', [
            'title' => 'Master Data Prodi',
            'active' => 'prodi',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'prodis' => $prodis,
        ]);
    }

    public function detail($id)
    {
        $prodi = Prodi::findOrFail($id);
        return view('ta.pages.admin.prodi.detail', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'prodi' => $prodi,
        ]);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new ProdisImport, $request->file('file'));
            return back()->with('success', 'Data Prodi behasil di Import');
        } catch (\Throwable $e) {
            return back()->with('warning', 'Data prodi gagal diimport');
        }
    }

    public function presentaseNilai($prodi_id)
    {
        $prodi = Prodi::findOrFail($prodi_id);
        $presentase_nilai = $prodi->presentase_nilai;

        return view('ta.pages.admin.prodi.presentase-nilai', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'prodi' => $prodi,
            'presentase_nilai' => $presentase_nilai ? $presentase_nilai : null,
        ]);
    }

    public function presentaseNilaiStore(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $presentase_nilai = $prodi->presentase_nilai;

        if ($request->presentase_1 + $request->presentase_2 + $request->presentase_3 + $request->presentase_4 != 100 || $request->bobot_penguji + $request->bobot_pembimbing != 100) {
            return back()->with('error', 'Total Presentase Nilai Harus 100%');
        }

        if ($presentase_nilai) {
            $presentase_nilai->update([
                'presentase_1' => $request->presentase_1,
                'presentase_2' => $request->presentase_2,
                'presentase_3' => $request->presentase_3,
                'presentase_4' => $request->presentase_4,
                'bobot_penguji' => $request->bobot_penguji,
                'bobot_pembimbing' => $request->bobot_pembimbing,
            ]);
        }

        PresentaseNilai::create([
            'prodi_id' => $prodi->id,
            'presentase_1' => $request->presentase_1,
            'presentase_2' => $request->presentase_2,
            'presentase_3' => $request->presentase_3,
            'presentase_4' => $request->presentase_4,
            'bobot_penguji' => $request->bobot_penguji,
            'bobot_pembimbing' => $request->bobot_pembimbing,
        ]);

        return back()->with('success', 'Presentase Nilai Berhasil Disimpan');
    }

    function account(){
        $prodi = Auth::guard('prodi')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'partials.sidebarProdi',
            'module' => 'ta',
            'prodi' => $prodi,
        ];

        return view('ta.pages.prodi.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $prodi = Prodi::findOrFail($id);

        $request->validate([
            'password' => ['required','string' ,'min:6'],
        ]);

        $prodi->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    function resetPassword($id){
        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'password' => Hash::make($prodi->kode)
        ]);
        return back()->with('success', 'Password berhasil direset');
    }
}


