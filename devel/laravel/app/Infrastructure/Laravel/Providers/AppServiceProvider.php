<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Security\Repositories\SecurityRepository;
use App\Infrastructure\Laravel\Eloquent\Security\{
    Repositories\EloquentSecurityRepository,
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interface to the Eloquent implementation
        // $this->app->bind(
        //     SecurityRepository::class,
        //     EloquentSecurityRepository::class
        // );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
