<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model SeminarCanceled untuk Tugas Akhir
 * Tabel: seminar_canceleds (tanpa suffix _kp)
 */
class SeminarCanceled extends Model
{
    use HasFactory;

    protected $table = 'seminar_canceleds';

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

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
