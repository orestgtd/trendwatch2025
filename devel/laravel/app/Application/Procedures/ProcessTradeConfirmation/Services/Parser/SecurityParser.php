<?php

namespace App\Application\Procedures\ProcessTradeConfirmation\Services\Parser;

use App\Application\Procedures\ProcessTradeConfirmation\Dto\{
    ParsedSecurityRequestDto,
    ValidatedSecurityDto
};

use App\Foundation\Result;

final class SecurityParser
{
    public function parse(array $input): Result
    {
        return ValidatedSecurityDto::fromArray($input)
            ->bind(fn(ValidatedSecurityDto $validated) => ParsedSecurityRequestDto::fromValidatedSecurityDto($validated));
    }
}
