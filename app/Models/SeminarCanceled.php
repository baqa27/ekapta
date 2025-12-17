<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeminarCanceled extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'jumlah_bayar',
        'nomor_pembayaran',
        'lampiran_proposal',
        'is_valid',
        'is_lulus',
        'tanggal_acc',
        'tanggal_ujian',
        'tempat_ujian',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
