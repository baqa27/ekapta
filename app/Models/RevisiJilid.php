<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisiJilid extends Model
{
    use HasFactory;

    protected $fillable = [
        'catatan',
        'jilid_id',
    ];

    public function jilid()
    {
        return $this->belongsTo(Jilid::class);
    }
}
