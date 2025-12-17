<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Prodi as Authenticatable;

class Prodi extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'namaprodi',
        'jenjang',
        'kodekaprodi',
        'password',
        'fakultas_id',
    ];

    protected $hidden = [
        'password'
    ];

    public function bagians()
    {
        return $this->hasMany(Bagian::class);
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function presentase_nilai()
    {
        return $this->hasOne(PresentaseNilai::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_prodis', 'prodi_id', 'dosen_id')
            ->withPivot(['kode','nidn']);
    }

    function pengajuans(){
        return $this->hasMany(Pengajuan::class);
    }
}
