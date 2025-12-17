<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiBimbingansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi_bimbingans', function (Blueprint $table) {
            $table->id();
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
            $table->foreignId('bimbingan_id')->constrained('bimbingans');
            $table->foreignId('dosen_id')->constrained('dosens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_bimbingans');
    }
}
