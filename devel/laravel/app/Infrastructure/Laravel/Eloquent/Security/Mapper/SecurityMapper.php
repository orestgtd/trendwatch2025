<?php

namespace App\Infrastructure\Laravel\Eloquent\Security\Mapper;

use App\Domain\Security\{
    Dto\SecurityData,
    Model\Security,
};
use App\Infrastructure\Laravel\Eloquent\Security\Model\Security as EloquentSecurity;

class SecurityMapper
{
    public static function toDto(EloquentSecurity $eloquent): SecurityData
    {
        dd($eloquent);

        return new SecurityData(
            $eloquent->security_number,
            $eloquent->symbol,
            $eloquent->description,
            $eloquent->variations,
            $eloquent->unit_type,
            $eloquent->expiration_date
        );
    }

    public static function toEloquent(Security $security): EloquentSecurity
    {
        $eloquent = new EloquentSecurity();

        $eloquent->security_number = $security->securityNumber();
        $eloquent->symbol = $security->symbol();
        $eloquent->canonical_description = $security->canonicalDescription();
        $eloquent->variations = $security->variations();
        $eloquent->unit_type = $security->unitType();
        $eloquent->expiration_date = $security->expirationDate();

        return $eloquent;
    }
}
