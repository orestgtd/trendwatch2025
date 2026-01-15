<?php

namespace App\Presentation\Console\Commands;

use App\Application\GetActivePositions\GetActivePositions;

use App\Domain\{
    Kernel\Money\Monetary,
    Position\Model\Position,
};

use Illuminate\Console\Command;

class GetActivePositionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of active positions';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private readonly GetActivePositions $useCase
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return $this->useCase
            ->handle()
            ->match(
                onSuccess: fn (array $positions) => $this->onSuccess($positions),
                onFailure: fn (string $error) => $this->onFailure($error)
            );
    }

    private function onSuccess(array $positions): int
    {
        if (empty($positions)) {
            $this->info('No positions found.');
            return Command::SUCCESS;
        }

        // Define table headers
        $headers = [
            'Security',
            'Symbol',
            'Position Type',
            'Qty',
            'Cost',
            'Proceeds',
            'Expiration Date',
        ];

        // Map positions to rows for table display
        $rows = array_map(function (Position $position) {

            // $formatMoney = function(Monetary $monetary): string {
            //     return "{$monetary->getCurrency()} {$monetary->getAmount()}";
            // };

            // Local helper for formatting Monetary objects
            $formatMoney = fn(Monetary $m): string => "{$m->getCurrency()} {$m->getAmount()}";

            return [
                $position->getSecurityNumber(),
                $position->getSymbol(),
                $position->getPositionType(),
                $position->getPositionQuantity(),
                $formatMoney($position->getTotalCost()),
                $formatMoney($position->getTotalProceeds()),
                (string) $position->getExpirationDate()
            ];
        }, $positions);

        // Display table
        $this->table($headers, $rows);

        $this->info(count($positions) . ' positions listed.');

        return Command::SUCCESS;
    }

    private function onFailure(string $error): int
    {
        $this->error($error);
        return Command::FAILURE;
    }

}