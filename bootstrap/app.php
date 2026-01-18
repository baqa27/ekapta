<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Load route Tugas Akhir (TA)
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/ta.php'));

            // Load route Kerja Praktik (KP)
            \Illuminate\Support\Facades\Route::middleware('web')
                ->group(base_path('routes/kp.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'isMahasiswa' => \App\Http\Middleware\IsMahasiswa::class,
            'isMahasiswaLogin' => \App\Http\Middleware\IsMahasiswaLogin::class,
            'isDosen' => \App\Http\Middleware\IsDosen::class,
            'isDosenLogin' => \App\Http\Middleware\IsDosenLogin::class,
            'isProdi' => \App\Http\Middleware\IsProdi::class,
            'isProdiLogin' => \App\Http\Middleware\IsProdiLogin::class,
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'isAdminLogin' => \App\Http\Middleware\IsAdminLogin::class,
            'isLogin' => \App\Http\Middleware\IsLogin::class,
            'isAdminFotokopi' => \App\Http\Middleware\IsAdminFotokopi::class,
            'isAdminProdi' => \App\Http\Middleware\IsAdminProdi::class,
            'isHimpunan' => \App\Http\Middleware\isHimpunan::class,
            'system.check' => \App\Http\Middleware\SystemContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
