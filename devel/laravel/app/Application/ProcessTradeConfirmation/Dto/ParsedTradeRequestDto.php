<?php

namespace App\Application\ProcessTradeConfirmation\Dto;

use App\Application\Common\AbstractParsedRequestDto;

use App\Domain\Common\Money\Currency;

use App\Domain\Confirmation\ValueObjects\{
    Commission,
    PositionEffect,
    TradeAction,
    TradeNumber,
    TradeQuantity,
    TradeUnitType,
    UnitPrice,
    UsTax,
};

use App\Domain\Security\ValueObjects\{
    SecurityNumber,
};

use App\Shared\{
    Collection,
    Result
};

final class ParsedTradeRequestDto extends AbstractParsedRequestDto
{
    private function __construct(
        public readonly SecurityNumber $securityNumber,
        public readonly TradeNumber $tradeNumber,
        public readonly TradeAction $tradeAction,
        public readonly PositionEffect $positionEffect,
        public readonly TradeQuantity $tradeQuantity,
        public readonly TradeUnitType $tradeUnitType,
        public readonly UnitPrice $unitPrice,
        public readonly Commission $commission,
        public readonly UsTax $usTax,
    ) {}

    /** @return Result<self> */
    public static function fromValidatedConfirmationDto(ValidatedTradeDto $validatedDto): Result
    {
       $collection = Collection::from([
            'security_number'  => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'trade_number'     => TradeNumber::tryFrom($validatedDto->tradeNumber),
            'trade_action'     => TradeAction::tryFrom($validatedDto->tradeAction),
            'position_effect'  => PositionEffect::tryFrom($validatedDto->positionEffect),
            'trade_quantity'   => TradeQuantity::tryFrom($validatedDto->tradeQuantity),
            'trade_unit_type'  => TradeUnitType::tryFrom($validatedDto->tradeUnitType),
            'unit_price'       => UnitPrice::tryFrom($validatedDto->unitPrice, Currency::default()),
            'commission'       => Commission::tryFrom($validatedDto->commission, Currency::default()),
            'us_tax'           => UsTax::tryFrom($validatedDto->usTax, Currency::default()),
        ]);

        return self::processCollection($collection)->map(
            fn (array $values) => new self(
                $values['security_number'],
                $values['trade_number'],
                $values['trade_action'],
                $values['position_effect'],
                $values['trade_quantity'],
                $values['trade_unit_type'],
                $values['unit_price'],
                $values['commission'],
                $values['us_tax'],
            )
        );
    }
}
