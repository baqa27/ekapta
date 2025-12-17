<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function index()
    {
        // Artisan::call('route:cache');
        return view('pages.home.home', [
            'title' => 'Ekapta',
        ]);
    }

    public function login()
    {
        return view('pages.home.login', [
            'title' => 'Login',
        ]);
    }
}
