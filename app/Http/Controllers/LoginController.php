<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function loginMahasiswa()
    {
        return view('pages.mahasiswa.login', [
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
        return view('pages.prodi.login', [
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
        return view('pages.admin.login', [
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
        return view('pages.dosen.login', [
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

    public function loginHimpunan()
    {
        return view('pages.himpunan.login', [
            'title' => 'Login Himpunan',
        ]);
    }

    public function cekHimpunan(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('himpunan')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard-himpunan');
        }

        return back()->with('error', 'User tidak ditemukan');
    }

    public function logoutMahasiswa()
    {
        Auth::guard('mahasiswa')->logout();
        return redirect()->route('login.mahasiswa');
    }

    public function logoutProdi()
    {
        Auth::guard('prodi')->logout();
        return redirect()->route('login.prodi');
    }

    public function logoutAdmin()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login.admin');
    }

    public function logoutDosen()
    {
        Auth::guard('dosen')->logout();
        return redirect()->route('login.dosen');
    }

    public function logoutHimpunan()
    {
        Auth::guard('himpunan')->logout();
        return redirect()->route('login.himpunan');
    }
}
