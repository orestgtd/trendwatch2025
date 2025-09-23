<?php

namespace App\Domain\Security\ValueObjects\ExpirationDate;

interface ExpirationDateInterface
{
    public function hasDate(): bool;
    public function isExpired(): bool;

    public function __toString(): string;
}

