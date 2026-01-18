<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            if (!Schema::hasColumn('seminars', 'catatan_himpunan')) {
                $table->text('catatan_himpunan')->nullable()->after('status_seminar');
            }
            if (!Schema::hasColumn('seminars', 'catatan_penguji')) {
                $table->text('catatan_penguji')->nullable()->after('catatan_himpunan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('seminars', 'catatan_himpunan')) {
                $columns[] = 'catatan_himpunan';
            }
            if (Schema::hasColumn('seminars', 'catatan_penguji')) {
                $columns[] = 'catatan_penguji';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
