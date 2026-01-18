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
        Schema::table('revisi_bimbingan_kps', function (Blueprint $table) {
            $table->enum('reviewer_type', ['dosen', 'prodi'])->default('dosen')->after('dosen_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisi_bimbingan_kps', function (Blueprint $table) {
            $table->dropColumn('reviewer_type');
        });
    }
};
