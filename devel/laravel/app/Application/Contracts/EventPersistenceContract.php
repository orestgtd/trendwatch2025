<?php

namespace App\Application\Contracts;

use App\Domain\{
    Events\DomainEvent
};

interface EventPersistenceContract
{
    public function insert(DomainEvent $event): void;
}
