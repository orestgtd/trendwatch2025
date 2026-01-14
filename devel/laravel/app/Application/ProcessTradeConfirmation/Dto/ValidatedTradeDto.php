<?php

namespace App\Application\ProcessTradeConfirmation\Dto;

use App\Application\Common\AbstractValidatedDto;

final class ValidatedTradeDto extends AbstractValidatedDto
{
    private function __construct(
        public readonly string $securityNumber,
        public readonly string $symbol,
        public readonly string $tradeNumber,
        public readonly string $tradeAction,
        public readonly string $positionEffect,
        public readonly string $tradeQuantity,
        public readonly string $tradeUnitType,
        public readonly string $unitPrice,
        public readonly string $commission,
        public readonly string $usTax,
        public readonly string $expirationDate,
    ) {
    }

    /**
     * @return array<string>
     */
    protected static function requiredFields(): array
    {
        return [
            'security_number',
            'symbol',
            'trade_number',
            'trade_action',
            'position_effect',
            'trade_quantity',
            'unit_type',
            'unit_price',
            'commission',
            'us_tax',
            'expiration_date',
        ];
    }

    /**
     * @param array<string> $allValues
     */
    protected static function build(array $allValues): self
    {
        return new self(
            $allValues['security_number'],
            $allValues['symbol'],
            $allValues['trade_number'],
            $allValues['trade_action'],
            $allValues['position_effect'],
            $allValues['trade_quantity'],
            $allValues['unit_type'],
            $allValues['unit_price'],
            $allValues['commission'],
            $allValues['us_tax'],
            $allValues['expiration_date'],
        );
    }
}
