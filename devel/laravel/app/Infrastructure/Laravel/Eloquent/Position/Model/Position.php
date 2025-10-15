<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Model;

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
    PositionType,
};

use App\Domain\Confirmation\ValueObjects\{
    CostAmount,
    ProceedsAmount,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\{
    Position\Casts\CostAmountCast,
    Position\Casts\PositionQuantityCast,
    Position\Casts\PositionTypeCast,
    Position\Casts\ProceedsAmountCast,
    Security\Casts\SecurityNumberCast,
};

use Illuminate\Database\Eloquent\Model;

/**
 * App\Infrastructure\Laravel\Eloquent\Trade\Model\Trade
 *
 * @property SecurityNumber     $security_number
 * @property PositionType       $position_type
 * @property PositionQuantity   $position_quantity
 * @property CostAmount         $total_cost
 * @property ProceedsAmount     $total_proceeds
 */

class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'security_number',
        'position_type',
        'position_quantity',
        'total_cost',
        'total_proceeds',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'position_type' => PositionTypeCast::class,
        'position_quantity' => PositionQuantityCast::class,
        'total_cost' => CostAmountCast::class,
        'total_proceeds' => ProceedsAmountCast::class,
    ];
}
