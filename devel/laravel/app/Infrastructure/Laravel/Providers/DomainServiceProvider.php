<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Services\DomainServices;
use App\Infrastructure\Laravel\BrickMoney\BrickMoneyCalculator;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register any domain services.
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
        DomainServices::setMoneyCalculator(new BrickMoneyCalculator());
    }
}
