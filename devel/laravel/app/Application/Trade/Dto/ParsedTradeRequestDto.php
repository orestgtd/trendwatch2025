<?php

namespace App\Application\Trade\Dto;

use App\Application\Common\AbstractParsedRequestDto;
use App\Domain\Confirmation\ValueObjects\{
    PositionEffect,
    TradeAction,
    TradeNumber,
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
    ) {}

    /** @return Result<ParsedConfirmationDto> */
    public static function fromValidatedConfirmationDto(ValidatedTradeDto $validatedDto): Result
    {
       $collection = Collection::from([
            'security_number'  => SecurityNumber::tryFrom($validatedDto->securityNumber),
            'trade_number'     => TradeNumber::tryFrom($validatedDto->tradeNumber),
            'trade_action'     => TradeAction::tryFrom($validatedDto->tradeAction),
            'position_effect'  => PositionEffect::tryFrom($validatedDto->positionEffect),
        ]);

        return self::processCollection($collection)->map(
            fn (array $values) => new self(
                $values['security_number'],
                $values['trade_number'],
                $values['trade_action'],
                $values['position_effect'],
            )
        );
    }
}
