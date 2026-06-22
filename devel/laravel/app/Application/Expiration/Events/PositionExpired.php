<?php

namespace App\Application\Expiration\Events;

use App\Domain\{
    Expiration\Outcome\PositionExpired as PositionExpiredOutcome,
};

final class PositionExpired
{
    public function __construct(
        public readonly PositionExpiredOutcome $outcome
    ) {}
}