<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuans', 'lokasi_kp')) {
                $table->string('lokasi_kp')->nullable();
            }
            if (!Schema::hasColumn('pengajuans', 'alamat_instansi')) {
                $table->text('alamat_instansi')->nullable();
            }
            if (!Schema::hasColumn('pengajuans', 'files_pendukung')) {
                $table->text('files_pendukung')->nullable();
            }
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftarans', 'lampiran_6')) {
                $table->string('lampiran_6')->nullable();
            }
            if (!Schema::hasColumn('pendaftarans', 'lampiran_7')) {
                $table->string('lampiran_7')->nullable();
            }
            if (!Schema::hasColumn('pendaftarans', 'lampiran_8')) {
                $table->string('lampiran_8')->nullable();
            }
        });

        Schema::table('bimbingans', function (Blueprint $table) {
            if (!Schema::hasColumn('bimbingans', 'bukti_bimbingan_offline')) {
                $table->string('bukti_bimbingan_offline')->nullable();
            }
        });

        Schema::table('seminars', function (Blueprint $table) {
            if (!Schema::hasColumn('seminars', 'judul_laporan')) {
                $table->string('judul_laporan')->nullable();
            }
            if (!Schema::hasColumn('seminars', 'file_laporan')) {
                $table->string('file_laporan')->nullable();
            }
            if (!Schema::hasColumn('seminars', 'file_pengesahan')) {
                $table->string('file_pengesahan')->nullable();
            }
            if (!Schema::hasColumn('seminars', 'bukti_bayar')) {
                $table->string('bukti_bayar')->nullable();
            }
            if (!Schema::hasColumn('seminars', 'lampiran_4')) {
                $table->string('lampiran_4')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn(['lokasi_kp', 'alamat_instansi', 'files_pendukung']);
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['lampiran_6', 'lampiran_7', 'lampiran_8']);
        });

        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['bukti_bimbingan_offline']);
        });

        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn(['judul_laporan', 'file_laporan', 'file_pengesahan', 'bukti_bayar', 'lampiran_4']);
        });
    }
};
