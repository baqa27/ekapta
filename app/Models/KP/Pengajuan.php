<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\KP\BimbinganCanceled;
use App\Models\KP\SeminarCanceled;
use App\Models\KP\RevisiPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pengajuan untuk Kerja Praktik
 * Tabel: pengajuan_kps (dengan suffix _kp)
 */
class Pengajuan extends Model
{
    use HasFactory;

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
        'jumlah_tolak',
        'file_kerangka_pikir',
        'status_kerangka_pikir',
        'tanggal_acc_kerangka',
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
        return $this->hasMany(RevisiPengajuan::class, 'pengajuan_id');
    }

    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class, 'pengajuan_id');
    }

    public function seminar()
    {
        return $this->hasOne(Seminar::class, 'pengajuan_id');
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
        return $this->hasMany(BimbinganCanceled::class, 'pengajuan_id');
    }

    public function seminar_canceleds()
    {
        return $this->hasMany(SeminarCanceled::class, 'pengajuan_id');
    }

    public function calonPembimbing()
    {
        return $this->belongsTo(Dosen::class, 'calon_pembimbing_id');
    }

    public function isKerangkaPikirDisetujui()
    {
        return $this->status_kerangka_pikir === self::KERANGKA_DITERIMA;
    }

    public function isJudulDisetujui()
    {
        return $this->status === self::DITERIMA;
    }

    public function bisaAjukanLagi()
    {
        return $this->jumlah_tolak < self::MAX_TOLAK;
    }

    public static function hasActivePengajuan($mahasiswaId)
    {
        return self::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', [self::REVIEW, self::DITERIMA, self::REVISI])
            ->exists();
    }
}
