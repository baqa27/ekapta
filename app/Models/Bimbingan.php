<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    use HasFactory;

    // Nama tabel dengan suffix _kp
    protected $table = 'bimbingan_kps';

    public const DITERIMA = 'diterima';
    public const REVIEW = 'review';
    public const REVISI = 'revisi';

    protected $fillable = [
        'keterangan',
        'lampiran',
        'status',
        'mahasiswa_id',
        'bagian_id',
        'tanggal_bimbingan',
        'tanggal_acc',
        'pembimbing',
        'bukti_bimbingan_offline',
        'tipe',
        'status_offline',
        'lampiran_acc',
        'tanggal_manual_acc',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    public function revisis()
    {
        return $this->hasMany(RevisiBimbingan::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_bimbingan_kps', 'bimbingan_id', 'dosen_id',)
            ->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($bimbingan) {
            $bimbingan->revisis()->delete();
        });
    }
}
