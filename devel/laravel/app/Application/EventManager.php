<?php

namespace App\Application;

use App\Application\Contracts\EventPersistenceContract;
use App\Domain\Events\DomainEvent;

final class EventManager
{
    public function __construct(
        private readonly EventPersistenceContract $eventPersistence,
    ) {}

    public function record(DomainEvent $event)
    {
        $this->eventPersistence->insert($event);
        event($event);
    }
}