<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
    ValueObjects\TradeQuantity,
};

use App\Domain\Position\{
    ValueObjects\PositionQuantity,
    ValueObjects\PositionType,
};

use App\Domain\Security\ValueObjects\SecurityNumber;

interface Position
{
    // public function markClosed(): void;
    // public function isClosed(): bool;
    public function getSecurityNumber(): SecurityNumber;
    public function getPositionQuantity(): PositionQuantity;
    public function getPositionType(): PositionType;

    public function getTotalCost(): CostAmount;
    public function getTotalProceeds(): ProceedsAmount;

    public function increaseHolding(TradeQuantity $change): static;
}
