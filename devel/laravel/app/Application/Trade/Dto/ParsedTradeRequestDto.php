<?php

namespace App\Application\Trade\Dto;

use App\Domain\Confirmation\ValueObjects\{
    TradeNumber,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Shared\{
    Collection,
    Result
};

final class ParsedTradeRequestDto
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly TradeNumber $tradeNumber,
    ) {}

    /** @return Result<ParsedConfirmationDto> */
    public static function fromValidatedConfirmationDto(ValidatedTradeDto $validatedDto): Result
    {
        return self::doCollection(Collection::from([
            'security_number' => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'trade_number' => TradeNumber::tryFrom($validatedDto->tradeNumber),
        ]))->map(
            fn (array $values) => new self(
                $values['security_number'],
                $values['trade_number'],
            )
        );
    }

    /** @return Result<array> */
    private static function doCollection(Collection $collection): Result
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
     * @return Rsult<array>
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
