<?php

namespace App\Infrastructure\Laravel\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Domain\Common\ValueObjects\Money\MoneyDto;
use App\Domain\Common\ValueObjects\Money\MoneyInterface;

abstract class AbstractMoneyCast implements CastsAttributes
{
    /**
     * Fully-qualified class name of the Money-based Value Object.
     * Each concrete cast must define this constant.
     * e.g.     protected const VALUE_OBJECT_CLASS = UnitPrice::class;
     */
    protected const VALUE_OBJECT_CLASS = '';

    public function get($model, string $key, $value, array $attributes): ?MoneyInterface
    {
        $class = static::VALUE_OBJECT_CLASS;

        $amountKey   = $key . '_amount';
        $currencyKey = $key . '_currency';

        if (! isset($attributes[$amountKey], $attributes[$currencyKey])) {
            return null;
        }

        return $class::create(
            MoneyDto::create(
                $attributes[$amountKey],
                $attributes[$currencyKey]
            )
        );
    }

    public function set($model, string $key, $value, array $attributes): array
    {
        $class = static::VALUE_OBJECT_CLASS;

        $amountKey   = $key . '_amount';
        $currencyKey = $key . '_currency';

        if (! $value instanceof $class) {
            return [
                $amountKey   => null,
                $currencyKey => null,
            ];
        }

        /** @var MoneyInterface $value */
        return [
            $amountKey   => $value->getAmount(),
            $currencyKey => $value->getCurrency(),
        ];
    }
}
