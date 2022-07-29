<?php

namespace App\Contracts;

interface PrivateWithdrawContract
{    
    /**
     * process each row of csv 
     * set data 
     * return calculated commission
     *
     * @param  mixed $record as csv row
     * @return int or float
     */
    public function processPrivateWithdrawTransaction(array $record): string;
        
    /**
     * apply the withdraw condition 
     * maximum limit per week
     * maximum transaction per week
     * return the commission fee
     *
     * @param  mixed $user
     * @param  mixed $year
     * @param  mixed $week
     * @param  mixed $currency
     * @param  mixed $amount
     * @return int or float
     */
    public function applyPrivateWithdrawRules(int $user, int $year, int $week, string $currency, int|float $amount): string;
}