<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToJilids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('jilids', function (Blueprint $table) {
        //     $table->string('laporan_pdf')->nullable();
        //     $table->string('laporan_word')->nullable();
        //     $table->string('lembar_pengesahan')->nullable();
        //     $table->string('link_project')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jilids', function (Blueprint $table) {
            $table->dropColumn('laporan_pdf');
            $table->dropColumn('laporan_word');
            $table->dropColumn('lembar_pengesahan');
            $table->dropColumn('link_project');
        });
    }
}
