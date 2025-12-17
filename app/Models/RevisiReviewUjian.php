<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiReviewUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_ujian_id',
        'catatan',
        'lampiran',
    ];

    public function ujian()
    {
        return $this->belongsTo(ReviewUjian::class);
    }
}
