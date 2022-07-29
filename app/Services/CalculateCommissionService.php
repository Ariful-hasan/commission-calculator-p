<?php

namespace App\Services;

use App\Contracts\CalculateCommissionContract;

class CalculateCommissionService implements CalculateCommissionContract
{
    public function calculateCommission(int|float $amount, string $currency, int|float $percentage): string
    {
        $amount = ($amount * $percentage)/100;
        
        return config('constants.CURRENCY_DO_NOT_HAVE_DECIMAL') == $currency ? number_format($amount, 0, '.', '') : number_format($amount, 2, '.', '');
    }
}