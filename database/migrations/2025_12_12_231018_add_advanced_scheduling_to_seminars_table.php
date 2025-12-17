<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            // Tambah kolom tanggal_ujian dan tempat_ujian dulu jika belum ada
            if (!Schema::hasColumn('seminars', 'tanggal_ujian')) {
                $table->timestamp('tanggal_ujian')->nullable()->after('tanggal_acc');
            }
            if (!Schema::hasColumn('seminars', 'tempat_ujian')) {
                $table->string('tempat_ujian')->nullable()->after('tanggal_ujian');
            }
            // Tambah kolom scheduling
            if (!Schema::hasColumn('seminars', 'urutan_presentasi')) {
                $table->integer('urutan_presentasi')->nullable()->after('tempat_ujian');
            }
            if (!Schema::hasColumn('seminars', 'jumlah_per_sesi')) {
                $table->integer('jumlah_per_sesi')->default(5)->after('urutan_presentasi');
            }
            if (!Schema::hasColumn('seminars', 'link_penilaian')) {
                $table->string('link_penilaian', 100)->nullable()->unique()->after('jumlah_per_sesi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn(['tanggal_ujian', 'tempat_ujian', 'urutan_presentasi', 'jumlah_per_sesi', 'link_penilaian']);
        });
    }
};
