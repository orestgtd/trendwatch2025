<?php

namespace App\Domain\Security\Expiration;

use App\Domain\{
    Kernel\Values\ExpirationDate,
    Security\Expiration\ExpirationRule,
};

use App\Foundation\Date;

final class ExpiresOn extends ExpirationRule
{
    private ExpirationDate $expirationDate;

    private function __construct(ExpirationDate $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    protected static function create(ExpirationDate $expirationDate): self
    {
        return new self($expirationDate);
    }

    public function hasExpirationDate(): bool
    {
        return true;
    }

    public function getExpirationDate(): ExpirationDate
    {
        return $this->expirationDate;
    }

    public function isExpiredAsOf(Date $asOf): bool
    {
        return $this->expirationDate->isExpiredAsOf($asOf);
    }

    // public function __toString(): string
    // {
    //     return $this->date->toFormattedString();
    // }
}
