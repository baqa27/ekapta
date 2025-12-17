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
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->string('sertifikat_peserta_1')->nullable()->after('lampiran_7');
            $table->string('sertifikat_peserta_2')->nullable()->after('sertifikat_peserta_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->dropColumn(['sertifikat_peserta_1', 'sertifikat_peserta_2']);
        });
    }
};
