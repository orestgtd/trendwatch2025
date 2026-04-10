<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Application\{
    ProcessTradeConfirmation\Events\RealizedGainCreated,
    ProcessTradeConfirmation\Events\TradeConfirmationCreated,
    ProcessTradeConfirmation\Projections\ApplyRealizedGain,
    ProcessTradeConfirmation\Projections\ApplyTradeToSecurity,
    ProcessTradeConfirmation\Projections\ApplyTradeToPosition,
    ProcessTradeConfirmation\Projections\RecordTradeFromConfirmation,
};

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RealizedGainCreated::class => [
            ApplyRealizedGain::class,
        ],
        TradeConfirmationCreated::class => [
            ApplyTradeToSecurity::class,
            ApplyTradeToPosition::class,
            RecordTradeFromConfirmation::class,
        ],
    ];
}
