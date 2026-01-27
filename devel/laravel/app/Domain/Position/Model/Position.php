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
    ValueObjects\BaseQuantity,
    ValueObjects\PositionQuantity,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    ValueObjects\RealizationSource,
};

use App\Shared\Date;

abstract class Position
{
    abstract public function getPositionType(): PositionType;
    abstract public function getBaseQuantity(): BaseQuantity;
    abstract public function getPositionQuantity(): PositionQuantity;
    abstract public function getTotalCost(): CostAmount;
    abstract public function getTotalProceeds(): ProceedsAmount;
    abstract public function expireQuantity(): void;

    protected SecurityNumber $securityNumber;
    protected Symbol $symbol;
    protected UnitType $unitType;
    protected ExpirationDate $expirationDate;

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getSymbol(): Symbol { return $this->symbol; }
    public function getUnitType(): UnitType { return $this->unitType; }
    public function getExpirationDate(): ExpirationDate { return $this->expirationDate; }
    public function isExpiredAsOf(Date $asOf): bool { return $this->expirationDate->isExpiredAsOf($asOf); }

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
            $realizedGainBasis = RealizedGainBasis::create(
                $this->securityNumber,
                RealizationSource::expiration($asOf),
                $this->getBaseQuantity(),
                $this->getPositionQuantity()->toTradeQuantity(),
                $this->unitType,
                $this->getTotalCost(),
                $this->getTotalProceeds()
            );

            dd($realizedGainBasis);

            // TODO: set quantity to zero
            $this->expireQuantity();

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
