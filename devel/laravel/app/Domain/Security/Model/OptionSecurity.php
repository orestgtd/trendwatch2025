<?php

namespace App\Domain\Security\Model;

use App\Domain\Security\{
    Model\Security,
    ValueObjects\SecurityInfo,
    ValueObjects\Variations\VariationsInterface,
};

final class OptionSecurity extends Security
{
    public static function create(
        SecurityInfo $securityInfo,
        VariationsInterface $variations,
    ): self {

        return new self(
            $securityInfo,
            $variations,
        );
    }
}
