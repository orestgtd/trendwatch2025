<?php

namespace App\Presentation\ViewModels\Values;

final class ExpirationView
{
    public function __construct(
        public readonly string $value,
        public readonly bool $isExpired,
    ) {}
}
