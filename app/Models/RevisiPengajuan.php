<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiPengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'catatan',
        'lampiran',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
