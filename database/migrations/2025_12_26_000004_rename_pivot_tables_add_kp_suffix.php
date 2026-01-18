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
        // Rename pivot table dosen_bimbingans ke dosen_bimbingan_kps
        if (Schema::hasTable('dosen_bimbingans') && !Schema::hasTable('dosen_bimbingan_kps')) {
            Schema::rename('dosen_bimbingans', 'dosen_bimbingan_kps');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('dosen_bimbingan_kps') && !Schema::hasTable('dosen_bimbingans')) {
            Schema::rename('dosen_bimbingan_kps', 'dosen_bimbingans');
        }
    }
};
