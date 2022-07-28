<?php

namespace App\Contracts;

interface WithdrawContract
{
    public function clculateWithdrawFee (array $record): float | int;
}