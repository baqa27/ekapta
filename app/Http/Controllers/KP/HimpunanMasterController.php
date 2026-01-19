<?php

namespace App\Http\Controllers\KP;

use App\Models\Himpunan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HimpunanMasterController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $himpunans = Himpunan::with('prodi')->get();
        $prodis = Prodi::all();
        
        return view('kp.pages.admin.himpunan.index', [
            'title' => 'Data Himpunan',
            'active' => 'himpunan',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'kp',
            'himpunans' => $himpunans,
            'prodis' => $prodis,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:himpunans,username',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:6',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        Himpunan::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'prodi_id' => $request->prodi_id,
            'is_pendaftaran_seminar_open' => false,
        ]);

        return redirect()->route('himpunans')->with('success', 'Himpunan berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:himpunans,id',
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:himpunans,username,' . $request->id,
            'email' => 'nullable|email|max:255',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        $himpunan = Himpunan::findOrFail($request->id);
        $himpunan->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'prodi_id' => $request->prodi_id,
        ]);

        if ($request->filled('password')) {
            $himpunan->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('himpunans')->with('success', 'Himpunan berhasil diupdate');
    }

    public function delete(Request $request)
    {
        $himpunan = Himpunan::findOrFail($request->id);
        $himpunan->delete();

        return redirect()->route('himpunans')->with('success', 'Himpunan berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data); // Remove header row

        $imported = 0;
        foreach ($data as $row) {
            if (count($row) >= 5) {
                $prodi = Prodi::where('kode', $row[4])->first();
                
                if ($prodi) {
                    Himpunan::updateOrCreate(
                        ['username' => $row[1]],
                        [
                            'nama' => $row[0],
                            'email' => $row[2] ?: null,
                            'password' => Hash::make($row[3]),
                            'prodi_id' => $prodi->id,
                            'is_pendaftaran_seminar_open' => false,
                        ]
                    );
                    $imported++;
                }
            }
        }

        return redirect()->route('himpunans')->with('success', "Berhasil import $imported data himpunan");
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:himpunans,id',
            'biaya_seminar' => 'required|integer|min:0',
        ]);

        $himpunan = Himpunan::findOrFail($request->id);
        $himpunan->update([
            'biaya_seminar' => $request->biaya_seminar,
            'nama_rekening' => $request->nama_rekening,
            'nomor_rekening' => $request->nomor_rekening,
            'bank' => $request->bank,
            'nomor_dana' => $request->nomor_dana,
            'nomor_seabank' => $request->nomor_seabank,
        ]);

        return redirect()->route('himpunans')->with('success', 'Info pembayaran berhasil diupdate');
    }
}

