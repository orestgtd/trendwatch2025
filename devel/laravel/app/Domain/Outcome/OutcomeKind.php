<?php

namespace App\Domain\Outcome;

enum OutcomeKind: string
{
    case CONFIRMATION = 'confirmation';
    case SECURITY = 'security';
    case POSITION = 'position';
}
