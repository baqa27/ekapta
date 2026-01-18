<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiPengajuan untuk Kerja Praktik
 * Tabel: revisi_pengajuan_kps (dengan suffix _kp)
 */
class RevisiPengajuan extends Model
{
    use HasFactory;

    protected $table = 'revisi_pengajuan_kps';

    protected $fillable = [
        'pengajuan_id',
        'catatan',
        'lampiran',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
