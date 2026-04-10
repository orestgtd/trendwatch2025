<?php

namespace App\Infrastructure\Laravel\Eloquent\Position\Repositories;

use App\Application\Contracts\{
    PositionRepositoryContract,
};

use App\Domain\{
    Confirmation\ValueObjects\CostAmount,
    Kernel\Identifiers\SecurityNumber,
    Position\Model\Position,
    Position\Record\PositionRecord,
    Position\ValueObjects\PositionQuantity,
    Security\Expiration\ExpirationRule,
    Security\ValueObjects\SecurityInfo,
};

use App\Infrastructure\Laravel\Eloquent\Position\{
    Model\Position as EloquentPosition,
};

use App\Foundation\Date;

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

    // public function save(Position $position): void
    // {
    //     $this
    //         ->toEloquent($position)
    //         ->save();
    // }

    public function insert(Position $position): void
    {
        $this
            ->toEloquent($position)
            ->save();
    }

    public function update(SecurityNumber $securityNumber, PositionQuantity $quantity, CostAmount $totalCost): void
    {
        // $toUpdate = collect($scope->fields())
        //     ->mapWithKeys(fn(string $field) => [
        //         $field => match ($field) {
        //             'position_quantity' => $position->getPositionQuantity(),
        //             'total_cost' => $position->getTotalCost(),
        //         },
        //     ])
        //     ->toArray();

        // $security_number = (string) $position->getSecurityNumber();

        EloquentPosition::where('security_number', $securityNumber)->first()
            ->update([
                'position_quantity' => $quantity,
                'total_cost' => $totalCost,
            ]);
    }

    // public function upsert(Position $position): void
    // {
    //     $this->save($position);
    // }

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
