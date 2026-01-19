<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah field-field baru untuk workflow KP:
     * - Pengajuan: jumlah_tolak untuk tracking max 2x tolak
     * - Seminar: link_akses_produk untuk pendaftaran seminar
     * - Hapus presentase_nilai dan nilai_akhir dari seminar
     * - Surat: masa_berlaku
     */
    public function up()
    {
        // Tambah field jumlah_tolak di pengajuan_kps
        $pengajuanTable = Schema::hasTable('pengajuan_kps') ? 'pengajuan_kps' : 'pengajuans';
        Schema::table($pengajuanTable, function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'jumlah_tolak')) {
                $table->integer('jumlah_tolak')->default(0)->after('status');
            }
        });

        // Tambah field link_akses_produk di seminar_kps
        $seminarTable = Schema::hasTable('seminar_kps') ? 'seminar_kps' : 'seminars';
        Schema::table($seminarTable, function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'link_akses_produk')) {
                $table->string('link_akses_produk')->nullable()->after('lampiran_proposal');
            }
            if (!Schema::hasColumn($table->getTable(), 'dokumen_penilaian')) {
                $table->string('dokumen_penilaian')->nullable()->after('link_akses_produk');
            }
        });

        // Tambah field masa_berlaku di pendaftaran_kps (untuk surat tugas)
        $pendaftaranTable = Schema::hasTable('pendaftaran_kps') ? 'pendaftaran_kps' : 'pendaftarans';
        Schema::table($pendaftaranTable, function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'masa_berlaku_surat')) {
                $table->date('masa_berlaku_surat')->nullable()->after('file_lembar_bimbingan');
            }
        });

        // Hapus tabel presentase_nilais jika ada (tidak dipakai di KP)
        // REVISI: Tabel ini masih dipakai di TA, jadi jangan dihapus di unified database.
        // if (Schema::hasTable('presentase_nilais')) {
        //    Schema::dropIfExists('presentase_nilais');
        // }
    }

    public function down()
    {
        $pengajuanTable = Schema::hasTable('pengajuan_kps') ? 'pengajuan_kps' : 'pengajuans';
        Schema::table($pengajuanTable, function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'jumlah_tolak')) {
                $table->dropColumn('jumlah_tolak');
            }
        });

        $seminarTable = Schema::hasTable('seminar_kps') ? 'seminar_kps' : 'seminars';
        Schema::table($seminarTable, function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'link_akses_produk')) {
                $table->dropColumn('link_akses_produk');
            }
            if (Schema::hasColumn($table->getTable(), 'dokumen_penilaian')) {
                $table->dropColumn('dokumen_penilaian');
            }
        });

        $pendaftaranTable = Schema::hasTable('pendaftaran_kps') ? 'pendaftaran_kps' : 'pendaftarans';
        Schema::table($pendaftaranTable, function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'masa_berlaku_surat')) {
                $table->dropColumn('masa_berlaku_surat');
            }
        });
    }
};
