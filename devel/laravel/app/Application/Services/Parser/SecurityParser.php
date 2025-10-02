<?php

namespace App\Application\Services\Parser;

use App\Application\Security\Dto\{
    ParsedSecurityRequestDto,
    ValidatedSecurityDto
};
use App\Shared\Result;

final class SecurityParser
{
    public function parse(array $input): Result
    {
        return ValidatedSecurityDto::fromArray($input)
            ->bind(fn(ValidatedSecurityDto $validated) => ParsedSecurityRequestDto::fromValidatedSecurityDto($validated));
    }
}
