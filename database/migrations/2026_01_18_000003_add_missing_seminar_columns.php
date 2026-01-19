<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang hilang ke tabel seminars (TA)
     * Sesuaikan dengan struktur asli ekapta-ta
     */
    public function up(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                // Kolom lampiran asli TA
                if (!Schema::hasColumn('seminars', 'lampiran_1')) {
                    $table->string('lampiran_1')->nullable()->after('pengajuan_id');
                }
                if (!Schema::hasColumn('seminars', 'lampiran_2')) {
                    $table->string('lampiran_2')->nullable()->after('lampiran_1');
                }
                if (!Schema::hasColumn('seminars', 'lampiran_3')) {
                    $table->string('lampiran_3')->nullable()->after('lampiran_2');
                }
                if (!Schema::hasColumn('seminars', 'jumlah_bayar')) {
                    $table->string('jumlah_bayar')->nullable()->after('lampiran_3');
                }
                if (!Schema::hasColumn('seminars', 'nomor_pembayaran')) {
                    $table->string('nomor_pembayaran')->nullable()->after('jumlah_bayar');
                }
                if (!Schema::hasColumn('seminars', 'lampiran_proposal')) {
                    $table->string('lampiran_proposal')->nullable()->after('nomor_pembayaran');
                }
                if (!Schema::hasColumn('seminars', 'tanggal_acc')) {
                    $table->timestamp('tanggal_acc')->nullable()->after('is_valid');
                }
                if (!Schema::hasColumn('seminars', 'tanggal_ujian')) {
                    $table->timestamp('tanggal_ujian')->nullable()->after('tanggal_acc');
                }
                if (!Schema::hasColumn('seminars', 'tempat_ujian')) {
                    $table->string('tempat_ujian')->nullable()->after('tanggal_ujian');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seminars')) {
            Schema::table('seminars', function (Blueprint $table) {
                $table->dropColumn([
                    'lampiran_1',
                    'lampiran_2',
                    'lampiran_3',
                    'jumlah_bayar',
                    'nomor_pembayaran',
                    'lampiran_proposal',
                    'tanggal_acc',
                    'tanggal_ujian',
                    'tempat_ujian',
                ]);
            });
        }
    }
};
