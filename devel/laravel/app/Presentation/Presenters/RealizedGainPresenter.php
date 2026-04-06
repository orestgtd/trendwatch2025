<?php

namespace App\Presentation\Presenters;

use App\Domain\RealizedGain\{
    Record\RealizedGainBasisRecord,
};

use App\Presentation\ViewModels\{
    RealizedGainRowViewModel,
    Values\MoneyView,
};

final class RealizedGainPresenter
{
    public function present(RealizedGainBasisRecord $record): RealizedGainRowViewModel
    {
        return new RealizedGainRowViewModel(
            securityNumber: $record->securityNumber,
            positionType: $record->positionType,
            baseQuantity: $record->baseQuantity->toInt(),
            tradeQuantity: $record->tradeQuantity->toInt(),
            unitType: $record->unitType,

            cost: new MoneyView(
                $record->cost->getCurrency()->getValue(),
                (string) $record->cost->getAmount(),
            ),
            proceeds: new MoneyView(
                $record->proceeds->getCurrency()->getValue(),
                (string) $record->proceeds->getAmount(),
            ),
            reference: $record->realizationSource,
        );
    }
}
