<?php

namespace App\Models\TA;

use App\Models\Prodi;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Bagian untuk Tugas Akhir
 * Tabel: bagians (tanpa suffix _kp)
 */
class Bagian extends Model
{
    use HasFactory;

    protected $table = 'bagians';

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
        return $this->hasMany(Bimbingan::class, 'bagian_id');
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'bimbingans', 'bagian_id', 'mahasiswa_id');
    }
}
