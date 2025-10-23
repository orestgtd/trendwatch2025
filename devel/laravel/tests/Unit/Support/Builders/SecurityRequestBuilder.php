<?php

namespace Tests\Unit\Support\Builders;

use App\Application\ProcessTradeConfirmation\Dto\{
    ParsedSecurityRequestDto,
    ValidatedSecurityDto,
};

use App\Domain\Security\ValueObjects\{
    UnitType,
};

final class SecurityRequestBuilder
{
    private function __construct(
        private string $securityNumber,
        private string $symbol,
        private string $description,
        private string $unitType,
        private string $expirationDate,
    ) {}

    public static function YYZ(): self
    {
        return new self(
            '2112',
            'YYZ',
            'SECURITY UNDER PRESSURE',
            UnitType::SHARES,
            '',
        );
    }

    public function withDescription(string $value): self
    {
        $this->description = $value;
        return $this;
    }

    public function build(): ParsedSecurityRequestDto
    {
        return ParsedSecurityRequestDto::fromValidatedSecurityDto(
            ValidatedSecurityDto::fromArray([
                'security_number' => $this->securityNumber,
                'symbol' => $this->symbol,
                'description' => $this->description,
                'unit_type' => $this->unitType,
                'expiration_date' => $this->expirationDate,
            ])->getValue()
        )->getValue();
    }
}
