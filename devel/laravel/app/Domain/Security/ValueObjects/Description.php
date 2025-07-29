<?php

namespace App\Domain\Security\ValueObjects;

use App\Domain\Common\ValueObjects\AbstractValueObject;

use App\Shared\Result;

final class Description extends AbstractValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('description', $value);
    }

    public function equals(Description $other): bool
    {
        return $this->value === $other->value;
    }
}
