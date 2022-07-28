<?php

namespace App\Services;

use App\Contracts\BusinessWithdrawContract;
use App\Contracts\PrivateWithdrawContract;
use App\Contracts\WithdrawContract;
use InvalidArgumentException;

class WithdrawService implements WithdrawContract, BusinessWithdrawContract, PrivateWithdrawContract
{
    public array $privateUserWithdrawHistory;

    public function __construct()
    {
        $this->privateUserWithdrawHistory = [];
    }

    public function clculateWithdrawFee(array $record): float | int
    {
        if ($record[config('constants.OPERATION_USER_TYPE')] == config('constants.USER_BUSINESS')) {
            return $this->calculateBusinessWithdrawFee($record[config('constants.OPERATION_AMOUNT')]);
        }

        return $this->calculatePrivateWithdrawFee($record);
    }

    public function calculateBusinessWithdrawFee(int|float $amount): int|float
    {
        if (empty($amount)) {
            throw new InvalidArgumentException("Amount is not valid!");
        }

        $amount = ($amount * config('constants.WITHDRAW_FEE_FOR_BUSINESS_USER'))/100;
        
        return is_float($amount) ? round($amount, 2) : $amount;
    }

    public function calculatePrivateWithdrawFee(array $record): int|float
    {
        $date = strtotime($record[config('constants.OPERATION_DATE')]);
        $year = date("Y", $date);
        $week = date("W", $date);
        $user = $record[config('constants.OPERATION_USER_IDENTITY')];

        if (array_key_exists($user, $this->privateUserWithdrawHistory)) {
            if (array_key_exists($year, $this->privateUserWithdrawHistory[$user])) {
                if (array_key_exists($week, $this->privateUserWithdrawHistory[$user][$year])) {
                    // sum amount in week array
                    // count transaction
                    // 1000 euro condition
                    // 3 transaction condition
                } else {
                    // add the week and add amout to the week array
                    // remove previous week array
                }
            } else {
                // add this year to the user array
                // remove previous year array
            }
        } else {
            // add this user data to history
        }

        return 1;
    }
}