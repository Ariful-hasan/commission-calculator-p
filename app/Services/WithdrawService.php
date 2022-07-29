<?php

namespace App\Services;

use App\Contracts\CalculateCommissionContract;
use App\Contracts\PrivateWithdrawContract;
use App\Contracts\WithdrawContract;
use App\Contracts\CurrencyContract;
use Carbon\Carbon;
use Exception;

class WithdrawService implements WithdrawContract, PrivateWithdrawContract
{
    public array $privateUserWithdrawHistory;
    private array $currencyExchangeRates;
    protected CalculateCommissionContract $calculateCommissionService;

    public function __construct(CurrencyContract $currencyService, CalculateCommissionContract $calculateCommissionService)
    {
        $this->privateUserWithdrawHistory = [];
        $this->calculateCommissionService = $calculateCommissionService;
        $this->currencyExchangeRates = $currencyService->getCurrencyExchangeRates();
    }

    public function processWithdrawTransaction(array $record): string
    {
        if ($record[config('constants.OPERATION_USER_TYPE')] == config('constants.USER_BUSINESS')) {
            // calculate commission fee for business user.
            return $this->calculateCommissionService->calculateCommission($record[config('constants.OPERATION_AMOUNT')], config('constants.OPERATION_CURRENCY'), config('constants.WITHDRAW_FEE_FOR_BUSINESS_USER'));
        }

        // calculate commission for private user.
        return $this->processPrivateWithdrawTransaction($record);
    }

    public function processPrivateWithdrawTransaction(array $record): string
    {
        $carbon = Carbon::parse($record[config('constants.OPERATION_DATE')]);
        $year = $carbon->isoFormat("G"); // year
        $week = $carbon->isoFormat("W"); // week
        $user = $record[config('constants.OPERATION_USER_IDENTITY')];
        $amount = $record[config('constants.OPERATION_AMOUNT')];
        $currency = (string)$record[config('constants.OPERATION_CURRENCY')];

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
        
        return $this->applyPrivateWithdrawRules($user, $year, $week, $currency, $amount);
    }

    public function applyPrivateWithdrawRules(int $user, int $year, int $week, string $currency, int|float $amount): string
    {
        $totalTransactionsInWeek = count($this->privateUserWithdrawHistory[$user][$year][$week]);
        $totalWithdrawAmountInWeek = $totalTransactionsInWeek > 0 ? array_sum($this->privateUserWithdrawHistory[$user][$year][$week]) : 0;
        $currentAmountInEuros = $this->convertCurrencyInEuros($currency, $amount);

        $this->privateUserWithdrawHistory[$user][$year][$week][] = $currentAmountInEuros;

        if ($totalTransactionsInWeek < config('constants.CHARGE_FREE_WITHDRAW_TRANSACTION_PER_WEEK')) {
            if ($totalWithdrawAmountInWeek >= config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK')) {
                return $this->calculateCommissionService->calculateCommission($amount, $currency, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
            } else {
                $totalWithdrawAmountInWeek += $currentAmountInEuros;

                if ($totalWithdrawAmountInWeek > config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK')) {
                    $feeAbleAmount = $totalWithdrawAmountInWeek - (float)config('constants.CHARGE_FREE_WITHDRAW_AMOUNT_PER_WEEK');
                    $feeAbleAmount = $feeAbleAmount * $this->currencyExchangeRates[$currency];

                    return $this->calculateCommissionService->calculateCommission($feeAbleAmount, $currency, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
                }

                return $this->calculateCommissionService->calculateCommission(0, $currency, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
            }
        } else {
            return $this->calculateCommissionService->calculateCommission($amount, $currency, config('constants.WITHDRAW_FEE_FOR_PRIVATE_USER'));
        }
    }
    
    /**
     * convert the local currency in EURO
     *
     * @param  mixed $currency
     * @param  mixed $amount
     * @return int
     */
    public function convertCurrencyInEuros(string $currency, int|float $amount): int|float
    {
        if (!isset($this->currencyExchangeRates[$currency])) {
            throw new Exception("Currency not found!");
        }

        return $currency != config('constants.BASE_CURRENCY') ? $amount/$this->currencyExchangeRates[$currency] : $amount;
    }
}