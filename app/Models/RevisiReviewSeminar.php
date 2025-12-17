<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiReviewSeminar extends Model
{
    use HasFactory;

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
