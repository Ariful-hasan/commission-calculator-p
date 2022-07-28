<?php

namespace App\Contracts;

interface BusinessWithdrawContract
{
    public function calculateBusinessWithdrawFee(int|float $amount): int|float;
}