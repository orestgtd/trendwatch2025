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
    private int $row_number = 2;    // import data starts on Row 2
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
        $securityNumber = $rowData['security_number'];
        if (empty($securityNumber)) {
            return; // skip rows without security_number
        }

        $tradeNumber = $rowData['trade_number'];

        $result = $this->action->handle($rowData);
        if ($result->isFailure()) {
            $this->failedRows[] = [
                'row' => $this->row_number,
                'security_number' => $securityNumber,
                'trade_number' => $tradeNumber,
                'data' => $rowData,
                'error' => $result->getError(),
            ];
            return;
        }
    
        $this->count_of_rows++;
        $this->row_number++;
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
