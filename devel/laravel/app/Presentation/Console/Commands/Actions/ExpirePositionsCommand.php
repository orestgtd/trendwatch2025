<?php

namespace App\Presentation\Console\Commands\Actions;

use App\Application\{
    Procedures\Expiration\ExpirePositions,
};

use App\Foundation\Date;

use Illuminate\Console\Command;

class ExpirePositionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:expire {--as-of= : Date through which positions should be expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire positions which are past their expiration date';

    public function __construct(
        private readonly ExpirePositions $useCase,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $asOf = Date::fromString($this->option('as-of'));

        $result = $this->useCase->handle($asOf);
        $expiredCount = $result->expiredCount();

        $this->info("Expired {$expiredCount} positions.");

        return Command::SUCCESS;
    }
}
