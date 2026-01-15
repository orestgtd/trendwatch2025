<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

use App\Domain\{
    Kernel\Values\ExpirationDate,
};

final class NeverExpires extends ExpirationDate
{
    protected static function create(): self
    {
        return new self;
    }

    public function hasDate(): bool
    {
        return false;
    }

    public function isExpired(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return '';
    }
}
