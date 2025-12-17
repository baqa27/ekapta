<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBimbinganCanceledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bimbingan_canceleds', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamp('tanggal_bimbingan')->nullable();
            $table->timestamp('tanggal_acc')->nullable();
            $table->string('status')->nullable();
            $table->string('pembimbing')->nullable();
            $table->timestamps();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->foreignId('bagian_id')->constrained('bagians');
            $table->foreignId('dosen_id')->constrained('dosens');
            $table->foreignId('pengajuan_id')->constrained('pengajuans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bimbingan_canceleds');
    }
}
