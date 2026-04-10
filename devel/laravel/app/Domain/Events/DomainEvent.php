<?php

namespace App\Domain\Events;

interface DomainEvent
{
    public function aggregateType(): string;
    public function aggregateId(): string;
    public function eventType(): string;
}
