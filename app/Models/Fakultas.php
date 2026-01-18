<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $fillable = [
        'namafakultas',
        'image',
    ];

    public function prodis()
    {
        return $this->hasMany(Prodi::class);
    }

    public function dekans()
    {
        return $this->hasMany(Dekan::class);
    }
}