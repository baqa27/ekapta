<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use App\Models\TA\RevisiPendaftaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pendaftaran untuk Tugas Akhir
 * Tabel: pendaftarans (tanpa suffix _kp)
 */
class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftarans';

    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';
    public const DISABLED = 'disabled';

    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'email',
        'hp',
        'semester',
        'nomor_pembayaran',
        'tanggal_pembayaran',
        'biaya',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_acc',
        'tanggal_acc',
        'status',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPendaftaran::class, 'pendaftaran_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
