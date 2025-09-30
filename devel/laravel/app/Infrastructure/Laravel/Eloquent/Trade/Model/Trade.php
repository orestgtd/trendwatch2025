<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Model;

use App\Domain\Confirmation\ValueObjects\{
    TradeNumber,
    TradeAction, PositionEffect,
    TradeQuantity,
    UnitPrice, Commission, UsTax,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

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

/**
 * App\Infrastructure\Laravel\Eloquent\Trade\Model\Trade
 *
 * @property SecurityNumber $security_number
 * @property TradeNumber $trade_number
 * @property TradeAction $trade_action
 * @property PositionEffect $position_effect
 * @property TradeQuantity    $trade_quantity
 * @property UnitPrice  $unit_price
 * @property Commission  $commission
 * @property UsTax  $us_tax
 */

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
