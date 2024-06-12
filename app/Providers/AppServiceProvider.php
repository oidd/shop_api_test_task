<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Models\Customer;
use App\Service\Auth\AuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(AuthController::class)
            ->needs(AuthService::class)
            ->give(fn () => new AuthService(new Customer()));

//        $this->app->when(admin controller::class)
//            ->needs(AuthService::class)
//            ->give(fn () => new AuthService(new Admin()));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
