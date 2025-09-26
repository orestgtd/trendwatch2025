<?php

namespace App\Application\Common;

use App\Shared\{
    Collection,
    Result,
};

abstract class AbstractValidatedDto
{
    /**
     * Return the list of required keys for this DTO.
     *
     * @return string[]
     */
    abstract protected static function requiredFields(): array;

    /**
     * Build a concrete DTO instance from sanitized/validated values.
     *
     * @param array $allValues
     * @return self
     */
    abstract protected static function build(array $allValues): self;

    /**
     * Factory for building the DTO from fully-validated domain value objects.
     * 
     * @return Result<static>
     */
    public static function fromArray(array $input): Result
    {
        return Collection::from(static::requiredFields())
        ->reduce(
            self::validateRequiredField(...),
            Result::success(self::sanitizeInput($input))
        )->map(fn (array $allValues) => static::build($allValues));
    }

    /**
     * @param $result Result<array>
     * @return Result<array>
     * */
    private static function validateRequiredField(Result $result, string $key): Result
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