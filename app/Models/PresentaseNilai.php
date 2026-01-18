<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresentaseNilai extends Model
{
    use HasFactory;

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
