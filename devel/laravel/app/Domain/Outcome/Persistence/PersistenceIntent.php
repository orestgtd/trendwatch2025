<?php

namespace App\Domain\Outcome\Persistence;

use App\Domain\Outcome\Persistence\{
    PersistenceAction,
    PersistenceScope,
};
final class PersistenceIntent
{
    public function __construct(
        public readonly PersistenceAction $action,
        public readonly PersistenceScope $scope,
    ) {}

    public static function none(): self
    {
        return new self(PersistenceAction::NONE, PersistenceScope::none());
    }

    public static function insertAll(): self
    {
        return new self(PersistenceAction::INSERT, PersistenceScope::all());
    }

    public static function update(array $fields): self
    {
        return new self(PersistenceAction::UPDATE, PersistenceScope::of($fields));
    }

    public static function upsertAll(): self
    {
        return new self(PersistenceAction::UPSERT, PersistenceScope::all());
    }

    public function requiresPersistence(): bool
    {
        return $this->action->requiresPersistence();
    }
}
