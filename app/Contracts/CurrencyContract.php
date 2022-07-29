<?php

namespace App\Contracts;

interface CurrencyContract
{    
    public function __construct();
    

    /**
     * get all the currency rates.
     *
     * @return array
     */
    public function getCurrencyExchangeRates(): array;
}