<?php

namespace App\Services;

use App\Contracts\CalculateCommissionContract;
use App\Contracts\DepositeContract;

class DepositeService implements DepositeContract
{
    public function __construct(protected CalculateCommissionContract $calculateCommissionService)
    {
        # code...
    }

    public function clculateDepositeFee(int|float $amount, string $currency, int|float $percentage): string
    {
       return $this->calculateCommissionService->calculateCommission($amount, $currency, $percentage);
    }
}