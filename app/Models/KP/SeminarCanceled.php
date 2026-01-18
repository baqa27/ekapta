<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SeminarCanceled untuk Kerja Praktik
 * Tabel: seminar_canceled_kps (dengan suffix _kp)
 */
class SeminarCanceled extends Model
{
    use HasFactory;

    protected $table = 'seminar_canceled_kps';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'jumlah_bayar',
        'nomor_pembayaran',
        'lampiran_proposal',
        'is_valid',
        'is_lulus',
        'tanggal_acc',
        'tanggal_ujian',
        'tempat_ujian',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
