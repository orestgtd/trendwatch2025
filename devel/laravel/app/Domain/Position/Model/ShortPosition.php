<?php

namespace App\Domain\Position\Model;

use App\Domain\Confirmation\{
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
    ValueObjects\TradeQuantity,
};

use App\Domain\Kernel\{
    Values\PositionType,
};

use App\Domain\Position\{
    Model\Position,
    Record\PositionRecord,
    ValueObjects\BaseQuantity,
    ValueObjects\PositionQuantity,
    ValueObjects\ProceedsBase,
};

use App\Domain\Security\{
    ValueObjects\SecurityInfo,
};

final class ShortPosition extends Position
{
    private ProceedsBase $proceedsBase;

    private function __construct(
        SecurityInfo $securityInfo,
        PositionQuantity $positionQuantity,
        ProceedsAmount $totalProceeds,
    ) {
        parent::__construct(
            $securityInfo,
            PositionType::short(),
            $positionQuantity,
        );

        $this->proceedsBase = ProceedsBase::create(
            BaseQuantity::fromPositionQuantity($positionQuantity),
            $totalProceeds,
        );
    }

    public static function create(
        SecurityInfo $securityInfo,
        PositionQuantity $positionQuantity,
        ProceedsAmount $totalProceeds,
    ): self {
        return new self(
            $securityInfo,
            $positionQuantity,
            $totalProceeds,
        );
    }

    public static function fromRecord(
        PositionRecord $record
    ): self {

        $instance = new self(
            $record->securityInfo,
            $record->positionQuantity,
            $record->totalProceeds,
        );

        $instance->proceedsBase = ProceedsBase::fromPersisted(
            BaseQuantity::fromPositionQuantity($record->positionQuantity),
            $record->totalProceeds,
            $record->totalCost
        );

        return $instance;
    }

    public function getBaseQuantity(): BaseQuantity {
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

    public function addShortSale(TradeQuantity $change, ProceedsAmount $tradeProceeds): static
    {
        $this->proceedsBase->addShortSale($change, $tradeProceeds);

        return $this;
    }

    public function expireQuantity(): void
    {
        $this->proceedsBase->expire();
    }

    public function markClosed(): void {}

    public function isClosed(): bool
    {
        return false;
    }
}
