<?php

namespace App\Providers;

use App\View\Composers\EquipmentImageComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inject $imageMap ke semua view yang menampilkan kartu alat
        View::composer(
            ['equipments.catalog', 'equipments.index'],
            EquipmentImageComposer::class
        );
    }
}
