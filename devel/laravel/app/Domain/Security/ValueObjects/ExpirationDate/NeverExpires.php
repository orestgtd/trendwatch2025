<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

use App\Domain\{
    Kernel\Values\ExpirationDate,
};

use App\Shared\Date;

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

    public function isExpiredAsOf(Date $asOf): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return '';
    }
}
