<?php

namespace App\Domain\Outcome;

enum OutcomeKind: string
{
    case CONFIRMATION = 'confirmation';
    case EXPIRATION = 'expiration';
    case POSITION = 'position';
    case REALIZED_GAIN = 'realized_gain';
    case SECURITY = 'security';
}
