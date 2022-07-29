<?php

namespace App\Services;

use App\Contracts\CurrencyContract;
use Illuminate\Support\Facades\Http;

class CurrencyService implements CurrencyContract
{
    private string $currencyExchangeApi;

    public function __construct()
    {
        $this->currencyExchangeApi = config('constants.CURRENCY_EXCHANGE_API');
    }

    public function getCurrencyExchangeRates(): array
    {
        try {
            $response = Http::get($this->currencyExchangeApi);

            if (isset($response->json()['rates'])) {
                return $response->json()['rates'];
            }

            return [];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}