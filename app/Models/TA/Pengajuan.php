<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\TA\BimbinganCanceled;
use App\Models\TA\SeminarCanceled;
use App\Models\TA\RevisiPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pengajuan untuk Tugas Akhir
 * Tabel: pengajuans (tanpa suffix _kp)
 */
class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const DITOLAK = 'ditolak';
    public const REVIEW = 'review';

    protected $fillable = [
        'mahasiswa_id',
        'prodi_id',
        'judul',
        'deskripsi',
        'lampiran',
        'status',
        'tanggal_acc',
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

    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'pengajuan_id');
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
}
