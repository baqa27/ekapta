<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prodi;
use App\Models\Mahasiswa;

class Bagian extends Model
{
    use HasFactory;

    // Tabel KP dengan suffix _kp
    protected $table = 'bagian_kps';

    protected $fillable = [
        'prodi_id',
        'bagian',
        'is_seminar',
        'is_pendadaran',
        'tahun_masuk',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'bimbingan_kps');
    }
}
