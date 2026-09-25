<?php

namespace App\Application\Procedures\Expiration;

use App\Application\{
    EventManager,
    Procedures\Expiration\Events\PositionExpired as PositionExpiredEvent,
    Reports\Queries\GetExpirablePositionsQuery,
};

use App\Domain\{
    Expiration\Outcome\ExpirationOutcome,
    Expiration\Outcome\PositionExpired,
    Position\Model\Position,
};

use App\Foundation\{
    Collection,
    Date,
};

class ExpirePositions
{
    public function __construct(
        private readonly GetExpirablePositionsQuery $query,
        private readonly EventManager $eventManager,
    ) {}

    public function handle(Date $asOf): ExpirePositionsResult
    {
        $expiredCount = 0;

        $tapExpire = function (PositionExpired $outcome) use (&$expiredCount) {
            $this->tapExpiration($outcome);
            $expiredCount++;
        };

        Collection::from($this->query->asOf($asOf))
            ->map(fn (Position $position) => $position->expire($asOf))
            ->filter(fn (ExpirationOutcome $outcome) => $outcome instanceof PositionExpired)
            ->each(fn(PositionExpired $outcome) => $tapExpire($outcome));

        return new ExpirePositionsResult(
            expiredCount: $expiredCount
        );
    }

    private function tapExpiration(PositionExpired $outcome): void
    {
        $this->eventManager->record(
            new PositionExpiredEvent($outcome)
        );
    }
}
