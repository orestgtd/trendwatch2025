<?php

namespace App\Domain\Outcome;

enum OutcomeKind: string
{
    case CONFIRMATION = 'confirmation';
    case POSITION = 'position';
    case REALIZED_GAIN = 'realized_gain';
    case SECURITY = 'security';
}
