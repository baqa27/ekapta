<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenProdi extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'prodi_id',
        'nidn',
        'kode',
    ];
}
