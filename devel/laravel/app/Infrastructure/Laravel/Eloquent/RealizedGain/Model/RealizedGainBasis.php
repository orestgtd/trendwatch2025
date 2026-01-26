<?php

namespace App\Infrastructure\Laravel\Eloquent\RealizedGain\Model;

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Confirmation\{
    ValueObjects\TradeQuantity,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\{
    ValueObjects\BaseQuantity,
};

use App\Domain\RealizedGain\{
    ValueObjects\RealizationSource,
};

use App\Infrastructure\Laravel\Eloquent\{
    Casts\UnitTypeCast,
    Position\Casts\CostAmountCast,
    Position\Casts\PositionTypeCast,
    Position\Casts\ProceedsAmountCast,
    RealizedGain\Casts\BaseQuantityCast,
    RealizedGain\Casts\RealizationSourceCast,
    Security\Casts\SecurityNumberCast,
    Trade\Casts\TradeQuantityCast,
};

use Illuminate\Database\Eloquent\Model;

/**
 * @property SecurityNumber $security_number
 * @property PositionType $position_type
 * @property RealizationSource $realization_source
 * @property BaseQuantity $base_quantity
 * @property TradeQuantity $trade_quantity
 * @property UnitType $unit_type
 * @property CostAmount $cost
 * @property ProceedsAmount $proceeds
 */

class RealizedGainBasis extends Model
{
    protected $table = 'realized_gain_basis';

    protected $fillable = [
        'security_number',
        'position_type',
        'realization_source',
        'base_quantity',
        'trade_quantity',
        'unit_type',
        'cost',
        'proceeds',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'position_type' => PositionTypeCast::class,
        'realization_source' => RealizationSourceCast::class,
        'base_quantity' => BaseQuantityCast::class,
        'trade_quantity' => TradeQuantityCast::class,
        'unit_type' => UnitTypeCast::class,
        'cost' => CostAmountCast::class,
        'proceeds' => ProceedsAmountCast::class,
    ];
}
