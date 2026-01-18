<?php

namespace App\Models\TA;

use App\Models\Mahasiswa;
use App\Models\TA\RevisiJilid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Jilid untuk Tugas Akhir
 * Tabel: jilids (tanpa suffix _kp)
 */
class Jilid extends Model
{
    use HasFactory;

    protected $table = 'jilids';

    // Status jilid
    public const JILID_REVIEW = 1;
    public const JILID_REVISI = 2;
    public const JILID_VALID = 3;
    public const JILID_SELESAI = 4;
    public const JILID_COMPLETED = 1;

    protected $fillable = [
        'mahasiswa_id',
        'lampiran_laporan_word',
        'lampiran_laporan_pdf',
        'lampiran_lembar_pengesahan',
        'lampiran_file_project',
        'lampiran_berita_acara',
        'status',
        'is_completed',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiJilid::class, 'jilid_id');
    }
}
