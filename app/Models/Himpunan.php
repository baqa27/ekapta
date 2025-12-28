<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Himpunan as Authenticatable;

class Himpunan extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'prodi_id',
        'is_pendaftaran_seminar_open',
        'biaya_seminar',
        'nama_rekening',
        'nomor_rekening',
        'bank',
        'nomor_dana',
        'nomor_seabank',
    ];

    protected $casts = [
        'is_pendaftaran_seminar_open' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke Prodi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    /**
     * Cek apakah pendaftaran seminar dibuka
     */
    public static function isPendaftaranSeminarOpen(): bool
    {
        $himpunan = self::first();
        return $himpunan ? $himpunan->is_pendaftaran_seminar_open : true;
    }
}
