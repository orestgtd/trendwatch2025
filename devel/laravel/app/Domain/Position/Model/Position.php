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
    ValueObjects\PositionInfo,
    ValueObjects\PositionQuantity,
};

use App\Domain\RealizedGain\{
    Model\RealizedGainBasis,
    ValueObjects\RealizationSource,
};

use App\Domain\Security\{
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

    protected PositionInfo $info;

    public function getSecurityNumber(): SecurityNumber { return $this->info->getSecurityNumber(); }
    public function getSymbol(): Symbol { return $this->info->getSymbol(); }
    public function getDescription(): Description { return $this->info->getDescription(); }
    public function getPositionType(): PositionType { return $this->info->positionType; }
    public function getUnitType(): UnitType { return $this->info->getUnitType(); }
    public function getExpirationDate(): ExpirationDate { return $this->info->getExpirationDate(); }
    public function isExpiredAsOf(Date $asOf): bool { return $this->info->isExpiredAsOf($asOf); }

    protected function __construct(
        SecurityInfo $securityInfo,
        PositionType $positionType,
        PositionQuantity $positionQuantity,
    )
    {
        $this->info = PositionInfo::from(
            $securityInfo,
            $positionType,
            $positionQuantity,
        );
    }

    public function isLong(): bool { return $this->info->isLong(); }
    public function isShort(): bool{ return $this->info->isShort(); }

    // public function expire(Date $asOf): void // ExpiredPositionOutcome
    // {
    //     if ($this->isExpiredAsOf($asOf))
    //     {
    //         $realizedGainBasis = RealizedGainBasis::create(
    //             $this->getSecurityNumber(),
    //             RealizationSource::expiration($asOf),
    //             $this->getBaseQuantity(),
    //             $this->getPositionQuantity()->toTradeQuantity(),
    //             $this->getUnitType(),
    //             $this->getTotalCost(),
    //             $this->getTotalProceeds()
    //         );

    //         dd($realizedGainBasis);

    //         // TODO: set quantity to zero
    //         $this->expireQuantity();

    //         // TODO: return Expired outcome with Realized Gain Basis
    //     }
    // }


    // abstract public static function create(): static;
    // abstract public function applyTrade(Confirmation $confirmation): Result;
    // abstract public function decrease(TradeQuantity $change): Result;
    // abstract public function markClosed(): void;
    // abstract public function isClosed(): bool;
    // abstract public function type(): string; // "long" or "short"
}
