<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model BimbinganCanceled untuk Tugas Akhir
 * Tabel: bimbingan_canceleds (tanpa suffix _kp)
 */
class BimbinganCanceled extends Model
{
    use HasFactory;

    protected $table = 'bimbingan_canceleds';

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

    public function dosen()
    {
        return $this->belongsTo(\App\Models\Dosen::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }
}
