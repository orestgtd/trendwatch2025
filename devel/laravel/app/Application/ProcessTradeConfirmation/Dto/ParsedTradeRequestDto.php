<?php

namespace App\Application\ProcessTradeConfirmation\Dto;

use App\Application\Common\AbstractParsedRequestDto;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Identifiers\TradeNumber,
    Money\Currency,
    Values\ExpirationDate,
    Values\UnitType,
};

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeQuantity,
    UnitPrice,
    UsTax,
};

use App\Shared\{
    Collection,
    Result
};

final class ParsedTradeRequestDto extends AbstractParsedRequestDto
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly Symbol $symbol,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly UnitType $unitType,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
        public readonly ExpirationDate $expirationDate,
    ) {}

    /** @return Result<self> */
    public static function fromValidatedConfirmationDto(ValidatedTradeDto $validatedDto): Result
    {
        $collection = Collection::from([
            'security_number'  => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'symbol'           => Symbol::tryFrom($validatedDto->symbol),
            'trade_number'     => TradeNumber::tryFrom($validatedDto->tradeNumber),
            'trade_action'     => TradeAction::tryFrom($validatedDto->tradeAction),
            'position_effect'  => PositionEffect::tryFrom($validatedDto->positionEffect),
            'trade_quantity'   => TradeQuantity::tryFrom($validatedDto->tradeQuantity),
            'trade_unit_type'  => UnitType::tryFrom($validatedDto->tradeUnitType),
            'unit_price'       => UnitPrice::tryFrom($validatedDto->unitPrice, Currency::default()),
            'commission'       => Commission::tryFrom($validatedDto->commission, Currency::default()),
            'us_tax'           => UsTax::tryFromOrZero($validatedDto->usTax, Currency::default()),
            'expiration_date'  => ExpirationDate::tryFrom($validatedDto->expirationDate),
        ]);

        return self::processCollection($collection)->map(
            fn (array $values) => new self(
                $values['security_number'],
                $values['symbol'],
                $values['trade_number'],
                $values['trade_action'],
                $values['position_effect'],
                $values['trade_quantity'],
                $values['trade_unit_type'],
                $values['unit_price'],
                $values['commission'],
                $values['us_tax'],
                $values['expiration_date'],
            )
        );
    }
}
