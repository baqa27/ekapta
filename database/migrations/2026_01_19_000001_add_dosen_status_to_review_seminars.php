<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing dosen_status column to review_seminars table
     * This column is required by the ReviewSeminar model and SeminarController
     */
    public function up(): void
    {
        if (Schema::hasTable('review_seminars') && !Schema::hasColumn('review_seminars', 'dosen_status')) {
            Schema::table('review_seminars', function (Blueprint $table) {
                $table->string('dosen_status')->after('status')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('review_seminars') && Schema::hasColumn('review_seminars', 'dosen_status')) {
            Schema::table('review_seminars', function (Blueprint $table) {
                $table->dropColumn('dosen_status');
            });
        }
    }
};
