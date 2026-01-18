<?php

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\Values\{
    ExpirationView,
    MoneyView,
};

final class PositionRowViewModel
{
    public function __construct(
        public readonly string $securityNumber,
        public readonly string $symbol,
        public readonly string $positionType,
        public readonly int $quantity,
        public readonly MoneyView $cost,
        public readonly MoneyView $proceeds,
        public readonly ExpirationView $expiration,
    ) {
    }
}
