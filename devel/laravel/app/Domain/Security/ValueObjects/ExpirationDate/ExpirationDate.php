<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

use App\Shared\{
    Date,
    Result,
};

final class ExpirationDate implements ExpirationDateInterface
{
    private Date $date;

    private function __construct(Date $date)
    {
        $this->date = $date;
    }

    public static function create(Date $date): self
    {
        return new self($date);
    }

    /** @return Result<ExpirationDate> */
    public static function tryFrom(string $value): Result
    {
        return Date::tryFrom($value)
            ->match(
                fn (Date $date): Result => Result::success(self::create($date)),
                fn (string $error): Result => Result::failure($error)
            );
    }

    public function date(): Date
    {
        return $this->date;
    }

    public function hasDate(): bool
    {
        return true;
    }

    public function isExpired(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return $this->date->toFormattedString();
    }
}
