<?php

namespace App\Presentation\Http\Presenters;

use App\Domain\Security\Model\Security;

final class SecurityPresenter
{
    public static function toArray(Security $security): array
    {
        return [
            'security_number' => (string) $security->securityNumber(),
            'symbol' => (string) $security->symbol(),
            'description' => (string) $security->canonicalDescription(),
            'unit_type' => (string) $security->unitType(),
        ];
    }
}
