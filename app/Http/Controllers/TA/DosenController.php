<?php

namespace App\Http\Controllers\TA;

use App\Helpers\AppHelper;
use App\Imports\DosensImport;
use App\Models\Dosen;
use App\Models\Prodi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        $dosens = Dosen::all();
        return view('ta.pages.admin.dosen.dosen', [
            'title' => 'Master Data Dosen',
            'active' => 'dosen',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'dosens' => $dosens,
        ]);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new DosensImport, $request->file('file'));
            return back()->with('success', 'Data Dosen berhasil di Import');
        } catch (Exception $e) {
            return back()->with('warning', 'Data Dosen gagal di Import');
        }
    }

    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen_prodi_id = [];
        foreach ($dosen->prodis as $prodi){
            $dosen_prodi_id[] = $prodi->id;
        }

        if (count($dosen->prodis) == 0){
            $prodis = Prodi::all();
        }else{
            $prodis = Prodi::whereNotIn('id', $dosen_prodi_id)->get();
        }

        return view('ta.pages.admin.dosen.setting',[
            'title' => 'Setting Dosen',
            'active' => 'dosen',
            'module' => 'ta',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'ta',
            'dosen' => $dosen,
            'prodis' => $prodis,
        ]);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validatedData = $request->validate([
            'ttd' => [Rule::requiredIf(function () {
                if (empty($this->request->image)) {
                    return false;
                }
                return true;
            }), 'mimes:png,jpg,jpeg', 'max:300']
        ]);

        $dosen->update([
            'ttd' => AppHelper::instance()->uploadLampiran($request->ttd,'images'),
        ]);

        return back()->with('success','TTD Dosen berhasil di update.');
    }

    public function account(){
        $dosen = Auth::guard('dosen')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'partials.sidebarDosen',
            'module' => 'ta',
            'dosen' => $dosen,
        ];

        return view('ta.pages.dosen.account', $data);
    }

    public function accountUpdate(Request $request, $id){
        $dosen = Dosen::findOrFail($id);

        $request->validate([
            'password' => ['required','string' ,'min:6'],
        ]);

        $dosen->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    public function resetPassword($id){
        $dosen = Dosen::findOrFail($id);
        $dosen->update([
            'password' => Hash::make($dosen->nidn)
        ]);
        return back()->with('success', 'Password berhasil direset');
    }

    public function changeManual($id){
        $dosen = Dosen::findOrFail($id);
        $dosen->update([
            'is_manual' => $dosen->is_manual == 1 ? 0 : 1,
        ]);
        return back()->with('success', 'Password berhasil di'.$dosen->is_manual == 1 ? 'enable' : 'disable');
    }

}


