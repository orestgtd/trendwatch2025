<?php

namespace App\Presentation\ViewModels\Values;

use App\Domain\{
    Kernel\Money\Monetary,
};

final class MoneyView
{
    public function __construct(
        public readonly string $currency,
        public readonly string $amount,
    ) {}

    public static function fromMonetary(Monetary $monetary): self
    {
        return new self(
            $monetary->getCurrency(),
            $monetary->getAmount(),
        );
    }
}
