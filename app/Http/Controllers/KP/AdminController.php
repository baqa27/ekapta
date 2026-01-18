<?php

namespace App\Http\Controllers\KP;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends \App\Http\Controllers\Controller
{
    function account(){
        $admin = Auth::guard('admin')->user();

        $data = [
            'title' => 'Pengaturan Akun',
            'active' => '',
            'sidebar' => 'kp.partials.sidebarAdmin',
            'module' => 'kp',
            'admin' => $admin,
        ];

        return view('kp.pages.admin.account', $data);
    }

    function accountUpdate(Request $request, $id){
        $admin = Admin::findOrFail($id);

        $request->validate([
            'nama' => ['required', 'string'],
            'password' => ['required','string' ,'min:6'],
        ]);

        $admin->update([
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Akun berhasil diubah');
    }
}

