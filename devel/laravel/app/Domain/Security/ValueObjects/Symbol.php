<?php

namespace App\Domain\Security\ValueObjects;

use App\Shared\Result;

final class Symbol extends AbstractValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('Symbol', $value);
    }
}
