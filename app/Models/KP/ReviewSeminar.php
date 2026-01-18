<?php

namespace App\Models\KP;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ReviewSeminar untuk Kerja Praktik
 * Tabel: review_seminar_kps (dengan suffix _kp)
 */
class ReviewSeminar extends Model
{
    use HasFactory;

    protected $table = 'review_seminar_kps';

    // Status review
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DITERIMA = 'diterima';

    // Dosen status
    public const DOSEN_PEMBIMBING = 'pembimbing';
    public const DOSEN_PENGUJI = 'penguji';

    protected $fillable = [
        'seminar_id',
        'dosen_id',
        'dosen_status',
        'status',
        'nilai_1',
        'nilai_2',
        'nilai_3',
        'nilai_4',
        'catatan',
        'lampiran',
        'lampiran_lembar_revisi',
        'tanggal_acc',
        'tanggal_acc_manual',
        'token',
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
