<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    public const DITERIMA = 'diterima';
    public const REVISI = 'revisi';
    public const DITOLAK = 'ditolak';
    public const REVIEW = 'review';

    protected $fillable = [
        'mahasiswa_id',
        'prodi_id',
        'judul',
        'deskripsi',
        'lokasi_kp',
        'alamat_instansi',
        'files_pendukung',
        'lampiran',
        'status',
        'tanggal_acc',
    ];

    public function revisis()
    {
        return $this->hasMany(RevisiPengajuan::class);
    }

    public function pendaftaran(){
        return $this->hasOne(Pendaftaran::class);
    }

    public function seminar(){
        return $this->hasOne(Seminar::class);
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }

    public function prodi(){
        return $this->belongsTo(Prodi::class);
    }

    public function bimbingan_canceleds(){
        return $this->hasMany(BimbinganCanceled::class);
    }

    public function seminar_canceleds()
    {
        return $this->hasMany(SeminarCanceled::class);
    }
}
