<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\ProcessTradeConfirmation\{
    Actions\CreateNewTrade,
    Dto\ParsedTradeRequestDto,
    Lookups\TradeLookup,
};

use App\Domain\Confirmation\{
    Outcome\ConfirmationOutcome,
};

use App\Shared\Result;

class TradeService
{
    public function __construct(
        private readonly TradeLookup $lookup,
        private readonly CreateNewTrade $createTrade,
    ) {}

    /** @return Result<ConfirmationOutcome> */
    public function processConfirmationRequest(ParsedTradeRequestDto $requestDto): Result
    {
        $tradeNumber = $requestDto->tradeNumber;

        return $this->lookup->matchByTradeNumber(
            $requestDto->tradeNumber,
            onNotFound: fn () => $this->createTrade->createNewTradeFromDto($requestDto),
            onExists: fn () => Result::failure("Found duplicated trade number {$tradeNumber}.")
        );
    }
}
