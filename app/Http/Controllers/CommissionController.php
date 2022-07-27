<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommissionController extends Controller
{
        
    /**
     * index entry point
     * for the commission calculation.
     *
     * @return array
     */
    public function index(): array
    {
        return [
            0.60,
            3.00,
            0.00,
            0.06,
            1.50,
            0,
            0.70,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8612,
        ];
    }
}
