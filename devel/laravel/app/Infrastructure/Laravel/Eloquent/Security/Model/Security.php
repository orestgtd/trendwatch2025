<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
};

use App\Infrastructure\Laravel\Eloquent\{
    Casts\UnitTypeCast,
};

use App\Infrastructure\Laravel\Eloquent\Security\Casts\{
    DescriptionCast,
    ExpirationDateCast,
    SecurityNumberCast,
    SymbolCast,
    VariationsCast,
};

use Illuminate\Database\Eloquent\Model;


/**
 * App\Infrastructure\Laravel\Eloquent\Security\Model\Security
 *
 * @property SecurityNumber     $security_number
 */

class Security extends Model
{
    protected $table = 'securities';

    protected $fillable = [
        'canonical_description',
        'security_number',
        'symbol',
        'variations',
        'unit_type',
        'expiration_date',
    ];

    protected $casts = [
        'canonical_description' => DescriptionCast::class,
        'expiration_date' => ExpirationDateCast::class,
        'security_number' => SecurityNumberCast::class,
        'symbol' => SymbolCast::class,
        'unit_type' => UnitTypeCast::class,
        'variations'  => VariationsCast::class,
    ];
}
