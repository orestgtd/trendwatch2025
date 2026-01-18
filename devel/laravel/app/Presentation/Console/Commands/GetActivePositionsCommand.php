<?php

namespace App\Presentation\Console\Commands;

use App\Application\{
    GetActivePositions\GetActivePositions,
};

use App\Presentation\{
    Presenters\PositionPresenter,
    Console\Renderers\ConsolePositionRowRenderer,
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
        private readonly GetActivePositions $useCase,
        private readonly PositionPresenter $presenter,
        private readonly ConsolePositionRowRenderer $renderer,
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

        /** @var array<int, array<int, string>> $rows */
        $rows = array_map(
            fn ($position) =>
                $this->renderer->render(
                    $this->presenter->present($position)
                ),
            $positions
        );

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