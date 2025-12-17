<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pembayaran');
            $table->string('tanggal_pembayaran');
            $table->string('biaya');
            $table->string('lampiran_1');
            $table->string('lampiran_2');
            $table->string('lampiran_3');
            $table->string('lampiran_4');
            $table->string('lampiran_5');
            $table->string('lampiran_acc')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('status')->default('review');
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
        Schema::dropIfExists('pendaftarans');
    }
}
