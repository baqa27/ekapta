<?php

/**
 * EKAPTA Unified Routes
 *
 * File ini hanya berisi route untuk:
 * - Login (unified untuk semua role)
 * - Dashboard pilihan TA/KP
 * - Master data (shared antara TA dan KP)
 *
 * Route spesifik TA ada di routes/ta.php
 * Route spesifik KP ada di routes/kp.php
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenProdiController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\HimpunanMasterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PlotingController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home & Login Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'login'])->name('login');

// Login User
Route::get('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa')->middleware('isMahasiswaLogin');
Route::post('/login/mahasiswa', [LoginController::class, 'cekMahasiswa'])->name('cek.mahasiswa')->middleware('isMahasiswaLogin');
Route::get('/login/prodi', [LoginController::class, 'loginProdi'])->name('login.prodi')->middleware('isProdiLogin');
Route::post('/login/prodi', [LoginController::class, 'cekProdi'])->name('cek.prodi')->middleware('isProdiLogin');
Route::get('/login/dosen', [LoginController::class, 'loginDosen'])->name('login.dosen')->middleware('isDosenLogin');
Route::post('/login/dosen', [LoginController::class, 'cekDosen'])->name('cek.dosen')->middleware('isDosenLogin');
Route::get('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin')->middleware('isAdminLogin');
Route::post('/login/admin', [LoginController::class, 'cekAdmin'])->name('cek.admin')->middleware('isAdminLogin');
Route::get('/login/himpunan', [LoginController::class, 'loginHimpunan'])->name('login.himpunan');
Route::post('/login/himpunan', [LoginController::class, 'cekHimpunan'])->name('cek.himpunan');

// Logout Routes
Route::get('/logout-mahasiswa', [LoginController::class, 'logoutMahasiswa'])->name('logout.mahasiswa');
Route::get('/logout-prodi', [LoginController::class, 'logoutProdi'])->name('logout.prodi');
Route::get('/logout-admin', [LoginController::class, 'logoutAdmin'])->name('logout.admin');
Route::get('/logout-dosen', [LoginController::class, 'logoutDosen'])->name('logout.dosen');
Route::get('/logout-himpunan', [LoginController::class, 'logoutHimpunan'])->name('logout.himpunan');

/*
|--------------------------------------------------------------------------
| Dashboard Routes - Halaman Pilihan TA/KP
|--------------------------------------------------------------------------
*/

// Dashboard Utama - Halaman Pilihan TA/KP
Route::get('/dashboard-mahasiswa', [DashboardController::class, 'dashboardMahasiswa'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');
Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');
Route::get('/dashboard-himpunan', [DashboardController::class, 'dashboardHimpunan'])->name('dashboard.himpunan')->middleware('isHimpunan');

// Legacy routes - redirect ke sistem baru
Route::get('/dashboard-mahasiswa-kp', fn() => redirect()->route('kp.dashboard.mahasiswa'))->name('dashboard.mahasiswa.kp')->middleware('isMahasiswa');
Route::get('/dashboard-mahasiswa-ta', fn() => redirect()->route('ta.dashboard.mahasiswa'))->name('dashboard.mahasiswa.ta')->middleware('isMahasiswa');
Route::get('/dashboard-mahasiswa-jilid', fn() => redirect()->route('ta.jilid.mahasiswa'))->name('dashboard.mahasiswa.jilid')->middleware('isMahasiswa');
Route::get('/dashboard-fotokopi', fn() => redirect()->route('kp.pengumpulan-akhir.index'))->name('dashboard.fotokopi')->middleware('isAdminFotokopi');

/*
|--------------------------------------------------------------------------
| Master Data Routes - Shared antara TA dan KP
|--------------------------------------------------------------------------
*/

// Prodi
Route::get('/prodis', [ProdiController::class, 'index'])->name('prodis')->middleware('isAdmin');
Route::get('/prodi/{id}', [ProdiController::class, 'detail'])->middleware('isAdmin');
Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store')->middleware('isAdmin');
Route::post('/prodi/import', [ProdiController::class, 'import'])->name('prodi.import')->middleware('isAdmin');
Route::get('/prodi/presentase-nilai/{id}', [ProdiController::class, 'presentaseNilai'])->name('prodi.presentase.nilai')->middleware('isAdmin');
Route::post('/prodi/presentase-nilai/store', [ProdiController::class, 'presentaseNilaiStore'])->name('prodi.presentase.nilai.store')->middleware('isAdmin');
Route::get('/prodi/reset-password/{id}', [ProdiController::class, 'resetPassword'])->name('prodi.reset.password')->middleware('isAdmin');

// Prodi Account
Route::get('prodi/account', [ProdiController::class, 'account'])->name('prodi.account')->middleware('isProdi');
Route::put('prodi/account/{id}', [ProdiController::class, 'accountUpdate'])->name('prodi.account.update')->middleware('isProdi');

// Mahasiswa
Route::get('mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('profile')->middleware('isMahasiswa');
Route::get('mahasiswa/account', [MahasiswaController::class, 'account'])->name('mahasiswa.account')->middleware('isMahasiswa');
Route::put('mahasiswa/account/{id}', [MahasiswaController::class, 'accountUpdate'])->name('mahasiswa.account.update')->middleware('isMahasiswa');
Route::post('profile/update', [MahasiswaController::class, 'update'])->name('profile.update')->middleware('isMahasiswa');
Route::get('mahasiswas', [MahasiswaController::class, 'index'])->name('mahasiswas')->middleware('isAdmin');
Route::post('mahasiswa/store', [MahasiswaController::class, 'store'])->name('mahasiswa.store')->middleware('isAdmin');
Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import')->middleware('isAdmin');
Route::post('mahasiswa/detail/import', [MahasiswaController::class, 'importDetail'])->name('mahasiswa.detail.import')->middleware('isAdmin');
Route::get('mahasiswa/reset-password/{id}', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset.password')->middleware('isAdmin');

// Dosen
Route::get('/dosens', [DosenController::class, 'index'])->name('dosens')->middleware('isAdmin');
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store')->middleware('isAdmin');
Route::post('/dosen/import', [DosenController::class, 'import'])->name('dosen.import')->middleware('isAdmin');
Route::get('dosen/{id}', [DosenController::class, 'edit'])->name('dosen.edit')->middleware('isAdmin');
Route::put('dosen/{id}', [DosenController::class, 'update'])->name('dosen.update')->middleware('isAdmin');
Route::get('/dosen/change-manual/{id}', [DosenController::class, 'changeManual'])->name('dosen.change.manual')->middleware('isAdmin');
Route::get('/dosen/reset-password/{id}', [DosenController::class, 'resetPassword'])->name('dosen.reset.password')->middleware('isAdmin');

// Dosen Account
Route::get('dosen/account', [DosenController::class, 'account'])->name('dosen.account')->middleware('isDosen');
Route::put('dosen/account/{id}', [DosenController::class, 'accountUpdate'])->name('dosen.account.update')->middleware('isDosen');

// Dosen Prodi
Route::post('/dosen-prodi/import', [DosenProdiController::class, 'import'])->name('dosen.prodi.import')->middleware('isAdmin');
Route::put('/dosen-prodi/update/{dosen}', [DosenProdiController::class, 'update'])->name('dosen.prodi.update')->middleware('isAdmin');

// Fakultas
Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas')->middleware('isAdmin');
Route::post('/fakultas/import', [FakultasController::class, 'import'])->name('fakultas.import')->middleware('isAdmin');
Route::post('/fakultas/update', [FakultasController::class, 'update'])->name('fakultas.update')->middleware('isAdmin');
Route::get('/fakultas/setting/{fakultas}', [FakultasController::class, 'setting'])->middleware('isAdmin');
Route::post('/fakultas/add/prodi', [FakultasController::class, 'addProdi'])->name('fakultas.add.prodi')->middleware('isAdmin');
Route::post('/fakultas/delete/prodi', [FakultasController::class, 'deleteProdi'])->name('fakultas.delete.prodi')->middleware('isAdmin');

// Dekan
Route::post('/dekan/store', [DekanController::class, 'store'])->name('dekan.store')->middleware('isAdmin');
Route::post('/dekan/import', [DekanController::class, 'import'])->name('dekan.import')->middleware('isAdmin');
Route::post('/dekan/update', [DekanController::class, 'update'])->name('dekan.update')->middleware('isAdmin');
Route::post('/dekan/delete', [DekanController::class, 'delete'])->name('dekan.delete')->middleware('isAdmin');
Route::post('/dekan/enabled', [DekanController::class, 'enabled'])->name('dekan.enabled')->middleware('isAdmin');
Route::post('/dekan/disabled', [DekanController::class, 'disabled'])->name('dekan.disabled')->middleware('isAdmin');

// Himpunan (Master Data)
Route::get('/himpunans', [HimpunanMasterController::class, 'index'])->name('himpunans')->middleware('isAdmin');
Route::post('/himpunan/store', [HimpunanMasterController::class, 'store'])->name('himpunan.store')->middleware('isAdmin');
Route::post('/himpunan/update', [HimpunanMasterController::class, 'update'])->name('himpunan.update')->middleware('isAdmin');
Route::post('/himpunan/update-payment', [HimpunanMasterController::class, 'updatePayment'])->name('himpunan.update.payment')->middleware('isAdmin');
Route::post('/himpunan/delete', [HimpunanMasterController::class, 'delete'])->name('himpunan.delete')->middleware('isAdmin');
Route::post('/himpunan/import', [HimpunanMasterController::class, 'import'])->name('himpunan.import')->middleware('isAdmin');

// Admin Account
Route::get('admin/account', [AdminController::class, 'account'])->name('admin.account')->middleware('isAdmin');
Route::put('admin/account/{id}', [AdminController::class, 'accountUpdate'])->name('admin.account.update')->middleware('isAdmin');

// Bagian-bagian bimbingan
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');
Route::post('/bagian/import', [BagianController::class, 'import'])->name('bagian.import')->middleware('isAdmin');
Route::post('/bagian/active', [BagianController::class, 'bagianActive'])->name('bagian.active')->middleware('isAdmin');
Route::get('/bagian/up/{id}', [BagianController::class, 'up'])->name('bagian.up')->middleware('isAdmin');
Route::get('/bagian/down/{id}', [BagianController::class, 'down'])->name('bagian.down')->middleware('isAdmin');

// Ploting pembimbing (shared)
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isAdminProdi');

/*
|--------------------------------------------------------------------------
| Legacy Redirect Routes - Backward Compatibility
|--------------------------------------------------------------------------
| Route lama redirect ke route baru dengan prefix ta.* atau kp.*
| Redirect berdasarkan context session (default ke TA jika tidak ada context)
*/

// Helper function untuk redirect berdasarkan context
use Illuminate\Support\Facades\Session;

// Pengajuan - Redirect berdasarkan context
Route::get('/pengajuan-admin', fn() => redirect()->route('kp.pengajuan.admin'))->name('pengajuan.admin')->middleware('isAdmin');
Route::get('/pengajuan-prodi', fn() => redirect()->route('kp.pengajuan.prodi'))->name('pengajuan.prodi')->middleware('isProdi');
Route::get('/pengajuan-mahasiswa', function() {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pengajuan.mahasiswa');
})->name('pengajuan.mahasiswa')->middleware('isMahasiswa');

// Pengajuan Detail/Edit/Review - Redirect berdasarkan context
Route::get('/pengajuan/detail/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pengajuan.detail', $id);
})->middleware('isMahasiswa');
Route::get('/pengajuan/edit/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pengajuan.edit', $id);
})->middleware('isMahasiswa');
Route::get('/pengajuan/review/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pengajuan.review', $id);
})->middleware('isProdi');
Route::get('/pengajuan/review-admin/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pengajuan.review.admin', $id);
})->middleware('isAdmin');

// Pendaftaran - Redirect berdasarkan context
Route::get('/pendaftaran-admin', fn() => redirect()->route('kp.pendaftaran.admin'))->name('pendaftaran.admin')->middleware('isAdmin');
Route::get('/pendaftaran-mahasiswa', function() {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pendaftaran.mahasiswa');
})->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
Route::get('/pendaftaran/detail/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pendaftaran.detail', $id);
})->middleware('isMahasiswa');
Route::get('/pendaftaran/edit/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pendaftaran.edit', $id);
})->middleware('isMahasiswa');
Route::get('/pendaftaran/review/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.pendaftaran.review', $id);
})->middleware('isAdmin');

// Bimbingan - Redirect berdasarkan context
Route::get('/bimbingan-prodi', fn() => redirect()->route('kp.bimbingan.prodi'))->name('bimbingan.prodi')->middleware('isProdi');
Route::get('/bimbingan-dosen', fn() => redirect()->route('kp.bimbingan.dosen'))->name('bimbingan.dosen')->middleware('isDosen');
Route::get('/bimbingan-mahasiswa', function() {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.mahasiswa');
})->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
Route::get('/bimbingan/detail/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.detail', $id);
})->middleware('isMahasiswa');
Route::get('/bimbingan/edit/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.edit', $id);
})->middleware('isMahasiswa');
Route::get('/bimbingan/review/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.review', $id);
})->middleware('isDosen');
Route::get('/bimbingan/review-prodi/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.review.prodi', $id);
})->middleware('isProdi');
Route::get('/bimbingan/review-admin/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.bimbingan.review.admin', $id);
})->middleware('isAdmin');

// Seminar - Redirect berdasarkan context
Route::get('/seminar-admin', fn() => redirect()->route('kp.seminar.admin'))->name('seminar.admin')->middleware('isAdmin');
Route::get('/seminar-prodi', fn() => redirect()->route('kp.seminar.prodi'))->name('seminar.prodi')->middleware('isProdi');
Route::get('/seminar-dosen', fn() => redirect()->route('kp.seminar.dosen'))->name('seminar.dosen')->middleware('isDosen');
Route::get('/seminar-mahasiswa', function() {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.seminar.mahasiswa');
})->name('seminar.mahasiswa')->middleware('isMahasiswa');
Route::get('/seminar/detail/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.seminar.detail', $id);
})->middleware('isMahasiswa');
Route::get('/seminar/edit/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.seminar.edit', $id);
})->middleware('isMahasiswa');
Route::get('/seminar/review/{id}', function($id) {
    $context = Session::get('ekapta_context', 'ta');
    return redirect()->route($context . '.seminar.review.admin', $id);
})->middleware('isAdminProdi');

// Pengumpulan Akhir KP - Redirect ke kp.*
Route::get('/pengumpulan-akhir', fn() => redirect()->route('kp.pengumpulan-akhir.index'))->name('pengumpulan-akhir.index')->middleware('isAdminFotokopi');
Route::get('/pengumpulan-akhir-mahasiswa', fn() => redirect()->route('kp.pengumpulan-akhir.mahasiswa'))->name('pengumpulan-akhir.mahasiswa')->middleware('isMahasiswa');

/*
|--------------------------------------------------------------------------
| Context Switch Routes - Switch antara TA dan KP
|--------------------------------------------------------------------------
*/

Route::get('/switch/{context}', [DashboardController::class, 'switchContext'])
    ->name('switch.context')
    ->middleware('isLogin')
    ->where('context', 'ta|kp');

/*
|--------------------------------------------------------------------------
| Back Dashboard Route
|--------------------------------------------------------------------------
*/

Route::get('/back/dashboard', function () {
    if (Auth::guard('mahasiswa')->check()) {
        return redirect('dashboard-mahasiswa');
    } elseif (Auth::guard('dosen')->check()) {
        return redirect('dashboard-dosen');
    } elseif (Auth::guard('prodi')->check()) {
        return redirect('dashboard-prodi');
    } elseif (Auth::guard('admin')->check()) {
        return redirect('dashboard-admin');
    } elseif (Auth::guard('himpunan')->check()) {
        return redirect('dashboard-himpunan');
    } else {
        return redirect('login');
    }
})->name('back.dashboard');
