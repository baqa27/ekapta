<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToReviewSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('review_seminars', function (Blueprint $table) {
            $table->date('tanggal_acc_manual')->nullable();
            $table->string('lampiran_lembar_revisi')->nullable();
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
            $table->dropColumn('tanggal_acc_manual');
            $table->dropColumn('lampiran_lembar_revisi');
        });
    }
}
