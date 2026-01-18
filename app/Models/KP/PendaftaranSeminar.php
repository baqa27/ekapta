<?php

namespace App\Models\KP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranSeminar extends Model
{
    protected $table = 'pendaftaran_seminar_kps';

    protected $fillable = [
        'pengajuan_id',
        'sesi_seminar_id',
        'verified',
        'verified_at',
        'verified_by',
        'nilai',
        'catatan',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    // Relationships
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function sesiSeminar(): BelongsTo
    {
        return $this->belongsTo(SesiSeminar::class, 'sesi_seminar_id');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopePending($query)
    {
        return $query->where('verified', false);
    }
}
