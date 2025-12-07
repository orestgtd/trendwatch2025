<?php

namespace App\Infrastructure\Imports;

use App\Application\ProcessTradeConfirmation\ProcessTradeConfirmation;

use Maatwebsite\Excel\{
    Concerns\OnEachRow,
    Concerns\WithHeadingRow,
    Facades\Excel,
    Row,
};

use App\Domain\Confirmation\{
    Contracts\ConfirmationImportInterface,
};

class ConfirmationImport implements ConfirmationImportInterface, OnEachRow, WithHeadingRow
{
    private int $count_of_rows = 0;
    private array $failedRows = [];

    private ProcessTradeConfirmation $action;

    public function __construct()
    {
        $this->action = app(ProcessTradeConfirmation::class);
    }

    public static function doImport(string $file): self
    {
        $import = new self;
        Excel::import($import, $file);

        return $import;
    }

    public function onRow(Row $row)
    {
        $rowData = $row->toArray();

        $result = $this->action->handle($rowData);
        if ($result->isFailure()) {
            $this->failedRows[] = [
                'row' => $rowData,
                'errors' => $result->getError(),
            ];
            return;
        }
    
        $this->count_of_rows++;
    }

    public function numberOfRows(): int
    {
        return $this->count_of_rows;
    }

    public function failedRows(): array
    {
        return $this->failedRows;
    }
}
