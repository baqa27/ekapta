<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register class aliases
        $loader = AliasLoader::getInstance();
        $loader->alias('PDF', \Barryvdh\DomPDF\Facade\Pdf::class);
        $loader->alias('Excel', \Maatwebsite\Excel\Facades\Excel::class);
        $loader->alias('QrCode', \SimpleSoftwareIO\QrCode\Facades\QrCode::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Ensure storage directories exist
        $viewPath = storage_path('framework/views');
        if (!is_dir($viewPath)) {
            mkdir($viewPath, 0755, true);
        }

        $sessionPath = storage_path('framework/sessions');
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0755, true);
        }

        // Register policies manually (replacing AuthServiceProvider)
        Gate::policy(\App\Models\Bagian::class, \App\Policies\BagianPolicy::class);
        Gate::policy(\App\Models\Mahasiswa::class, \App\Policies\MahasiswaPolicy::class);
        Gate::policy(\App\Models\RevisiPendaftaran::class, \App\Policies\RevisiPendaftaranPolicy::class);
        Gate::policy(\App\Models\RevisiPengajuan::class, \App\Policies\RevisiPengajuanPolicy::class);
    }
}
