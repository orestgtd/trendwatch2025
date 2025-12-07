<?php

namespace App\Application;

use App\Domain\Confirmation\{
    Contracts\ConfirmationImportInterface,
};

use App\Infrastructure\Imports\{
    ConfirmationImport,
};

use App\Shared\Result;

class ImportTradeConfirmations
{
    /** @return Result<ConfirmationImportInterface> */
    public function handle(string $file): Result
    {
        try {
            $import = ConfirmationImport::doImport($file);
            $failed = count($import->failedRows());
            return $failed > 0
                ? Result::failure("Import failed: {$failed} rows failed to import")
                : Result::success($import->numberOfRows());
        } catch (\Exception $e) {
            return Result::failure($e->getMessage());
        }
    }
}
