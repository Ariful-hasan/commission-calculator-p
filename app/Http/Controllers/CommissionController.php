<?php

namespace App\Http\Controllers;

use App\Contracts\CommissionContract;

class CommissionController extends Controller
{
    public function __construct(protected CommissionContract $commissionServie)
    {
        # code...
    }
        
    /**
     * index entry point
     * for the commission calculation.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->commissionServie->importCSV();      
    }
}
