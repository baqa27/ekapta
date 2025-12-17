<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDekansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dekans', function (Blueprint $table) {
            $table->id();
            $table->string('nidn')->unique();
            $table->string('namadekan');
            $table->string('gelar');
            $table->date('dari')->nullable();
            $table->date('sampai')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->unique()->nullable();
            $table->timestamps();
            $table->foreignId('fakultas_id')->constrained('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dekans');
    }
}
