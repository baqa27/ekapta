<?php

namespace App\Helpers\Auth;

use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class Himpunan extends \Illuminate\Database\Eloquent\Model implements Authenticatable
{
    use AuthenticableTrait;
}
