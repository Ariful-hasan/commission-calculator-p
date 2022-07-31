<?php

namespace Tests\Feature;

use App\Contracts\CalculateCommissionContract;
use App\Services\CalculateCommissionService;
use App\Services\DepositeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepositeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_for_calculating_deposit_fee()
    {
        $mock = $this->getMockBuilder(CalculateCommissionService::class)->getMock();
        $mock->expects($this->once())->method('calculateCommission');

        $this->app->bind(CalculateCommissionContract::class, fn()=>$mock);
        $this->app->make(DepositeService::class)->clculateDepositeFee(1000, 'EUR', 0.3);
    }
}
