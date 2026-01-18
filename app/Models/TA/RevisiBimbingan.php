<?php

namespace App\Models\TA;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiBimbingan untuk Tugas Akhir
 * Tabel: revisi_bimbingans (tanpa suffix _kp)
 */
class RevisiBimbingan extends Model
{
    use HasFactory;

    protected $table = 'revisi_bimbingans';

    protected $fillable = [
        'bimbingan_id',
        'keterangan',
        'lampiran',
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
