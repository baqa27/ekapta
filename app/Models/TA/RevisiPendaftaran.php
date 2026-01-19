<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiPendaftaran untuk Tugas Akhir
 * Tabel: revisi_pendaftarans (tanpa suffix _kp)
 */
class RevisiPendaftaran extends Model
{
    use HasFactory;

    protected $table = 'revisi_pendaftarans';

    protected $fillable = [
        'pendaftaran_id',
        'keterangan',
        'lampiran',
    ];

    /**
     * Accessor untuk backward compatibility
     * View lama pakai 'catatan', tapi database pakai 'keterangan'
     */
    public function getCatatanAttribute()
    {
        return $this->keterangan;
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }
}
