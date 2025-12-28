<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiPengajuan extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
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
