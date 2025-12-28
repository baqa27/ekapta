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
        Schema::table('himpunans', function (Blueprint $table) {
            $table->integer('biaya_seminar')->default(25000)->after('is_pendaftaran_seminar_open');
            $table->string('nama_rekening')->nullable()->after('biaya_seminar');
            $table->string('nomor_rekening')->nullable()->after('nama_rekening');
            $table->string('bank')->nullable()->after('nomor_rekening');
            $table->string('nomor_dana')->nullable()->after('bank');
            $table->string('nomor_seabank')->nullable()->after('nomor_dana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('himpunans', function (Blueprint $table) {
            $table->dropColumn(['biaya_seminar', 'nama_rekening', 'nomor_rekening', 'bank', 'nomor_dana', 'nomor_seabank']);
        });
    }
};
