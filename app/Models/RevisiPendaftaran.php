<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiPendaftaran extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'revisi_pendaftaran_kps';

    protected $fillable = [
        'pendaftaran_id',
        'catatan',
        'lampiran',
    ];

    public function pendaftarans()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
