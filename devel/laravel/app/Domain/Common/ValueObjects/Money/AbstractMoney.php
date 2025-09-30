<?php

namespace App\Domain\Common\ValueObjects\Money;

use App\Domain\Common\ValueObjects\Money\MoneyDto;

use App\Shared\Result;

abstract class AbstractMoney implements MoneyInterface
{
    protected MoneyDto $dto;

    protected function __construct(MoneyDto $dto)
    {
        $this->dto = $dto;
    }

    abstract protected static function create(MoneyDto $dto): static;

    /** @return Result<static> */
    public static function tryFrom(?string $value, ?string $currency = 'USD'): Result
    {
        return MoneyDto::tryFrom($value, $currency)
            ->map(fn (MoneyDto $dto) => static::create($dto));
    }

    public static function zero(?string $currency = 'USD'): static
    {
        return static::create(
            MoneyDto::zero($currency)
        );
    }

    public function getAmount(): string
    {
        return $this->dto->amount;
    }

    public function getCurrency(): string
    {
        return $this->dto->currency;
    }

    public function __toString(): string
    {
        return (string) $this->dto;
    }
}