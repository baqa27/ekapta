<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use App\Models\TA\RevisiSeminar;
use App\Models\TA\ReviewSeminar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Seminar untuk Tugas Akhir
 * Tabel: seminars (tanpa suffix _kp)
 */
class Seminar extends Model
{
    use HasFactory;

    protected $table = 'seminars';

    public const REVIEW = 0;
    public const DITERIMA = 1;
    public const REVISI = 2;

    public const VALID = 1;
    public const NOT_VALID = 0;

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'jumlah_bayar',
        'nomor_pembayaran',
        'lampiran_proposal',
        'is_valid',
        'is_lulus',
        'tanggal_acc',
        'tanggal_ujian',
        'tempat_ujian',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiSeminar::class, 'seminar_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewSeminar::class, 'seminar_id');
    }
}
