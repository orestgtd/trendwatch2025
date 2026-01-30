<?php

namespace App\Domain\Security\Expiration;

use App\Domain\{
    Kernel\Values\ExpirationDate,
    Security\Expiration\ExpirationRule,
};

use App\Shared\Date;

final class NeverExpires extends ExpirationRule
{
    protected static function create(): self
    {
        return new self;
    }

    public function hasExpirationDate(): bool
    {
        return false;
    }

    public function getExpirationDate(): ?ExpirationDate
    {
        return null;
    }

    public function isExpiredAsOf(Date $asOf): bool
    {
        return false;
    }
}
