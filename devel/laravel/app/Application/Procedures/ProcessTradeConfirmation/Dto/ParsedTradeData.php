<?php

namespace App\Application\Procedures\ProcessTradeConfirmation\Dto;

use App\Domain\Kernel\Identifiers\SecurityNumber;

final class ParsedTradeData
{
    public function __construct(
        public ParsedTradeRequestDto $trade,
        public ParsedSecurityRequestDto $security,
    ) {}

    public function getSecurityNumber(): SecurityNumber
    {
        return $this->security->securityNumber;
    }
}
