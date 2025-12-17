<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbinganCanceled extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'keterangan',
        'lampiran',
        'status',
        'mahasiswa_id',
        'bagian_id',
        'tanggal_bimbingan',
        'tanggal_acc',
        'pembimbing',
        'dosen_id',
        'pengajuan_id',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
