<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk menyesuaikan database dengan alur Kerja Praktek (KP)
 * 
 * ALUR KP:
 * 1. Pengajuan Kerangka Pikir (Prodi review)
 * 2. Pengajuan Judul KP (Prodi review + tetapkan pembimbing)
 * 3. Pendaftaran KP Fakultas (Admin verifikasi berkas)
 * 4. Pembayaran (Admin verifikasi)
 * 5. Surat Tugas & Awal Bimbingan
 * 6. Bimbingan KP (Bab I-V, Produk, Full Laporan)
 * 7. Seminar KP (Himpunan kelola)
 * 8. Pengumpulan Laporan Akhir
 * 9. Selesai
 */
return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // TABEL PENGAJUANS - Pengajuan Kerangka Pikir & Judul KP
        // =====================================================
        Schema::table('pengajuans', function (Blueprint $table) {
            // Field untuk Kerangka Pikir (Tahap 2)
            if (!Schema::hasColumn('pengajuans', 'file_kerangka_pikir')) {
                $table->string('file_kerangka_pikir')->nullable()->after('lampiran');
            }
            if (!Schema::hasColumn('pengajuans', 'status_kerangka_pikir')) {
                $table->string('status_kerangka_pikir')->default('review')->after('status');
                // review, diterima, revisi, ditolak
            }
            if (!Schema::hasColumn('pengajuans', 'tanggal_acc_kerangka')) {
                $table->timestamp('tanggal_acc_kerangka')->nullable()->after('tanggal_acc');
            }
            
            // Field untuk Judul KP (Tahap 3)
            if (!Schema::hasColumn('pengajuans', 'file_bukti_penerimaan_instansi')) {
                $table->string('file_bukti_penerimaan_instansi')->nullable()->after('file_kerangka_pikir');
            }
            if (!Schema::hasColumn('pengajuans', 'file_persetujuan_kerangka')) {
                $table->string('file_persetujuan_kerangka')->nullable()->after('file_bukti_penerimaan_instansi');
            }
            if (!Schema::hasColumn('pengajuans', 'file_lembar_persetujuan_pembimbing')) {
                $table->string('file_lembar_persetujuan_pembimbing')->nullable()->after('file_persetujuan_kerangka');
            }
            
            // Calon dosen pembimbing yang ditetapkan prodi
            if (!Schema::hasColumn('pengajuans', 'calon_pembimbing_id')) {
                $table->foreignId('calon_pembimbing_id')->nullable()->after('prodi_id');
            }
        });

        // =====================================================
        // TABEL PENDAFTARANS - Pendaftaran KP Fakultas
        // =====================================================
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Dokumen pendaftaran KP (Tahap 4)
            // lampiran_1 = Bukti Persetujuan Kerangka Pikir
            // lampiran_2 = Bukti Persetujuan Judul
            // lampiran_3 = Lembar Persetujuan Pembimbing bertanda tangan
            // lampiran_4 = Transkrip nilai
            // lampiran_5 = Sertifikat KKL
            // lampiran_6 = Bukti status aktif (sudah ada dari migration sebelumnya)
            // lampiran_7 = Form lokasi KP (sudah ada)
            
            // Status pembayaran (Tahap 5)
            if (!Schema::hasColumn('pendaftarans', 'status_pembayaran')) {
                $table->string('status_pembayaran')->default('belum_bayar')->after('status');
                // belum_bayar, menunggu_verifikasi, lunas, ditolak
            }
            if (!Schema::hasColumn('pendaftarans', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('lampiran_acc');
            }
            if (!Schema::hasColumn('pendaftarans', 'tanggal_verifikasi_bayar')) {
                $table->timestamp('tanggal_verifikasi_bayar')->nullable()->after('tanggal_acc');
            }
            
            // Surat Tugas (Tahap 6)
            if (!Schema::hasColumn('pendaftarans', 'nomor_surat_tugas')) {
                $table->string('nomor_surat_tugas')->nullable()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('pendaftarans', 'tanggal_surat_tugas')) {
                $table->date('tanggal_surat_tugas')->nullable()->after('nomor_surat_tugas');
            }
            if (!Schema::hasColumn('pendaftarans', 'file_surat_tugas')) {
                $table->string('file_surat_tugas')->nullable()->after('tanggal_surat_tugas');
            }
            if (!Schema::hasColumn('pendaftarans', 'file_lembar_bimbingan')) {
                $table->string('file_lembar_bimbingan')->nullable()->after('file_surat_tugas');
            }
            
            // Jenis mahasiswa untuk biaya
            if (!Schema::hasColumn('pendaftarans', 'jenis_mahasiswa')) {
                $table->string('jenis_mahasiswa')->default('reguler')->after('biaya');
                // reguler_s1, reguler_d3, karyawan
            }
            
            // Perpanjangan KP
            if (!Schema::hasColumn('pendaftarans', 'jumlah_perpanjangan')) {
                $table->integer('jumlah_perpanjangan')->default(0)->after('jenis_mahasiswa');
            }
            if (!Schema::hasColumn('pendaftarans', 'tanggal_perpanjangan_terakhir')) {
                $table->date('tanggal_perpanjangan_terakhir')->nullable()->after('jumlah_perpanjangan');
            }
        });

        // =====================================================
        // TABEL SEMINARS - Seminar KP (sudah banyak field dari migration sebelumnya)
        // Tambahan field jika belum ada
        // =====================================================
        Schema::table('seminars', function (Blueprint $table) {
            // Dosen penguji untuk seminar
            if (!Schema::hasColumn('seminars', 'dosen_penguji_id')) {
                $table->foreignId('dosen_penguji_id')->nullable()->after('sesi_seminar_id');
            }
        });

        // =====================================================
        // TABEL JILIDS - Pengumpulan Laporan Akhir (Tahap 9)
        // Sudah ada, cek field yang diperlukan
        // =====================================================
        Schema::table('jilids', function (Blueprint $table) {
            if (!Schema::hasColumn('jilids', 'status_arsip')) {
                $table->string('status_arsip')->default('belum_arsip')->after('status');
                // belum_arsip, diarsipkan
            }
            if (!Schema::hasColumn('jilids', 'tanggal_arsip')) {
                $table->timestamp('tanggal_arsip')->nullable()->after('status_arsip');
            }
        });

        // =====================================================
        // TABEL MAHASISWAS - Status KP keseluruhan
        // =====================================================
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'status_kp')) {
                $table->string('status_kp')->default('belum_mulai');
                // belum_mulai, pengajuan_kerangka, pengajuan_judul, pendaftaran, 
                // pembayaran, bimbingan, seminar, pengumpulan_akhir, selesai
            }
            if (!Schema::hasColumn('mahasiswas', 'tanggal_mulai_kp')) {
                $table->date('tanggal_mulai_kp')->nullable();
            }
            if (!Schema::hasColumn('mahasiswas', 'tanggal_selesai_kp')) {
                $table->date('tanggal_selesai_kp')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $columns = [
                'file_kerangka_pikir', 'status_kerangka_pikir', 'tanggal_acc_kerangka',
                'file_bukti_penerimaan_instansi', 'file_persetujuan_kerangka',
                'file_lembar_persetujuan_pembimbing', 'calon_pembimbing_id'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pengajuans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('pendaftarans', function (Blueprint $table) {
            $columns = [
                'status_pembayaran', 'bukti_pembayaran', 'tanggal_verifikasi_bayar',
                'nomor_surat_tugas', 'tanggal_surat_tugas', 'file_surat_tugas',
                'file_lembar_bimbingan', 'jenis_mahasiswa', 'jumlah_perpanjangan',
                'tanggal_perpanjangan_terakhir'
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('pendaftarans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('seminars', function (Blueprint $table) {
            if (Schema::hasColumn('seminars', 'dosen_penguji_id')) {
                $table->dropColumn('dosen_penguji_id');
            }
        });

        Schema::table('jilids', function (Blueprint $table) {
            $columns = ['status_arsip', 'tanggal_arsip'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('jilids', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('mahasiswas', function (Blueprint $table) {
            $columns = ['status_kp', 'tanggal_mulai_kp', 'tanggal_selesai_kp'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('mahasiswas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
