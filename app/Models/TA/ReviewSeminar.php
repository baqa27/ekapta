<?php

namespace App\Models\TA;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ReviewSeminar untuk Tugas Akhir
 * Tabel: review_seminars (tanpa suffix _kp)
 */
class ReviewSeminar extends Model
{
    use HasFactory;

    protected $table = 'review_seminars';

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const REVIEW = 'review';
    public const DOSEN_PEMBIMBING = 'pembimbing';
    public const DOSEN_PENGUJI = 'penguji';

    protected $fillable = [
        'seminar_id',
        'dosen_id',
        'status',
        'dosen_status',
        'nilai',
        'catatan',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiReviewSeminar::class, 'review_seminar_id');
    }
}
