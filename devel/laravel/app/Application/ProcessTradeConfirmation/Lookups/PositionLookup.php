<?php

namespace App\Application\ProcessTradeConfirmation\Lookups;

use App\Application\Contracts\{
    PositionRepositoryContract,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
};

use App\Domain\Position\{
    Builders\BuildPositionFromRecord,
    Model\Position,
    Outcome\PositionOutcome,
};

use App\Foundation\Result;

final class PositionLookup
{
    public function __construct(
        private readonly PositionRepositoryContract $repository
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
            : $onExists(BuildPositionFromRecord::from($persisted));
    }
}
