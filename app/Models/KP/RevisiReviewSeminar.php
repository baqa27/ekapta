<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiReviewSeminar untuk Kerja Praktik
 * Tabel: revisi_review_seminar_kps (dengan suffix _kp)
 */
class RevisiReviewSeminar extends Model
{
    use HasFactory;

    protected $table = 'revisi_review_seminar_kps';

    protected $fillable = [
        'review_seminar_id',
        'catatan',
        'lampiran',
    ];

    public function reviewSeminar()
    {
        return $this->belongsTo(ReviewSeminar::class);
    }
}
