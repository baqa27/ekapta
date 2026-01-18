<?php

/**
 * Routes untuk Tugas Akhir (TA)
 * Copy langsung dari ekapta-ta/routes/web.php
 * Semua route menggunakan prefix 'ta' dan nama 'ta.*'
 */

use App\Http\Controllers\TA\AdminController;
use App\Http\Controllers\TA\BagianController;
use App\Http\Controllers\TA\BimbinganController;
use App\Http\Controllers\TA\CetakController;
use App\Http\Controllers\TA\DashboardController;
use App\Http\Controllers\TA\DekanController;
use App\Http\Controllers\TA\DosenController;
use App\Http\Controllers\TA\FakultasController;
use App\Http\Controllers\TA\HomeController;
use App\Http\Controllers\TA\LoginController;
use App\Http\Controllers\TA\MahasiswaController;
use App\Http\Controllers\TA\PendaftaranController;
use App\Http\Controllers\TA\PengajuanController;
use App\Http\Controllers\TA\PlotingController;
use App\Http\Controllers\TA\ProdiController;
use App\Http\Controllers\TA\SeminarController;
use App\Http\Controllers\TA\ReviewSeminarController;
use App\Http\Controllers\TA\UjianController;
use App\Http\Controllers\TA\ReviewUjianController;
use App\Http\Controllers\TA\DosenProdiController;
use App\Http\Controllers\TA\JilidController;
use Illuminate\Support\Facades\Route;

Route::prefix('ta')->name('ta.')->group(function () {

    // Dashboard TA
    Route::get('/dashboard', [DashboardController::class, 'dashboardMahasiswaTA'])->name('dashboard.mahasiswa')->middleware('isMahasiswa');
    Route::get('/dashboard-prodi', [DashboardController::class, 'dashboardProdi'])->name('dashboard.prodi')->middleware('isProdi');
    Route::get('/dashboard-dosen', [DashboardController::class, 'dashboardDosen'])->name('dashboard.dosen')->middleware('isDosen');
    Route::get('/dashboard-admin', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin')->middleware('isAdmin');

    // Mahasiswa Profile & Account TA
    Route::get('/mahasiswa/profile', [MahasiswaController::class, 'profile'])->name('profile')->middleware('isMahasiswa');
    Route::post('/mahasiswa/profile/update', [MahasiswaController::class, 'update'])->name('profile.update')->middleware('isMahasiswa');
    Route::get('/mahasiswa/account', [MahasiswaController::class, 'account'])->name('mahasiswa.account')->middleware('isMahasiswa');
    Route::put('/mahasiswa/account/{id}', [MahasiswaController::class, 'accountUpdate'])->name('mahasiswa.account.update')->middleware('isMahasiswa');

    // Pengajuan TA
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
    Route::post('/ploting/penguji/ujian', [PlotingController::class, 'plotingPengujiUjian'])->name('ploting.penguji.ujian')->middleware('isAdminProdi');

    // Pendaftaran TA
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

    // Bimbingan TA
    Route::get('/bimbingan-prodi', [BimbinganController::class, 'bimbinganProdi'])->name('bimbingan.prodi')->middleware('isProdi');
    Route::get('/bimbingan-dosen', [BimbinganController::class, 'bimbinganDosen'])->name('bimbingan.dosen')->middleware('isDosen');
    Route::get('/bimbingan-dosen-progress', [BimbinganController::class, 'bimbinganDosenProgress'])->name('bimbingan.dosen.progress')->middleware('isDosen');
    Route::get('/bimbingan-mahasiswa', [BimbinganController::class, 'bimbinganMahasiswa'])->name('bimbingan.mahasiswa')->middleware('isMahasiswa');
    Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create')->middleware('isMahasiswa');
    Route::post('/bimbingan/store', [BimbinganController::class, 'store'])->name('bimbingan.store')->middleware('isMahasiswa');
    Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->name('bimbingan.edit')->middleware('isMahasiswa');
    Route::post('/bimbingan/update', [BimbinganController::class, 'update'])->name('bimbingan.update')->middleware('isMahasiswa');
    Route::post('/bimbingan/delete', [BimbinganController::class, 'delete'])->name('bimbingan.delete')->middleware('isMahasiswa');
    Route::post('/bimbingan/acc', [BimbinganController::class, 'accBimbingan'])->name('bimbingan.acc')->middleware('isDosen');
    Route::post('/bimbingan/revisi/store', [BimbinganController::class, 'revisiBimbingan'])->name('bimbingan.revisi.store')->middleware('isDosen');
    Route::post('/bimbingan/revisi/delete', [BimbinganController::class, 'deleteRevisiBimbingan'])->name('bimbingan.revisi.delete')->middleware('isDosen');
    Route::get('/bimbingan/detail/{id}', [BimbinganController::class, 'bimbinganDetail'])->name('bimbingan.detail')->middleware('isMahasiswa');
    Route::get('/bimbingan/review/{id}', [BimbinganController::class, 'bimbinganReview'])->name('bimbingan.review')->middleware('isDosen');
    Route::post('/bimbingan/cancel/acc', [BimbinganController::class, 'cancelAcc'])->name('bimbingan.cancel.acc')->middleware('isDosen');
    Route::post('/bimbingan/cancel/revisi', [BimbinganController::class, 'cancelRevisi'])->name('bimbingan.cancel.revisi')->middleware('isDosen');
    Route::get('/bimbingan/review-prodi/{id}', [BimbinganController::class, 'reviewProdi'])->name('bimbingan.review.prodi')->middleware('isProdi');
    Route::get('/bimbingan/review-admin/{id}', [BimbinganController::class, 'reviewAdmin'])->name('bimbingan.review.admin')->middleware('isAdmin');
    Route::get('/bimbingan/rekap-dosen', [BimbinganController::class, 'rekapDosen'])->name('bimbingan.rekap.dosen')->middleware('isProdi');
    Route::get('/bimbingan/input-prodi', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.prodi.input')->middleware('isProdi');
    Route::get('/bimbingan/input', [BimbinganController::class, 'bimbinganAdminInput'])->name('bimbingan.admin.input')->middleware('isAdmin');
    Route::get('/bimbingan-admin', [BimbinganController::class, 'bimbinganAdmin'])->name('bimbingan.admin')->middleware('isAdmin');
    Route::get('/bimbingan/input/{dosen_id}/{mahasiswa_id}', [BimbinganController::class, 'bimbinganAdminInputCreate'])->name('bimbingan.admin.input.create')->middleware('isAdminProdi');
    Route::post('/bimbingan/input/store', [BimbinganController::class, 'bimbinganAdminInputStore'])->name('bimbingan.admin.input.store')->middleware('isAdminProdi');

    // Seminar TA
    Route::get('/seminar/review/{id}', [SeminarController::class,'seminarReviewAdmin'])->name('seminar.review.admin')->middleware('isAdminProdi');
    Route::post('/seminar/acc', [SeminarController::class, 'accSeminar'])->name('seminar.acc')->middleware('isAdminProdi');
    Route::post('/seminar/revisi', [SeminarController::class, 'revisiSeminar'])->name('seminar.revisi')->middleware('isAdminProdi');
    Route::post('/seminar/cancel-acc', [SeminarController::class, 'cancelAcc'])->name('seminar.cancel.acc')->middleware('isAdminProdi');
    Route::post('/seminar/revisi/delete', [SeminarController::class, 'deleteRevisi'])->name('seminar.revisi.delete')->middleware('isAdminProdi');
    Route::post('/seminar/set/date-exam', [SeminarController::class, 'setDateExam'])->name('seminar.set.date.exam')->middleware('isAdminProdi');
    Route::get('/seminar/rekap', [SeminarController::class,'rekapSeminar'])->name('seminar.rekap')->middleware('isAdminProdi');

    // Ujian TA
    Route::get('/ujian/review/{id}', [UjianController::class,'ujianReviewAdmin'])->name('ujian.review.admin')->middleware('isAdminProdi');
    Route::post('/ujian/acc', [UjianController::class, 'accUjian'])->name('ujian.acc')->middleware('isAdminProdi');
    Route::post('/ujian/revisi', [UjianController::class, 'revisiUjian'])->name('ujian.revisi')->middleware('isAdminProdi');
    Route::post('/ujian/cancel-acc', [UjianController::class, 'cancelAcc'])->name('ujian.cancel.acc')->middleware('isAdminProdi');
    Route::post('/ujian/revisi/delete', [UjianController::class, 'deleteRevisi'])->name('ujian.revisi.delete')->middleware('isAdminProdi');
    Route::post('/ujian/set/date-exam', [UjianController::class, 'setDateExam'])->name('ujian.set.date.exam')->middleware('isAdminProdi');
    Route::get('/ujian/rekap', [UjianController::class,'rekapujian'])->name('ujian.rekap')->middleware('isAdminProdi');

    // Mahasiswa - Seminar & Ujian TA
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
        Route::get('/review/seminar/edit/{id}', [ReviewSeminarController::class, 'edit'])->name('review.seminar.edit');
        Route::post('/review/seminar/update', [ReviewSeminarController::class, 'update'])->name('review.seminar.update');
        Route::get('/review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManual'])->name('review.seminar.submit.acc.manual');
        Route::put('/review/seminar/submit-acc-manual/{id}', [ReviewSeminarController::class, 'submitManualStore'])->name('review.seminar.submit.acc.manual.store');

        Route::get('/ujian-mahasiswa', [UjianController::class, 'ujianMahasiswa'])->name('ujian.mahasiswa');
        Route::get('/ujian/create', [UjianController::class, 'create'])->name('ujian.create');
        Route::post('/ujian/store', [UjianController::class, 'store'])->name('ujian.store');
        Route::get('/ujian/detail/{id}', [UjianController::class, 'detail'])->name('ujian.detail');
        Route::get('/ujian/edit/{id}', [UjianController::class, 'edit'])->name('ujian.edit');
        Route::put('/ujian/update/{id}', [UjianController::class, 'update'])->name('ujian.update');
        Route::delete('/ujian/delete/{id}', [UjianController::class, 'delete'])->name('ujian.delete');
        Route::get('/ujian/reviews/{id}', [UjianController::class, 'ujianReviews'])->name('ujian.reviews');
        Route::get('/review/ujian/edit/{id}', [ReviewUjianController::class, 'edit'])->name('review.ujian.edit');
        Route::post('/review/ujian/update', [ReviewUjianController::class, 'update'])->name('review.ujian.update');
        Route::get('/ujian/edit/proposal/{id}', [UjianController::class, 'editProposal'])->name('ujian.edit.proposal');
        Route::put('/ujian/update/proposal/{id}', [UjianController::class, 'updateProposal'])->name('ujian.update.proposal');
        Route::get('/review/ujian/submit-acc-manual/{id}', [ReviewUjianController::class, 'submitManual'])->name('review.ujian.submit.acc.manual');
        Route::put('/review/ujian/submit-acc-manual/{id}', [ReviewUjianController::class, 'submitManualStore'])->name('review.ujian.submit.acc.manual.store');
    });

    // Admin - Seminar & Ujian TA
    Route::group(['middleware' => 'isAdmin'], function(){
        Route::get('/seminar-admin', [SeminarController::class,'seminarAdmin'])->name('seminar.admin');
        Route::get('/ujian-admin', [UjianController::class,'ujianAdmin'])->name('ujian.admin');
        Route::get('/bimbingan/canceled/{id}', [BimbinganController::class, 'bimbinganCanceled'])->name('bimbingan.canceled');
    });

    // Dosen - Seminar & Ujian TA
    Route::group(['middleware' => 'isDosen'], function(){
        Route::get('/seminar-dosen', [SeminarController::class,'seminarDosen'])->name('seminar.dosen');
        Route::get('/review/seminar/{id}', [ReviewSeminarController::class, 'reviewDosen'])->name('review.seminar.dosen');
        Route::post('/review/seminar/revisi/store', [ReviewSeminarController::class, 'revisiStore'])->name('review.seminar.revisi.store');
        Route::post('/review/seminar/revisi/delete', [ReviewSeminarController::class, 'revisiDelete'])->name('review.seminar.revisi.delete');
        Route::post('/review/seminar/acc', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc');
        Route::post('/review/seminar/nilai', [ReviewSeminarController::class, 'reviewNilai'])->name('review.seminar.nilai');
        Route::post('/review/seminar/cancel/acc', [ReviewSeminarController::class, 'reviewCancelAcc'])->name('review.seminar.cancel.acc');

        Route::get('/ujian-dosen', [UjianController::class,'ujianDosen'])->name('ujian.dosen');
        Route::get('/review/ujian/{id}', [ReviewUjianController::class, 'reviewDosen'])->name('review.ujian.dosen');
        Route::post('/review/ujian/revisi/store', [ReviewUjianController::class, 'revisiStore'])->name('review.ujian.revisi.store');
        Route::post('/review/ujian/revisi/delete', [ReviewUjianController::class, 'revisiDelete'])->name('review.ujian.revisi.delete');
        Route::post('/review/ujian/acc', [ReviewUjianController::class, 'reviewAcc'])->name('review.ujian.acc');
        Route::post('/review/ujian/nilai', [ReviewUjianController::class, 'reviewNilai'])->name('review.ujian.nilai');
        Route::post('/review/ujian/cancel/acc', [ReviewUjianController::class, 'reviewCancelAcc'])->name('review.ujian.cancel.acc');
    });

    // Prodi - Seminar & Ujian TA
    Route::group(['middleware' => 'isProdi'], function(){
        Route::get('/seminar-prodi', [SeminarController::class, 'seminarProdi'])->name('seminar.prodi');
        Route::get('/seminar/detail-prodi/{id}', [SeminarController::class, 'seminarProdiDetail'])->name('seminar.prodi.detail');
        Route::post('/review/seminar/acc-prodi', [ReviewSeminarController::class, 'reviewAcc'])->name('review.seminar.acc.prodi');
        Route::post('/seminar/update-status', [SeminarController::class, 'updateStatus'])->name('seminar.update.status');
        Route::post('/review/seminar/update-nilai', [ReviewSeminarController::class, 'updateNilai'])->name('review.update.nilai');

        Route::get('/ujian-prodi', [UjianController::class, 'ujianProdi'])->name('ujian.prodi');
        Route::get('/ujian/detail-prodi/{id}', [UjianController::class, 'ujianProdiDetail'])->name('ujian.prodi.detail');
        Route::post('/review/ujian/acc-prodi', [ReviewUjianController::class, 'reviewAcc'])->name('review.ujian.acc.prodi');
        Route::post('/ujian/update-status', [UjianController::class, 'updateStatus'])->name('ujian.update.status');
        Route::post('/review/ujian/update-nilai', [ReviewUjianController::class, 'updateNilai'])->name('review.update.nilai.ujian');
    });

    // Jilid TA
    Route::get('/dashboard-fotokopi',[JilidController::class, 'index'])->name('jilid.index')->middleware('isAdminFotokopi');
    Route::get('/jilid/detail/{id}',[JilidController::class, 'detail'])->name('jilid.detail')->middleware('isAdminFotokopi');
    Route::get('/jilid/detail-mahasiswa/{id}',[JilidController::class, 'detailMahasiswa'])->name('jilid.detail.mahasiswa')->middleware('isMahasiswa');
    Route::put('/jilid/acc/{id}',[JilidController::class, 'acc'])->name('jilid.acc')->middleware('isAdminFotokopi');
    Route::get('/jilid-mahasiswa',[JilidController::class, 'jilidMahasiswa'])->name('jilid.mahasiswa')->middleware('isMahasiswa');
    Route::get('/jilid/create',[JilidController::class, 'create'])->name('jilid.create')->middleware('isMahasiswa');
    Route::post('/jilid/store',[JilidController::class, 'store'])->name('jilid.store')->middleware('isMahasiswa');
    Route::get('/jilid/edit/{id}',[JilidController::class, 'edit'])->name('jilid.edit')->middleware('isMahasiswa');
    Route::put('/jilid/update/{id}',[JilidController::class, 'update'])->name('jilid.update')->middleware('isMahasiswa');
    Route::get('/jilid/confirm-completed/{id}',[JilidController::class, 'confirmCompleted'])->name('jilid.confirm.completed')->middleware('isAdmin');

    // Cetak Dokumen TA
    Route::group(['middleware' => 'isLogin'], function (){
        Route::get('/cetak/lembar-persetujuan-pembimbing-mahasiswa', [CetakController::class, 'cetakLembarPersetujuanMahasiswa'])->name('cetak.lembar.persetujuan.mahasiswa');
        Route::get('/cetak/lembar-pernyataan-keaslian', [CetakController::class, 'cetakLembarPernyataanKeaslian'])->name('cetak.lembar.pernyataan.keaslian');
        Route::get('/cetak/surat-tugas-bimbingan', [CetakController::class, 'cetakSuratTugasBimbinganMahasiswa'])->name('cetak.surat.tugas.bimbingan');
        Route::get('/cetak/surat-tugas-bimbingan/{pendaftaran}', [CetakController::class, 'cetakSuratTugasBimbingan'])->name('cetak.surat.tugas.bimbingan.pendaftaran');
        Route::get('/cetak/berita-acara-ujian-proposal/{seminar}', [CetakController::class, 'cetakBeritaAcaraUjianProposal'])->name('cetak.berita.acara.ujian.proposal');
        Route::get('/cetak/berita-acara-ujian-proposal-blank/{ujian_or_seminar}/{type}', [CetakController::class, 'cetakBeritaAcaraUjianProposalBlank'])->name('cetak.berita.acara.ujian.proposal.blank');
        Route::get('/cetak/berita-acara-ujian-pendadaran/{ujian}', [CetakController::class, 'cetakBeritaAcaraUjianPendadaran'])->name('cetak.berita.acara.ujian.pendadaran');
        Route::get('/cetak/surat-riwayat-bimbingan-mahasiswa', [CetakController::class, 'cetakRiwayatBimbinganMahasiswa'])->name('cetak.riwayat.bimbingan.mahasiswa');
        Route::get('/cetak/surat-riwayat-bimbingan/{id}', [CetakController::class, 'cetakRiwayatBimbingan'])->name('cetak.riwayat.bimbingan');
        Route::get('/cetak/lembar-persetujuan/{type}', [CetakController::class, 'cetakLembarPersetujuan'])->name('cetak.lembar.persetujuan');
        Route::get('/cetak/lembar-pengesahan', [CetakController::class, 'cetakLembarPengesahan'])->name('cetak.lembar.pengesahan');
    });

    // Public TA
    Route::get('/public/riwayat-bimbingan/{id}',[BimbinganController::class, 'public'])->name('bimbingan.public');
});
