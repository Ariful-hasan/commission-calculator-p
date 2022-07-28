<?php

namespace App\Contracts;

interface PrivateWithdrawContract
{
    public function calculatePrivateWithdrawFee(array $record): int|float;
}