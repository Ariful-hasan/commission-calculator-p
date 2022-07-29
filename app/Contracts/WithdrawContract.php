<?php

namespace App\Contracts;

interface WithdrawContract
{    
    /**
     * process each row of csv 
     * decide private or business transaction
     * return calculated commission
     *
     * @param  mixed $record as csv row
     * @return float or int
     */
    public function processWithdrawTransaction (array $record): float | int;
    
    /**
     * clculate the withdraw commission 
     *
     * @param  mixed $amount
     * @param  mixed $percentage
     * @return float or int 
     */
    public function clculateWithdrawFee (int|float $amount, string $currency, int|float $percentage): float | int;
}