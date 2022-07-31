<?php

namespace Tests\Feature;

use App\Contracts\CalculateCommissionContract;
use App\Contracts\CurrencyContract;
use App\Services\CalculateCommissionService;
use App\Services\CurrencyService;
use App\Services\WithdrawService;
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use InvalidArgumentException;
use Tests\TestCase;

class WithdrawTest extends TestCase
{
    /**
     * convert the currency:
     *
     * @return void
     */
    public function test_that_convert_currency(): void
    {
        // $this->expectExceptionMessage("Currency not found!");
        $this->expectException(InvalidArgumentException::class);

        $withdrawServiceMock = $this->mock(CurrencyService::class, function($mock) {
            $mock->shouldReceive('getCurrencyExchangeRates')->andReturn([]);
        });

        $this->app->make(WithdrawService::class)->convertCurrencyInEuros('EUR', 1000);
    }
    
    /**
     * test that process the csv record
     *
     * @return void
     */
    public function test_that_process_csv_record(): void
    {
        $mockComm = $this->getMockBuilder(CalculateCommissionService::class)->getMock();
        $mockComm->expects($this->once())->method('calculateCommission');
        $this->app->bind(CalculateCommissionContract::class, fn() => $mockComm);

        $this->app->make(WithdrawService::class)->processWithdrawTransaction([     
            '2016-01-06',2,'business','withdraw',300.00,'EUR'
        ]);
    }
}
