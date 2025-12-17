<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiPengajuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
            $table->foreignId('pengajuan_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_pengajuans');
    }
}
