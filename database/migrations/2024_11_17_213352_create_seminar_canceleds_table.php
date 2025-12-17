<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeminarCanceledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seminar_canceleds', function (Blueprint $table) {
            $table->id();
            $table->string('lampiran_1');
            $table->string('lampiran_2');
            $table->string('lampiran_3')->nullable();
            $table->string('jumlah_bayar')->nullable();
            $table->string('nomor_pembayaran')->nullable();
            $table->string('lampiran_proposal')->nullable();
            $table->boolean('is_valid')->default(0);
            $table->timestamp('tanggal_acc')->nullable();
            $table->tinyInteger('is_lulus')->nullable();
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
        Schema::dropIfExists('seminar_canceleds');
    }
}
