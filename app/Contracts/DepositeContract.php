<?php

namespace App\Contracts;

interface DepositeContract
{
    public function clculateDepositeFee ($amount): float | int
}