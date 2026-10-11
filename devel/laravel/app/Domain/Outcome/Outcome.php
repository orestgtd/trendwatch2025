<?php

namespace App\Domain\Outcome;

interface Outcome
{
    public function kind(): OutcomeKind;
}
