<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\CostAmount,
    ValueObjects\TradeQuantity,
    ValueObjects\ProceedsAmount,
};

use App\Domain\Position\ValueObjects\{
    PositionQuantity,
    PositionType,
};
use App\Domain\Security\ValueObjects\SecurityNumber;

// use App\Shared\Result;

abstract class AbstractPosition implements Position
{
    abstract public function getPositionType(): PositionType;

    protected SecurityNumber $securityNumber;
    protected PositionQuantity $positionQuantity;

    protected CostAmount $totalCost;
    protected ProceedsAmount $totalProceeds;

    public function getSecurityNumber(): SecurityNumber { return $this->securityNumber; }
    public function getPositionQuantity(): PositionQuantity { return $this->positionQuantity; }

    public function getTotalCost(): CostAmount { return $this->totalCost; }
    public function getTotalProceeds(): ProceedsAmount { return $this->totalProceeds; }

    abstract public function increaseHolding(TradeQuantity $change): static;

    // abstract public static function create(): static;
    // abstract public function applyTrade(Confirmation $confirmation): Result;
    // abstract public function decrease(TradeQuantity $change): Result;
    // abstract public function markClosed(): void;
    // abstract public function isClosed(): bool;
    // abstract public function type(): string; // "long" or "short"
}
