<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Queries\FindPositionQuery,
};

use App\Domain\{
    Confirmation\Model\Confirmation,
    Position\Outcome\PositionOutcome,
    Position\PositionManager,
};

use App\Shared\Result;

final class PositionService
{
    public function __construct(
        private FindPositionQuery $findPosition,
        private PositionManager $manager,
    ) {}

    /**
     * Update the position based on the confirmation outcome.
     *
     * @return Result<PositionOutcome>
     */
    public function createOrUpdatePosition(Confirmation $confirmation): Result
    {
        return $this->manager->createOrUpdatePosition(
            $confirmation,
            $this->findPosition->findBySecurityNumber(
                    $confirmation->getSecurityNumber()
                )
            );
    }
}
