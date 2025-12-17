<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah field ke seminars
        Schema::table('seminars', function (Blueprint $table) {
            $table->string('no_wa')->nullable()->after('mahasiswa_id');
            $table->string('metode_bayar')->nullable()->after('jumlah_bayar'); // Cash, DANA, SeaBank
            $table->string('file_laporan_revisi')->nullable()->after('file_laporan');
            $table->string('bukti_perbaikan')->nullable()->after('file_laporan_revisi');
            $table->decimal('nilai_instansi', 5, 2)->nullable()->after('is_lulus');
            $table->string('file_nilai_instansi')->nullable()->after('nilai_instansi');
            $table->decimal('nilai_seminar', 5, 2)->nullable()->after('file_nilai_instansi');
            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('nilai_seminar');
            $table->string('status_seminar')->default('menunggu_verifikasi')->after('nilai_akhir');
            // Status: menunggu_verifikasi, diterima, revisi, ditolak, dijadwalkan, selesai_seminar, revisi_pasca, revisi_disetujui, selesai
            $table->foreignId('sesi_seminar_id')->nullable()->after('status_seminar');
            $table->timestamp('tanggal_selesai')->nullable()->after('tanggal_acc');
        });

        // Buat tabel sesi_seminars untuk penjadwalan per sesi
        Schema::create('sesi_seminars', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tempat'); // Ruangan atau link online
            $table->integer('jumlah_mahasiswa')->default(8);
            $table->string('token_penilaian', 100)->unique(); // Link unik untuk dosen penguji
            $table->boolean('is_token_used')->default(false); // Token sudah dipakai atau belum
            $table->timestamp('token_used_at')->nullable();
            $table->foreignId('dosen_penguji_id')->nullable()->constrained('dosens');
            $table->text('catatan_teknis')->nullable();
            $table->timestamps();
        });

        // Tambah field ke review_seminars untuk penilaian
        Schema::table('review_seminars', function (Blueprint $table) {
            $table->decimal('nilai_angka', 5, 2)->nullable()->after('nilai_4');
            $table->string('status_hasil')->nullable()->after('status'); // diterima, revisi
            $table->text('catatan_penguji')->nullable()->after('keterangan');
            $table->boolean('is_dinilai')->default(false)->after('catatan_penguji');
        });
    }

    public function down(): void
    {
        Schema::table('seminars', function (Blueprint $table) {
            $table->dropColumn([
                'no_wa', 'metode_bayar', 'file_laporan_revisi', 'bukti_perbaikan',
                'nilai_instansi', 'file_nilai_instansi', 'nilai_seminar', 'nilai_akhir',
                'status_seminar', 'sesi_seminar_id', 'tanggal_selesai'
            ]);
        });

        Schema::dropIfExists('sesi_seminars');

        Schema::table('review_seminars', function (Blueprint $table) {
            $table->dropColumn(['nilai_angka', 'status_hasil', 'catatan_penguji', 'is_dinilai']);
        });
    }
};
