<?php

namespace App\Models\KP;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model PresentaseNilai untuk Kerja Praktik (KP)
 * Tabel: presentase_nilai_kps (dengan suffix _kp)
 */
class PresentaseNilai extends Model
{
    use HasFactory;

    protected $table = 'presentase_nilai_kps';

    protected $fillable = [
        'presentase_1',
        'presentase_2',
        'presentase_3',
        'presentase_4',
        'prodi_id',
        'bobot_penguji',
        'bobot_pembimbing',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
