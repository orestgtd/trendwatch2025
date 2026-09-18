<?php

namespace App\Application\Reports\GetRealizedGains;

use App\Application\{
    Contracts\RealizedGainRepositoryContract,
};

use App\Domain\RealizedGain\{
    Record\RealizedGainBasisRecord,
};

use App\Infrastructure\Laravel\Eloquent\RealizedGain\{
    Dto\PersistedRealizedGainBasisDto,
};

final class GetAllRealizedGainsQuery
{
    public function __construct(
        private readonly RealizedGainRepositoryContract $repository,
    ) {}

    /** @return RealizedGainBasisRecord[] */
    public function all(): array
    {
        $persistedGains = $this->repository->all();

        return array_map(
            fn(PersistedRealizedGainBasisDto $dto) => $this->mapToRecord($dto),
            $persistedGains
        );
    }

    private function mapToRecord(PersistedRealizedGainBasisDto $dto): RealizedGainBasisRecord
    {
        return new RealizedGainBasisRecord(
            $dto->securityNumber,
            $dto->positionType,
            $dto->realizationSource,
            $dto->baseQuantity,
            $dto->tradeQuantity,
            $dto->unitType,
            $dto->cost,
            $dto->proceeds,
        );
    }
}
