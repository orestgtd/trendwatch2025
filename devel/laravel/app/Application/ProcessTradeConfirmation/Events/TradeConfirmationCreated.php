<?php

namespace App\Application\ProcessTradeConfirmation\Events;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Events\DomainEvent,
};

final class TradeConfirmationCreated implements DomainEvent
{
    public function __construct(
        public readonly Confirmation $confirmation
    ) {}

    public function aggregateType(): string
    {
        return 'trade';
    }

    public function aggregateId(): string
    {
        return (string) $this->confirmation->getTradeNumber();
    }

    public function eventType(): string
    {
        return 'TradeConfirmationCreated';
    }

    public function payload(): array
    {
        return [
            'trade_number' => (string) $this->confirmation->getTradeNumber(),
            'security_number' => (string) $this->confirmation->getSecurityNumber(),
        ];
    }
}