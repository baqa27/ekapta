<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiReviewSeminar extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'revisi_review_seminar_kps';

    protected $fillable = [
        'review_seminar_id',
        'catatan',
        'lampiran',
    ];

    public function seminar()
    {
        return $this->belongsTo(ReviewSeminar::class);
    }
}
