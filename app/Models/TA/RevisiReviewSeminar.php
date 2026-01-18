<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiReviewSeminar untuk Tugas Akhir
 * Tabel: revisi_review_seminars (tanpa suffix _kp)
 */
class RevisiReviewSeminar extends Model
{
    use HasFactory;

    protected $table = 'revisi_review_seminars';

    protected $fillable = [
        'review_seminar_id',
        'keterangan',
        'lampiran',
    ];

    public function reviewSeminar()
    {
        return $this->belongsTo(ReviewSeminar::class);
    }
}
