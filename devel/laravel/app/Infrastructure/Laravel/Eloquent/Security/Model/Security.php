<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Model;

use App\Infrastructure\Laravel\Eloquent\Security\Casts\{
    DescriptionCast,
    ExpirationDateCast,
    SecurityNumberCast,
    SymbolCast,
    UnitTypeCast,
    VariationsCast,
};

use Illuminate\Database\Eloquent\Model;

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
