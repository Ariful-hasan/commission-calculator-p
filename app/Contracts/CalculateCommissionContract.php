<?php

namespace App\Contracts;

interface CalculateCommissionContract
{        
    /**
     * calculate the commission 
     * for each transaction 
     *
     * @param  mixed $amount
     * @param  mixed $currency
     * @param  mixed $percentage
     * @return string
     */
    public function calculateCommission(int|float $amount, string $currency, int|float $percentage): string;
}