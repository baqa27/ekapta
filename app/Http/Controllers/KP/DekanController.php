<?php

namespace App\Http\Controllers\KP;

use App\Helpers\AppHelper;
use App\Imports\DekansImport;
use App\Models\Dekan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DekanController extends \App\Http\Controllers\Controller
{
    public function import(Request $request)
    {
        try {
            Excel::import(new DekansImport($request->fakultas), $request->file('file'));
            return back()->with('success', 'Data dekan berhasil diimport');
        } catch (Exception $e) {
            return back()->with('warning', 'Data dekan gagal diimport');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fakultas_id' => 'required',
            'nidn' => 'required',
            'namadekan' => 'required',
            'gelar' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg|max:300'
        ]);
        $validatedData['image'] = AppHelper::instance()->uploadLampiran($request->image, 'images');
        Dekan::create($validatedData);
        return back()->with('success', 'Dekan berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $dekan = Dekan::findOrFail($request->dekan);
        $validatedData = $request->validate([
            'image' => [Rule::requiredIf(function () {
                if (empty($this->request->image)) {
                    return false;
                }
                return true;
            }), 'mimes:png,jpg,jpeg', 'max:300']
        ]);

        if ($request->file('image')) {
            AppHelper::instance()->deleteLampiran($dekan->image);
            $validatedData['image'] = AppHelper::instance()->uploadLampiran($request->image, 'images');
            $dekan->update([
                'image' => $validatedData['image'],
            ]);
        }
        return back()->with('success', 'TTD Dekan berhasil disimpan');
    }

    public function enabled(Request $request)
    {
        try {
            $dekan = Dekan::findOrFail($request->dekan);
            $dekan->update([
                'status' => 'active',
            ]);
            return back()->with('success', 'Dekan berhasil diaktifkan');
        } catch (Exception $e) {
            return back()->with('warning', 'Dekan sudah diaktifkan');
        }
    }
    public function disabled(Request $request)
    {
        $dekan = Dekan::findOrFail($request->dekan);
        $dekan->update([
            'status' => null,
        ]);
        return back()->with('success', 'Dekan berhasil dinonaktifkan');
    }

    public function delete(Request $request)
    {
        $dekan = Dekan::findOrFail($request->dekan);
        if ($dekan->status == 'active') {
            return back()->with('warning', 'Dekan tidak bisa dihapus');
        }
        $dekan->delete();
        return back()->with('success', 'Dekan berhasil dihapus');
    }
}

