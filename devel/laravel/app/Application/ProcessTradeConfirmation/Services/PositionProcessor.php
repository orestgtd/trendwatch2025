<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Lookups\PositionLookup,
};

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\PositionManager,
    Position\Model\Position,
    Position\Outcome\PositionOutcome,
};

use App\Shared\Result;

final class PositionProcessor
{
    public function __construct(
        private PositionLookup $lookup,
        private PositionManager $manager,
    ) {}

    /** @return Result<PositionOutcome> */
    public function computePositionOutcome(Confirmation $confirmation): Result
    {
        return $this->lookup->matchBySecurityNumber(
            $confirmation->getSecurityNumber(),
            onNotFound: fn() => $this->manager->createPosition($confirmation),
            onExists: fn(Position $position) => $confirmation->matchPositionEffect(
                onOpen: fn() => $this->manager->increasePosition($confirmation, $position),
                onClose: fn() => $this->manager->decreasePosition($confirmation, $position)
            )
        );
    }
}