<?php

namespace App\Contracts;

interface DepositeContract
{    
    /**
     * clculate the deposite transaction fee
     *
     * @param  mixed $amount
     * @param  mixed $currency
     * @param  mixed $percentage
     * @return string
     */
    public function clculateDepositeFee (int|float $amount, string $currency, int|float $percentage): string;
}