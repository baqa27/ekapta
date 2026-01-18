<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUjiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->string('lampiran_1');
            $table->string('lampiran_2');
            $table->string('lampiran_3');
            $table->string('lampiran_4');
            $table->string('lampiran_5');
            $table->string('lampiran_6');
            $table->string('lampiran_7');
            $table->string('lampiran_8');
            $table->string('lampiran_proposal')->nullable();
            $table->boolean('is_valid')->default(3);
            $table->tinyInteger('is_lulus')->default(3);
            $table->timestamp('tanggal_acc')->nullable();
            $table->timestamp('tanggal_ujian')->nullable();
            $table->timestamps();
            $table->foreignId('pengajuan_id')->constrained();
            $table->foreignId('mahasiswa_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ujians');
    }
}
