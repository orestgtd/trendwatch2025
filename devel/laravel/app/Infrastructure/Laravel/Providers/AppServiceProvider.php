<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

use App\Application\Contracts\{
    EventPersistenceContract,
    PositionRepositoryContract,
    RealizedGainRepositoryContract,
    SecurityRepositoryContract,
    TradeRepositoryContract,
};

use App\Infrastructure\Laravel\Eloquent\{
    EventStore\EloquentEventStoreRepository,
    Position\Repositories\EloquentPositionRepository,
    RealizedGain\Repositories\EloquentRealizedGainRepository,
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
            PositionRepositoryContract::class,
            EloquentPositionRepository::class
        );

        $this->app->bind(
            RealizedGainRepositoryContract::class,
            EloquentRealizedGainRepository::class
        );

        $this->app->bind(
            SecurityRepositoryContract::class,
            EloquentSecurityRepository::class
        );

        $this->app->bind(
            TradeRepositoryContract::class,
            EloquentTradeRepository::class
        );

        $this->app->bind(
            EventPersistenceContract::class,
            EloquentEventStoreRepository::class
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
