<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Model;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Values\UnitType,
};

use App\Infrastructure\Laravel\Eloquent\{
    Casts\UnitTypeCast,
    Security\Casts\SecurityNumberCast,
};

use App\Infrastructure\Laravel\Eloquent\Trade\Casts\{
    PositionEffectCast,
    TradeActionCast,
    TradeNumberCast,
    TradeQuantityCast,
    UnitPriceCast,
    CommissionCast,
    UsTaxCast,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Casts\SymbolCast,
};

use Illuminate\Database\Eloquent\Model;

/**
 * App\Infrastructure\Laravel\Eloquent\Trade\Model\Trade
 *
 * @property SecurityNumber $security_number
 * @property Symbol $symbol
 * @property TradeNumber $trade_number
 * @property TradeAction $trade_action
 * @property PositionEffect $position_effect
 * @property TradeQuantity    $trade_quantity
 * @property UnitType $unit_type
 * @property UnitPrice  $unit_price
 * @property Commission  $commission
 * @property UsTax  $us_tax
 */

class Trade extends Model
{
    protected $table = 'trades';

    protected $fillable = [
        'security_number',
        'symbol',
        'trade_number',
        'trade_action',
        'position_effect',
        'trade_quantity',
        'unit_type',
        'unit_price',
        'commission',
        'us_tax',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'symbol' => SymbolCast::class,
        'trade_number' => TradeNumberCast::class,
        'trade_action' => TradeActionCast::class,
        'position_effect' => PositionEffectCast::class,
        'trade_quantity' => TradeQuantityCast::class,
        'unit_type' => UnitTypeCast::class,
        'unit_price' => UnitPriceCast::class,
        'commission' => CommissionCast::class,
        'us_tax' => UsTaxCast::class,
    ];
}
