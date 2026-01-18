<?php

namespace App\Models\KP;

use App\Models\Mahasiswa;
use App\Models\KP\Bagian;
use App\Models\Dosen;
use App\Models\KP\RevisiBimbingan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Bimbingan untuk Kerja Praktik
 * Tabel: bimbingan_kps (dengan suffix _kp)
 */
class Bimbingan extends Model
{
    use HasFactory;

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
        return $this->hasMany(RevisiBimbingan::class, 'bimbingan_id');
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_bimbingan_kps', 'bimbingan_id', 'dosen_id')
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
