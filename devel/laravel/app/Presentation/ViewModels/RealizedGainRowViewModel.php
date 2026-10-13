<?php

namespace App\Presentation\ViewModels;

use App\Presentation\ViewModels\Values\{
    MoneyView,
};

final class RealizedGainRowViewModel
{
    public function __construct(
        public readonly string $securityNumber,
        public readonly string $realizationDate,
        public readonly string $positionType,
        public readonly int $baseQuantity,
        public readonly int $tradeQuantity,
        public readonly string $unitType,
        public readonly MoneyView $cost,
        public readonly MoneyView $proceeds,
        public readonly string $reference,
    ) {
    }

    /** @return string[] */
    public static function headers(): array
    {
        return [
            'Security',
            'Realization Date',
            'Position Type',
            'Base Qty',
            'Trade Qty',
            'Unit Type',
            'Cost',
            'Proceeds',
            'Realization Source',
        ];
    }
}
