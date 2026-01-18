<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel-tabel untuk sistem Tugas Akhir (TA)
     * Tabel TA TANPA suffix _kp
     * Tabel KP sudah ada dengan suffix _kp
     */
    public function up(): void
    {
        // =====================================================
        // TABEL UTAMA TA
        // =====================================================

        // Tabel pengajuans (TA)
        if (!Schema::hasTable('pengajuans')) {
            Schema::create('pengajuans', function (Blueprint $table) {
                $table->id();
                $table->string('judul');
                $table->text('deskripsi');
                $table->string('lampiran');
                $table->timestamp('tanggal_acc')->nullable();
                $table->string('status')->default('review');
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->unsignedBigInteger('prodi_id');
                $table->foreign('mahasiswa_id', 'pengajuans_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
                $table->foreign('prodi_id', 'pengajuans_ta_prodi_fk')->references('id')->on('prodis')->onDelete('cascade');
            });
        }

        // Tabel bagians (TA) - harus dibuat sebelum bimbingans
        if (!Schema::hasTable('bagians')) {
            Schema::create('bagians', function (Blueprint $table) {
                $table->id();
                $table->string('bagian');
                $table->tinyInteger('is_seminar')->default(0);
                $table->tinyInteger('is_pendadaran')->default(0);
                $table->string('tahun_masuk')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('prodi_id');
                $table->foreign('prodi_id', 'bagians_ta_prodi_fk')->references('id')->on('prodis')->onDelete('cascade');
            });
        }

        // Tabel pendaftarans (TA)
        if (!Schema::hasTable('pendaftarans')) {
            Schema::create('pendaftarans', function (Blueprint $table) {
                $table->id();
                $table->string('acc_kaprodi')->nullable();
                $table->string('ktm')->nullable();
                $table->string('transkrip')->nullable();
                $table->string('sertifikat_kkl')->nullable();
                $table->string('bukti_aktif')->nullable();
                $table->string('bukti_pembayaran')->nullable();
                $table->string('lembar_persetujuan')->nullable();
                $table->string('status')->default('review');
                $table->timestamp('tanggal_acc')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('pengajuan_id');
                $table->foreign('pengajuan_id', 'pendaftarans_ta_pengajuan_fk')->references('id')->on('pengajuans')->onDelete('cascade');
            });
        }

        // Tabel bimbingans (TA)
        if (!Schema::hasTable('bimbingans')) {
            Schema::create('bimbingans', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamp('tanggal_bimbingan')->nullable();
                $table->timestamp('tanggal_acc')->nullable();
                $table->string('status')->nullable();
                $table->string('pembimbing')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->unsignedBigInteger('bagian_id');
                $table->foreign('mahasiswa_id', 'bimbingans_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
                $table->foreign('bagian_id', 'bimbingans_ta_bagian_fk')->references('id')->on('bagians')->onDelete('cascade');
            });
        }

        // Tabel seminars (TA)
        if (!Schema::hasTable('seminars')) {
            Schema::create('seminars', function (Blueprint $table) {
                $table->id();
                $table->string('lampiran_bukti_pembayaran')->nullable();
                $table->string('lampiran_bukti_turnitin')->nullable();
                $table->string('lampiran_laporan')->nullable();
                $table->string('lampiran_lembar_pengesahan')->nullable();
                $table->string('lampiran_sertifikat1')->nullable();
                $table->string('lampiran_sertifikat2')->nullable();
                $table->string('lampiran_sertifikat3')->nullable();
                $table->string('lampiran_sertifikat4')->nullable();
                $table->boolean('is_valid')->default(false);
                $table->boolean('is_lulus')->default(false);
                $table->timestamp('tanggal_seminar')->nullable();
                $table->string('waktu_seminar')->nullable();
                $table->string('ruang_seminar')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id', 'seminars_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }

        // Tabel ujians (TA)
        if (!Schema::hasTable('ujians')) {
            Schema::create('ujians', function (Blueprint $table) {
                $table->id();
                $table->string('lampiran_bukti_pembayaran')->nullable();
                $table->string('lampiran_bukti_turnitin')->nullable();
                $table->string('lampiran_laporan')->nullable();
                $table->string('lampiran_lembar_pengesahan')->nullable();
                $table->string('lampiran_sertifikat1')->nullable();
                $table->string('lampiran_sertifikat2')->nullable();
                $table->string('lampiran_sertifikat3')->nullable();
                $table->string('lampiran_sertifikat4')->nullable();
                $table->boolean('is_valid')->default(false);
                $table->boolean('is_lulus')->default(false);
                $table->timestamp('tanggal_ujian')->nullable();
                $table->string('waktu_ujian')->nullable();
                $table->string('ruang_ujian')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id', 'ujians_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }

        // Tabel jilids (TA)
        if (!Schema::hasTable('jilids')) {
            Schema::create('jilids', function (Blueprint $table) {
                $table->id();
                $table->string('lampiran_laporan_word')->nullable();
                $table->string('lampiran_laporan_pdf')->nullable();
                $table->string('lampiran_lembar_pengesahan')->nullable();
                $table->string('lampiran_file_project')->nullable();
                $table->string('lampiran_berita_acara')->nullable();
                $table->string('status')->default('review');
                $table->boolean('is_completed')->default(false);
                $table->text('catatan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id', 'jilids_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }

        // =====================================================
        // TABEL REVISI TA
        // =====================================================

        // Tabel revisi_pengajuans (TA)
        if (!Schema::hasTable('revisi_pengajuans')) {
            Schema::create('revisi_pengajuans', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('pengajuan_id');
                $table->foreign('pengajuan_id', 'revisi_pengajuans_ta_fk')->references('id')->on('pengajuans')->onDelete('cascade');
            });
        }

        // Tabel revisi_pendaftarans (TA)
        if (!Schema::hasTable('revisi_pendaftarans')) {
            Schema::create('revisi_pendaftarans', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('pendaftaran_id');
                $table->foreign('pendaftaran_id', 'revisi_pendaftarans_ta_fk')->references('id')->on('pendaftarans')->onDelete('cascade');
            });
        }

        // Tabel revisi_bimbingans (TA)
        if (!Schema::hasTable('revisi_bimbingans')) {
            Schema::create('revisi_bimbingans', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('bimbingan_id');
                $table->foreign('bimbingan_id', 'revisi_bimbingans_ta_fk')->references('id')->on('bimbingans')->onDelete('cascade');
            });
        }

        // Tabel revisi_seminars (TA)
        if (!Schema::hasTable('revisi_seminars')) {
            Schema::create('revisi_seminars', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('seminar_id');
                $table->foreign('seminar_id', 'revisi_seminars_ta_fk')->references('id')->on('seminars')->onDelete('cascade');
            });
        }

        // Tabel revisi_ujians (TA)
        if (!Schema::hasTable('revisi_ujians')) {
            Schema::create('revisi_ujians', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('ujian_id');
                $table->foreign('ujian_id', 'revisi_ujians_ta_fk')->references('id')->on('ujians')->onDelete('cascade');
            });
        }

        // Tabel revisi_jilids (TA)
        if (!Schema::hasTable('revisi_jilids')) {
            Schema::create('revisi_jilids', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('jilid_id');
                $table->foreign('jilid_id', 'revisi_jilids_ta_fk')->references('id')->on('jilids')->onDelete('cascade');
            });
        }

        // =====================================================
        // TABEL REVIEW TA
        // =====================================================

        // Tabel review_seminars (TA)
        if (!Schema::hasTable('review_seminars')) {
            Schema::create('review_seminars', function (Blueprint $table) {
                $table->id();
                $table->string('nilai')->nullable();
                $table->text('catatan')->nullable();
                $table->string('status')->default('review');
                $table->timestamps();
                $table->unsignedBigInteger('seminar_id');
                $table->unsignedBigInteger('dosen_id');
                $table->foreign('seminar_id', 'review_seminars_ta_seminar_fk')->references('id')->on('seminars')->onDelete('cascade');
                $table->foreign('dosen_id', 'review_seminars_ta_dosen_fk')->references('id')->on('dosens')->onDelete('cascade');
            });
        }

        // Tabel review_ujians (TA)
        if (!Schema::hasTable('review_ujians')) {
            Schema::create('review_ujians', function (Blueprint $table) {
                $table->id();
                $table->string('nilai')->nullable();
                $table->text('catatan')->nullable();
                $table->string('status')->default('review');
                $table->timestamps();
                $table->unsignedBigInteger('ujian_id');
                $table->unsignedBigInteger('dosen_id');
                $table->foreign('ujian_id', 'review_ujians_ta_ujian_fk')->references('id')->on('ujians')->onDelete('cascade');
                $table->foreign('dosen_id', 'review_ujians_ta_dosen_fk')->references('id')->on('dosens')->onDelete('cascade');
            });
        }

        // Tabel revisi_review_seminars (TA)
        if (!Schema::hasTable('revisi_review_seminars')) {
            Schema::create('revisi_review_seminars', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('review_seminar_id');
                $table->foreign('review_seminar_id', 'revisi_review_seminars_ta_fk')->references('id')->on('review_seminars')->onDelete('cascade');
            });
        }

        // Tabel revisi_review_ujians (TA)
        if (!Schema::hasTable('revisi_review_ujians')) {
            Schema::create('revisi_review_ujians', function (Blueprint $table) {
                $table->id();
                $table->text('keterangan')->nullable();
                $table->string('lampiran')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('review_ujian_id');
                $table->foreign('review_ujian_id', 'revisi_review_ujians_ta_fk')->references('id')->on('review_ujians')->onDelete('cascade');
            });
        }

        // =====================================================
        // TABEL PIVOT & PENDUKUNG TA
        // =====================================================

        // Tabel dosen_bimbingans (TA)
        if (!Schema::hasTable('dosen_bimbingans')) {
            Schema::create('dosen_bimbingans', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->unsignedBigInteger('dosen_id');
                $table->unsignedBigInteger('bimbingan_id');
                $table->foreign('dosen_id', 'dosen_bimbingans_ta_dosen_fk')->references('id')->on('dosens')->onDelete('cascade');
                $table->foreign('bimbingan_id', 'dosen_bimbingans_ta_bimbingan_fk')->references('id')->on('bimbingans')->onDelete('cascade');
            });
        }

        // Tabel bimbingan_canceleds (TA)
        if (!Schema::hasTable('bimbingan_canceleds')) {
            Schema::create('bimbingan_canceleds', function (Blueprint $table) {
                $table->id();
                $table->text('alasan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id', 'bimbingan_canceleds_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }

        // Tabel seminar_canceleds (TA)
        if (!Schema::hasTable('seminar_canceleds')) {
            Schema::create('seminar_canceleds', function (Blueprint $table) {
                $table->id();
                $table->text('alasan')->nullable();
                $table->timestamps();
                $table->unsignedBigInteger('mahasiswa_id');
                $table->foreign('mahasiswa_id', 'seminar_canceleds_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tabel pendukung
        Schema::dropIfExists('seminar_canceleds');
        Schema::dropIfExists('bimbingan_canceleds');
        Schema::dropIfExists('dosen_bimbingans');

        // Drop tabel revisi review
        Schema::dropIfExists('revisi_review_ujians');
        Schema::dropIfExists('revisi_review_seminars');

        // Drop tabel review
        Schema::dropIfExists('review_ujians');
        Schema::dropIfExists('review_seminars');

        // Drop tabel revisi
        Schema::dropIfExists('revisi_jilids');
        Schema::dropIfExists('revisi_ujians');
        Schema::dropIfExists('revisi_seminars');
        Schema::dropIfExists('revisi_bimbingans');
        Schema::dropIfExists('revisi_pendaftarans');
        Schema::dropIfExists('revisi_pengajuans');

        // Drop tabel utama
        Schema::dropIfExists('jilids');
        Schema::dropIfExists('ujians');
        Schema::dropIfExists('seminars');
        Schema::dropIfExists('bimbingans');
        Schema::dropIfExists('pendaftarans');
        Schema::dropIfExists('bagians');
        Schema::dropIfExists('pengajuans');
    }
};
