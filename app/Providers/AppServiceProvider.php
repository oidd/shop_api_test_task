<?php

namespace App\Providers;

use App\Models\Admin;
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
        $this->app->when(\App\Http\Controllers\AuthController::class)
            ->needs(AuthService::class)
            ->give(fn () => new AuthService(new Customer()));

        $this->app->when(\App\Http\Controllers\CustomerController::class)
            ->needs(AuthService::class)
            ->give(fn () => new AuthService(new Customer()));

        $this->app->when(\App\Http\Controllers\Admin\AuthController::class)
            ->needs(AuthService::class)
            ->give(fn () => new AuthService(new Admin()));

        $this->app->when(\App\Http\Controllers\Admin\AdminController::class)
            ->needs(AuthService::class)
            ->give(fn () => new AuthService(new Admin()));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
