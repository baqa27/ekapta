<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'pendaftaran_kps';

    // Status pendaftaran berkas
    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DISABLED = 'disabled';

    // Status pembayaran
    public const BAYAR_BELUM = 'belum_bayar';
    public const BAYAR_MENUNGGU = 'menunggu_verifikasi';
    public const BAYAR_LUNAS = 'lunas';
    public const BAYAR_DITOLAK = 'ditolak';

    // Jenis mahasiswa (untuk biaya)
    public const JENIS_REGULER_S1 = 'reguler_s1';
    public const JENIS_REGULER_D3 = 'reguler_d3';
    public const JENIS_KARYAWAN = 'karyawan';

    // Biaya pendaftaran KP
    public const BIAYA_REGULER = 300000;
    public const BIAYA_KARYAWAN = 350000;
    public const BIAYA_PERPANJANGAN_PERSEN = 50; // 50% dari biaya awal

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'email',
        'hp',
        'semester',
        'nomor_pembayaran',
        'tanggal_pembayaran',
        'biaya',
        'jenis_mahasiswa',
        'lampiran_1', // Bukti Persetujuan Kerangka Pikir
        'lampiran_2', // Bukti Persetujuan Judul
        'lampiran_3', // Lembar Persetujuan Pembimbing bertanda tangan
        'lampiran_4', // Transkrip nilai
        'lampiran_5', // Sertifikat KKL
        'lampiran_6', // Bukti status aktif
        'lampiran_7', // Form lokasi KP
        'lampiran_8',
        'lampiran_acc',
        'tanggal_acc',
        'status',
        'status_pembayaran',
        'bukti_pembayaran',
        'tanggal_verifikasi_bayar',
        'nomor_surat_tugas',
        'tanggal_surat_tugas',
        'file_surat_tugas',
        'file_lembar_bimbingan',
        'jumlah_perpanjangan',
        'tanggal_perpanjangan_terakhir',
        'sertifikat_peserta_1',
        'sertifikat_peserta_2',
        'dokumen_pendukung', // Dokumen pendukung KP (menggantikan 2 sertifikat)
        'dokumen_pendukung_kp', // Dokumen pendukung KP (gabungan 2 sertifikat)
        'masa_berlaku_surat', // Masa berlaku surat tugas
    ];

    protected $casts = [
        'tanggal_acc' => 'datetime',
        'tanggal_verifikasi_bayar' => 'datetime',
        'tanggal_surat_tugas' => 'date',
        'tanggal_perpanjangan_terakhir' => 'date',
        'masa_berlaku_surat' => 'date',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPendaftaran::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Cek apakah pembayaran sudah lunas
     */
    public function isPembayaranLunas()
    {
        return $this->status_pembayaran === self::BAYAR_LUNAS;
    }

    /**
     * Cek apakah surat tugas sudah diterbitkan
     */
    public function isSuratTugasTerbit()
    {
        return !empty($this->nomor_surat_tugas) && !empty($this->file_surat_tugas);
    }

    /**
     * Cek apakah masih bisa perpanjangan (max 2x)
     */
    public function bisaPerpanjangan()
    {
        return $this->jumlah_perpanjangan < 2;
    }

    /**
     * Hitung biaya perpanjangan
     */
    public function getBiayaPerpanjangan()
    {
        $biayaAwal = $this->jenis_mahasiswa === self::JENIS_KARYAWAN
            ? self::BIAYA_KARYAWAN
            : self::BIAYA_REGULER;

        return $biayaAwal * (self::BIAYA_PERPANJANGAN_PERSEN / 100);
    }

    /**
     * Get biaya berdasarkan jenis mahasiswa
     */
    public static function getBiayaPendaftaran($jenisMahasiswa)
    {
        return $jenisMahasiswa === self::JENIS_KARYAWAN
            ? self::BIAYA_KARYAWAN
            : self::BIAYA_REGULER;
    }
}
