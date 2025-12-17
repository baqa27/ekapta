<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DekanController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PlotingController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\SeminarController;
use App\Http\Controllers\ReviewSeminarController;

use App\Http\Controllers\DosenProdiController;
use App\Http\Controllers\PengumpulanAkhirController;
use App\Http\Controllers\HimpunanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::get('/login', [LoginController::class, 'loginMahasiswa'])->name('login')->middleware('isMahasiswaLogin');

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

// Dashboard
Route::get('/dashboard-mahasiswa', [DashboardController::class, 'dashboardMahasiswaTA'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');
Route::get('/dashboard-mahasiswa-ta', [DashboardController::class, 'dashboardMahasiswaTA'])->name('dashboard.mahasiswa.ta')->middleware('isMahasiswa');
Route::get('/dashboard-mahasiswa-kp', [DashboardController::class, 'dashboardMahasiswaKP'])->name('dashboard.mahasiswa.kp')->middleware('isMahasiswa');
Route::get('/dashboard-mahasiswa-jilid', [DashboardController::class, 'dashboardMahasiswaJilid'])->name('dashboard.mahasiswa.jilid')->middleware('isMahasiswa');
Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');
Route::get('/dashboard-himpunan', [DashboardController::class, 'dashboardHimpunan'])->name('dashboard.himpunan')->middleware('isHimpunan');

// Routes Himpunan - Verifikasi Seminar KP
Route::group(['middleware' => 'isHimpunan', 'prefix' => 'himpunan'], function () {
    // Verifikasi Pendaftaran
    Route::get('/seminar', [HimpunanController::class, 'seminarIndex'])->name('seminar.himpunan');
    Route::get('/seminar/review/{id}', [HimpunanController::class, 'seminarReview'])->name('seminar.himpunan.review');
    Route::post('/seminar/acc', [HimpunanController::class, 'seminarAcc'])->name('seminar.himpunan.acc');
    Route::post('/seminar/revisi', [HimpunanController::class, 'seminarRevisi'])->name('seminar.himpunan.revisi');
    Route::post('/seminar/tolak', [HimpunanController::class, 'seminarTolak'])->name('seminar.himpunan.tolak');
    Route::post('/seminar/toggle-pendaftaran', [HimpunanController::class, 'togglePendaftaranSeminar'])->name('seminar.himpunan.toggle');
    
    // Penjadwalan Sesi Seminar
    Route::get('/jadwal', [HimpunanController::class, 'jadwalIndex'])->name('jadwal.himpunan');
    Route::post('/jadwal/create-sesi', [HimpunanController::class, 'createSesi'])->name('jadwal.himpunan.create');
    Route::get('/jadwal/sesi/{id}', [HimpunanController::class, 'detailSesi'])->name('jadwal.himpunan.detail');
    
    // Pasca Seminar
    Route::post('/seminar/validasi-revisi', [HimpunanController::class, 'validasiRevisiPasca'])->name('seminar.himpunan.validasi.revisi');
    Route::post('/seminar/finalisasi-nilai', [HimpunanController::class, 'finalisasiNilai'])->name('seminar.himpunan.finalisasi');
    
    // Rekap
    Route::get('/seminar/rekap', [HimpunanController::class, 'rekapSeminar'])->name('seminar.himpunan.rekap');
});

// Pengajuan KP
Route::get('/pengajuan-admin', [PengajuanController::class, 'pengajuanAdmin'])->name('pengajuan.admin')->middleware('isAdmin');
Route::get('/pengajuan-prodi', [PengajuanController::class, 'pengajuanProdi'])->name('pengajuan.prodi')->middleware('isProdi');
Route::get('/pengajuan-mahasiswa', [PengajuanController::class, 'pengajuanMahasiswa'])->name('pengajuan.mahasiswa')->middleware('isMahasiswa');
Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create')->middleware('isMahasiswa');
Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store')->middleware('isMahasiswa');
Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/pengajuan/update', [PengajuanController::class, 'update'])->name('pengajuan.update')->middleware('isMahasiswa');
Route::post('/pengajuan/delete', [PengajuanController::class, 'delete'])->name('pengajuan.delete')->middleware('isMahasiswa');
Route::post('/pengajuan/acc', [PengajuanController::class, 'accPengajuan'])->name('pengajuan.acc')->middleware('isProdi');
Route::post('/pengajuan/revisi', [PengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi')->middleware('isProdi');
Route::post('/pengajuan/revisi/delete', [PengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete')->middleware('isProdi');
Route::post('/pengajuan/tolak', [PengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak')->middleware('isProdi');
Route::get('/pengajuan/detail/{id}', [PengajuanController::class, 'pengajuanDetail'])->middleware('isMahasiswa');
Route::get('/pengajuan/review/{id}', [PengajuanController::class, 'pengajuanReview'])->middleware('isProdi');
Route::get('/pengajuan/review-admin/{id}', [PengajuanController::class, 'pengajuanReviewAdmin'])->middleware('isAdmin');
Route::post('pengajuan/cancel/acc', [PengajuanController::class, 'cancelAcc'])->name('pengajuan.cancel.acc')->middleware('isProdi');
Route::post('pengajuan/cancel/tolak', [PengajuanController::class, 'cancelTolak'])->name('pengajuan.cancel.tolak')->middleware('isProdi');
Route::put('pengajuan/edit/judul/{id}', [PengajuanController::class, 'editJudulPengajuan'])->name('pengajuan.edit.judul')->middleware('isProdi');

// Ploting pembimbing
Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isAdminProdi');


// Pendaftaran KP
Route::get('/pendaftaran-admin', [PendaftaranController::class, 'pendaftaranAdmin'])->name('pendaftaran.admin')->middleware('isAdmin');
Route::get('/pendaftaran-mahasiswa', [PendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('isMahasiswa');
Route::get('/pendaftaran/detail/{id}', [PendaftaranController::class, 'pendaftaranDetail'])->middleware('isMahasiswa');
Route::get('/pendaftaran/review/{id}', [PendaftaranController::class, 'pendaftaranReview'])->middleware('isAdmin');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('isMahasiswa');
Route::get('/pendaftaran/edit/{id}', [PendaftaranController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update')->middleware('isMahasiswa');
Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete')->middleware('isMahasiswa');
Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc')->middleware('isAdmin');
Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi')->middleware('isAdmin');
Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete')->middleware('isAdmin');
Route::post('/pendaftaran/cancel/acc', [PendaftaranController::class, 'cancelAcc'])->name('pendaftaran.cancel.acc')->middleware('isAdmin');
Route::get('/pendaftaran/disable/{id}', [PendaftaranController::class, 'disablePendaftaran'])->name('pendaftaran.disable')->middleware('isMahasiswa');

// Bagian-bagian bimbingan
Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');
Route::post('/bagian/import', [BagianController::class, 'import'])->name('bagian.import')->middleware('isAdmin');
Route::post('/bagian/active', [BagianController::class, 'bagianActive'])->name('bagian.active')->middleware('isAdmin');
Route::get('/bagian/up/{id}', [BagianController::class, 'up'])->name('bagian.up')->middleware('isAdmin');
Route::get('/bagian/down/{id}', [BagianController::class, 'down'])->name('bagian.down')->middleware('isAdmin');

// Bimbingan KP
Route::get('/bimbingan-prodi', [BimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
Route::get('/bimbingan-dosen-progress', [BimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
Route::get('/bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create')->middleware('isMahasiswa');
Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store')->middleware('isMahasiswa');
Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->middleware('isMahasiswa');
Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update')->middleware('isMahasiswa');
Route::post('/bimbingan/delete', [BimbinganController::class, 'delete'])->name('bimbingan.delete')->middleware('isMahasiswa');
Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc')->middleware('isDosen');
Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store')->middleware('isDosen');
Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete')->middleware('isDosen');
Route::get('/bimbingan/detail/{id}', [BimbinganController::class, 'bimbinganDetail'])->middleware('isMahasiswa');
Route::get('/bimbingan/review/{id}', [BimbinganController::class, 'bimbinganReview'])->middleware('isDosen');
Route::post('/bimbingan/cancel/acc', [BimbinganController::class, 'cancelAcc'])->name('bimbingan.cancel.acc')->middleware('isDosen');
Route::post('/bimbingan/cancel/revisi', [BimbinganController::class, 'cancelRevisi'])->name('bimbingan.cancel.revisi')->middleware('isDosen');
Route::get('/bimbingan/review-prodi/{id}', [BimbinganController::class, 'reviewProdi'])->name('bimbingan.review.prodi')->middleware('isProdi');
Route::get('/bimbingan/review-admin/{id}', [BimbinganController::class, 'reviewAdmin'])->name('bimbingan.review.admin')->middleware('isAdmin');
Route::get('/bimbingan/rekap-dosen', [BimbinganController::class, 'rekapDosen'])->name('bimbingan.rekap.dosen')->middleware('isProdi');
// Route::get('/bimbingan/input', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.admin.input')->middleware('isAdmin'); // Tidak dipakai - input bimbingan hanya di prodi
Route::get('/bimbingan/input-prodi', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.prodi.input')->middleware('isProdi');
Route::get('/bimbingan/input/{dosen_id}/{mahasiswa_id}', [BimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.admin.input.create')->middleware('isAdminProdi');
Route::post('/bimbingan/store', [BimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.admin.input.store')->middleware('isAdminProdi');

// Route for Seminar KP
Route::get('seminar/review/{id}', [SeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin')->middleware('isAdminProdi');
Route::post('seminar/acc', [SeminarController::class, 'accSeminar'])->name('seminar.acc')->middleware('isAdminProdi');
Route::post('seminar/revisi', [SeminarController::class, 'revisiSeminar'])->name('seminar.revisi')->middleware('isAdminProdi');
Route::post('seminar/cancel-acc', [SeminarController::class, 'cancelAcc'])->name('seminar.cancel.acc')->middleware('isAdminProdi');
Route::post('/seminar/revisi/delete', [SeminarController::class, 'deleteRevisi'])->name('seminar.revisi.delete')->middleware('isAdminProdi');
Route::post('/seminar/set/date-exam', [SeminarController::class, 'setDateExam'])->name('seminar.set.date.exam')->middleware('isAdminProdi');
Route::get('seminar/rekap', [SeminarController::class,'rekapSeminar'])->name('seminar.rekap')->middleware('isAdminProdi');



Route::group(['middleware' => 'isMahasiswa'], function(){
    // Route for Seminar KP
    Route::get('seminar-mahasiswa', [SeminarController::class, 'seminarMahasiswa'])->name('seminar.mahasiswa');
    Route::get('seminar/create', [SeminarController::class, 'create'])->name('seminar.create');
    Route::get('seminar/edit/{id}', [SeminarController::class, 'edit'])->name('seminar.edit');
    Route::get('seminar/detail/{id}', [SeminarController::class, 'detail'])->name('seminar.detail');
    Route::get('seminar/reviews/{id}', [SeminarController::class, 'seminarReviews'])->name('seminar.reviews');
    Route::post('seminar/store', [SeminarController::class, 'store'])->name('seminar.store');
    Route::put('seminar/update/{id}', [SeminarController::class, 'update'])->name('seminar.update');
    Route::get('seminar/edit/proposal/{id}', [SeminarController::class, 'editProposal'])->name('seminar.edit.proposal');
    Route::put('seminar/update/proposal/{id}', [SeminarController::class, 'updateProposal'])->name('seminar.update.proposal');
    Route::post('seminar/delete', [SeminarController::class, 'delete'])->name('seminar.delete');
    Route::post('seminar/upload-revisi/{id}', [SeminarController::class, 'uploadRevisi'])->name('seminar.upload.revisi');
    Route::post('seminar/upload-nilai-instansi/{id}', [SeminarController::class, 'uploadNilaiInstansi'])->name('seminar.upload.nilai.instansi');
    Route::get('review/seminar/edit/{id}', [ReviewSeminarController::class, 'edit'])->name('review.seminar.edit');
    Route::post('review/seminar/update', [ReviewSeminarController::class, 'update'])->name('review.seminar.update');
    Route::get('review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManual'])->name('review.seminar.submit.acc.manual');
    Route::put('review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManualStore'])->name('review.seminar.submit.acc.manual.store');


});

Route::group(['middleware' => 'isAdmin'], function(){
    // Route for Seminar KP
    Route::get('seminar-admin', [SeminarController::class,'seminarAdmin'])->name('seminar.admin');
    // Route::get('seminar/review/{id}', [SeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin');
    // Route::post('seminar/acc', [SeminarController::class, 'accSeminar'])->name('seminar.acc');
    // Route::post('seminar/revisi', [SeminarController::class, 'revisiSeminar'])->name('seminar.revisi');
    // Route::post('seminar/cancel-acc', [SeminarController::class, 'cancelAcc'])->name('seminar.cancel.acc');
    // Route::post('/seminar/revisi/delete', [SeminarController::class, 'deleteRevisi'])->name('seminar.revisi.delete');
    // Route::post('/seminar/set/date-exam', [SeminarController::class, 'setDateExam'])->name('seminar.set.date.exam');
    // Route::get('seminar/rekap', [SeminarController::class,'rekapSeminar'])->name('seminar.rekap');



    // Route for Dosen Prodi
    Route::post('/dosen-prodi/import', [DosenProdiController::class, 'import'])->name('dosen.prodi.import');
    Route::put('/dosen-prodi/update/{dosen}', [DosenProdiController::class, 'update'])->name('dosen.prodi.update');

    // Route for Setting
    Route::get('admin/account', [AdminController::class, 'account'])->name('admin.account');
    Route::put('admin/account/{id}', [AdminController::class, 'accountUpdate'])->name('admin.account.update');

    //Route form Bimbingan
    Route::get('bimbingan/canceled/{id}', [BimbinganController::class, 'bimbinganCanceled'])->name('bimbingan.canceled');
});

Route::group(['middleware' => 'isDosen'], function(){
    // Route for Seminar KP
    Route::get('seminar-dosen', [SeminarController::class,'seminarDosen'])->name('seminar.dosen');
    Route::get('review/seminar/{id}', [ReviewSeminarController::class, 'reviewDosen'])->name('review.seminar.dosen');
    Route::post('review/seminar/revisi/store', [ReviewSeminarController::class, 'revisiStore'])->name('review.seminar.revisi.store');
    Route::post('review/seminar/revisi/delete', [ReviewSeminarController::class, 'revisiDelete'])->name('review.seminar.revisi.delete');
    Route::post('review/seminar/acc', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc');
    Route::post('review/seminar/nilai', [ReviewSeminarController::class, 'reviewNilai'])->name('review.seminar.nilai');
    Route::post('review/seminar/cancel/acc', [ReviewSeminarController::class, 'reviewCancelAcc'])->name('review.seminar.cancel.acc');



    // Route for Setting
    Route::get('dosen/account', [DosenController::class, 'account'])->name('dosen.account');
    Route::put('dosen/account/{id}', [DosenController::class, 'accountUpdate'])->name('dosen.account.update');
});

Route::group(['middleware' => 'isProdi'], function(){
    // Route for Seminar KP
    Route::get('seminar-prodi', [SeminarController::class, 'seminarProdi'])->name('seminar.prodi');
    Route::get('seminar/detail-prodi/{id}', [SeminarController::class, 'seminarProdiDetail'])->name('seminar.prodi.detail');
    Route::post('review/seminar/acc-prodi', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc.prodi');
    Route::post('seminar/update-status', [SeminarController::class, 'updateStatus'])->name('seminar.update.status');
    Route::post('review/seminar/update-nilai', [ReviewSeminarController::class, 'updateNilai'])->name('review.update.nilai');



    // Route for Setting
    Route::get('prodi/account', [ProdiController::class, 'account'])->name('prodi.account');
    Route::put('prodi/account/{id}', [ProdiController::class, 'accountUpdate'])->name('prodi.account.update');
});

// Prodi
Route::get('/prodis', [ProdiController::class, 'index'])->name('prodis')->middleware('isAdmin');
Route::get('/prodi/{id}', [ProdiController::class, 'detail'])->middleware('isAdmin');
Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store')->middleware('isAdmin');
Route::post('/prodi/import', [ProdiController::class, 'import'])->name('prodi.import')->middleware('isAdmin');
Route::get('/prodi/presentase-nilai/{id}', [ProdiController::class, 'presentaseNilai'])->name('prodi.presentase.nilai')->middleware('isAdmin');
Route::post('/prodi/presentase-nilai/store', [ProdiController::class, 'presentaseNilaiStore'])->name('prodi.presentase.nilai.store')->middleware('isAdmin');
Route::get('/prodi/reset-password/{id}', [ProdiController::class, 'resetPassword'])->name('prodi.reset.password')->middleware('isAdmin');

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

//Dosen
Route::get('/dosens', [DosenController::class, 'index'])->name('dosens')->middleware('isAdmin');
Route::post('/dosen/store', [DosenController::class, 'store'])->name('dosen.store')->middleware('isAdmin');
Route::post('/dosen/import', [DosenController::class, 'import'])->name('dosen.import')->middleware('isAdmin');
Route::get('dosen/{id}', [DosenController::class, 'edit'])->name('dosen.edit')->middleware('isAdmin');
Route::put('dosen/{id}', [DosenController::class, 'update'])->name('dosen.update')->middleware('isAdmin');
Route::get('/dosen/change-manual/{id}', [DosenController::class, 'changeManual'])->name('dosen.change.manual')->middleware('isAdmin');
Route::get('/dosen/reset-password/{id}', [DosenController::class, 'resetPassword'])->name('dosen.reset.password')->middleware('isAdmin');

//Fakultas
Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas')->middleware('isAdmin');
Route::post('/fakultas/import', [FakultasController::class, 'import'])->name('fakultas.import')->middleware('isAdmin');
Route::post('/fakultas/update', [FakultasController::class, 'update'])->name('fakultas.update')->middleware('isAdmin');
Route::get('/fakultas/setting/{fakultas}', [FakultasController::class, 'setting'])->middleware('isAdmin');
Route::post('/fakultas/add/prodi', [FakultasController::class, 'addProdi'])->name('fakultas.add.prodi')->middleware('isAdmin');
Route::post('/fakultas/delete/prodi', [FakultasController::class, 'deleteProdi'])->name('fakultas.delete.prodi')->middleware('isAdmin');

//Dekan
Route::post('/dekan/store', [DekanController::class, 'store'])->name('dekan.store')->middleware('isAdmin');
Route::post('/dekan/import', [DekanController::class, 'import'])->name('dekan.import')->middleware('isAdmin');
Route::post('/dekan/update', [DekanController::class, 'update'])->name('dekan.update')->middleware('isAdmin');
Route::post('/dekan/delete', [DekanController::class, 'delete'])->name('dekan.delete')->middleware('isAdmin');
Route::post('/dekan/enabled', [DekanController::class, 'enabled'])->name('dekan.enabled')->middleware('isAdmin');
Route::post('/dekan/disabled', [DekanController::class, 'disabled'])->name('dekan.disabled')->middleware('isAdmin');

//Himpunan (Master Data)
Route::get('/himpunans', [App\Http\Controllers\HimpunanMasterController::class, 'index'])->name('himpunans')->middleware('isAdmin');
Route::post('/himpunan/store', [App\Http\Controllers\HimpunanMasterController::class, 'store'])->name('himpunan.store')->middleware('isAdmin');
Route::post('/himpunan/update', [App\Http\Controllers\HimpunanMasterController::class, 'update'])->name('himpunan.update')->middleware('isAdmin');
Route::post('/himpunan/delete', [App\Http\Controllers\HimpunanMasterController::class, 'delete'])->name('himpunan.delete')->middleware('isAdmin');
Route::post('/himpunan/import', [App\Http\Controllers\HimpunanMasterController::class, 'import'])->name('himpunan.import')->middleware('isAdmin');

// Pengumpulan Akhir KP
Route::get('pengumpulan-akhir',[PengumpulanAkhirController::class, 'index'])->name('pengumpulan-akhir.index')->middleware('isAdminFotokopi');
Route::get('pengumpulan-akhir/detail/{id}',[PengumpulanAkhirController::class, 'detail'])->name('pengumpulan-akhir.detail')->middleware('isAdminFotokopi');
Route::get('pengumpulan-akhir/detail-mahasiswa/{id}',[PengumpulanAkhirController::class, 'detailMahasiswa'])->name('pengumpulan-akhir.detail.mahasiswa')->middleware('isMahasiswa');
Route::put('pengumpulan-akhir/acc/{id}',[PengumpulanAkhirController::class, 'acc'])->name('pengumpulan-akhir.acc')->middleware('isAdminFotokopi');
Route::get('pengumpulan-akhir-mahasiswa',[PengumpulanAkhirController::class, 'mahasiswaIndex'])->name('pengumpulan-akhir.mahasiswa')->middleware('isMahasiswa');
Route::get('pengumpulan-akhir/create',[PengumpulanAkhirController::class, 'create'])->name('pengumpulan-akhir.create')->middleware('isMahasiswa');
Route::post('pengumpulan-akhir/store',[PengumpulanAkhirController::class, 'store'])->name('pengumpulan-akhir.store')->middleware('isMahasiswa');
Route::get('pengumpulan-akhir/edit/{id}',[PengumpulanAkhirController::class, 'edit'])->name('pengumpulan-akhir.edit')->middleware('isMahasiswa');
Route::put('pengumpulan-akhir/update/{id}',[PengumpulanAkhirController::class, 'update'])->name('pengumpulan-akhir.update')->middleware('isMahasiswa');
Route::get('pengumpulan-akhir/confirm-completed/{id}',[PengumpulanAkhirController::class, 'confirmCompleted'])->name('pengumpulan-akhir.confirm.completed')->middleware('isAdmin');

// Cetak Dokumen
Route::group(['middleware' => 'isLogin'], function (){
    Route::get('/cetak/lembar-persetujuan-pembimbing-mahasiswa', [CetakController::class, 'cetakLembarPersetujuanMahasiswa'])->name('cetak.lembar.persetujuan.mahasiswa');
    Route::get('/cetak/lembar-pernyataan-keaslian', [CetakController::class, 'cetakLembarPernyataanKeaslian'])->name('cetak.lembar.pernyataan.keaslian');
    Route::get('/cetak/surat-tugas-bimbingan', [CetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
    Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [CetakController::class, 'cetakSuratTugasBimbingan']);
    Route::get('/cetak/berita-acara-seminar-kp/{seminar}', [CetakController::class, 'cetakBeritaAcaraUjianProposal'])->name('cetak.berita.acara.ujian.proposal');
    Route::get('/cetak/berita-acara-seminar-kp-blank/{ujian_or_seminar}/{type}', [CetakController::class, 'cetakBeritaAcaraUjianProposalBlank'])->name('cetak.berita.acara.ujian.proposal.blank');


    Route::get('/cetak/surat-riwayat-bimbingan-mahasiswa', [CetakController::class, 'cetakRiwayatBimbinganMahasiswa'])->name('cetak.riwayat.bimbingan.mahasiswa');
    Route::get('/cetak/surat-riwayat-bimbingan/{id}', [CetakController::class, 'cetakRiwayatBimbingan'])->name('cetak.riwayat.bimbingan');
    Route::get('/cetak/lembar-persetujuan/{type}', [CetakController::class, 'cetakLembarPersetujuan'])->name('cetak.lembar.persetujuan');
    Route::get('/cetak/lembar-pengesahan', [CetakController::class, 'cetakLembarPengesahan'])->name('cetak.lembar.pengesahan');
});

//Public
Route::get('/public/riwayat-bimbingan/{id}',[BimbinganController::class, 'public'])->name('bimbingan.public');
Route::get('/public/review-seminar/{token}', [ReviewSeminarController::class, 'reviewPublic'])->name('review.seminar.public');
Route::post('/public/review-seminar/{token}', [ReviewSeminarController::class, 'storePublic'])->name('review.seminar.public.store');

// Penilaian Seminar KP (Public - Tanpa Login)
Route::get('/penilaian-seminar/{token}', [App\Http\Controllers\PenilaianSeminarController::class, 'index'])->name('penilaian.seminar');
Route::post('/penilaian-seminar/{token}/submit', [App\Http\Controllers\PenilaianSeminarController::class, 'submit'])->name('penilaian.seminar.submit');



// Back Dashboard
Route::get('/back/dashboard', function () {
    if (Auth::guard('mahasiswa')->check()) {
        return redirect('dashboard-mahasiswa');
    } elseif (Auth::guard('dosen')->check()) {
        return redirect('dashboard-dosen');
    } elseif (Auth::guard('prodi')->check()) {
        return redirect('dashboard-prodi');
    } elseif (Auth::guard('admin')->check()) {
        return redirect('dashboard-admin');
    } else {
        return redirect('login');
    }
})->name('back.dashboard');

Route::group(['middleware' => 'isAdmin'], function (){
    Route::get('mahasiswa/reset-password/{id}', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset.password');
    Route::get('dosen/reset-password/{id}', [DosenController::class, 'resetPassword'])->name('dosen.reset.password');
    Route::get('prodi/reset-password/{id}', [ProdiController::class, 'resetPassword'])->name('prodi.reset.password');

    Route::get('laporan-bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganAdmin'])->name('bimbingan.admin');
});
