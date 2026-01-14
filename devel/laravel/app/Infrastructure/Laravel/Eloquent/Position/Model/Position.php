<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
};

use App\Infrastructure\Laravel\Eloquent\{
    Casts\UnitTypeCast,
    Position\Casts\CostAmountCast,
    Position\Casts\PositionQuantityCast,
    Position\Casts\PositionTypeCast,
    Position\Casts\ProceedsAmountCast,
    Security\Casts\SecurityNumberCast,
    Security\Casts\SymbolCast,
};

use Illuminate\Database\Eloquent\Model;

/**
 * App\Infrastructure\Laravel\Eloquent\Position\Model\Position
 *
 * @property SecurityNumber     $security_number
 * @property Symbol             $symbol
 * @property PositionType       $position_type
 * @property PositionQuantity   $position_quantity
 * @property UnitType           $unit_type
 * @property CostAmount         $total_cost
 * @property ProceedsAmount     $total_proceeds
 */

class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'security_number',
        'symbol',
        'position_type',
        'position_quantity',
        'unit_type',
        'total_cost',
        'total_proceeds',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'symbol' => SymbolCast::class,
        'position_type' => PositionTypeCast::class,
        'position_quantity' => PositionQuantityCast::class,
        'unit_type' => UnitTypeCast::class,
        'total_cost' => CostAmountCast::class,
        'total_proceeds' => ProceedsAmountCast::class,
    ];
}
