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
    public function processWithdrawTransaction (array $record): string;
}