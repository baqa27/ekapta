<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'semester',
        'status',
    ];
}