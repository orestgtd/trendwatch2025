<?php

namespace App\Application\ProcessTradeConfirmation\Services\Parser;

use App\Application\ProcessTradeConfirmation\Dto\{
    ParsedTradeRequestDto,
    ValidatedTradeDto
};
use App\Foundation\Result;

final class TradeParser
{
    public function parse(array $input): Result
    {
        return ValidatedTradeDto::fromArray($input)
            ->bind(fn(ValidatedTradeDto $validated) => ParsedTradeRequestDto::fromValidatedConfirmationDto($validated));
    }
}
