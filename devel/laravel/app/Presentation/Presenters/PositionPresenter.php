<?php

namespace App\Presentation\Presenters;

use App\Domain\Position\{
    Model\Position,
};

use App\Presentation\ViewModels\{
    PositionRowViewModel,
    Values\ExpirationView,
    Values\MoneyView,
};

use App\Shared\Date;

final class PositionPresenter
{
    public function present(Position $position): PositionRowViewModel
    {
        return new PositionRowViewModel(
            securityNumber: $position->getSecurityNumber(),
            symbol: $position->getSymbol(),
            positionType: $position->getPositionType(),
            quantity: $position->getPositionQuantity()->toInt(),

            cost: new MoneyView(
                $position->getTotalCost()->getCurrency()->getValue(),
                (string) $position->getTotalCost()->getAmount(),
            ),
            proceeds: new MoneyView(
                $position->getTotalProceeds()->getCurrency()->getValue(),
                (string) $position->getTotalProceeds()->getAmount(),
            ),
            expiration: new ExpirationView(
                value: (string) $position->getExpirationDate(),
                isExpired: $position->isExpiredAsOf(Date::today()),
            ),
        );
    }
}
