<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Repositories;

use App\Domain\{
    Position\Model\Position,
    Security\ValueObjects\SecurityNumber,
};
use App\Domain\Outcome\Persistence\PersistenceScope;
use App\Infrastructure\Laravel\Eloquent\Position\{
    Dto\PersistedPositionDto,
    Model\Position as EloquentPosition,
};

class EloquentPositionRepository
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PersistedPositionDto
    {
        $eloquent = EloquentPosition::where('security_number', (string) $securityNumber)->first();

        return $eloquent
            ? new PersistedPositionDTO(
                $eloquent->security_number,
                $eloquent->position_type,
                $eloquent->position_quantity,
                $eloquent->total_cost,
                $eloquent->total_proceeds,
            )
            : $eloquent;
    }

    public function save(Position $position): void
    {
        $this
            ->toEloquent($position)
            ->save();
    }

    public function insert(Position $position): void
    {
        $this->save($position);
    }

    public function update(Position $position, PersistenceScope $scope): void
    {
        $toUpdate = collect($scope->fields())
            ->mapWithKeys(fn(string $field) => [
                $field => match ($field) {
                    'position_quantity' => $position->getPositionQuantity(),
                    'total_cost' => $position->getTotalCost(),
                },
            ])
            ->toArray();

        $security_number = (string) $position->getSecurityNumber();

        EloquentPosition::where('security_number', $security_number)->first()
            ->update($toUpdate);
    }

    public function upsert(Position $position): void
    {
        $this->save($position);
    }

    public function delete(Position $position): void
    {
        EloquentPosition::where('security_number', (string) $position->getSecurityNumber())->delete();
    }

    private function toEloquent(Position $position): EloquentPosition
    {
        $eloquent = new EloquentPosition();

        $eloquent->security_number = $position->getSecurityNumber();
        $eloquent->position_type = $position->getPositionType();
        $eloquent->position_quantity = $position->getPositionQuantity();
        $eloquent->total_cost = $position->getTotalCost();
        $eloquent->total_proceeds = $position->getTotalProceeds();

        return $eloquent;
    }
}
