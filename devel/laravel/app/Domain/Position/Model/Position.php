<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
};

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

use App\Shared\Date;

interface Position
{
    // public function markClosed(): void;
    // public function isClosed(): bool;
    public function getSecurityNumber(): SecurityNumber;
    public function getSymbol(): Symbol;
    public function getPositionQuantity(): PositionQuantity;
    public function getPositionType(): PositionType;
    public function getUnitType(): UnitType;

    public function getTotalCost(): CostAmount;
    public function getTotalProceeds(): ProceedsAmount;

    public function getExpirationDate(): ExpirationDate;
    public function isExpiredAsOf(Date $asOf): bool;

    // public function getRealizedGainOutcome(): ?RealizedGainOutcome;
}
