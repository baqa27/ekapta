<?php

namespace App\Http\Controllers\TA;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        // Artisan::call('route:cache');
        return view('ta.pages.home.home', [
            'title' => 'Ekapta',
        ]);
    }

    public function login()
    {
        return view('ta.pages.home.login', [
            'title' => 'Login',
        ]);
    }
}

