<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename tabel-tabel KP dengan suffix _kp
     * Tabel master (admin, dekan, dosen, fakultas, prodi, mahasiswa, mahasiswa_details) TIDAK di-rename
     */
    public function up()
    {
        // Rename tabel utama KP
        if (Schema::hasTable('pengajuans') && !Schema::hasTable('pengajuan_kps')) {
            Schema::rename('pengajuans', 'pengajuan_kps');
        }
        
        if (Schema::hasTable('pendaftarans') && !Schema::hasTable('pendaftaran_kps')) {
            Schema::rename('pendaftarans', 'pendaftaran_kps');
        }
        
        if (Schema::hasTable('bimbingans') && !Schema::hasTable('bimbingan_kps')) {
            Schema::rename('bimbingans', 'bimbingan_kps');
        }
        
        if (Schema::hasTable('seminars') && !Schema::hasTable('seminar_kps')) {
            Schema::rename('seminars', 'seminar_kps');
        }
        
        if (Schema::hasTable('jilids') && !Schema::hasTable('jilid_kps')) {
            Schema::rename('jilids', 'jilid_kps');
        }
        
        // Rename tabel revisi
        if (Schema::hasTable('revisi_pengajuans') && !Schema::hasTable('revisi_pengajuan_kps')) {
            Schema::rename('revisi_pengajuans', 'revisi_pengajuan_kps');
        }
        
        if (Schema::hasTable('revisi_pendaftarans') && !Schema::hasTable('revisi_pendaftaran_kps')) {
            Schema::rename('revisi_pendaftarans', 'revisi_pendaftaran_kps');
        }
        
        if (Schema::hasTable('revisi_bimbingans') && !Schema::hasTable('revisi_bimbingan_kps')) {
            Schema::rename('revisi_bimbingans', 'revisi_bimbingan_kps');
        }
        
        if (Schema::hasTable('revisi_seminars') && !Schema::hasTable('revisi_seminar_kps')) {
            Schema::rename('revisi_seminars', 'revisi_seminar_kps');
        }
        
        if (Schema::hasTable('revisi_jilids') && !Schema::hasTable('revisi_jilid_kps')) {
            Schema::rename('revisi_jilids', 'revisi_jilid_kps');
        }
        
        // Rename tabel pendukung
        if (Schema::hasTable('bagians') && !Schema::hasTable('bagian_kps')) {
            Schema::rename('bagians', 'bagian_kps');
        }
        
        if (Schema::hasTable('dosen_bimbingans') && !Schema::hasTable('dosen_bimbingan_kps')) {
            Schema::rename('dosen_bimbingans', 'dosen_bimbingan_kps');
        }
        
        if (Schema::hasTable('review_seminars') && !Schema::hasTable('review_seminar_kps')) {
            Schema::rename('review_seminars', 'review_seminar_kps');
        }
        
        if (Schema::hasTable('revisi_review_seminars') && !Schema::hasTable('revisi_review_seminar_kps')) {
            Schema::rename('revisi_review_seminars', 'revisi_review_seminar_kps');
        }
        
        if (Schema::hasTable('bimbingan_canceleds') && !Schema::hasTable('bimbingan_canceled_kps')) {
            Schema::rename('bimbingan_canceleds', 'bimbingan_canceled_kps');
        }
        
        if (Schema::hasTable('seminar_canceleds') && !Schema::hasTable('seminar_canceled_kps')) {
            Schema::rename('seminar_canceleds', 'seminar_canceled_kps');
        }
    }

    public function down()
    {
        // Rollback - rename kembali ke nama asli
        if (Schema::hasTable('pengajuan_kps')) {
            Schema::rename('pengajuan_kps', 'pengajuans');
        }
        
        if (Schema::hasTable('pendaftaran_kps')) {
            Schema::rename('pendaftaran_kps', 'pendaftarans');
        }
        
        if (Schema::hasTable('bimbingan_kps')) {
            Schema::rename('bimbingan_kps', 'bimbingans');
        }
        
        if (Schema::hasTable('seminar_kps')) {
            Schema::rename('seminar_kps', 'seminars');
        }
        
        if (Schema::hasTable('jilid_kps')) {
            Schema::rename('jilid_kps', 'jilids');
        }
        
        if (Schema::hasTable('revisi_pengajuan_kps')) {
            Schema::rename('revisi_pengajuan_kps', 'revisi_pengajuans');
        }
        
        if (Schema::hasTable('revisi_pendaftaran_kps')) {
            Schema::rename('revisi_pendaftaran_kps', 'revisi_pendaftarans');
        }
        
        if (Schema::hasTable('revisi_bimbingan_kps')) {
            Schema::rename('revisi_bimbingan_kps', 'revisi_bimbingans');
        }
        
        if (Schema::hasTable('revisi_seminar_kps')) {
            Schema::rename('revisi_seminar_kps', 'revisi_seminars');
        }
        
        if (Schema::hasTable('revisi_jilid_kps')) {
            Schema::rename('revisi_jilid_kps', 'revisi_jilids');
        }
        
        if (Schema::hasTable('bagian_kps')) {
            Schema::rename('bagian_kps', 'bagians');
        }
        
        if (Schema::hasTable('dosen_bimbingan_kps')) {
            Schema::rename('dosen_bimbingan_kps', 'dosen_bimbingans');
        }
        
        if (Schema::hasTable('review_seminar_kps')) {
            Schema::rename('review_seminar_kps', 'review_seminars');
        }
        
        if (Schema::hasTable('revisi_review_seminar_kps')) {
            Schema::rename('revisi_review_seminar_kps', 'revisi_review_seminars');
        }
        
        if (Schema::hasTable('bimbingan_canceled_kps')) {
            Schema::rename('bimbingan_canceled_kps', 'bimbingan_canceleds');
        }
        
        if (Schema::hasTable('seminar_canceled_kps')) {
            Schema::rename('seminar_canceled_kps', 'seminar_canceleds');
        }
    }
};
