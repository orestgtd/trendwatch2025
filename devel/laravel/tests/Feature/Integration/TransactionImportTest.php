<?php

namespace Tests\Feature\Integration;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Support\ConfirmationsApiGivenWhenThen;

use App\Application\ImportTradeConfirmations;

use App\Domain\Confirmation\{
    Contracts\ConfirmationImportInterface,
};

use App\Infrastructure\{
    Laravel\Eloquent\Trade\Model\Trade,
};

use Illuminate\{
    Foundation\Testing\RefreshDatabase,
    Support\Facades\Storage,
};

use App\Foundation\Result;

class TransactionImportTest extends TestCase
{
    use ConfirmationsApiGivenWhenThen;
    use RefreshDatabase;

    #[Test]
    public function it_imports_transactions_from_excel()
    {
        // Arrange: create trades

$csvContent = <<<CSV
trade_number,transaction_date,security_number,symbol,description,trade_action,position_effect,trade_quantity,unit_type,unit_price,commission,us_tax,expiration_date
001733,2022-05-16,7653ZG,SPX,"CALL-100 SPX'22 JN@4245",BUY,OPEN,1,CONTRACTS,21,9.99,0.02,2022-06-10
333499,2022-05-23,151447,CVE,"CENOVUSENERGY INC",BUY,OPEN,100,SHARES,21.94,12.40,0.49,
CSV;

        // Arrange: create a fake Excel file
        Storage::disk('local')->put('imports/test.csv', $csvContent);
        $file = Storage::disk('local')->path('imports/test.csv');

        // Act: perform the import
        $result = app(ImportTradeConfirmations::class)->handle($file);

        // Assert: result
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());

        // Assert correct number of rows imported
        $this->assertEquals($result->getValue(), 2);

        // Assert: check database records
        $this->assertDatabaseHas('trades', [
            'security_number' => '7653ZG',
            'trade_number' => '001733',
            'trade_quantity' => 1,
        ]);

        $this->assertDatabaseHas('trades', [
            'security_number' => '151447',
            'trade_number' => '333499',
            'trade_quantity' => 100,
        ]);

        // Assert that the securities were persisted
        $this->assertDatabaseHas('securities', [
            'security_number' => '7653ZG',
            'symbol' => 'SPX',
            'canonical_description' => "CALL-100 SPX'22 JN@4245",
        ]);

        $this->assertDatabaseHas('securities', [
            'security_number' => '151447',
            'symbol' => 'CVE',
            'canonical_description' => "CENOVUSENERGY INC",
        ]);

        $this->assertEquals(2, Trade::count());
    }

    #[Test]
    public function it_returns_error_on_invalid_trade_action()
    {
        // Arrange: create trades

$csvContent = <<<CSV
trade_number,transaction_date,security_number,symbol,description,trade_action,position_effect,trade_quantity,unit_type,unit_price,commission,us_tax,expiration_date
333499,2022-05-23,151447,CVE,"CENOVUSENERGY INC",BUYIT,OPEN,100,SHARES,21.94,12.40,0.49,
CSV;

        // Arrange: create a fake Excel file
        Storage::disk('local')->put('imports/test.csv', $csvContent);
        $file = Storage::disk('local')->path('imports/test.csv');

        // Act: perform the import
        $result = app(ImportTradeConfirmations::class)->handle($file);

        // Assert: result
        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isFailure());
        // $this->assertEquals("Import failed: 1 rows failed to import", $result->getError());

        // Assert: check database records
        $this->assertDatabaseMissing('trades', [
            'security_number' => '151447',
            'trade_number' => '333499',
            'trade_quantity' => 100,
        ]);

        // Assert that the securities were not persisted
        $this->assertDatabaseMissing('securities', [
            'security_number' => '151447',
            'symbol' => 'CVE',
            'canonical_description' => "CENOVUSENERGY INC",
        ]);

        $this->assertEquals(0, Trade::count());
    }
}
