<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Dosen as Authenticatable;

// Import TA Models
use App\Models\TA\Bimbingan as TABimbingan;

// Import KP Models
use App\Models\KP\Bimbingan as KPBimbingan;

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
        'mode_bimbingan',
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

    /**
     * Mahasiswa KP yang dibimbing
     * KP menggunakan tabel yang sama (dosen_mahasiswas) tapi dengan status 'pembimbing'
     */
    public function mahasiswasKP()
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

    /**
     * Revisi bimbingan TA
     */
    public function revisis()
    {
        return $this->hasMany(\App\Models\TA\RevisiBimbingan::class);
    }

    /**
     * Revisi bimbingan KP
     */
    public function revisisKP()
    {
        return $this->hasMany(\App\Models\KP\RevisiBimbingan::class);
    }

    /**
     * Bimbingan TA (tabel: dosen_bimbingans)
     * Default relationship untuk TA
     */
    public function bimbingans()
    {
        return $this->belongsToMany(TABimbingan::class, 'dosen_bimbingans', 'dosen_id', 'bimbingan_id')
            ->withTimestamps();
    }

    /**
     * Bimbingan KP (tabel: dosen_bimbingan_kps)
     */
    public function bimbingansKP()
    {
        return $this->belongsToMany(KPBimbingan::class, 'dosen_bimbingan_kps', 'dosen_id', 'bimbingan_id')
            ->withTimestamps();
    }

    /**
     * Review Seminar TA
     */
    public function seminars()
    {
        return $this->hasMany(\App\Models\TA\ReviewSeminar::class);
    }

    /**
     * Review Seminar KP
     */
    public function seminarsKP()
    {
        return $this->hasMany(\App\Models\KP\ReviewSeminar::class);
    }

    /**
     * Review Ujian TA
     */
    public function ujians()
    {
        return $this->hasMany(\App\Models\TA\ReviewUjian::class);
    }

    /**
     * Bimbingan canceled TA
     */
    public function bimbingan_canceleds()
    {
        return $this->hasMany(\App\Models\TA\BimbinganCanceled::class);
    }

    /**
     * Bimbingan canceled KP
     */
    public function bimbingan_canceledsKP()
    {
        return $this->hasMany(\App\Models\KP\BimbinganCanceled::class);
    }
}

