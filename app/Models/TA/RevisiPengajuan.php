<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiPengajuan untuk Tugas Akhir
 * Tabel: revisi_pengajuans (tanpa suffix _kp)
 */
class RevisiPengajuan extends Model
{
    use HasFactory;

    protected $table = 'revisi_pengajuans';

    protected $fillable = [
        'pengajuan_id',
        'keterangan',
        'lampiran',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
