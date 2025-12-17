<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_seminars', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai_1')->nullable();
            $table->integer('nilai_2')->nullable();
            $table->integer('nilai_3')->nullable();
            $table->integer('nilai_4')->nullable();
            $table->string('status')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('dosen_status');
            $table->boolean('is_lulus')->nullable();
            $table->timestamps();
            $table->foreignId('seminar_id')->constrained();
            $table->foreignId('dosen_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_seminars');
    }
}
