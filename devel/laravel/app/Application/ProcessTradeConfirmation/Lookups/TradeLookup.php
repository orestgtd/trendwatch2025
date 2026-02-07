<?php

namespace App\Application\ProcessTradeConfirmation\Lookups;

use App\Domain\{
    Kernel\Identifiers\TradeNumber,
    Confirmation\Builders\BuildNewConfirmation,
    Confirmation\Model\Confirmation,
    Confirmation\Outcome\ConfirmationOutcome,
    Confirmation\Record\TradeRecord,
};

use App\Infrastructure\{
    Laravel\Eloquent\Trade\Repositories\EloquentTradeRepository as TradeRepository,
};

use App\Shared\Result;

final class TradeLookup
{
    public function __construct(
        private readonly TradeRepository $repository
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
            : $onExists($this->buildConfirmationFromRecord($persisted));
    }

    private function buildConfirmationFromRecord(TradeRecord $persisted): Confirmation
    {
        return BuildNewConfirmation::from(
            $persisted->securityInfo,
            $persisted->tradeNumber,
            $persisted->tradeAction,
            $persisted->positionEffect,
            $persisted->tradeQuantity,
            $persisted->unitPrice,
            $persisted->commission,
            $persisted->usTax,
        );
    }
}
