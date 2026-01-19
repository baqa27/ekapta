<?php

namespace App\Http\Controllers\TA;

use App\Imports\MahasiswaDetailsImport;
use App\Imports\MahasiswasImport;
use App\Models\Mahasiswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $mahasiswas = Mahasiswa::orderBy('thmasuk','desc')->get();
        return view('ta.pages.admin.mahasiswa.mahasiswa', [
            'title' => 'Master Data Mahasiswa',
            'active' => 'mahasiswa',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'mahasiswas' => $mahasiswas,
        ]);
    }

    public function profile()
    {
        $mahasiswa = Mahasiswa::where('nim', Auth::guard('mahasiswa')->user()->nim)->first();
        return view('ta.pages.mahasiswa.profile', [
            'title' => 'Profil Mahasiswa',
            'active' => 'profile',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request)
    {
        $mahasiswa = Mahasiswa::findOrFail($request->id);
        $validatedData = $request->validate([
            'email' => ['required', 'email:dns'],
            'hp' => 'required',
            'alamat' => 'required',
        ]);
        $mahasiswa->update($validatedData);
        return back()->with('success', 'Profil berhasil diupdate');
    }

    public function import(Request $request)
    {
        //try {
            Excel::import(new MahasiswasImport, $request->file('file'));
            return back()->with('success', 'Data Mahasiswa berhasil di Import');
        //} catch (Exception $e) {
        //    return back()->with('warning', 'Data Mahasiswa gagal di Import');
        //}
    }

    public function importDetail(Request $request)
    {
        try {
            Excel::import(new MahasiswaDetailsImport, $request->file('file'));
            return back()->with('success', 'Data semester dan status mahasiswa berhasil di import');
        } catch (Exception $e) {
            return back()->with('warning', 'Data semester dan status mahasiswa gagal di import');
        }
    }

    function account(){
        $mahasiswa = Auth::guard('mahasiswa')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => 'profile',
            'module' => 'ta',
            'mahasiswa' => $mahasiswa,
        ];

        return view('ta.pages.mahasiswa.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $mahasiwa = Mahasiswa::findOrFail($id);

        $request->validate([
           'password' => ['required','string' ,'min:6'],
        ]);

        $mahasiwa->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    function resetPassword($id){
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update([
            'password' => Hash::make($mahasiswa->nim)
        ]);
        return back()->with('success', 'Password berhasil direset');
    }
}


