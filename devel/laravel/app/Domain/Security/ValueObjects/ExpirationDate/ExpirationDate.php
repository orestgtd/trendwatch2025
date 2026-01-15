<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

use App\Shared\Date;

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpiresOn,
    ValueObjects\ExpirationDate\NeverExpires,
};

abstract class ExpirationDate
{
    abstract public function hasDate(): bool;
    abstract public function isExpired(): bool;

    abstract public function __toString(): string;

    final public static function on(Date $date): self
    {
        return ExpiresOn::create($date);
    }

    final public static function never(): self
    {
        return NeverExpires::create();
    }
}

