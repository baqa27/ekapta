<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJilidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jilids', function (Blueprint $table) {
            $table->id();
            $table->integer('total_pembayaran')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('laporan_pdf')->nullable();
            $table->string('laporan_word')->nullable();
            $table->string('lembar_pengesahan')->nullable();
            $table->string('lembar_keaslian')->nullable();
            $table->string('lembar_persetujuan_penguji')->nullable();
            $table->string('lembar_persetujuan_pembimbing')->nullable();
            $table->string('lembar_bimbingan')->nullable();
            $table->string('lembar_revisi')->nullable();
            $table->string('berita_acara')->nullable();
            $table->string('link_project')->nullable();
            $table->string('artikel')->nullable();
            $table->string('panduan')->nullable();
            $table->string('lampiran')->nullable();
            $table->boolean('is_completed')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jilids');
    }
}
