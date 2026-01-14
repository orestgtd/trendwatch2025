<?php

namespace App\Domain\Position\Model;

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
    ValueObjects\ExpirationDate\ExpirationDateInterface,
};

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

    public function getExpirationDate(): ExpirationDateInterface;

    // public function getRealizedGainOutcome(): ?RealizedGainOutcome;
}
