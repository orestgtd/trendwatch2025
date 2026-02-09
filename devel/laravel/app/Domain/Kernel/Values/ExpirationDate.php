<?php

namespace App\Domain\Kernel\Values;

use App\Foundation\{
    Date,
    Result,
};

final class ExpirationDate
{
    private function __construct(
        private readonly Date $date,
    ){}

    public static function from(Date $date): self
    {
        return new self ($date);
    }

    /** @return Result<ExpirationDate> */
    public static function tryFrom(string $value): Result
    {
        return Date::tryFrom($value)->match(
            onSuccess: fn (Date $date) => Result::success(self::from($date)),
            onFailure: fn (string $error) => Result::failure($error)
        );
    }

    public function isExpiredAsOf(Date $asOf): bool
    {
        return $this->date->isBefore($asOf);
    }

    public function toDate(): Date
    {
        return $this->date;
    }

    public function __toString(): string
    {
        return (string) $this->date;
    }
}

