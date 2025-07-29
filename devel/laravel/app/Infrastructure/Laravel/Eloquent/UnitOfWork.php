<?php

namespace App\Infrastructure\Laravel\Eloquent;

use App\Domain\{
    Confirmation\Model\Confirmation,
    Security\Model\Security,
};

use App\Infrastructure\Laravel\Eloquent\{
    Trade\Repositories\EloquentTradeRepository as TradeRepository,
    Security\Repositories\EloquentSecurityRepository as SecurityRepository,
};

use Illuminate\Support\Facades\DB;

class UnitOfWork
{
    private ?Confirmation $confirmation = null;
    private ?Security $security = null;

    // private Position $position; // for future use

    public function __construct(
        private TradeRepository $tradeRepository,
        private SecurityRepository $securityRepository
    ) {}

    // public function beginTransaction(): self
    // {
    //     DB::beginTransaction();

    //     return $this;
    // }

    public function withConfirmation(Confirmation $confirmation): self
    {
        $this->confirmation = $confirmation;
        return $this;
    }

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
        DB::beginTransaction();
        if ($this->confirmation) {
            $this->tradeRepository->save($this->confirmation);
        }
        if ($this->security) {
            $this->securityRepository->save($this->security);
        }
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
