<?php

namespace App\Services;

use App\Contracts\PrivateWithdrawContract;
use App\Contracts\WithdrawContract;
use Carbon\Carbon;

class WithdrawService implements WithdrawContract, PrivateWithdrawContract
{
    public array $privateUserWithdrawHistory;

    public function __construct()
    {
        $this->privateUserWithdrawHistory = [];
    }

    public function clculateWithdrawFee(int|float $amount, int|float $percentage): int|float
    {
        $amount = ($amount * $percentage)/100;
        
        return is_float($amount) ? round($amount, 2) : $amount;
    }

    public function processWithdrawTransaction(array $record): float | int
    {
        if ($record[config('constants.OPERATION_USER_TYPE')] == config('constants.USER_BUSINESS')) {
            // calculate commission fee for business user.
            return $this->clculateWithdrawFee($record[config('constants.OPERATION_AMOUNT')], config('constants.WITHDRAW_FEE_FOR_BUSINESS_USER'));
        }

        // calculate commission for private user.
        return $this->processPrivateWithdrawTransaction($record);
    }

    public function processPrivateWithdrawTransaction(array $record): int|float
    {
        $carbon = Carbon::parse($record[config('constants.OPERATION_DATE')]);
        $year = $carbon->isoFormat("G"); // year
        $week = $carbon->isoFormat("W"); // week
        $user = $record[config('constants.OPERATION_USER_IDENTITY')];
        $amount = $record[config('constants.OPERATION_AMOUNT')];

        if (!array_key_exists($user, $this->privateUserWithdrawHistory)) {
            $this->privateUserWithdrawHistory[$user] = [];
            $this->privateUserWithdrawHistory[$user][$year] = [];
            $this->privateUserWithdrawHistory[$user][$year][$week] = [];
        } elseif (!array_key_exists($year, $this->privateUserWithdrawHistory[$user])) {
            if (isset($this->privateUserWithdrawHistory[$user])) {
                unset($this->privateUserWithdrawHistory[$user]);
            }

            $this->privateUserWithdrawHistory[$user][$year] = [];
            $this->privateUserWithdrawHistory[$user][$year][$week] = [];
        } elseif (!array_key_exists($week, $this->privateUserWithdrawHistory[$user][$year])) {
            if (isset($this->privateUserWithdrawHistory[$user][$year])) {
                unset($this->privateUserWithdrawHistory[$user][$year]);
            }

            $this->privateUserWithdrawHistory[$user][$year][$week] = [];
        }
        
        return $this->applyPrivateWithdrawRules($user, $year, $week, $amount);
    }

    public function applyPrivateWithdrawRules(int $user, int $year, int $week, int|float $amount): int|float
    {
        $totalTransactionsInWeek = count($this->privateUserWithdrawHistory[$user][$year][$week]);
        $totalWithdrawAmountInWeek = $totalTransactionsInWeek > 0 ? array_sum($this->privateUserWithdrawHistory[$user][$year][$week]) : 0;
        $this->privateUserWithdrawHistory[$user][$year][$week][] = $amount;

        if ($totalTransactionsInWeek < config('constants.CHARGE_FREE_WITHDRAW_TRANSACTION_PER_WEEK')) {
            if ($totalWithdrawAmountInWeek >= config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK')) {
                return $this->clculateWithdrawFee($amount, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
            } else {
                $totalWithdrawAmountInWeek += $amount;

                if ($totalWithdrawAmountInWeek > config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK')) {
                    $feeAbleAmount = $totalWithdrawAmountInWeek - (float)config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK');
                    
                    return $this->clculateWithdrawFee($feeAbleAmount, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
                }

                return 0;
            }            
        } else {
            return $this->clculateWithdrawFee($amount, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
        }
    }
}