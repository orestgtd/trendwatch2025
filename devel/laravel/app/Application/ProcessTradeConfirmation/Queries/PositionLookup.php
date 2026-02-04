<?php

namespace App\Application\ProcessTradeConfirmation\Queries;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
};

use App\Domain\Position\{
    Builders\BuildPositionFromPersisted,
    Model\Position,
    Outcome\PositionOutcome,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Repositories\EloquentPositionRepository as PositionRepository,
};

use App\Shared\Result;

final class PositionLookup
{
    public function __construct(
        private readonly PositionRepository $repository
        ){}

    /**
     * @param callable(Position): Result<PositionOutcome> $onExists
     * @param callable(): Result<PositionOutcome> $onNotFound
     *
     * @return Result<PositionOutcome>
     */
    public function matchBySecurityNumber(
        SecurityNumber $securityNumber,
        callable $onExists,
        callable $onNotFound,
    ): Result {

        $persisted = $this->repository->findBySecurityNumber($securityNumber);

        return is_null($persisted)
            ? $onNotFound()
            : $onExists($this->buildPositionFromPersisted($persisted));
    }

    private function buildPositionFromPersisted(PersistedPositionDto $persisted): Position
    {
        return BuildPositionFromPersisted::from(
            $persisted->securityInfo,
            $persisted->positionType,
            $persisted->positionQuantity,
            $persisted->totalCost,
            $persisted->totalProceeds,
        );
    }
}
