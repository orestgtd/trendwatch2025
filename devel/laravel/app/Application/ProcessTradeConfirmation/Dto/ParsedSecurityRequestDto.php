<?php

namespace App\Application\ProcessTradeConfirmation\Dto;

use App\Application\Common\AbstractParsedRequestDto;

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

final class ParsedSecurityRequestDto extends AbstractParsedRequestDto
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
       $collection = Collection::from([
            'security_number'   => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'symbol'            => Symbol::tryFrom($validatedDto->symbol),
            'description'       => Description::tryFrom($validatedDto->description),
            'unit_type'         => UnitType::tryFrom($validatedDto->unitType),
            'expiration_date'   => ExpirationDate::tryFrom($validatedDto->expirationDate),
        ]);

        return self::processCollection($collection)->map(
            fn (array $values) => new self(
                $values['security_number'],
                $values['symbol'],
                $values['description'],
                $values['unit_type'],
                $values['expiration_date'],
            )
        );
    }
}
