<?php

namespace App\Application\Trade\Dto;

use App\Shared\{
    Collection,
    Result,
};

final class ValidatedTradeDto
{
    private function __construct(
        public readonly string $securityNumber,
        public readonly string $tradeNumber,
        public readonly string $tradeAction,
        public readonly string $positionEffect,
    ) {}

    /**
     * Factory for building the DTO from fully-validated domain value objects.
     * 
     * @return Result<ValidatedTradeDto>
     */
    public static function fromArray(array $input): Result
    {
        return Collection::from([
            'security_number',
            'trade_number',
            'trade_action',
            'position_effect',
        ])->reduce(
            fn (Result $result, string $key): Result => self::allRequiredFieldsReduction($result, $key),
            Result::success(self::sanitizeInput($input))
        )->map(fn (array $allValues) => new self(
            $allValues['security_number'],
            $allValues['trade_number'],
            $allValues['trade_action'],
            $allValues['position_effect'],
        ));
    }

    /**
     * @param $result Result<array>
     * @return Result<array>
     * */
    private static function allRequiredFieldsReduction(Result $result, string $key): Result
    {
        return $result->bind(
            fn (array $input) => array_key_exists($key, $input)
                ? Result::success($input)
                : Result::failure("Missing value for {$key}")
        );
    }

    private static function sanitizeInput(array $input): array
    {
        $sanitize = fn (?string $value): string =>
            is_null($value) ? '' : $value;

        return Collection::from($input)->mapWithKeys(
            fn (?string $item, string $key) => [$key => $sanitize($item)]
        )->all();
    }

}