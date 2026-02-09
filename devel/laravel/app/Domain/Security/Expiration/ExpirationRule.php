<?php

namespace App\Domain\Security\Expiration;

use App\Domain\{
    Kernel\Values\ExpirationDate,
    Security\Expiration\ExpiresOn,
    Security\Expiration\NeverExpires,
};


use App\Foundation\Date;

abstract class ExpirationRule
{
    abstract public function isExpiredAsOf(Date $asOf): bool;
    abstract public function hasExpirationDate(): bool;
    abstract public function getExpirationDate(): ?ExpirationDate;

    final public static function fromNullableDate(?ExpirationDate $date): self
    {
        return is_null($date)
            ? self::neverExpires()
            : self::expiresOn($date);
    }

    final public static function neverExpires(): self
    {
        return NeverExpires::create();
    }

    final public static function expiresOn(ExpirationDate $date): self
    {
        return ExpiresOn::create($date);
    }

    public function __toString(): string
    {
        $expirationDate = $this->getExpirationDate();

        return is_null($expirationDate)
            ? ''
            : (string) $expirationDate;
    }
}
