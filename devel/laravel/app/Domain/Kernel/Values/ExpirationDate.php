<?php

namespace App\Domain\Kernel\Values;

use App\Domain\Security\{
    ValueObjects\ExpirationDate\ExpiresOn,
    ValueObjects\ExpirationDate\NeverExpires,
};

use App\Shared\{
    Date,
    Result,
};

abstract class ExpirationDate
{
    abstract public function hasDate(): bool;
    abstract public function isExpired(): bool;

    /**
     * String representation suitable for persistence and logging.
     * Not intended for user-facing presentation.
     */
    abstract public function __toString(): string;

    final public static function on(Date $date): self
    {
        return ExpiresOn::create($date);
    }

    final public static function never(): self
    {
        return NeverExpires::create();
    }

    /** @return Result<ExpirationDate> */
    final public static function tryFrom(string $value): Result
    {
        return empty($value)
            ? Result::success(ExpirationDate::never())
            : Date::tryFrom($value)
                ->match(
                    fn (Date $date): Result => Result::success(ExpirationDate::on($date)),
                    fn (string $error): Result => Result::failure($error)
                )
            ;
    }

    /**
     * This function is intendede to be used with values that were already persisted
     * therefore already sanitized and validated.
     */
    final public static function from(string $value): self
    {
        return (empty($value))
            ? ExpirationDate::never()
            : ExpirationDate::on(Date::fromString($value));
    }
}

