<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
};

use App\Domain\Expiration\{
    Outcome\ExpirationOutcome,
    Outcome\PositionExpired,
    Outcome\PositionNotExpired,
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
    ValueObjects\PositionInfo,
    ValueObjects\PositionQuantity,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    ValueObjects\RealizationSource,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

use App\Shared\Date;

abstract class Position
{
    abstract public function getBaseQuantity(): BaseQuantity;
    abstract public function getPositionQuantity(): PositionQuantity;
    abstract public function getTotalCost(): CostAmount;
    abstract public function getTotalProceeds(): ProceedsAmount;
    abstract public function expireQuantity(): void;

    protected PositionInfo $positionInfo;

    public function getDescription(): Description { return $this->positionInfo->getDescription(); }
    public function getExpirationDate(): ExpirationDate { return $this->positionInfo->getExpirationDate(); }
    public function getSecurityNumber(): SecurityNumber { return $this->positionInfo->getSecurityNumber(); }
    public function getSymbol(): Symbol { return $this->positionInfo->getSymbol(); }
    public function getPositionType(): PositionType { return $this->positionInfo->positionType; }
    public function getUnitType(): UnitType { return $this->positionInfo->getUnitType(); }
 
    public function isLong(): bool { return $this->positionInfo->isLong(); }
    public function isShort(): bool { return $this->positionInfo->isShort(); }
    public function isExpiredAsOf(Date $asOf): bool { return $this->positionInfo->isExpiredAsOf($asOf); }

    protected function __construct(
        SecurityInfo $securityInfo,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
    ) {
         $this->positionInfo = PositionInfo::from(
            $securityInfo,
            $positionType,
            $positionQuantity,
        );
     }

    public function expire(Date $asOf): ExpirationOutcome
    {
        if (! $this->isExpiredAsOf($asOf))
        {
            return PositionNotExpired::create($this);
        }

        $realizedGainBasis = RealizedGainBasis::create(
            $this->getSecurityNumber(),
            RealizationSource::expiration($this->getExpirationDate()),
            $this->getBaseQuantity(),
            $this->getPositionQuantity()->toTradeQuantity(),
            $this->getUnitType(),
            $this->getTotalCost(),
            $this->getTotalProceeds()
        );

        $this->expireQuantity();

        return PositionExpired::create($this, $realizedGainBasis);
    }

    /**
     * @template T
     * @param callable():T $onLong
     * @param callable():T $onShort
     * @return T
     */
    public function matchPositionType(callable $onLong, callable $onShort)
    {
        return $this->getPositionType()->delegate(
            onLong: fn () => $onLong(),
            onShort: fn () => $onShort()
        );
    }

    // abstract public function markClosed(): void;
    // abstract public function isClosed(): bool;
}
