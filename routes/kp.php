<?php

/**
 * Routes untuk Kerja Praktik (KP)
 * Copy langsung dari ekapta-kp/routes/web.php
 * Semua route menggunakan prefix 'kp' dan nama 'kp.*'
 */

use App\Http\Controllers\KP\AdminController;
use App\Http\Controllers\KP\BagianController;
use App\Http\Controllers\KP\BimbinganController;
use App\Http\Controllers\KP\CetakController;
use App\Http\Controllers\KP\DashboardController;
use App\Http\Controllers\KP\DekanController;
use App\Http\Controllers\KP\DosenController;
use App\Http\Controllers\KP\FakultasController;
use App\Http\Controllers\KP\HomeController;
use App\Http\Controllers\KP\LoginController;
use App\Http\Controllers\KP\MahasiswaController;
use App\Http\Controllers\KP\PendaftaranController;
use App\Http\Controllers\KP\PengajuanController;
use App\Http\Controllers\KP\PlotingController;
use App\Http\Controllers\KP\ProdiController;
use App\Http\Controllers\KP\SeminarController;
use App\Http\Controllers\KP\ReviewSeminarController;
use App\Http\Controllers\KP\DosenProdiController;
use App\Http\Controllers\KP\PengumpulanAkhirController;
use App\Http\Controllers\KP\HimpunanController;
use App\Http\Controllers\KP\HimpunanMasterController;
use App\Http\Controllers\KP\PenilaianPembimbingController;
use App\Http\Controllers\KP\PenilaianSeminarController;
use Illuminate\Support\Facades\Route;

Route::prefix('kp')->name('kp.')->group(function () {

    // Dashboard KP
    Route::get('/dashboard', [DashboardController::class, 'dashboardMahasiswaKP'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');

    // Mahasiswa Profile & Account KP
    Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('profile')->middleware('isMahasiswa');
    Route::post('/mahasiswa/profile/update', [MahasiswaController::class, 'update'])->name('profile.update')->middleware('isMahasiswa');
    Route::get('/mahasiswa/account', [MahasiswaController::class, 'account'])->name('mahasiswa.account')->middleware('isMahasiswa');
    Route::put('/mahasiswa/account/{id}', [MahasiswaController::class, 'accountUpdate'])->name('mahasiswa.account.update')->middleware('isMahasiswa');
    Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
    Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
    Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');
    Route::get('/dashboard-fotokopi', [PengumpulanAkhirController::class, 'index'])->name('dashboard.fotokopi')->middleware('isAdminFotokopi');
    Route::get('/dashboard-himpunan', [DashboardController::class, 'dashboardHimpunan'])->name('dashboard.himpunan')->middleware('isHimpunan');

    // Routes Himpunan - Verifikasi Seminar KP
    Route::group(['middleware' => 'isHimpunan', 'prefix' => 'himpunan'], function () {
        Route::get('/seminar', [HimpunanController::class, 'seminarIndex'])->name('seminar.himpunan');
        Route::get('/seminar/review/{id}', [HimpunanController::class, 'seminarReview'])->name('seminar.himpunan.review');
        Route::post('/seminar/acc', [HimpunanController::class, 'seminarAcc'])->name('seminar.himpunan.acc');
        Route::post('/seminar/revisi', [HimpunanController::class, 'seminarRevisi'])->name('seminar.himpunan.revisi');
        Route::post('/seminar/toggle-pendaftaran', [HimpunanController::class, 'togglePendaftaranSeminar'])->name('seminar.himpunan.toggle');
        Route::get('/jadwal', [HimpunanController::class, 'jadwalIndex'])->name('jadwal.himpunan');
        Route::post('/jadwal/create-sesi', [HimpunanController::class, 'createSesi'])->name('jadwal.himpunan.create');
        Route::get('/jadwal/sesi/{id}', [HimpunanController::class, 'detailSesi'])->name('jadwal.himpunan.detail');
        Route::delete('/jadwal/sesi/{id}', [HimpunanController::class, 'deleteSesi'])->name('jadwal.himpunan.delete');
        Route::post('/seminar/validasi-revisi', [HimpunanController::class, 'validasiRevisiPasca'])->name('seminar.himpunan.validasi.revisi');
        Route::post('/seminar/finalisasi-nilai', [HimpunanController::class, 'finalisasiNilai'])->name('seminar.himpunan.finalisasi');
        Route::get('/seminar/rekap', [HimpunanController::class, 'rekapSeminar'])->name('seminar.himpunan.rekap');
        
        // Payment Settings
        Route::get('/payment', [HimpunanController::class, 'paymentSettings'])->name('payment.himpunan');
        Route::post('/payment/update', [HimpunanController::class, 'updatePayment'])->name('payment.himpunan.update');
    });

    // Pengajuan KP
    Route::get('/pengajuan-admin', [PengajuanController::class, 'pengajuanAdmin'])->name('pengajuan.admin')->middleware('isAdmin');
    Route::get('/pengajuan-prodi', [PengajuanController::class, 'pengajuanProdi'])->name('pengajuan.prodi')->middleware('isProdi');
    Route::get('/pengajuan-mahasiswa', [PengajuanController::class, 'pengajuanMahasiswa'])->name('pengajuan.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create')->middleware('isMahasiswa');
    Route::post('/pengajuan/store', [PengajuanController::class, 'store'])->name('pengajuan.store')->middleware('isMahasiswa');
    Route::get('/pengajuan/edit/{id}', [PengajuanController::class, 'edit'])->name('pengajuan.edit')->middleware('isMahasiswa');
    Route::post('/pengajuan/update', [PengajuanController::class, 'update'])->name('pengajuan.update')->middleware('isMahasiswa');
    Route::post('/pengajuan/delete', [PengajuanController::class, 'delete'])->name('pengajuan.delete')->middleware('isMahasiswa');
    Route::post('/pengajuan/acc', [PengajuanController::class, 'accPengajuan'])->name('pengajuan.acc')->middleware('isProdi');
    Route::post('/pengajuan/revisi', [PengajuanController::class, 'revisiPengajuan'])->name('pengajuan.revisi')->middleware('isProdi');
    Route::post('/pengajuan/revisi/delete', [PengajuanController::class, 'deleteRevisiPengajuan'])->name('pengajuan.revisi.delete')->middleware('isProdi');
    Route::post('/pengajuan/tolak', [PengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak')->middleware('isProdi');
    Route::get('/pengajuan/detail/{id}', [PengajuanController::class, 'pengajuanDetail'])->name('pengajuan.detail')->middleware('isMahasiswa');
    Route::get('/pengajuan/review/{id}', [PengajuanController::class, 'pengajuanReview'])->name('pengajuan.review')->middleware('isProdi');
    Route::get('/pengajuan/review-admin/{id}', [PengajuanController::class, 'pengajuanReviewAdmin'])->name('pengajuan.review.admin')->middleware('isAdmin');
    Route::post('/pengajuan/cancel/acc', [PengajuanController::class, 'cancelAcc'])->name('pengajuan.cancel.acc')->middleware('isProdi');
    Route::post('/pengajuan/cancel/tolak', [PengajuanController::class, 'cancelTolak'])->name('pengajuan.cancel.tolak')->middleware('isProdi');
    Route::put('/pengajuan/edit/judul/{id}', [PengajuanController::class, 'editJudulPengajuan'])->name('pengajuan.edit.judul')->middleware('isProdi');

    // Ploting pembimbing
    Route::post('/ploting/pembimbing', [PlotingController::class, 'plotingPembimbing'])->name('ploting.pembimbing')->middleware('isProdi');
    Route::post('/ploting/penguji', [PlotingController::class, 'plotingPenguji'])->name('ploting.penguji')->middleware('isAdminProdi');

    // Pendaftaran KP
    Route::get('/pendaftaran-admin', [PendaftaranController::class, 'pendaftaranAdmin'])->name('pendaftaran.admin')->middleware('isAdmin');
    Route::get('/pendaftaran-mahasiswa', [PendaftaranController::class, 'pendaftaranMahasiswa'])->name('pendaftaran.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create')->middleware('isMahasiswa');
    Route::get('/pendaftaran/detail/{id}', [PendaftaranController::class, 'pendaftaranDetail'])->name('pendaftaran.detail')->middleware('isMahasiswa');
    Route::get('/pendaftaran/review/{id}', [PendaftaranController::class, 'pendaftaranReview'])->name('pendaftaran.review')->middleware('isAdmin');
    Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store')->middleware('isMahasiswa');
    Route::get('/pendaftaran/edit/{id}', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit')->middleware('isMahasiswa');
    Route::post('/pendaftaran/update', [PendaftaranController::class, 'update'])->name('pendaftaran.update')->middleware('isMahasiswa');
    Route::post('/pendaftaran/delete', [PendaftaranController::class, 'delete'])->name('pendaftaran.delete')->middleware('isMahasiswa');
    Route::post('/pendaftaran/acc', [PendaftaranController::class, 'accPendaftaran'])->name('pendaftaran.acc')->middleware('isAdmin');
    Route::post('/pendaftaran/revisi', [PendaftaranController::class, 'revisiPendaftaran'])->name('pendaftaran.revisi')->middleware('isAdmin');
    Route::post('/pendaftaran/revisi/delete', [PendaftaranController::class, 'deleteRevisiPendaftaran'])->name('pendaftaran.revisi.delete')->middleware('isAdmin');
    Route::post('/pendaftaran/cancel/acc', [PendaftaranController::class, 'cancelAcc'])->name('pendaftaran.cancel.acc')->middleware('isAdmin');
    Route::get('/pendaftaran/disable/{id}', [PendaftaranController::class, 'disablePendaftaran'])->name('pendaftaran.disable')->middleware('isMahasiswa');

    // Bimbingan KP
    Route::get('/bimbingan-prodi', [BimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
    Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
    Route::get('/bimbingan-dosen-progress', [BimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
    Route::get('/bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
    Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create')->middleware('isMahasiswa');
    Route::get('/bimbingan/create-manual', [BimbinganController::class, 'createManual'])->name('bimbingan.create.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/store-manual', [BimbinganController::class, 'storeManual'])->name('bimbingan.store.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store')->middleware('isMahasiswa');
    Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->name('bimbingan.edit')->middleware('isMahasiswa');
    Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update')->middleware('isMahasiswa');
    Route::post('/bimbingan/delete', [BimbinganController::class, 'delete'])->name('bimbingan.delete')->middleware('isMahasiswa');
    Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc')->middleware('isDosen');
    Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store')->middleware('isDosen');
    Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete')->middleware('isDosen');
    Route::get('/bimbingan/detail/{id}', [BimbinganController::class, 'bimbinganDetail'])->name('bimbingan.detail')->middleware('isMahasiswa');
    Route::get('/bimbingan/submit-acc-manual/{id}', [BimbinganController::class, 'submitAccManual'])->name('bimbingan.submit.acc.manual')->middleware('isMahasiswa');
    Route::post('/bimbingan/submit-acc-manual-store', [BimbinganController::class, 'submitAccManualStore'])->name('bimbingan.submit.acc.manual.store')->middleware('isMahasiswa');
    Route::get('/bimbingan/review/{id}', [BimbinganController::class, 'bimbinganReview'])->name('bimbingan.review')->middleware('isDosen');

    // Bimbingan Offline KP
    Route::get('/bimbingan-offline/create', [BimbinganController::class, 'createOffline'])->name('bimbingan-offline.create')->middleware('isMahasiswa');
    Route::post('/bimbingan-offline/store', [BimbinganController::class, 'storeOffline'])->name('bimbingan-offline.store')->middleware('isMahasiswa');
    Route::get('/bimbingan-offline/detail/{id}', [BimbinganController::class, 'detailOffline'])->name('bimbingan-offline.detail')->middleware('isMahasiswa');
    Route::post('/bimbingan-offline/verify', [BimbinganController::class, 'verifyOffline'])->name('bimbingan-offline.verify')->middleware('isProdi');
    Route::post('/bimbingan-offline/reject', [BimbinganController::class, 'rejectOffline'])->name('bimbingan-offline.reject')->middleware('isProdi');
    Route::post('/bimbingan/cancel/acc', [BimbinganController::class, 'cancelAcc'])->name('bimbingan.cancel.acc')->middleware('isDosen');
    Route::post('/bimbingan/cancel/revisi', [BimbinganController::class, 'cancelRevisi'])->name('bimbingan.cancel.revisi')->middleware('isDosen');
    Route::get('/bimbingan/review-prodi/{id}', [BimbinganController::class, 'reviewProdi'])->name('bimbingan.review.prodi')->middleware('isProdi');
    Route::get('/bimbingan/review-admin/{id}', [BimbinganController::class, 'reviewAdmin'])->name('bimbingan.review.admin')->middleware('isAdmin');
    Route::get('/bimbingan/rekap-dosen', [BimbinganController::class, 'rekapDosen'])->name('bimbingan.rekap.dosen')->middleware('isProdi');
    Route::get('/bimbingan-admin', [BimbinganController::class, 'bimbinganAdmin'])->name('bimbingan.admin')->middleware('isAdmin');
    Route::get('/bimbingan/input-admin', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.admin.input')->middleware('isAdmin');
    Route::get('/bimbingan/input-prodi', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.prodi.input')->middleware('isProdi');
    Route::get('/bimbingan/input/{dosen_id}/{mahasiswa_id}', [BimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.admin.input.create')->middleware('isAdminProdi');
    Route::post('/bimbingan/input/store', [BimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.admin.input.store')->middleware('isAdminProdi');
    Route::post('/bimbingan/acc-prodi', [BimbinganController::class, 'accBimbinganProdi'])->name('bimbingan.acc.prodi')->middleware('isAdminProdi');
    Route::post('/bimbingan/revisi-prodi', [BimbinganController::class, 'revisiBimbinganProdi'])->name('bimbingan.revisi.prodi')->middleware('isAdminProdi');

    // Seminar KP
    Route::get('/seminar/review/{id}', [SeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin')->middleware('isAdminProdi');
    Route::get('/seminar/rekap', [SeminarController::class,'rekapSeminar'])->name('seminar.rekap')->middleware('isAdminProdi');

    // Mahasiswa - Seminar KP
    Route::group(['middleware' => 'isMahasiswa'], function(){
        Route::get('/seminar-mahasiswa', [SeminarController::class, 'seminarMahasiswa'])->name('seminar.mahasiswa');
        Route::get('/seminar/create', [SeminarController::class, 'create'])->name('seminar.create');
        Route::get('/seminar/edit/{id}', [SeminarController::class, 'edit'])->name('seminar.edit');
        Route::get('/seminar/detail/{id}', [SeminarController::class, 'detail'])->name('seminar.detail');
        Route::get('/seminar/reviews/{id}', [SeminarController::class, 'seminarReviews'])->name('seminar.reviews');
        Route::post('/seminar/store', [SeminarController::class, 'store'])->name('seminar.store');
        Route::put('/seminar/update/{id}', [SeminarController::class, 'update'])->name('seminar.update');
        Route::get('/seminar/edit/proposal/{id}', [SeminarController::class, 'editProposal'])->name('seminar.edit.proposal');
        Route::put('/seminar/update/proposal/{id}', [SeminarController::class, 'updateProposal'])->name('seminar.update.proposal');
        Route::post('/seminar/delete', [SeminarController::class, 'delete'])->name('seminar.delete');
        Route::post('/seminar/upload-revisi/{id}', [SeminarController::class, 'uploadRevisi'])->name('seminar.upload.revisi');
        Route::post('/seminar/upload-nilai-instansi/{id}', [SeminarController::class, 'uploadNilaiInstansi'])->name('seminar.upload.nilai.instansi');
        Route::get('/review/seminar/edit/{id}', [ReviewSeminarController::class, 'edit'])->name('review.seminar.edit');
        Route::post('/review/seminar/update', [ReviewSeminarController::class, 'update'])->name('review.seminar.update');
        Route::get('/review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManual'])->name('review.seminar.submit.acc.manual');
        Route::put('/review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManualStore'])->name('review.seminar.submit.acc.manual.store');
    });

    // Admin - Seminar KP
    Route::group(['middleware' => 'isAdmin'], function(){
        Route::get('/seminar-admin', [SeminarController::class,'seminarAdmin'])->name('seminar.admin');
        Route::get('/bimbingan/canceled/{id}', [BimbinganController::class, 'bimbinganCanceled'])->name('bimbingan.canceled');
    });

    // Dosen - Seminar KP
    Route::group(['middleware' => 'isDosen'], function(){
        Route::get('/seminar-dosen', [SeminarController::class,'seminarDosen'])->name('seminar.dosen');
        Route::get('/review/seminar/{id}', [ReviewSeminarController::class, 'reviewDosen'])->name('review.seminar.dosen');
        Route::post('/review/seminar/revisi/store', [ReviewSeminarController::class, 'revisiStore'])->name('review.seminar.revisi.store');
        Route::post('/review/seminar/revisi/delete', [ReviewSeminarController::class, 'revisiDelete'])->name('review.seminar.revisi.delete');
        Route::post('/review/seminar/acc', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc');
        Route::post('/review/seminar/nilai', [ReviewSeminarController::class, 'reviewNilai'])->name('review.seminar.nilai');
        Route::post('/review/seminar/cancel/acc', [ReviewSeminarController::class, 'reviewCancelAcc'])->name('review.seminar.cancel.acc');

        // Penilaian Pembimbing KP
        Route::get('/penilaian-pembimbing', [PenilaianPembimbingController::class, 'index'])->name('penilaian.pembimbing.index');
        Route::post('/penilaian-pembimbing/store', [PenilaianPembimbingController::class, 'store'])->name('penilaian.pembimbing.store');
    });

    // Prodi - Seminar KP
    Route::group(['middleware' => 'isProdi'], function(){
        Route::get('/seminar-prodi', [SeminarController::class, 'seminarProdi'])->name('seminar.prodi');
        Route::get('/seminar/detail-prodi/{id}', [SeminarController::class, 'seminarProdiDetail'])->name('seminar.prodi.detail');
        Route::post('/review/seminar/acc-prodi', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc.prodi');
        Route::post('/seminar/update-status', [SeminarController::class, 'updateStatus'])->name('seminar.update.status');
        Route::post('/review/seminar/update-nilai', [ReviewSeminarController::class, 'updateNilai'])->name('review.update.nilai');
    });

    // Pengumpulan Akhir KP
    Route::get('/pengumpulan-akhir',[PengumpulanAkhirController::class, 'index'])->name('pengumpulan-akhir.index')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir/detail/{id}',[PengumpulanAkhirController::class, 'detail'])->name('pengumpulan-akhir.detail')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir/detail-mahasiswa/{id}',[PengumpulanAkhirController::class, 'detailMahasiswa'])->name('pengumpulan-akhir.detail.mahasiswa')->middleware('isMahasiswa');
    Route::put('/pengumpulan-akhir/acc/{id}',[PengumpulanAkhirController::class, 'acc'])->name('pengumpulan-akhir.acc')->middleware('isAdminFotokopi');
    Route::get('/pengumpulan-akhir-mahasiswa',[PengumpulanAkhirController::class, 'mahasiswaIndex'])->name('pengumpulan-akhir.mahasiswa')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/create',[PengumpulanAkhirController::class, 'create'])->name('pengumpulan-akhir.create')->middleware('isMahasiswa');
    Route::post('/pengumpulan-akhir/store',[PengumpulanAkhirController::class, 'store'])->name('pengumpulan-akhir.store')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/edit/{id}',[PengumpulanAkhirController::class, 'edit'])->name('pengumpulan-akhir.edit')->middleware('isMahasiswa');
    Route::put('/pengumpulan-akhir/update/{id}',[PengumpulanAkhirController::class, 'update'])->name('pengumpulan-akhir.update')->middleware('isMahasiswa');
    Route::get('/pengumpulan-akhir/confirm-completed/{id}',[PengumpulanAkhirController::class, 'confirmCompleted'])->name('pengumpulan-akhir.confirm.completed')->middleware('isAdmin');

    // Cetak Dokumen KP
    Route::group(['middleware' => 'isLogin'], function (){
        Route::get('/cetak/lembar-persetujuan-pembimbing-mahasiswa', [CetakController::class, 'cetakLembarPersetujuanMahasiswa'])->name('cetak.lembar.persetujuan.mahasiswa');
        Route::get('/cetak/lembar-pernyataan-keaslian', [CetakController::class, 'cetakLembarPernyataanKeaslian'])->name('cetak.lembar.pernyataan.keaslian');
        Route::get('/cetak/surat-tugas-bimbingan', [CetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
        Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [CetakController::class, 'cetakSuratTugasBimbingan'])->name('cetak.surat.tugas.bimbingan.pendaftaran');
        Route::get('/cetak/berita-acara-seminar-kp/{seminar}', [CetakController::class, 'cetakBeritaAcaraUjianProposal'])->name('cetak.berita.acara.ujian.proposal');
        Route::get('/cetak/berita-acara-seminar-kp-blank/{ujian_or_seminar}/{type}', [CetakController::class, 'cetakBeritaAcaraUjianProposalBlank'])->name('cetak.berita.acara.ujian.proposal.blank');
        Route::get('/cetak/surat-penolakan/{pengajuan}', [CetakController::class, 'cetakSuratPenolakan'])->name('cetak.surat.penolakan');
        Route::get('/cetak/surat-riwayat-bimbingan-mahasiswa', [CetakController::class, 'cetakRiwayatBimbinganMahasiswa'])->name('cetak.riwayat.bimbingan.mahasiswa');
        Route::get('/cetak/surat-riwayat-bimbingan/{id}', [CetakController::class, 'cetakRiwayatBimbingan'])->name('cetak.riwayat.bimbingan');
        Route::get('/cetak/lembar-persetujuan/{type}', [CetakController::class, 'cetakLembarPersetujuan'])->name('cetak.lembar.persetujuan');
        Route::get('/cetak/lembar-pengesahan', [CetakController::class, 'cetakLembarPengesahan'])->name('cetak.lembar.pengesahan');
    });

    // Bagian Bimbingan KP (Admin)
    Route::post('/bagian/store', [BagianController::class, 'store'])->name('bagian.store')->middleware('isAdmin');
    Route::post('/bagian/update', [BagianController::class, 'update'])->name('bagian.update')->middleware('isAdmin');
    Route::post('/bagian/delete', [BagianController::class, 'delete'])->name('bagian.delete')->middleware('isAdmin');
    Route::post('/bagian/import', [BagianController::class, 'import'])->name('bagian.import')->middleware('isAdmin');
    Route::post('/bagian/active', [BagianController::class, 'bagianActive'])->name('bagian.active')->middleware('isAdmin');
    Route::get('/bagian/up/{id}', [BagianController::class, 'up'])->name('bagian.up')->middleware('isAdmin');
    Route::get('/bagian/down/{id}', [BagianController::class, 'down'])->name('bagian.down')->middleware('isAdmin');

    // Public KP
    Route::get('/public/riwayat-bimbingan/{id}',[BimbinganController::class, 'public'])->name('bimbingan.public');
    Route::get('/public/review-seminar/{token}', [ReviewSeminarController::class, 'reviewPublic'])->name('review.seminar.public');
    Route::post('/public/review-seminar/{token}', [ReviewSeminarController::class, 'storePublic'])->name('review.seminar.public.store');

    // Penilaian Seminar KP (Public)
    Route::get('/penilaian-seminar/{token}', [PenilaianSeminarController::class, 'index'])->name('penilaian.seminar');
    Route::post('/penilaian-seminar/{token}/submit', [PenilaianSeminarController::class, 'submit'])->name('penilaian.seminar.submit');
});
