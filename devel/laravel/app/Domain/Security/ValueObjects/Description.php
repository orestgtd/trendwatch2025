<?php

namespace App\Domain\Security\ValueObjects;

use App\Domain\Support\{
    ValueObjects\Abstract\AbstractStringValueObject,
};

use App\Shared\Result;

final class Description extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('description', $value);
    }
}
