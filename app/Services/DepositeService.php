<?php

namespace App\Services;

use App\Contracts\DepositeContract;
use InvalidArgumentException;

class DepositeService implements DepositeContract
{
    public function clculateDepositeFee($amount): float | int
    {
        if (empty($amount)) {
            throw new InvalidArgumentException("Amount is not valid!");
        }

        $amount = ($amount * config('constants.DEPOSITE_FEE'))/100;
        
        return is_float($amount) ? round($amount, 2) : $amount;
    }
}