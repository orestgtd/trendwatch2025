<?php

namespace App\Application\ProcessTradeConfirmation\Services\Parser;

use App\Application\Trade\Dto\{
    ParsedTradeRequestDto,
    ValidatedTradeDto
};
use App\Shared\Result;

final class TradeParser
{
    public function parse(array $input): Result
    {
        return ValidatedTradeDto::fromArray($input)
            ->bind(fn(ValidatedTradeDto $validated) => ParsedTradeRequestDto::fromValidatedConfirmationDto($validated));
    }
}
