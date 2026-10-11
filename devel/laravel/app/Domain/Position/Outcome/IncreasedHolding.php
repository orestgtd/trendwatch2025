<?php
 
namespace App\Domain\Position\Outcome;
 
use App\Domain\{
    Position\Model\Position,
    Position\Outcome\AbstractPositionOutcome,
};

final class IncreasedHolding extends AbstractPositionOutcome
{
    public function __construct(
        Position $position,
    ) {
        parent::__construct(
            $position,
        );
    }
}
