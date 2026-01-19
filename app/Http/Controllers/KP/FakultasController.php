<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Imports\FakultasImport;
use App\Models\Fakultas;
use App\Models\Prodi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class FakultasController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $fakultass = Fakultas::all();
        return view('kp.pages.admin.fakultas.fakultas', [
            'title' => 'Master Data Fakultas',
            'active' => 'fakultas',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'kp',
            'fakultass' => $fakultass,
        ]);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new FakultasImport, $request->file('file'));
            return back()->with('success', 'Data fakultas berhasil diimport');
        } catch (Exception $e) {
            return back()->with('warning', 'Data fakultas gagal diimport');
        }
    }

    public function setting($fakultas)
    {
        $fakultas = Fakultas::findOrFail($fakultas);
        $prodis = Prodi::where('fakultas_id', null)->get();
        $dekanActive =   $fakultas->dekans()->where('status', 'active')->first();
        return view('kp.pages.admin.fakultas.setting', [
            'title' => '' . $fakultas->namafakultas,
            'active' => 'fakultas',
            'sidebar' => 'partials.sidebarAdmin',
            'module' => 'kp',
            'fakultas' => $fakultas,
            'prodis' => $prodis,
            'dekanActive' => $dekanActive != null ? $dekanActive : null,
        ]);
    }

    public function addProdi(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi);
        $prodi->update([
            'fakultas_id' => $request->fakultas,
        ]);
        return back()->with('success', 'Prodi berhasil ditambahkan');
    }

    public function deleteProdi(Request $request)
    {
        $prodi = Prodi::findOrFail($request->prodi);
        $prodi->update([
            'fakultas_id' => null,
        ]);
        return back()->with('success', 'Prodi berhasil dihapus');
    }

    public function update(Request $request)
    {
        $fakultas = Fakultas::findOrFail($request->fakultas);
        $validatedData = $request->validate([
            'image' => [Rule::requiredIf(function () {
                if (empty($this->request->image)) {
                    return false;
                }
                return true;
            }), 'mimes:png,jpg,jpeg', 'max:300']
        ]);

        if ($request->file('image')) {
            AppHelper::instance()->deleteLampiran($fakultas->image);
            $validatedData['image'] = AppHelper::instance()->uploadLampiran($request->image, 'images');
            $fakultas->update([
                'image' => $validatedData['image'],
            ]);
        }

        return back()->with('success', 'TTD fakultas berhasil disimpan');
    }
}

