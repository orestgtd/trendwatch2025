<?php

namespace App\Domain\Position\ValueObjects;

use App\Domain\Common\ValueObjects\Money\MoneyDto;

use App\Domain\Common\ValueObjects\Money\AbstractMoney;

final class TotalProceeds extends AbstractMoney
{
    public static function create(MoneyDto $dto): static
    {
        return new self($dto);
    }
}