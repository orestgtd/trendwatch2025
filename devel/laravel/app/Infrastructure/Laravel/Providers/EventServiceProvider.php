<?php

namespace App\Infrastructure\Laravel\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Application\{
    Expiration\Events\PositionExpired,
    Expiration\Projections\ApplyExpirationToPosition,
    Expiration\Projections\ApplyExpirationToRealizedGain,
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
        PositionExpired::class => [
            ApplyExpirationToPosition::class,
            ApplyExpirationToRealizedGain::class,
        ],
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
