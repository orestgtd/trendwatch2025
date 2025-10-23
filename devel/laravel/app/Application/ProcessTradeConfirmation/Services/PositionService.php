<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Queries\FindPositionQuery,
};

use App\Domain\Common\Money\Currency;

use App\Domain\Confirmation\{
    Model\Confirmation,
    ValueObjects\CostAmount,
    ValueObjects\ProceedsAmount,
};

use App\Domain\Position\{
    Model\LongPosition,
    Model\ShortPosition,
    Model\Position,
    Outcome\DecreasedHolding,
    Outcome\IncreasedHolding,
    Outcome\NewPositionCreated,
    Outcome\PositionOutcome,
    ValueObjects\PositionQuantity,
};

use App\Domain\Security\{
    ValueObjects\SecurityNumber,
};

use App\Shared\Result;

final class PositionService
{
    public function __construct(
        private FindPositionQuery $findPosition,
    ) {}

    /**
     * Update the position based on the confirmation outcome.
     *
     * @return Result<PositionOutcome>
     */
    public function createOrUpdatePosition(Confirmation $confirmation): Result
    {
        return Result::success(
            $confirmation->matchPositionEffect(
                onOpen: fn(Confirmation $confirmation) => $this->addToNewOrExistingPosition($confirmation),
                onClose: fn(Confirmation $confirmation) => $this->reduceExistingPosition($confirmation),
            )
        );
    }

    private function addToNewOrExistingPosition(Confirmation $confirmation): PositionOutcome
    {
        return
            $confirmation->matchTradeAction(
                onBuy: fn(Confirmation $confirmation) => $this->addToNewOrExistingLongPosition($confirmation),
                onSell: fn(Confirmation $confirmation) => $this->addToNewOrExistingShortPosition($confirmation),
            );
    }

    private function addToNewOrExistingLongPosition(Confirmation $confirmation): PositionOutcome
    {
        $position = $this->lookupPosition($confirmation->getSecurityNumber());

        return $position
            ? $this->addToExistingLongPosition($confirmation, $position)
            : $this->createNewLongPosition($confirmation);
    }

    private function createNewLongPosition(Confirmation $confirmation): NewPositionCreated
    {
        return new NewPositionCreated(
            LongPosition::create(
                $confirmation->getSecurityNumber(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                $confirmation->netCost(),
                ProceedsAmount::zero(Currency::default()),
            )
        );
    }

    private function createNewShortPosition(Confirmation $confirmation): NewPositionCreated
    {
        return new NewPositionCreated(
            ShortPosition::create(
                $confirmation->getSecurityNumber(),
                PositionQuantity::fromTradeQuantity($confirmation->getTradeQuantity()),
                CostAmount::zero(Currency::default()),
                $confirmation->netProceeds(),
            )
        );
    }

    private function addToExistingLongPosition(Confirmation $confirmation, LongPosition $position): IncreasedHolding
    {
        return new IncreasedHolding(
            $position->increaseHolding(
                $confirmation->getTradeQuantity(),
                $confirmation->netCost()
            )
        );
    }

    private function addToExistingShortPosition(Confirmation $confirmation, ShortPosition $position): IncreasedHolding
    {
        return new IncreasedHolding(
            $position->increaseHolding(
                $confirmation->getTradeQuantity(),
                $confirmation->netProceeds()
            )
        );
    }

    private function addToNewOrExistingShortPosition(Confirmation $confirmation): PositionOutcome
    {
        $position = $this->lookupPosition($confirmation->getSecurityNumber());

        return $position
            ? $this->addToExistingShortPosition($confirmation, $position)
            : $this->createNewShortPosition($confirmation);
    }

    private function reduceExistingPosition(Confirmation $confirmation): PositionOutcome
    {
        $position = $this->lookupPosition($confirmation->getSecurityNumber());

        return
            $confirmation->matchTradeAction(
                onBuy: fn(Confirmation $confirmation) => $this->reduceExistingShortPosition($confirmation, $position),
                onSell: fn(Confirmation $confirmation) => $this->reduceExistingLongPosition($confirmation, $position),
            );
    }

    private function reduceExistingLongPosition(Confirmation $confirmation, LongPosition $position): PositionOutcome
    {
        return new DecreasedHolding(
            $position->decreaseHolding(
                $confirmation->getTradeQuantity(),
                $confirmation->netProceeds()
            )
        );
    }

    private function reduceExistingShortPosition(Confirmation $confirmation, ShortPosition $position): PositionOutcome
    {
        return new DecreasedHolding(
            $position->decreaseHolding(
                $confirmation->getTradeQuantity(),
                $confirmation->netCost()
            )
        );
    }

    private function lookupPosition(SecurityNumber $securityNumber): ?Position
    {
        return $this->findPosition->findBySecurityNumber($securityNumber);
    }
}
