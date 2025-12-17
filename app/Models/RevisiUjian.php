<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiUjian extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'catatan',
        'lampiran',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
