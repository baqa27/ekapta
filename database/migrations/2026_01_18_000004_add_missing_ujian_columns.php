<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang hilang ke tabel ujians (TA)
     * Sesuaikan dengan struktur asli ekapta-ta
     */
    public function up(): void
    {
        if (Schema::hasTable('ujians')) {
            Schema::table('ujians', function (Blueprint $table) {
                // Tambah pengajuan_id jika belum ada
                if (!Schema::hasColumn('ujians', 'pengajuan_id')) {
                    $table->unsignedBigInteger('pengajuan_id')->nullable()->after('id');
                    $table->foreign('pengajuan_id', 'ujians_ta_pengajuan_fk')->references('id')->on('pengajuans')->onDelete('cascade');
                }
                
                // Kolom lampiran asli TA
                if (!Schema::hasColumn('ujians', 'lampiran_1')) {
                    $table->string('lampiran_1')->nullable()->after('pengajuan_id');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_2')) {
                    $table->string('lampiran_2')->nullable()->after('lampiran_1');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_3')) {
                    $table->string('lampiran_3')->nullable()->after('lampiran_2');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_4')) {
                    $table->string('lampiran_4')->nullable()->after('lampiran_3');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_5')) {
                    $table->string('lampiran_5')->nullable()->after('lampiran_4');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_6')) {
                    $table->string('lampiran_6')->nullable()->after('lampiran_5');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_7')) {
                    $table->string('lampiran_7')->nullable()->after('lampiran_6');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_8')) {
                    $table->string('lampiran_8')->nullable()->after('lampiran_7');
                }
                if (!Schema::hasColumn('ujians', 'lampiran_proposal')) {
                    $table->string('lampiran_proposal')->nullable()->after('lampiran_8');
                }
                if (!Schema::hasColumn('ujians', 'tanggal_acc')) {
                    $table->timestamp('tanggal_acc')->nullable()->after('is_valid');
                }
                if (!Schema::hasColumn('ujians', 'tempat_ujian')) {
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
        if (Schema::hasTable('ujians')) {
            Schema::table('ujians', function (Blueprint $table) {
                if (Schema::hasColumn('ujians', 'pengajuan_id')) {
                    $table->dropForeign('ujians_ta_pengajuan_fk');
                }
                $table->dropColumn([
                    'pengajuan_id',
                    'lampiran_1',
                    'lampiran_2',
                    'lampiran_3',
                    'lampiran_4',
                    'lampiran_5',
                    'lampiran_6',
                    'lampiran_7',
                    'lampiran_8',
                    'lampiran_proposal',
                    'tanggal_acc',
                    'tempat_ujian',
                ]);
            });
        }
    }
};
