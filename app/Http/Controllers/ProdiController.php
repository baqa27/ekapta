<?php

namespace App\Http\Controllers;

use App\Imports\ProdisImport;
use App\Models\PresentaseNilai;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ProdiController extends Controller
{

    public function index()
    {
        $prodis = Prodi::orderBy('created_at', 'desc')->get();
        return view('pages.admin.prodi.prodi', [
            'title' => 'Master Data Prodi',
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodis' => $prodis,
        ]);
    }

    public function detail($id)
    {
        $prodi = Prodi::with(['bagians.bimbingans', 'bagiansKP.bimbingans'])->findOrFail($id);
        return view('pages.admin.prodi.detail', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodi' => $prodi,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:prodis,kode',
            'namaprodi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:10',
            'password' => 'required|string|min:6',
        ]);

        Prodi::create([
            'kode' => $request->kode,
            'namaprodi' => $request->namaprodi,
            'jenjang' => $request->jenjang,
            'kodekaprodi' => $request->kodekaprodi,
            'password' => Hash::make($request->password),
            'fakultas_id' => $request->fakultas_id,
        ]);

        return redirect()->route('prodis')->with('success', 'Prodi berhasil ditambahkan');
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

        return view('pages.admin.prodi.presentase-nilai', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
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

    /**
     * Presentase Nilai KP - Show form
     */
    public function presentaseNilaiKp($prodi_id)
    {
        $prodi = Prodi::findOrFail($prodi_id);
        $presentase_nilai = $prodi->presentase_nilai_kp;

        return view('pages.admin.prodi.presentase-nilai-kp', [
            'title' => 'Prodi: ' . $prodi->namaprodi,
            'active' => 'prodi',
            'sidebar' => 'partials.sidebarAdmin',
            'prodi' => $prodi,
            'presentase_nilai' => $presentase_nilai ? $presentase_nilai : null,
        ]);
    }

    /**
     * Presentase Nilai KP - Store/Update
     */
    public function presentaseNilaiKpStore(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi_id);
        $presentase_nilai = $prodi->presentase_nilai_kp;

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
            return back()->with('success', 'Presentase Nilai KP Berhasil Diupdate');
        }

        \App\Models\KP\PresentaseNilai::create([
            'prodi_id' => $prodi->id,
            'presentase_1' => $request->presentase_1,
            'presentase_2' => $request->presentase_2,
            'presentase_3' => $request->presentase_3,
            'presentase_4' => $request->presentase_4,
            'bobot_penguji' => $request->bobot_penguji,
            'bobot_pembimbing' => $request->bobot_pembimbing,
        ]);

        return back()->with('success', 'Presentase Nilai KP Berhasil Disimpan');
    }

    function account(){
        $prodi = Auth::guard('prodi')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'partials.sidebarProdi',
            'prodi' => $prodi,
        ];

        return view('pages.prodi.account', $data);
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
