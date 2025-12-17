<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiSeminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'seminar_id',
        'catatan',
        'lampiran'
    ];

    public function seminar()
    {
        return $this->belongsTo(Seminar::class);
    }
}