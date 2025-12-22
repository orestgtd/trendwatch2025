<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
    ValueObjects\TradeQuantity,
};

use App\Domain\Position\{
    Model\AbstractPosition,
    ValueObjects\BaseQuantity,
    ValueObjects\PositionQuantity,
    ValueObjects\ProceedsBase,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Values\PositionType,
    Values\UnitType,
};

final class ShortPosition extends AbstractPosition
{
    private ProceedsBase $proceedsBase;

    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        ProceedsAmount $totalProceeds,
    ) {
        $this->securityNumber = $securityNumber;
        $this->proceedsBase = ProceedsBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalProceeds,
        );
        $this->unitType = $unitType;
    }

    public static function create(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        ProceedsAmount $totalProceeds,
    ): self {
        return new self(
            $securityNumber,
            $positionQuantity,
            $unitType,
            $totalProceeds,
        );
    }

    public static function fromPersisted(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        UnitType $unitType,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): self {
        $instance = new self($securityNumber, $positionQuantity, $unitType, $totalProceeds);
        $instance->proceedsBase = ProceedsBase::fromPersisted(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalProceeds,
            $totalCost
        );
        return $instance;
    }

    public function getPositionType(): PositionType
    {
        return PositionType::short();
    }

    public function getBaseQuantity(): BaseQuantity
    {
        return $this->proceedsBase->getQuantity();
    }

    public function getPositionQuantity(): PositionQuantity
    {
        return PositionQuantity::fromBaseQuantity(
            $this->proceedsBase->getQuantity()
        );
    }

    public function getTotalCost(): CostAmount
    {
        return $this->proceedsBase->getTotalCost();
    }

    public function getTotalProceeds(): ProceedsAmount
    {
        return $this->proceedsBase->getTotalProceeds();
    }

    public function addCover(TradeQuantity $change, CostAmount $tradeCost): static
    {
        $this->proceedsBase->addShortCover($change, $tradeCost);

        return $this;
    }

    public function increaseHolding(TradeQuantity $change, ProceedsAmount $tradeProceeds): static
    {
        $this->proceedsBase->addShortSale($change, $tradeProceeds);

        return $this;
    }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }
}
