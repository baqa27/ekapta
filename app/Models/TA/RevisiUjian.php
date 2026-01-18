<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiUjian untuk Tugas Akhir
 * Tabel: revisi_ujians (tanpa suffix _kp)
 */
class RevisiUjian extends Model
{
    use HasFactory;

    protected $table = 'revisi_ujians';

    protected $fillable = [
        'ujian_id',
        'keterangan',
        'lampiran',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
