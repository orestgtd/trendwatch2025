<?php

namespace App\Application\Procedures\Expiration\Events;

use App\Domain\{
    Expiration\Outcome\PositionExpired as PositionExpiredOutcome,
    Events\DomainEvent,
};

final class PositionExpired implements DomainEvent
{
    public function __construct(
        public readonly PositionExpiredOutcome $outcome
    ) {}

    public function aggregateType(): string
    {
        return 'position';
    }

    public function aggregateId(): string
    {
        return (string) $this->outcome->getPosition()->getSecurityNumber();
    }

    public function eventType(): string
    {
        return 'PositionExpired';
    }

    public function payload(): array
    {
        // determine the minimum historical data needed to reconstruct
        // the expiration event
        return [];
    }
}
