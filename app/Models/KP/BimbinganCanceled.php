<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model BimbinganCanceled untuk Kerja Praktik
 * Tabel: bimbingan_canceled_kps (dengan suffix _kp)
 */
class BimbinganCanceled extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_canceled_kps';

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

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
