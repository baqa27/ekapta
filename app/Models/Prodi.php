<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Prodi as Authenticatable;
use App\Models\KP\Bagian as KPBagian;
use App\Models\KP\Pengajuan as KPPengajuan;
use App\Models\TA\Pengajuan as TAPengajuan;

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

    /**
     * Bagian TA (tabel: bagians)
     */
    public function bagians()
    {
        return $this->hasMany(Bagian::class);
    }

    /**
     * Bagian KP (tabel: bagian_kps)
     */
    public function bagiansKP()
    {
        return $this->hasMany(KPBagian::class);
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

    /**
     * Pengajuan KP (tabel: pengajuan_kps)
     */
    function pengajuans(){
        return $this->hasMany(KPPengajuan::class);
    }

    /**
     * Pengajuan TA (tabel: pengajuans)
     */
    function pengajuansTA(){
        return $this->hasMany(TAPengajuan::class);
    }
}
