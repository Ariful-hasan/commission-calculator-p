<?php

namespace App\Contracts;


interface CommissionContract
{    
    /**
     * calculate tracsaction fee
     * both deposite and withdraw
     * return fees for each tracsactions
     *
     * @return array
     */
    public function importCSV(): array;
}