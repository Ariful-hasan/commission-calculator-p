<?php

namespace Tests\Unit;

use App\Services\CalculateCommissionService;
use App\Services\CsvImportService;
use App\Services\DepositeService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class CommissionTest extends TestCase
{
    public function commissionProvider(): array
    {
        return [
            [1000, 'EUR', 0.03, 0.3],
            [10000, 'JPY', 0.03, 3],
            [1200, 'USD', 0.5, 6],
        ];
    }

    /**
     * calculating commission fee
     * according to percentage.
     *
     * @dataProvider commissionProvider
     * @return void
     */
    public function test_for_calculating_commission(float $amount, string $currency, float $percentage, float $expectd): void
    {
        $comm = new CalculateCommissionService();
        $result = $comm->calculateCommission($amount, $currency, $percentage);
        $this->assertEquals($expectd, $result);
    }
    
    /**
     * test for csv file not found
     *
     * @return void
     */
    public function test_that_file_not_found(): void
    {
        $this->expectException(FileNotFoundException::class);
        $csv = new CsvImportService();
        $csv->getCsvRecords(config('constants.CSV_FILE_PATH'));
    }
}
