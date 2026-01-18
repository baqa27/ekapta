<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use App\Models\TA\RevisiUjian;
use App\Models\TA\ReviewUjian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Ujian untuk Tugas Akhir
 * Tabel: ujians (tanpa suffix _kp)
 */
class Ujian extends Model
{
    use HasFactory;

    protected $table = 'ujians';

    public const DITERIMA = 1;
    public const REVISI = 2;
    public const REVIEW = 3;

    public const VALID_LULUS = 1;
    public const NOT_VALID_LULUS = 2;

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_6',
        'lampiran_7',
        'lampiran_8',
        'lampiran_proposal',
        'is_valid',
        'tanggal_acc',
        'tanggal_ujian',
        'is_lulus',
        'lampiran_laporan',
        'tempat_ujian',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiUjian::class, 'ujian_id');
    }

    public function reviews()
    {
        return $this->hasMany(ReviewUjian::class, 'ujian_id');
    }
}
