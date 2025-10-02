<?php

namespace App\Domain\Position\Outcome;

use App\Domain\Outcome\Outcome;
use App\Domain\Outcome\OutcomeKind;

final class PositionOutcomePlaceholder implements Outcome
{
    public function requiresPersistence(): bool
    {
        // Placeholder outcome - do not persist.
        return false;
    }

    public function kind(): OutcomeKind
    {
        return OutcomeKind::POSITION;
    }
}
