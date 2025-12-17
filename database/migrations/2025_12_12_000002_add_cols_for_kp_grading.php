<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_seminars', function (Blueprint $table) {
            $table->string('token')->nullable()->after('id');
        });

        Schema::table('jilids', function (Blueprint $table) {
            $table->double('nilai_instansi')->nullable()->after('bukti_nilai_instansi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('review_seminars', function (Blueprint $table) {
            $table->dropColumn('token');
        });

        Schema::table('jilids', function (Blueprint $table) {
            $table->dropColumn('nilai_instansi');
        });
    }
};
