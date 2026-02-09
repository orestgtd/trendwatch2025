<?php

namespace App\Application\ProcessTradeConfirmation\Lookups;

use App\Application\Contracts\{
    TradeRepositoryContract,
};

use App\Domain\{
    Kernel\Identifiers\TradeNumber,
    Confirmation\Builders\BuildConfirmationFromRecord,
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Confirmation\Record\ConfirmationRecord,
 };
 
use App\Infrastructure\{
     Laravel\Eloquent\Trade\Repositories\EloquentTradeRepository as TradeRepository,
};

use App\Foundation\Result;

final class TradeLookup
{
    public function __construct(
        private readonly TradeRepositoryContract $repository
    ){}

    /**
     * @param callable(Confirmation): Result<ConfirmationOutcome> $onExists
     * @param callable(): Result<ConfirmationOutcome> $onNotFound
     *
     * @return Result<ConfirmationOutcome>
     */
    public function matchByTradeNumber(
        TradeNumber $tradeNumber,
        callable $onExists,
        callable $onNotFound,
    ): Result {

        $persisted = $this->repository->findByTradeNumber($tradeNumber);

        return is_null($persisted)
            ? $onNotFound()
            : $onExists(BuildConfirmationFromRecord::from($persisted));
     }
}
