<?php

namespace App\Infrastructure\Laravel\Eloquent\Trade\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use App\Domain\{
    Kernel\Values\TransactionDate,
};

final class TransactionDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): TransactionDate
    {
        return TransactionDate::tryFrom($value)
            ->match(
                fn (TransactionDate $transactionDate) => $transactionDate,
                fn (string $error) => throw new \LogicException($error)
            );
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        return match (true) {
            is_null($value) => null,
            ($value instanceof TransactionDate) => (string) $value,
            default => $value,
        };
    }
}
