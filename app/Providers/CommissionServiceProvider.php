<?php

namespace App\Providers;

use App\Contracts\CalculateCommissionContract;
use App\Contracts\CommissionContract;
use App\Contracts\CsvImportContract;
use App\Contracts\CurrencyContract;
use App\Contracts\DepositeContract;
use App\Contracts\WithdrawContract;
use App\Services\CalculateCommissionService;
use App\Services\CommissionService;
use App\Services\CsvImportService;
use App\Services\CurrencyService;
use App\Services\DepositeService;
use App\Services\WithdrawService;
use Illuminate\Support\ServiceProvider;

class CommissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CommissionContract::class, CommissionService::class);
        $this->app->bind(CsvImportContract::class, CsvImportService::class);
        $this->app->bind(DepositeContract::class, DepositeService::class);
        $this->app->bind(WithdrawContract::class, WithdrawService::class);
        $this->app->bind(CurrencyContract::class, CurrencyService::class);
        $this->app->bind(CalculateCommissionContract::class, CalculateCommissionService::class);
    }
}
