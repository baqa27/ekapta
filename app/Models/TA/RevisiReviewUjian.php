<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiReviewUjian untuk Tugas Akhir
 * Tabel: revisi_review_ujians (tanpa suffix _kp)
 */
class RevisiReviewUjian extends Model
{
    use HasFactory;

    protected $table = 'revisi_review_ujians';

    protected $fillable = [
        'review_ujian_id',
        'keterangan',
        'lampiran',
    ];

    public function reviewUjian()
    {
        return $this->belongsTo(ReviewUjian::class);
    }
}
