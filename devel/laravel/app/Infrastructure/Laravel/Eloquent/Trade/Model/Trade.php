<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Model;

use App\Infrastructure\Laravel\Eloquent\{
    Security\Casts\SecurityNumberCast,
    Trade\Casts\PositionEffectCast,
    Trade\Casts\TradeActionCast,
    Trade\Casts\TradeNumberCast,
    Trade\Casts\TradeQuantityCast,
    Trade\Casts\UnitPriceCast,
    Trade\Casts\CommissionCast,
    Trade\Casts\UsTaxCast,
};

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'trades';

    protected $fillable = [
        'security_number',
        'trade_number',
        'trade_action',
        'position_effect',
        'trade_quantity',
        'unit_price',
        'commission',
        'us_tax',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'trade_number' => TradeNumberCast::class,
        'trade_action' => TradeActionCast::class,
        'position_effect' => PositionEffectCast::class,
        'trade_quantity' => TradeQuantityCast::class,
        'unit_price' => UnitPriceCast::class,
        'commission' => CommissionCast::class,
        'us_tax' => UsTaxCast::class,
    ];
}
