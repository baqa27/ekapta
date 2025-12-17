<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentaseNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentase_nilais', function (Blueprint $table) {
            $table->id();
            $table->integer('presentase_1')->default(0);
            $table->integer('presentase_2')->default(0);
            $table->integer('presentase_3')->default(0);
            $table->integer('presentase_4')->default(0);
            $table->integer('bobot_penguji')->default(0);
            $table->integer('bobot_pembimbing')->default(0);
            $table->timestamps();
            $table->foreignId('prodi_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentase_nilais');
    }
}
