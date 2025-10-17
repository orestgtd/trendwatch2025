<?php

namespace App\Domain\Common\Money;

use App\Domain\Common\ValueObjects\AbstractStringValueObject;

use App\Shared\Result;

final class Currency extends AbstractStringValueObject
{
    public static function tryFrom(string $value): Result
    {
        return parent::tryKind('Currency', $value);
    }

    public static function default(): self
    {
        return parent::fromString('USD');
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
