<?php

namespace App\Infrastructure\Laravel\Eloquent;

use App\Domain\Security\{
    Model\Security,
};

use App\Infrastructure\Laravel\Eloquent\Security\{
    Repositories\EloquentSecurityRepository as SecurityRepository,
};

use Illuminate\Support\Facades\DB;

class UnitOfWork
{
    private ?Security $security = null;

    // private Position $position; // for future use

    public function __construct(
        private SecurityRepository $securityRepository
    ) {}

    // public function beginTransaction(): self
    // {
    //     DB::beginTransaction();

    //     return $this;
    // }

    public function withSecurity(Security $security): self
    {
        $this->security = $security;
        return $this;
    }

    // public function withPosition(Position $position): self
    // {
    //     $this->position = $position;
    //     return $this;
    // }

    public function persist(): void
    {
        if ($this->security) {
            DB::beginTransaction();
            // if ($this->position) {
            //     $this->position->save();
            // }
            $this->securityRepository->save($this->security);
            DB::commit();
        }
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
