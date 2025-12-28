<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiBimbingan extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'revisi_bimbingan_kps';

    protected $fillable = [
        'bimbingan_id',
        'dosen_id',
        'reviewer_type',
        'prodi_id',
        'catatan',
        'lampiran',
        'lampiran_revisi',
        'tanggal_bimbingan',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
