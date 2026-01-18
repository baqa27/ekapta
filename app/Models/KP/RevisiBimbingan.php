<?php

namespace App\Models\KP;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiBimbingan untuk Kerja Praktik
 * Tabel: revisi_bimbingan_kps (dengan suffix _kp)
 */
class RevisiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'revisi_bimbingan_kps';

    protected $fillable = [
        'bimbingan_id',
        'catatan',
        'lampiran',
        'lampiran_revisi',
        'dosen_id',
        'reviewer_type',
        'prodi_id',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
