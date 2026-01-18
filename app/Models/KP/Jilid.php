<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Jilid KP - Pengumpulan Akhir KP
 * 
 * File yang harus diupload:
 * - Laporan Word
 * - Laporan PDF final (setelah seminar)
 * - Lembar Pengesahan final
 * - File project (.zip/.rar)
 * - Berita Acara Serah Terima Produk
 * - Panduan Penggunaan Aplikasi
 * - Form Nilai KP lengkap (Pembimbing, Penguji, Instansi)
 */
class Jilid extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'jilid_kps';

    // Status pengumpulan akhir
    public const STATUS_REVIEW = 'review';
    public const STATUS_REVISI = 'revisi';
    public const STATUS_TERKUMPUL = 'terkumpul';
    public const STATUS_SELESAI = 'selesai';

    // Legacy constants untuk backward compatibility
    public const JILID_REVIEW = 1;
    public const JILID_REVISI = 2;
    public const JILID_VALID = 3;
    public const JILID_SELESAI = 4;
    public const JILID_COMPLETED = 1;

    protected $fillable = [
        'mahasiswa_id',
        'status',
        'status_arsip',
        'tanggal_arsip',
        // File-file pengumpulan akhir KP
        'laporan_word',
        'laporan_pdf',
        'lembar_pengesahan',
        'file_project', // .zip/.rar
        'berita_acara', // Berita Acara Serah Terima Produk
        'panduan', // Panduan Penggunaan Aplikasi
        // Nilai
        'nilai_pembimbing',
        'nilai_penguji',
        'nilai_instansi',
        'nilai_akhir',
        'bukti_nilai_instansi',
        // Catatan
        'catatan',
        'catatan_admin',
        // Legacy fields
        'total_pembayaran',
        'lembar_keaslian',
        'lembar_persetujuan_penguji',
        'lembar_persetujuan_pembimbing',
        'lembar_bimbingan',
        'lembar_revisi',
        'link_project',
        'artikel',
        'lampiran',
        'is_completed',
    ];

    protected $casts = [
        'tanggal_arsip' => 'datetime',
        'nilai_pembimbing' => 'decimal:2',
        'nilai_penguji' => 'decimal:2',
        'nilai_instansi' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiJilid::class, 'jilid_id');
    }

    /**
     * Hitung nilai akhir KP
     * Bobot: Pembimbing 35%, Penguji 35%, Instansi 30%
     */
    public function hitungNilaiAkhir()
    {
        if ($this->nilai_pembimbing && $this->nilai_penguji && $this->nilai_instansi) {
            $this->nilai_akhir = 
                ($this->nilai_pembimbing * 0.35) + 
                ($this->nilai_penguji * 0.35) + 
                ($this->nilai_instansi * 0.30);
            $this->save();
        }
    }

    /**
     * Get nilai huruf dari nilai akhir
     */
    public function getNilaiHurufAttribute()
    {
        if (!$this->nilai_akhir) return null;
        
        if ($this->nilai_akhir > 85) return 'A';
        if ($this->nilai_akhir > 69) return 'B';
        if ($this->nilai_akhir > 55) return 'C';
        if ($this->nilai_akhir > 45) return 'D';
        return 'E';
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_REVIEW => 'Menunggu Verifikasi',
            self::STATUS_REVISI => 'Perlu Revisi',
            self::STATUS_TERKUMPUL => 'Terkumpul & Diverifikasi',
            self::STATUS_SELESAI => 'Selesai KP',
        ];

        return $labels[$this->status] ?? $this->status;
    }
}
