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

abstract class AbstractPosition implements Position
{
    abstract public function getPositionType(): PositionType;

    protected SecurityNumber $securityNumber;
    protected Symbol $symbol;
    protected UnitType $unitType;
    protected ExpirationDate $expirationDate;

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getSymbol(): Symbol { return $this->symbol; }
    public function getUnitType(): UnitType { return $this->unitType; }
    public function getExpirationDate(): ExpirationDate { return $this->expirationDate; }
    public function isExpired(): bool { return $this->expirationDate->isExpired(); }

    abstract public function getTotalCost(): CostAmount;
    abstract public function getTotalProceeds(): ProceedsAmount;

    public function isLong(): bool
    {
        return $this->getPositionType()->isLong();
    }

    public function isShort(): bool
    {
        return $this->getPositionType()->isShort();
    }


    // abstract public static function create(): static;
    // abstract public function applyTrade(Confirmation $confirmation): Result;
    // abstract public function decrease(TradeQuantity $change): Result;
    // abstract public function markClosed(): void;
    // abstract public function isClosed(): bool;
    // abstract public function type(): string; // "long" or "short"
}
