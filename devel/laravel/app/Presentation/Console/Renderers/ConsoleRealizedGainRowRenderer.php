<?php

namespace App\Presentation\Console\Renderers;

use App\Presentation\{
    Formatters\MoneyFormatter,
    ViewModels\RealizedGainRowViewModel,
};

final class ConsoleRealizedGainRowRenderer
{
    public function __construct(
        private readonly MoneyFormatter $moneyFormatter
    ) {}

    public function render(RealizedGainRowViewModel $vm): array
    {
        return [
            $vm->securityNumber,
            $vm->positionType,
            $vm->baseQuantity,
            $vm->tradeQuantity,
            $vm->unitType,
            $this->moneyFormatter->format($vm->cost),
            $this->moneyFormatter->format($vm->proceeds),
            $vm->reference,
        ];
    }
}
