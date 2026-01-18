<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiReviewUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi_review_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('catatan');
            $table->string('lampiran')->nullable();
            $table->timestamps();
            $table->foreignId('review_ujian_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_review_ujians');
    }
}
