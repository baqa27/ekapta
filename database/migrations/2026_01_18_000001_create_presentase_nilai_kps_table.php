<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presentase_nilai_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            $table->integer('presentase_1')->default(0);
            $table->integer('presentase_2')->default(0);
            $table->integer('presentase_3')->default(0);
            $table->integer('presentase_4')->default(0);
            $table->integer('bobot_penguji')->default(0);
            $table->integer('bobot_pembimbing')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presentase_nilai_kps');
    }
};
