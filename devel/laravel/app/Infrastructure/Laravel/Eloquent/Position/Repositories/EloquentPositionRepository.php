<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Repositories;

use App\Foundation\Date;

use App\Application\Contracts\{
    PositionRepositoryContract,
};

use App\Domain\{
    Kernel\Identifiers\SecurityNumber,
    Outcome\Persistence\PersistenceScope,
    Position\Model\Position,
    Position\Record\PositionRecord,
};

use App\Domain\Security\{
    Expiration\ExpirationRule,
    ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Model\Position as EloquentPosition,
};

class EloquentPositionRepository implements PositionRepositoryContract
{
    public function findBySecurityNumber(SecurityNumber $securityNumber): ?PositionRecord
    {
        $eloquent = EloquentPosition::where('security_number', (string) $securityNumber)->first();

        return $eloquent
            ? new PositionRecord(
                SecurityInfo::from(
                    $eloquent->security_number,
                    $eloquent->symbol,
                    $eloquent->description,
                    $eloquent->unit_type,
                    ExpirationRule::fromNullableDate($eloquent->expiration_date)
                ),
                $eloquent->position_type,
                $eloquent->position_quantity,
                $eloquent->total_cost,
                $eloquent->total_proceeds,
            )
            : $eloquent;
    }

    public function active(): array
    {
        return EloquentPosition::where('position_quantity', '>', 0)->get()
            ->map(fn(EloquentPosition $eloquent) => new PositionRecord(
                SecurityInfo::from(
                    $eloquent->security_number,
                    $eloquent->symbol,
                    $eloquent->description,
                    $eloquent->unit_type,
                    ExpirationRule::fromNullableDate($eloquent->expiration_date)
                ),
                $eloquent->position_type,
                $eloquent->position_quantity,
                $eloquent->total_cost,
                $eloquent->total_proceeds,
            ))
            ->toArray();
    }

    public function expiredAsOf(Date $asOf): array
    {
        return EloquentPosition::where('position_quantity', '>', 0)
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '!=', '')
            ->where('expiration_date', '<', (string) $asOf)
            ->get()
            ->map(fn(EloquentPosition $eloquent) => new PositionRecord(
                SecurityInfo::from(
                    $eloquent->security_number,
                    $eloquent->symbol,
                    $eloquent->description,
                    $eloquent->unit_type,
                    ExpirationRule::fromNullableDate($eloquent->expiration_date)
                ),
                $eloquent->position_type,
                $eloquent->position_quantity,
                $eloquent->total_cost,
                $eloquent->total_proceeds,
            ))
            ->toArray();
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
        $eloquent->symbol = $position->getSymbol();
        $eloquent->description = $position->getDescription();
        $eloquent->position_type = $position->getPositionType();
        $eloquent->position_quantity = $position->getPositionQuantity();
        $eloquent->unit_type = $position->getUnitType();
        $eloquent->total_cost = $position->getTotalCost();
        $eloquent->total_proceeds = $position->getTotalProceeds();
        $eloquent->expiration_date = $position->getExpirationDate();

        return $eloquent;
    }
}
