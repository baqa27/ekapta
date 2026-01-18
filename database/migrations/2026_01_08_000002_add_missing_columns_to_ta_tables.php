<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang kurang pada tabel TA
     * Berdasarkan model yang sudah ada di ekapta-ta
     */
    public function up(): void
    {
        // Tambah kolom mahasiswa_id ke pendaftarans (TA)
        if (Schema::hasTable('pendaftarans') && !Schema::hasColumn('pendaftarans', 'mahasiswa_id')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('pengajuan_id');
                $table->string('email')->nullable()->after('mahasiswa_id');
                $table->string('hp')->nullable()->after('email');
                $table->string('semester')->nullable()->after('hp');
                $table->string('nomor_pembayaran')->nullable()->after('semester');
                $table->timestamp('tanggal_pembayaran')->nullable()->after('nomor_pembayaran');
                $table->decimal('biaya', 15, 2)->nullable()->after('tanggal_pembayaran');
                $table->string('lampiran_1')->nullable()->after('biaya');
                $table->string('lampiran_2')->nullable()->after('lampiran_1');
                $table->string('lampiran_3')->nullable()->after('lampiran_2');
                $table->string('lampiran_4')->nullable()->after('lampiran_3');
                $table->string('lampiran_5')->nullable()->after('lampiran_4');
                $table->string('lampiran_acc')->nullable()->after('lampiran_5');

                $table->foreign('mahasiswa_id', 'pendaftarans_ta_mhs_fk')->references('id')->on('mahasiswas')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pendaftarans')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->dropForeign('pendaftarans_ta_mhs_fk');
                $table->dropColumn([
                    'mahasiswa_id',
                    'email',
                    'hp',
                    'semester',
                    'nomor_pembayaran',
                    'tanggal_pembayaran',
                    'biaya',
                    'lampiran_1',
                    'lampiran_2',
                    'lampiran_3',
                    'lampiran_4',
                    'lampiran_5',
                    'lampiran_acc',
                ]);
            });
        }
    }
};
