<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

use App\Domain\{
    Kernel\Values\ExpirationDate,
};

use App\Shared\{
    Date,
};

final class ExpiresOn extends ExpirationDate
{
    private Date $date;

    private function __construct(Date $date)
    {
        $this->date = $date;
    }

    protected static function create(Date $date): self
    {
        return new self($date);
    }

    public function date(): Date
    {
        return $this->date;
    }

    public function hasDate(): bool
    {
        return true;
    }

    public function isExpiredAsOf(Date $asOf): bool
    {
        return $this->date->isBefore($asOf);
    }

    public function __toString(): string
    {
        return $this->date->toFormattedString();
    }
}
