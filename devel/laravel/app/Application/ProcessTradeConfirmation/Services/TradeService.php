<?php

namespace App\Application\ProcessTradeConfirmation\Services;

use App\Application\Trade\{
    Queries\FindByTradeNumberQuery,
};

use App\Application\ProcessTradeConfirmation\{
    Dto\ParsedTradeRequestDto,
    Actions\CreateNewTrade,
};

use App\Domain\Confirmation\Outcome\ConfirmationOutcome;
use App\Shared\Result;

class TradeService
{
    public function __construct(
        private readonly FindByTradeNumberQuery $findTrade,
        private readonly CreateNewTrade $createTrade,
    ) {}

    /** @return Result<ConfirmationOutcome> */
    public function processConfirmationRequest(ParsedTradeRequestDto $requestDto): Result
    {
        $tradeNumber = $requestDto->tradeNumber;
        $trade = $this->findTrade->findByTradeNumber($tradeNumber);

        return $trade
        ? Result::failure("Found duplicated trade number {$tradeNumber}.")
        : $this->createTrade->createNewTradeFromDto($requestDto);
    }
}
