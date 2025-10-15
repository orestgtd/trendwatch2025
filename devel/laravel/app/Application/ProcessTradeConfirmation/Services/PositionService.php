<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Actions\CreateNewPosition,
    Actions\EvaluateTradeContext,
    Queries\FindPositionQuery,
};

use App\Domain\{
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Position\Model\Position,
    Position\Outcome\PositionOutcome,
    Security\ValueObjects\SecurityNumber,
};
use App\Domain\Common\Money\Currency;
use App\Domain\Confirmation\ValueObjects\PositionEffect;
use App\Domain\Confirmation\ValueObjects\ProceedsAmount;
use App\Domain\Confirmation\ValueObjects\TradeAction;
use App\Domain\Position\Model\LongPosition;
use App\Domain\Position\Outcome\IncreasedHolding;
use App\Domain\Position\Outcome\NewPositionCreated;
use App\Domain\Position\ValueObjects\PositionQuantity;
use App\Shared\Result;

final class PositionService
{
    public function __construct(
        private FindPositionQuery $findPosition,
        // private CreateNewPosition $createPosition,
        // private UpdateExistingPosition $updatePosition,
    ) {}

    /**
     * Update the position based on the confirmation outcome.
     *
     * @return Result<PositionOutcome>
     */
    public function createOrUpdatePosition(Confirmation $confirmation): Result
    {
        return Result::success(
            match ($confirmation->getPositionEffect()->value()) {
                PositionEffect::OPEN => $this->addToNewOrExistingPosition($confirmation),
                PositionEffect::CLOSE => $this->reduceExistingPosition($confirmation),
            }
        );
    }

    private function addToNewOrExistingPosition(Confirmation $confirmation): PositionOutcome
    {
        return match ($confirmation->getTradeAction()->value()) {
            TradeAction::BUY => $this->addToNewOrExistingLongPosition($confirmation),
            TradeAction::SELL => $this->addToNewOrExistingShortPosition($confirmation),
        };
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

    private function addToExistingLongPosition(Confirmation $confirmation, Position $position): IncreasedHolding
    {
        return new IncreasedHolding(
            $position->increaseHolding(
                $confirmation->getTradeQuantity()
            )
        );
    }

    private function addToNewOrExistingShortPosition(Confirmation $confirmation)
    {
        dd('addToNewOrExistingShortPosition');
    }

    private function reduceExistingPosition(Confirmation $confirmation)
    {
        dd('Reduce existing position');
    }

    private function lookupPosition(SecurityNumber $securityNumber): ?Position
    {
        return $this->findPosition->findBySecurityNumber($securityNumber);
    }
}
