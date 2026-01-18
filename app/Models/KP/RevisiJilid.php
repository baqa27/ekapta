<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiJilid untuk Kerja Praktik
 * Tabel: revisi_jilid_kps (dengan suffix _kp)
 */
class RevisiJilid extends Model
{
    use HasFactory;

    protected $table = 'revisi_jilid_kps';

    protected $fillable = [
        'jilid_id',
        'catatan',
    ];

    public function jilid()
    {
        return $this->belongsTo(Jilid::class);
    }
}
