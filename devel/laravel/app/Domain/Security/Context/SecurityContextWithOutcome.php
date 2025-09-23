<?php

namespace App\Domain\Security\Context;

use App\Domain\Security\{
    Model\Security,
    Outcome\SecurityOutcome,
};

final class SecurityContextWithOutcome
{
    public function __construct(
        public readonly Security $security,
        public readonly SecurityOutcome $outcome
    ) {}
}
