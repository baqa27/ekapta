<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk update tabel jilids sesuai alur Pengumpulan Akhir KP
 * 
 * File yang harus diupload:
 * - Laporan Word
 * - Laporan PDF final
 * - Lembar Pengesahan final
 * - File project (.zip/.rar)
 * - Berita Acara Serah Terima Produk
 * - Panduan Penggunaan Aplikasi
 * 
 * Nilai:
 * - Nilai Pembimbing (35%)
 * - Nilai Penguji Seminar (35%)
 * - Nilai Instansi (30%)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jilids', function (Blueprint $table) {
            // File project (zip/rar)
            if (!Schema::hasColumn('jilids', 'file_project')) {
                $table->string('file_project')->nullable();
            }
            
            // Nilai pembimbing
            if (!Schema::hasColumn('jilids', 'nilai_pembimbing')) {
                $table->decimal('nilai_pembimbing', 5, 2)->nullable();
            }
            
            // Nilai penguji seminar
            if (!Schema::hasColumn('jilids', 'nilai_penguji')) {
                $table->decimal('nilai_penguji', 5, 2)->nullable();
            }
            
            // Nilai akhir (kalkulasi)
            if (!Schema::hasColumn('jilids', 'nilai_akhir')) {
                $table->decimal('nilai_akhir', 5, 2)->nullable();
            }
            
            // Catatan admin
            if (!Schema::hasColumn('jilids', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable();
            }
            
            // Ubah status menjadi string jika masih integer
            // Status: review, revisi, terkumpul, selesai
        });
    }

    public function down(): void
    {
        Schema::table('jilids', function (Blueprint $table) {
            $columns = ['file_project', 'nilai_pembimbing', 'nilai_penguji', 'nilai_akhir', 'catatan_admin'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('jilids', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
