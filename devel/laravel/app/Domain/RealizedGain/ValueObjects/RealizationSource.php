<?php

namespace App\Domain\RealizedGain\ValueObjects;

use App\Domain\Kernel\{
    Identifiers\TradeNumber,
};

use App\Domain\RealizedGain\{
    ValueObjects\RealizationSourceType,
};

use App\Shared\Date;

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

    public static function expiration(Date $expiredAt): self
    {
        return new self(RealizationSourceType::expiration(), $expiredAt);
    }

    public function getType(): RealizationSourceType
    {
        return $this->type;
    }

    public function getReference(): TradeNumber | Date
    {
        return $this->reference;
    }
}
