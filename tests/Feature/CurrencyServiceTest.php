<?php

namespace Tests\Feature;

use App\Contracts\CalculateCommissionContract;
use App\Contracts\CurrencyContract;
use App\Services\CalculateCommissionService;
use App\Services\CurrencyService;
use App\Services\WithdrawService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    /**
     * test for validating currency rates.
     *
     * @dataProvider currencyRatesProvider
     * @return void
     */
    public function test_that_process_currencies(string $currency, float $amount, float $expected)
    {
        $currencyRates = ['EUR'=>1, 'USD'=>1.129031, 'JPY'=>130.869977, 'BDT'=> 96.872638];

        $mockCurrency = $this->getMockBuilder(CurrencyService::class)->getMock();
        $mockCurrency->method('getCurrencyExchangeRates')->willReturn($currencyRates);
        $this->app->bind(CurrencyContract::class, fn() => $mockCurrency);

        $mockComm = $this->getMockBuilder(CalculateCommissionService::class)->getMock();
        $mockComm->method('calculateCommission');
        $this->app->bind(CalculateCommissionContract::class, fn() => $mockComm);

        $result = $this->app->make(WithdrawService::class)->convertCurrencyInEuros($currency, $amount);
        $this->assertEquals($expected, $result);
    }

    public function currencyRatesProvider()
    {
        return [
            ["USD", 1000, 885.71527265416],
            ["EUR", 1000, 1000],
            ["JPY", 1000, 7.641171970252581],
            ["BDT", 1000, 10.32283233579331],
        ];
    }
}
