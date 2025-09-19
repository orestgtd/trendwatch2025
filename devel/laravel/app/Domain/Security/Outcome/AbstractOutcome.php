<?php

namespace App\Domain\Security\Outcome;

use App\Domain\Security\Model\Security;

abstract class AbstractOutcome implements SecurityOutcome
{
    public function __construct(
        public readonly Security $security
    ) {}
}