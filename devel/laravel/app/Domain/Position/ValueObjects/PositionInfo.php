<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\ExpirationDate,
    Values\PositionType,
    Values\UnitType,
};

use App\Domain\Position\{
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

use App\Shared\Date;

final class PositionInfo
{
    private function __construct(
        public readonly SecurityInfo $securityInfo,
        public readonly PositionType $positionType,
        public readonly PositionQuantity $positionQuantity,
    ) {}

    public static function from(
        SecurityInfo $securityInfo,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
    ): self
    {
        return new self(
            $securityInfo,
            $positionType,
            $positionQuantity,
        );
    }

    public function isLong(): bool { return $this->positionType->isLong(); }
    public function isShort(): bool { return $this->positionType->isShort(); }
    public function getExpirationDate(): ExpirationDate { return $this->securityInfo->getExpirationDate(); }
    public function isExpiredAsOf(Date $asOf): bool { return $this->securityInfo->isExpiredAsOf($asOf); }


    public function getSecurityNumber(): SecurityNumber { return $this->securityInfo->securityNumber; }
    public function getSymbol(): Symbol { return $this->securityInfo->symbol; }
    public function getDescription(): Description { return $this->securityInfo->canonicalDescription; }
    public function getUnitType(): UnitType { return $this->securityInfo->unitType; }
}
