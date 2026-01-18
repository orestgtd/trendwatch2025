<?php

namespace App\Presentation\ViewModels\Values;

final class MoneyView
{
    public function __construct(
        public readonly string $currency,
        public readonly string $amount,
    ) {}
}
