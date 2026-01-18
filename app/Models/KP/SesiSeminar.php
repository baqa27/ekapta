<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\Himpunan;
use App\Models\Dosen;

class SesiSeminar extends Model
{
    protected $table = 'sesi_seminar_kps';

    protected $fillable = [
        'nama_sesi',
        'tanggal_seminar',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'tempat',
        'kuota',
        'jumlah_mahasiswa',
        'terisi',
        'himpunan_id',
        'dosen_penguji_id',
        'status',
        'pendaftaran_dibuka',
        'catatan',
        'catatan_teknis',
        'token_penilaian',
        'is_token_used',
        'token_used_at',
    ];

    protected $casts = [
        'tanggal_seminar' => 'date',
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'pendaftaran_dibuka' => 'boolean',
        'is_token_used' => 'boolean',
        'token_used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sesi) {
            if (empty($sesi->token_penilaian)) {
                $sesi->token_penilaian = (string) Str::uuid();
            }
        });
    }

    // Accessor untuk link penilaian
    public function getLinkPenilaianAttribute()
    {
        return route('kp.penilaian.seminar', $this->token_penilaian);
    }

    public function invalidateToken()
    {
        $this->is_token_used = true;
        $this->token_used_at = now();
        $this->save();
    }

    // Relationships
    public function pendaftarans(): HasMany
    {
        return $this->hasMany(PendaftaranSeminar::class, 'sesi_seminar_id');
    }

    public function seminars(): HasMany
    {
        return $this->hasMany(Seminar::class, 'sesi_seminar_id');
    }

    public function himpunan(): BelongsTo
    {
        return $this->belongsTo(Himpunan::class);
    }

    public function dosenPenguji(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji_id');
    }

    // Accessors
    public function getIsFullAttribute(): bool
    {
        return $this->terisi >= $this->kuota;
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->kuota - $this->terisi);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeOpen($query)
    {
        return $query->where('pendaftaran_dibuka', true)
                     ->where('status', 'aktif');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_seminar', '>=', now()->toDateString())
                     ->whereIn('status', ['aktif', 'penuh']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }
}
