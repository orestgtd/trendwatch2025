<?php

namespace App\Domain\Confirmation\Builders;

use App\Domain\Confirmation\{
    Model\Confirmation,
    Record\ConfirmationRecord,
};

final class BuildConfirmationFromRecord
{
    public static function from(ConfirmationRecord $record): Confirmation
    {
        return Confirmation::create(
            $record->securityInfo,
            $record->tradeNumber,
            $record->tradeAction,
            $record->positionEffect,
            $record->tradeQuantity,
            $record->unitPrice,
            $record->commission,
            $record->usTax
        );
    }
}
