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
use App\Domain\RealizedGain\Model\RealizedGainBasis;
use App\Shared\Date;

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
    public function isExpiredAsOf(Date $asOf): bool { return $this->expirationDate->isExpiredAsOf($asOf); }

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

    public function expire(Date $asOf): void // ExpiredPositionOutcome
    {
        if ($this->isExpiredAsOf($asOf))
        {
            // TODO: calculate realized gain basis
            // $realizedGainBasis = RealizedGainBasis::create(
            //     $this->securityNumber,
            //         // we don't have a trade number !!!                
            // );
            // TODO: set quantity to zero
            // TODO: return Expired outcome with Realized Gain Basis
        }
    }


    // abstract public static function create(): static;
    // abstract public function applyTrade(Confirmation $confirmation): Result;
    // abstract public function decrease(TradeQuantity $change): Result;
    // abstract public function markClosed(): void;
    // abstract public function isClosed(): bool;
    // abstract public function type(): string; // "long" or "short"
}
