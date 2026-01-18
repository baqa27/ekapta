<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            if (!Schema::hasColumn('bimbingans', 'tipe')) {
                $table->enum('tipe', ['online', 'offline'])->default('online')->after('status');
            }
            if (!Schema::hasColumn('bimbingans', 'status_offline')) {
                $table->enum('status_offline', ['pending', 'verified', 'rejected'])->nullable()->after('tipe');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bimbingans', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'status_offline']);
        });
    }
};
