<?php

namespace App\Domain\Kernel\Money;

use App\Domain\Kernel\Money\{
    Currency,
    Monetary,
    Money,
    MoneyAmount,
};

use App\Shared\Result;

abstract class AbstractMoney implements Monetary
{
    final protected function __construct(
        public readonly MoneyAmount $amount,
        public readonly Currency $currency
    ) {}

    public static function create(MoneyAmount $amount, Currency $currency): static
    {
        return new static($amount, $currency);
    }

    /** @return Result<static> */
    public static function tryFrom(?string $moneyValue, ?string $currencyValue = null): Result
    {
        return MoneyAmount::tryFrom($moneyValue)
            ->map(fn(MoneyAmount $amount) => static::create(
                $amount,
                $currencyValue
                    ? Currency::fromString($currencyValue)
                    : Currency::default()
            ));
    }

    public static function fromMoney(Money $money): static
    {
        return self::create(
            $money->getAmount(),
            $money->getCurrency()
        );
    }

    public static function zero(?Currency $currency = null): static
    {
        return static::create(
            MoneyAmount::zero(),
            $currency ? $currency : Currency::default()
        );
    }

    public function getAmount(): MoneyAmount
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
