<?php

namespace App\Presentation\Console\Commands;

use App\Application\GetRealizedGains\GetRealizedGains;
use Illuminate\Console\Command;

class GetRealizedGainsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:realized_gains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of realized_gains';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private readonly GetRealizedGains $useCase
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
                onSuccess: fn (array $realizedGains) => $this->onSuccess($realizedGains),
                onFailure: fn (string $error) => $this->onFailure($error)
            );
    }

    private function onSuccess(array $realizedGains): int
    {
        if (empty($realizedGains)) {
            $this->info('No realized gains found.');
            return Command::SUCCESS;
        }

    // "id" => 6
    //     "security_number" => "MSFT"
    //     "trade_number" => "T-4001"
    //     "base_quantity" => 100
    //     "trade_quantity" => 100
    //     "unit_type" => "SHARES"
    //     "cost" => 28500.0
    //     "proceeds" => 30250.0

        // Define table headers
        $headers = [
            'ID',
            'Security',
            'Trade',
            'Base Qty',
            'Trade Qty',
            'Unit Type',
            'Cost',
            'Proceeds',
        ];

        // Map positions to rows for table display
        $rows = array_map(function ($realizedGains) {
            return [
                $realizedGains['id'],
                $realizedGains['security_number'],
                $realizedGains['trade_number'],
                $realizedGains['base_quantity'],
                $realizedGains['trade_quantity'],
                $realizedGains['unit_type'],
                number_format($realizedGains['cost'], 0, 2),
                number_format($realizedGains['proceeds'], 0, 2),
            ];
        }, $realizedGains);

        // Display table
        $this->table($headers, $rows);

        $this->info(count($realizedGains) . ' realized gains listed.');

        return Command::SUCCESS;
    }

    private function onFailure(string $error): int
    {
        $this->error($error);
        return Command::FAILURE;
    }

}