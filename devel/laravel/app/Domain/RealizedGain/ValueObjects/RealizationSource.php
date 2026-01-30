<?php

namespace App\Domain\RealizedGain\ValueObjects;

use App\Domain\Kernel\{
    Identifiers\TradeNumber,
    Values\ExpirationDate,
};

use App\Domain\RealizedGain\{
    ValueObjects\RealizationSourceType,
};

final class RealizationSource
{
    private function __construct(
        private readonly RealizationSourceType $type,
        private readonly mixed $reference
    ) {}

    public static function trade(TradeNumber $tradeNumber): self
    {
        return new self(RealizationSourceType::trade(), $tradeNumber);
    }

    public static function expiration(ExpirationDate $expirationDate): self
    {
        return new self(RealizationSourceType::expiration(), $expirationDate);
    }

    public function getType(): RealizationSourceType
    {
        return $this->type;
    }

    public function getReference(): TradeNumber | ExpirationDate
    {
        return $this->reference;
    }
}
