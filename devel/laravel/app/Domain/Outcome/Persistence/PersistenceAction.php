<?php

namespace App\Domain\Outcome\Persistence;

enum PersistenceAction: string
{
    case NONE = 'none';
    case INSERT = 'insert';
    case UPDATE = 'update';
    case UPSERT = 'upsert';
    case DELETE = 'delete';

    public function requiresPersistence(): bool
    {
        // Only NONE implies no persistence required
        return $this !== self::NONE;
    }
}
