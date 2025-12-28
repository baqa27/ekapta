<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'pengajuan_kps';

    // Status pengajuan judul KP
    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const DITOLAK = 'ditolak';
    public const REVIEW = 'review';
    
    // Maksimal pengajuan setelah ditolak
    public const MAX_TOLAK = 2;

    // Status kerangka pikir
    public const KERANGKA_REVIEW = 'review';
    public const KERANGKA_DITERIMA = 'diterima';
    public const KERANGKA_REVISI = 'revisi';
    public const KERANGKA_DITOLAK = 'ditolak';

    protected $fillable = [
        'mahasiswa_id',
        'prodi_id',
        'judul',
        'deskripsi',
        'lokasi_kp',
        'alamat_instansi',
        'files_pendukung',
        'lampiran',
        'status',
        'tanggal_acc',
        'jumlah_tolak', // Track jumlah penolakan (max 2x)
        // Field kerangka pikir
        'file_kerangka_pikir',
        'status_kerangka_pikir',
        'tanggal_acc_kerangka',
        // Field judul KP
        'file_bukti_penerimaan_instansi',
        'file_persetujuan_kerangka',
        'file_lembar_persetujuan_pembimbing',
        'calon_pembimbing_id',
    ];

    protected $casts = [
        'tanggal_acc' => 'datetime',
        'tanggal_acc_kerangka' => 'datetime',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPengajuan::class);
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    public function seminar()
    {
        return $this->hasOne(Seminar::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function bimbingan_canceleds()
    {
        return $this->hasMany(BimbinganCanceled::class);
    }

    public function seminar_canceleds()
    {
        return $this->hasMany(SeminarCanceled::class);
    }

    /**
     * Calon dosen pembimbing yang ditetapkan prodi
     */
    public function calonPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'calon_pembimbing_id');
    }

    /**
     * Cek apakah kerangka pikir sudah disetujui
     */
    public function isKerangkaPikirDisetujui()
    {
        return $this->status_kerangka_pikir === self::KERANGKA_DITERIMA;
    }

    /**
     * Cek apakah judul KP sudah disetujui
     */
    public function isJudulDisetujui()
    {
        return $this->status === self::DITERIMA;
    }

    /**
     * Cek apakah masih bisa mengajukan lagi setelah ditolak
     * Maksimal 2x pengajuan setelah ditolak
     */
    public function bisaAjukanLagi()
    {
        return $this->jumlah_tolak < self::MAX_TOLAK;
    }

    /**
     * Cek apakah sudah ada pengajuan aktif (review/diterima)
     */
    public static function hasActivePengajuan($mahasiswaId)
    {
        return self::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', [self::REVIEW, self::DITERIMA, self::REVISI])
            ->exists();
    }
}
