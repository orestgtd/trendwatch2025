<?php

namespace App\Application\TradeConfirmation;

use App\Application\ProcessTradeConfirmation\{
    Services\Parser\TradeParser,
    Services\Parser\SecurityParser,
};

use App\Application\TradeConfirmation\Dto\{
    ParsedTradeData,
};

use App\Shared\Result;

final class TradeRequestParser
{
    public function __construct(
        private SecurityParser $securityParser,
        private TradeParser $tradeParser,
    ) {}

    /**
     * Parse a normalized trade request into parsed trade data.
     *
     * @return Result<ParsedTradeData>
     */

    public function parse(array $request): Result
    {
        // Parse trade
        $resultConfirmation = $this->tradeParser->parse($request);

        if ($resultConfirmation->isFailure()) {
            return Result::failure($resultConfirmation->getError());
        }

        // Parse security
        $resultSecurity = $this->securityParser->parse($request);

        if ($resultSecurity->isFailure()) {
            return Result::failure($resultSecurity->getError());
        }

        // Return parsed results
        return Result::success(
            new ParsedTradeData(
                $resultConfirmation->getValue(),
                $resultSecurity->getValue()
            )
        );
    }
}