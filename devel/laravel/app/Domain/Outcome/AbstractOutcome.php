<?php

namespace App\Domain\Outcome;

use App\Domain\{
    Outcome\Outcome,
    Outcome\OutcomeKind,
};

abstract class AbstractOutcome implements Outcome
{
    abstract public function kind(): OutcomeKind;
}
