<?php

namespace App\Domain\Security\Model;

use App\Domain\Kernel\{
    Identifiers\SecurityNumber,
    Identifiers\Symbol,
    Values\UnitType,
};

use App\Domain\Security\ValueObjects\{
    Description,
    ExpirationDate\ExpirationDate,
    Variations\VariationsInterface,
};

final class OptionSecurity extends AbstractSecurity
{
    private ExpirationDate $expirationDate;

    private function __construct(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
        ExpirationDate $expirationDate
    ) {
        parent::__construct(
            $securityNumber,
            $symbol,
            UnitType::contracts(),
            $canonicalDescription,
            $variations,
        );

        $this->expirationDate = $expirationDate;
    }

    public static function create(
        SecurityNumber $securityNumber,
        Symbol $symbol,
        Description $canonicalDescription,
        VariationsInterface $variations,
        ExpirationDate $expirationDate
    ): self {

        return new self(
            $securityNumber,
            $symbol,
            $canonicalDescription,
            $variations,
            $expirationDate
        );
    }

    public function expirationDate(): ExpirationDate { return $this->expirationDate; }
}
