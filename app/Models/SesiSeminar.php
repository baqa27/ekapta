<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SesiSeminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'jumlah_mahasiswa',
        'token_penilaian',
        'is_token_used',
        'token_used_at',
        'dosen_penguji_id',
        'catatan_teknis',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_token_used' => 'boolean',
        'token_used_at' => 'datetime',
    ];

    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }

    public function dosenPenguji()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sesi) {
            $sesi->token_penilaian = (string) Str::uuid();
        });
    }

    public function getLinkPenilaianAttribute()
    {
        return url('/penilaian-seminar/' . $this->token_penilaian);
    }

    public function invalidateToken()
    {
        $this->is_token_used = true;
        $this->token_used_at = now();
        $this->save();
    }
}
