<?php

namespace App\Http\Controllers\TA;

use App\Models\Admin;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends \App\Http\Controllers\Controller
{

    public function loginMahasiswa()
    {
        return view('ta.pages.mahasiswa.login', [
            'title' => 'Login Mahasiswa',
        ]);
    }

    public function cekMahasiswa(Request $request)
    {
        $credentials = $request->validate([
            'nim' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('mahasiswa')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-mahasiswa');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginProdi()
    {
        return view('ta.pages.prodi.login', [
            'title' => 'Login Prodi',
        ]);
    }

    public function cekProdi(Request $request)
    {
        $credentials = $request->validate([
            'kode' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('prodi')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-prodi');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginAdmin()
    {
        return view('ta.pages.admin.login', [
            'title' => 'Login Admin',
        ]);
    }

    public function cekAdmin(Request $request)
    {
        $request->validate([
            'kode' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt(['kode' => $request->kode, 'password' => $request->password, 'type' => Admin::TYPE_SUPER_ADMIN])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-admin');
        } elseif (Auth::guard('admin')->attempt(['kode' => $request->kode, 'password' => $request->password, 'type' => Admin::TYPE_ADMIN_FOTOCOPY])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-fotokopi');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function loginDosen()
    {
        return view('ta.pages.dosen.login', [
            'title' => 'Login Dosen',
        ]);
    }

    public function cekDosen(Request $request)
    {
        $credentials = $request->validate([
            'nidn' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('dosen')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-dosen');
        }

        return back()->with('error', 'User tidak ditemukan');
    }
}

