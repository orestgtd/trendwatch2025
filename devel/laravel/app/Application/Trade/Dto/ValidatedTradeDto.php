<?php

namespace App\Application\Trade\Dto;

use App\Application\Common\AbstractValidatedDto;

final class ValidatedTradeDto extends AbstractValidatedDto
{
    private function __construct(
        public readonly string $securityNumber,
        public readonly string $tradeNumber,
        public readonly string $tradeAction,
        public readonly string $positionEffect,
    ) {}

    protected static function requiredFields(): array
    {
        return [
            'security_number',
            'trade_number',
            'trade_action',
            'position_effect',
        ];
    }

    protected static function build(array $allValues): self
    {
        return new self(
            $allValues['security_number'],
            $allValues['trade_number'],
            $allValues['trade_action'],
            $allValues['position_effect'],
        );
    }
}
