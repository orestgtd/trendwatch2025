<?php

namespace App\Application;

use App\Domain\Confirmation\{
    Contracts\ConfirmationImportInterface,
};

use App\Infrastructure\Imports\{
    ConfirmationImport,
};

use App\Foundation\Result;

class ImportTradeConfirmations
{
    /** @return Result<ConfirmationImportInterface> */
    public function handle(string $file): Result
    {
        try {
            $import = ConfirmationImport::doImport($file);
            $failed = count($import->failedRows());

            if ($failed > 0) {
                $first = $import->failedRows()[0];
                return Result::failure("Import failed on row {$first['row']} - Trade Number {$first['trade_number']}: {$first['error']}");
            }

            return $failed > 0
                ? Result::failure("Import failed: {$failed} rows failed to import")
                : Result::success($import->numberOfRows());
        } catch (\Exception $e) {
            return Result::failure($e->getMessage());
        }
    }
}
