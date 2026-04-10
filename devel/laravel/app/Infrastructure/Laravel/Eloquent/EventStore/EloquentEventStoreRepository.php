<?php

namespace App\Infrastructure\Laravel\Eloquent\EventStore;

use App\Application\{
    Contracts\EventPersistenceContract,
};

use App\Domain\{
    Events\DomainEvent,
};

use App\Infrastructure\{
    Laravel\Eloquent\EventStore\Model\EventStore as EloquentEventStore,
};

class EloquentEventStoreRepository implements EventPersistenceContract
{
    public function insert(DomainEvent $event): void
    {
        $eloquent = new EloquentEventStore();

        $eloquent->aggregate_type = $event->aggregateType();
        $eloquent->aggregate_id = $event->aggregateId();
        $eloquent->event_type = $event->eventType();

        // minimal placeholder
        $eloquent->payload = json_encode([]);

        $eloquent->version = 1;
        $eloquent->occurred_at = now();

        $eloquent->save();
    }
}
