<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Helpers\ContextHelper;
use App\Contracts\ContextRepositoryInterface;
use App\Contracts\ContextServiceInterface;
use App\Repositories\PengajuanRepository;
use App\Repositories\PendaftaranRepository;
use App\Repositories\BimbinganRepository;
use App\Repositories\SeminarRepository;
use App\Services\PengajuanService;
use App\Services\BimbinganService;
use App\Services\SeminarService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repositories
        $this->app->bind('pengajuan.repository', function ($app) {
            return new PengajuanRepository();
        });
        
        $this->app->bind('pendaftaran.repository', function ($app) {
            return new PendaftaranRepository();
        });
        
        $this->app->bind('bimbingan.repository', function ($app) {
            return new BimbinganRepository();
        });
        
        $this->app->bind('seminar.repository', function ($app) {
            return new SeminarRepository();
        });
        
        // Bind Services
        $this->app->bind('pengajuan.service', function ($app) {
            return new PengajuanService($app->make('pengajuan.repository'));
        });
        
        $this->app->bind('bimbingan.service', function ($app) {
            return new BimbinganService($app->make('bimbingan.repository'));
        });
        
        $this->app->bind('seminar.service', function ($app) {
            return new SeminarService($app->make('seminar.repository'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share context data to all views
        View::composer('*', function ($view) {
            $view->with('currentContext', ContextHelper::get());
            $view->with('isTA', ContextHelper::isTA());
            $view->with('isKP', ContextHelper::isKP());
            $view->with('contextLabel', ContextHelper::getLabel());
        });
    }
}
