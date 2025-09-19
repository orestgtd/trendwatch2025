<?php

namespace App\Application\Security\Dto;

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDate,
    ExpirationDate\ExpirationDateInterface,
    SecurityNumber,
    Symbol,
    UnitType,
};

use App\Shared\{
    Collection,
    Result
};

final class ParsedSecurityRequestDto
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly Description $description,
        public readonly UnitType $unitType,
        public readonly ExpirationDateInterface $expirationDate
    ) {}

    /** @return Result<ParsedSecurityRequestDto> */
    public static function fromValidatedSecurityDto(ValidatedSecurityDto $validatedDto): Result
    {
        $a = [
            'security_number' => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'symbol' => Symbol::tryFrom($validatedDto->symbol),
            'description' => Description::tryFrom($validatedDto->description),
            'unit_type' => UnitType::tryFrom($validatedDto->unitType),
            'expiration_date' => ExpirationDate::tryFrom($validatedDto->expirationDate)
        ];

        $mapFn = fn (array $values) => new self(
                $values['security_number'],
                $values['symbol'],
                $values['description'],
                $values['unit_type'],
                $values['expiration_date']
        );

        return self::sequence(Collection::from($a))->map($mapFn);
    }

    /** @return Result<array> */
    private static function sequence(Collection $collection): Result
    {
        return $collection->reduce(
            fn (Result $resSanitized, Result $resValueObject, string $key) =>
                self::allValidValuesReduction($resSanitized, $resValueObject, $key),
            Result::success([])
        );
    }

    /**
     * @param $resSanitized Result<array>
     * @param $resValueObject Result<array>
     * @return Result<array>
     * */
    private static function allValidValuesReduction(Result $resSanitized, Result $resValueObject, string $key): Result
    {
        return $resValueObject->match(
            fn (mixed $valueObject) => self::pushValueObject($resSanitized, $valueObject, $key),
            fn () => $resValueObject
        );
    }

    /**
     * @param $reSanitized Result<array>
     * @return Result<array>
     */
    private static function pushValueObject(Result $resSanitized, mixed $valueObject, string $key): Result
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
