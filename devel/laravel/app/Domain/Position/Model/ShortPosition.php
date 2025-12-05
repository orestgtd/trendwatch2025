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
    ValueObjects\PositionType,
    ValueObjects\PositionQuantity,
    ValueObjects\ProceedsBase,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

final class ShortPosition extends AbstractPosition
{
    private ProceedsBase $proceedsBase;

    private function __construct(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        ProceedsAmount $totalProceeds,
    ) {
        $this->securityNumber = $securityNumber;
        $this->proceedsBase = ProceedsBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalProceeds,
        );
    }

    public static function create(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        ProceedsAmount $totalProceeds,
    ): self {
        return new self(
            $securityNumber,
            $positionQuantity,
            $totalProceeds,
        );
    }

    public static function fromPersisted(
        SecurityNumber $securityNumber,
        PositionQuantity $positionQuantity,
        CostAmount $totalCost,
        ProceedsAmount $totalProceeds,
    ): self {
        $instance = new self($securityNumber, $positionQuantity, $totalProceeds);
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
