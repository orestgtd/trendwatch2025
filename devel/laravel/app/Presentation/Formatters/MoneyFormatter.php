<?php

namespace App\Presentation\Formatters;

use App\Presentation\ViewModels\Values\MoneyView;

final class MoneyFormatter
{
    public function format(MoneyView $money): string
    {
        return "{$money->currency} " . "$" . "{$money->amount}";

    }
}
