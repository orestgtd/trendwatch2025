<?php

namespace Tests\Unit\Presentation\Console\Commands;

use Mockery;
use Tests\TestCase;

use App\Application\ImportTradeConfirmations;
use App\Presentation\Console\Commands\ImportTradeConfirmationsCommand;

use App\Foundation\Result;

class ImportTradeConfirmationsCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_command_prompts_for_filename_and_passes_it_to_use_case(): void
    {
        // Arrange
        $fileName = 'trade_confirmations.csv';
        
        $mockUseCase = Mockery::mock(ImportTradeConfirmations::class);
        $mockUseCase->shouldReceive('handle')
            ->once()
            ->with($fileName)
            ->andReturn(Result::success(10));
        
        $this->app->instance(ImportTradeConfirmations::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(ImportTradeConfirmationsCommand::class)
            ->expectsQuestion('Please enter the file name to import', $fileName)
            ->expectsOutput("Importing trade confirmations from: {$fileName}")
            ->expectsOutput('10 trade confirmations have been imported.')
            ->assertExitCode(0);
    }

    public function test_command_displays_error_when_import_fails(): void
    {
        // Arrange
        $fileName = 'trade_confirmations.csv';
        $errorMessage = 'Import failed: 5 rows failed to import';
        
        $mockUseCase = Mockery::mock(ImportTradeConfirmations::class);
        $mockUseCase->shouldReceive('handle')
            ->once()
            ->with($fileName)
            ->andReturn(Result::failure($errorMessage));
        
        $this->app->instance(ImportTradeConfirmations::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(ImportTradeConfirmationsCommand::class)
            ->expectsQuestion('Please enter the file name to import', $fileName)
            ->expectsOutput("Importing trade confirmations from: {$fileName}")
            ->expectsOutput($errorMessage)
            ->assertExitCode(1);
    }

    public function test_command_returns_failure_when_filename_is_empty(): void
    {
        // Arrange
        $mockUseCase = Mockery::mock(ImportTradeConfirmations::class);
        $mockUseCase->shouldNotReceive('handle');
        
        $this->app->instance(ImportTradeConfirmations::class, $mockUseCase);
        
        // Act & Assert
        $this->artisan(ImportTradeConfirmationsCommand::class)
            ->expectsQuestion('Please enter the file name to import', '')
            ->expectsOutput('File name cannot be empty.')
            ->assertExitCode(1);
    }
}
