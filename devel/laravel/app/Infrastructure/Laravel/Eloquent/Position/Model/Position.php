<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\{
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpirationDate,
};

use App\Infrastructure\Laravel\Eloquent\{
    Casts\UnitTypeCast,
    Position\Casts\CostAmountCast,
    Position\Casts\PositionQuantityCast,
    Position\Casts\PositionTypeCast,
    Position\Casts\ProceedsAmountCast,
    Security\Casts\ExpirationDateCast,
    Security\Casts\SecurityNumberCast,
    Security\Casts\SymbolCast,
};

use Illuminate\Database\Eloquent\Model;

/**
 * App\Infrastructure\Laravel\Eloquent\Position\Model\Position
 *
 * @property SecurityNumber   $security_number
 * @property Symbol           $symbol
 * @property PositionType     $position_type
 * @property PositionQuantity $position_quantity
 * @property UnitType         $unit_type
 * @property CostAmount       $total_cost
 * @property ProceedsAmount   $total_proceeds
 * @property ExpirationDate   $expiration_date
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
        'expiration_date',
    ];

    protected $casts = [
        'security_number' => SecurityNumberCast::class,
        'symbol' => SymbolCast::class,
        'position_type' => PositionTypeCast::class,
        'position_quantity' => PositionQuantityCast::class,
        'unit_type' => UnitTypeCast::class,
        'total_cost' => CostAmountCast::class,
        'total_proceeds' => ProceedsAmountCast::class,
        'expiration_date' => ExpirationDateCast::class,
    ];
}
