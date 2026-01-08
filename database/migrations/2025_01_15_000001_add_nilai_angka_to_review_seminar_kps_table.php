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
        Schema::table('review_seminar_kps', function (Blueprint $table) {
            if (!Schema::hasColumn('review_seminar_kps', 'nilai_angka')) {
                $table->decimal('nilai_angka', 5, 2)->nullable()->after('nilai_4');
            }
            if (!Schema::hasColumn('review_seminar_kps', 'status_hasil')) {
                $table->string('status_hasil')->nullable()->after('nilai_angka');
            }
            if (!Schema::hasColumn('review_seminar_kps', 'catatan_penguji')) {
                $table->text('catatan_penguji')->nullable()->after('status_hasil');
            }
            if (!Schema::hasColumn('review_seminar_kps', 'is_dinilai')) {
                $table->boolean('is_dinilai')->default(false)->after('catatan_penguji');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_seminar_kps', function (Blueprint $table) {
            $table->dropColumn(['nilai_angka', 'status_hasil', 'catatan_penguji', 'is_dinilai']);
        });
    }
};
