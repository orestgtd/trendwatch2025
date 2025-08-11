<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

final class NoExpiration implements ExpirationDateInterface
{
    public static function create(): self
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
