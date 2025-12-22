<?php

namespace App\Domain\Kernel\Identifiers;

use App\Domain\Common\ValueObjects\Abstract\AbstractStringValueObject;

use App\Shared\Result;

final class SecurityNumber extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('SecurityNumber', $value);
    }
}
