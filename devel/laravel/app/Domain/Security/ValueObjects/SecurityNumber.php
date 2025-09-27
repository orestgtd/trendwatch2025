<?php

namespace App\Domain\Security\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractStringValueObject;

use App\Shared\Result;

final class SecurityNumber extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('SecurityNumber', $value);
    }
}
