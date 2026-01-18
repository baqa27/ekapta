<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('nama');
            $table->string('thmasuk');
            $table->string('prodi');
            $table->string('tptlahir');
            $table->date('tgllahir');
            $table->string('jeniskelamin');
            $table->string('kodedosenwali');
            $table->string('nik');
            $table->string('kelas');
            $table->string('email');
            $table->string('hp');
            $table->string('alamat');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}