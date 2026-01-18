<?php

namespace App\Models\TA;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model RevisiSeminar untuk Tugas Akhir
 * Tabel: revisi_seminars (tanpa suffix _kp)
 */
class RevisiSeminar extends Model
{
    use HasFactory;

    protected $table = 'revisi_seminars';

    protected $fillable = [
        'seminar_id',
        'keterangan',
        'lampiran',
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}
