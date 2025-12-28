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
            $table->unsignedBigInteger('prodi_id')->nullable()->after('reviewer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revisi_bimbingan_kps', function (Blueprint $table) {
            $table->dropColumn('prodi_id');
        });
    }
};
