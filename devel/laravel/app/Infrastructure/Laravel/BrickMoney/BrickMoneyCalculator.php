<?php

namespace App\Infrastructure\Laravel\BrickMoney;

use Brick\Money\Money as Brick;

use App\Domain\Common\Money\{
    Currency,
    Monetary,
    Money,
    MoneyAmount,
    MoneyCalculator,
};

final class BrickMoneyCalculator implements MoneyCalculator
{
    public function add(Monetary $money1, Monetary $money2): Monetary
    {
        $brick1 = $this->makeBrick($money1);
        $brick2 = $this->makeBrick($money2);

        return $this->fromBrick(
            $brick1->plus($brick2)
        );
    }

    public function multiply(Monetary $money, int $quantity): Monetary
    {
        $brick = $this->makeBrick($money);

        return $this->fromBrick(
            $brick->multipliedBy($quantity)
        );
    }

    public function subtract(Monetary $money1, Monetary $money2): Monetary
    {
        return Money::zero(Currency::default());
    }

    private function makeBrick(Monetary $money): Brick
    {
        return Brick::of(
            $money->getAmount(),
            $money->getCurrency()->getValue()
        );
    }

    private function fromBrick(Brick $brick): Monetary
    {
        return Money::create(
            MoneyAmount::fromString($brick->getAmount()),
            Currency::fromString($brick->getCurrency())
        );
    }
}
