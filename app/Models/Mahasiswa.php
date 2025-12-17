<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Mahasiswa as Authenticatable;

class Mahasiswa extends Authenticatable
{
    use HasFactory;

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'nim',
        'nama',
        'thmasuk',
        'prodi',
        'tptlahir',
        'tgllahir',
        'jeniskelamin',
        'kodedosenwali',
        'nik',
        'kelas',
        'email',
        'hp',
        'alamat',
        'password',
    ];

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_mahasiswas', 'mahasiswa_id', 'dosen_id')
            ->withTimestamps()
            ->withPivot(['status','lampiran']);
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class);
    }

    public function bagians()
    {
        return $this->belongsToMany(Bagian::class, 'bimbingans');
    }

    public function pengajuans(){
        return $this->hasMany(Pengajuan::class);
    }

    public function pendaftarans(){
        return $this->hasMany(Pendaftaran::class);
    }

    public function seminar(){
        return $this->hasOne(Seminar::class);
    }

    /**
     * Relasi untuk Ujian - DEPRECATED untuk sistem KP
     * KP tidak memiliki ujian pendadaran, hanya seminar
     * Tetap dipertahankan untuk backward compatibility
     * @deprecated Gunakan seminar() untuk sistem KP
     */
    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }

    /**
     * Alias untuk ujians() - untuk backward compatibility
     * @deprecated
     */
    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    /**
     * Pengumpulan Akhir KP (menggunakan model Jilid)
     */
    public function jilid()
    {
        return $this->hasOne(Jilid::class);
    }

    /**
     * Alias untuk jilid() - nama lebih deskriptif
     */
    public function pengumpulanAkhir()
    {
        return $this->hasOne(Jilid::class);
    }

    public function bimbingan_canceleds()
    {
        return $this->hasMany(BimbinganCanceled::class);
    }

    public function seminar_canceleds()
    {
        return $this->hasMany(SeminarCanceled::class);
    }

}
