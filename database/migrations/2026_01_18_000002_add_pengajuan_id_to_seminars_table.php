<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom pengajuan_id ke tabel seminars (TA)
     * Kolom ini diperlukan untuk relasi Pengajuan -> Seminar di sistem TA
     */
    public function up(): void
    {
        if (Schema::hasTable('seminars') && !Schema::hasColumn('seminars', 'pengajuan_id')) {
            Schema::table('seminars', function (Blueprint $table) {
                $table->unsignedBigInteger('pengajuan_id')->nullable()->after('id');
                $table->foreign('pengajuan_id', 'seminars_ta_pengajuan_fk')->references('id')->on('pengajuans')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seminars') && Schema::hasColumn('seminars', 'pengajuan_id')) {
            Schema::table('seminars', function (Blueprint $table) {
                $table->dropForeign('seminars_ta_pengajuan_fk');
                $table->dropColumn('pengajuan_id');
            });
        }
    }
};
