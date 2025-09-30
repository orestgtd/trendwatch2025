<?php

namespace App\Domain\Confirmation\ValueObjects;

use App\Domain\Common\ValueObjects\Money\MoneyDto;

use App\Domain\Common\ValueObjects\Money\AbstractMoney;

final class UsTax extends AbstractMoney
{
    public static function create(MoneyDto $dto): static
    {
        return new self($dto);
    }
}