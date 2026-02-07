<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

use App\Application\Contracts\{
    PositionRepositoryContract,
    SecurityRepositoryContract,
    TradeRepositoryContract,
};

use App\Infrastructure\Laravel\Eloquent\{
    Position\Repositories\EloquentPositionRepository,
    Security\Repositories\EloquentSecurityRepository,
    Trade\Repositories\EloquentTradeRepository,
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the interface to the Eloquent implementation

        $this->app->bind(
            SecurityRepositoryContract::class,
            EloquentSecurityRepository::class
        );

        $this->app->bind(
            PositionRepositoryContract::class,
            EloquentPositionRepository::class
        );

        $this->app->bind(
            TradeRepositoryContract::class,
            EloquentTradeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
