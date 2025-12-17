<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiSeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi_seminars', function (Blueprint $table) {
            $table->id();
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
            $table->foreignId('seminar_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi_seminars');
    }
}
