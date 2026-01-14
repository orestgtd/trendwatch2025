<?php

namespace App\Domain\Kernel\Identifiers;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Shared\Result;

final class Symbol extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('Symbol', $value);
    }
}
