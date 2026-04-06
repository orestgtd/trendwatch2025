<?php

namespace App\Presentation\Console\Commands\Queries;

use App\Application\{
    GetRealizedGains\GetRealizedGains,
};

use App\Presentation\{
    Presenters\RealizedGainPresenter,
    Console\Renderers\ConsoleRealizedGainRowRenderer,
};

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
        private readonly GetRealizedGains $useCase,
        private readonly RealizedGainPresenter $presenter,
        private readonly ConsoleRealizedGainRowRenderer $renderer,
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

        /** @var array<int, array<int, string>> $rows */
        $rows = array_map(
            fn ($rg) =>
                $this->renderer->render(
                    $this->presenter->present($rg)
            ),
            $realizedGains
        );

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