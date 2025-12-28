<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename tabel sesi_seminars ke sesi_seminar_kps
     */
    public function up(): void
    {
        if (Schema::hasTable('sesi_seminars') && !Schema::hasTable('sesi_seminar_kps')) {
            Schema::rename('sesi_seminars', 'sesi_seminar_kps');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sesi_seminar_kps') && !Schema::hasTable('sesi_seminars')) {
            Schema::rename('sesi_seminar_kps', 'sesi_seminars');
        }
    }
};
