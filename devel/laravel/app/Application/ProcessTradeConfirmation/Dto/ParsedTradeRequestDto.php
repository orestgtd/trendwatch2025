<?php

namespace App\Application\ProcessTradeConfirmation\Dto;

use App\Application\Common\AbstractParsedRequestDto;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax,
};

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Money\Currency,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
    ValueObjects\Description,
    ValueObjects\SecurityInfo,
};

use App\Shared\{
    Collection,
    Result
};

final class ParsedTradeRequestDto extends AbstractParsedRequestDto
{
    private function __construct(
        public readonly SecurityInfo $securityInfo,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
    ) {}

    /** @return Result<self> */
    public static function fromValidatedConfirmationDto(ValidatedTradeDto $validatedDto): Result
    {
        $collection = Collection::from([
            'security_number'  => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'symbol'           => Symbol::tryFrom($validatedDto->symbol),
            'description'      => Description::tryFrom($validatedDto->description),
            'trade_number'     => TradeNumber::tryFrom($validatedDto->tradeNumber),
            'trade_action'     => TradeAction::tryFrom($validatedDto->tradeAction),
            'position_effect'  => PositionEffect::tryFrom($validatedDto->positionEffect),
            'trade_quantity'   => TradeQuantity::tryFrom($validatedDto->tradeQuantity),
            'trade_unit_type'  => UnitType::tryFrom($validatedDto->tradeUnitType),
            'unit_price'       => UnitPrice::tryFrom($validatedDto->unitPrice, Currency::default()),
            'commission'       => Commission::tryFromOrZero($validatedDto->commission, Currency::default()),
            'us_tax'           => UsTax::tryFromOrZero($validatedDto->usTax, Currency::default()),
            'expiration_date'  => ExpirationDate::tryFrom($validatedDto->expirationDate),
        ]);

        return self::processCollection($collection)->map(
            fn (array $values) => new self(
                SecurityInfo::from(
                    $values['security_number'],
                    $values['symbol'],
                    $values['description'],
                    $values['trade_unit_type'],
                    ExpirationRule::fromNullableDate($values['expiration_date'])
                ),
                $values['trade_number'],
                $values['trade_action'],
                $values['position_effect'],
                $values['trade_quantity'],
                $values['unit_price'],
                $values['commission'],
                $values['us_tax'],
            )
        );
    }
}
