<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Dosen as Authenticatable;

class Dosen extends Authenticatable
{
    use HasFactory;

    public const UTAMA = 'utama';
    public const PENDAMPING = 'pendamping';
    public const PENGUJI = 'penguji';

    protected $fillable = [
        'nidn',
        'nik',
        'nama',
        'gelar',
        'tgllahir',
        'tptlahir',
        'alamat',
        'email',
        'hp',
        'kodeprodi',
        'password',
        'ttd',
        'is_manual',
    ];

    protected $hidden = [
        'password'
    ];

    public function mahasiswas()
    {
        return $this->belongsToMany(Mahasiswa::class, 'dosen_mahasiswas', 'dosen_id', 'mahasiswa_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    public function prodis()
    {
        return $this->belongsToMany(Prodi::class, 'dosen_prodis', 'dosen_id', 'prodi_id')
            ->withPivot(['kode','nidn']);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiBimbingan::class);
    }

    public function bimbingans()
    {
        return $this->belongsToMany(Bimbingan::class, 'dosen_bimbingans', 'dosen_id', 'bimbingan_id')
            ->withTimestamps();
    }

    public function seminars()
    {
        return $this->hasMany(ReviewSeminar::class);
    }

    public function ujians()
    {
        return $this->hasMany(ReviewUjian::class);
    }

    public function bimbingan_canceleds()
    {
        return $this->hasMany(BimbinganCanceled::class);
    }
}
