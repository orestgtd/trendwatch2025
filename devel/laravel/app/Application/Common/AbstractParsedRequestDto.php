<?php

namespace App\Application\Common;

use App\Foundation\{
    Collection,
    Result
};

/**
 * Abstract base class for parsed request DTOs.
 *
 * Provides a reusable pipeline to transform a collection of validated
 * Result objects into a single Result containing an array of
 * domain value objects, ready for DTO construction.
 *
 * Subclasses only need to define:
 *   - Their constructor with specific domain value objects.
 *   - A static factory method to map validated DTOs to Result objects.
 */
abstract class AbstractParsedRequestDto
{
    /**
     * @param Collection<string, Result<mixed>> $collection
     * @return Result<array>
     */
    protected static function processCollection(Collection $collection): Result
    {
        return $collection->reduce(
            fn (Result $resSanitized, Result $resValueObject, string $key) =>
                self::mergeParsedValues($resSanitized, $resValueObject, $key),
            Result::success([])
        );
    }

    /**
     * @param $resSanitized Result<array>
     * @param $resValueObject Result<mixed>
     * @return Result<array>
     */
    private static function mergeParsedValues(Result $resSanitized, Result $resValueObject, string $key): Result
    {
        return $resValueObject->match(
            fn (mixed $valueObject) => self::insertParsedValue($resSanitized, $valueObject, $key),
            fn () => $resValueObject
        );
    }

    /**
     * @param $resSanitized Result<array>
     * @return Result<array>
     */
    private static function insertParsedValue(Result $resSanitized, mixed $valueObject, string $key): Result
    {
        return $resSanitized->bind(
            fn (array $sanitized) => Result::success(self::push($sanitized, $valueObject, $key))
        );
    }

    private static function push(array $a, mixed $valueObject, string $key): array
    {
        $a[$key] = $valueObject;
        return $a;
    }
}
