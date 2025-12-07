<?php

namespace App\Domain\Confirmation\Contracts;

interface ConfirmationImportInterface
{
    public function failedRows(): array;
}