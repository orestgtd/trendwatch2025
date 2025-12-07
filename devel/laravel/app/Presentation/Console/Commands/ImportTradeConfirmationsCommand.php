<?php

namespace App\Presentation\Console\Commands;

use App\Application\ImportTradeConfirmations;
use Illuminate\Console\Command;

class ImportTradeConfirmationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import trade confirmations from a file';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private readonly ImportTradeConfirmations $useCase
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $fileName = $this->ask('Please enter the file name to import');

        if (empty($fileName)) {
            $this->error('File name cannot be empty.');
            return Command::FAILURE;
        }

        $this->info("Importing trade confirmations from: {$fileName}");

        return $this->useCase
            ->handle($fileName)
            ->match(
                onSuccess: fn (int $numberOfRows) => $this->onSuccess($numberOfRows),
                onFailure: fn (string $error) => $this->onFailure($error)
            );
    }

    private function onSuccess(int $numberOfRows): int
    {
        $this->info("{$numberOfRows} trade confirmations have been imported.");
        return Command::SUCCESS;
    }

    private function onFailure(string $error): int
    {
        $this->error($error);
        return Command::FAILURE;
    }

}