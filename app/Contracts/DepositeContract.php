<?php

namespace App\Contracts;

interface DepositeContract
{
    public function clculateDepositeFee (int|float $amount): float | int;
}