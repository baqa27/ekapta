<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Auth\Admin as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    public const TYPE_SUPER_ADMIN = 1;
    public const TYPE_ADMIN_FOTOCOPY = 2;

    protected $fillable = [
        'kode',
        'nik',
        'nama',
        'tgllahir',
        'tptlahir',
        'alamat',
        'email',
        'hp',
        'password',
        'type',
    ];

    protected $hidden = [
        'password'
    ];
}
