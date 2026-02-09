<?php

namespace App\Presentation\Console\Commands;

use App\Application\{
    GetExpirablePositions\GetExpirablePositions,
};

use App\Domain\Position\Model\Position;

use App\Presentation\{
    Presenters\PositionPresenter,
    Console\Renderers\ConsolePositionRowRenderer,
};

use App\Foundation\{
    Collection,
    Date,
};

use Illuminate\Console\Command;

class GetExpirablePositionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'list:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of expired positions';

    /**
     * Create a new command instance.
     */
    public function __construct(
        private readonly GetExpirablePositions $useCase,
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
            ->handle(Date::today())
            ->match(
                onSuccess: fn (array $positions) => $this->onSuccess($positions),
                onFailure: fn (string $error) => $this->onFailure($error)
            );
    }

    private function onSuccess(array $positions): int
    {
        if (empty($positions)) {
            $this->info('No expired positions found.');
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
        $rows = Collection::from($positions)
        ->map(
            fn (Position $position) =>
                $this->renderer->render(
                    $this->presenter->present($position)
                )
            )
        ->toArray();

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