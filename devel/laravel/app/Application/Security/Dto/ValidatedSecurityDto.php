<?php

namespace App\Application\Security\Dto;

use App\Application\Common\AbstractValidatedDto;

final class ValidatedSecurityDto extends AbstractValidatedDto
{
    private function __construct(
        public readonly string $securityNumber,
        public readonly string $symbol,
        public readonly string $description,
        public readonly string $unitType,
        public readonly string $expirationDate
    ) {}

    protected static function requiredFields(): array
    {
        return [
            'security_number',
            'symbol',
            'description',
            'unit_type',
            'expiration_date',
        ];
    }

    protected static function build(array $allValues): self
    {
        return new self(
            $allValues['security_number'],
            $allValues['symbol'],
            $allValues['description'],
            $allValues['unit_type'],
            $allValues['expiration_date'],
        );
    }
}
