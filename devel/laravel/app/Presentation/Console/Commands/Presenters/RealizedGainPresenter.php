<?php

namespace App\Presentation\Console\Commands\Presenters;

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
            realizationDate: $record->realizationDate,
            positionType: $record->positionType,
            baseQuantity: $record->baseQuantity->toInt(),
            tradeQuantity: $record->tradeQuantity->toInt(),
            unitType: $record->unitType,
            cost: MoneyView::fromMonetary($record->cost),
            proceeds: MoneyView::fromMonetary($record->proceeds),
            reference: $record->realizationSource,
        );
    }
}
