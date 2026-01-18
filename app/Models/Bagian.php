<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TA\Bimbingan;

class Bagian extends Model
{
    use HasFactory;

    // Tabel TA tanpa suffix
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
        return $this->hasMany(Bimbingan::class);
    }

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'bimbingans');
    }
}
