<?php

namespace App\Presentation\Console\Renderers;

use App\Presentation\{
    Formatters\MoneyFormatter,
    ViewModels\PositionRowViewModel,
};

final class ConsolePositionRowRenderer
{
    public function __construct(
        private readonly MoneyFormatter $moneyFormatter
    ) {}

    public function render(PositionRowViewModel $vm): array
    {
        return [
            $vm->securityNumber,
            $vm->symbol,
            $vm->positionType,
            $vm->quantity,
            $this->moneyFormatter->format($vm->cost),
            $this->moneyFormatter->format($vm->proceeds),
            $this->renderExpiration($vm),
        ];
    }

    private function renderExpiration(PositionRowViewModel $vm): string
    {
        return $vm->expiration->isExpired
            ? "\e[0;31m{$vm->expiration->value} EXPIRED\e[0m"
            : $vm->expiration->value;
    }
}
