<?php

namespace App\Infrastructure\Laravel\Eloquent\EventStore\Model;

use Illuminate\Database\Eloquent\{
    Casts\Attribute,
    Model,
};

use App\Domain\Kernel\Identifiers\{
    SecurityNumber,
    Symbol,
};

use App\Domain\{
    Security\ValueObjects\Description,
    Kernel\Values\ExpirationDate,
    Security\Expiration\ExpirationRule,
    Security\ValueObjects\SecurityInfo,
    Kernel\Values\UnitType,
};

class EventStore extends Model
{
    protected $table = 'eventstore';

    protected $fillable = [
        'aggregate_type',
        'aggregate_id',
        'version',
        'event_type',
        'payload',
        'external_id',
    ];

    protected function securityInfo(): Attribute
    {
        $makeAttribute = function(array $attributes): SecurityInfo
        {
            $payload = $attributes['payload'];
            $decoded = json_decode($payload, true);

            $expirationDate = ExpirationDate::tryFrom($decoded['expiration_date'])->getValue();

            return SecurityInfo::from(
                SecurityNumber::fromString($decoded['security_number']),
                Symbol::fromString($decoded['symbol']),
                Description::fromString($decoded['description']),
                UnitType::fromString($decoded['unit_type']),
                ExpirationRule::fromNullableDate($expirationDate)
            );
        };

        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $makeAttribute($attributes)
        );
    }
}
