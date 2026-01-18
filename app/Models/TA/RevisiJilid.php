<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiJilid untuk Tugas Akhir
 * Tabel: revisi_jilids (tanpa suffix _kp)
 */
class RevisiJilid extends Model
{
    use HasFactory;

    protected $table = 'revisi_jilids';

    protected $fillable = [
        'jilid_id',
        'keterangan',
        'lampiran',
    ];

    public function jilid()
    {
        return $this->belongsTo(Jilid::class);
    }
}
