<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan field untuk fitur input acc manual bimbingan
     */
    public function up(): void
    {
        Schema::table('bimbingan_kps', function (Blueprint $table) {
            $table->string('lampiran_acc')->nullable()->after('bukti_bimbingan_offline');
            $table->date('tanggal_manual_acc')->nullable()->after('lampiran_acc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan_kps', function (Blueprint $table) {
            $table->dropColumn(['lampiran_acc', 'tanggal_manual_acc']);
        });
    }
};
