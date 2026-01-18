<?php

namespace App\Models\TA;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ReviewUjian untuk Tugas Akhir
 * Tabel: review_ujians (tanpa suffix _kp)
 */
class ReviewUjian extends Model
{
    use HasFactory;

    protected $table = 'review_ujians';

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const REVIEW = 'review';
    public const DOSEN_PEMBIMBING = 'pembimbing';
    public const DOSEN_PENGUJI = 'penguji';

    protected $fillable = [
        'ujian_id',
        'dosen_id',
        'status',
        'nilai',
        'catatan',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiReviewUjian::class, 'review_ujian_id');
    }
}
