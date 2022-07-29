<?php

namespace App\Services;

use App\Contracts\CommissionContract;
use App\Contracts\CsvImportContract;
use App\Contracts\DepositeContract;
use App\Contracts\WithdrawContract;
use Exception;

class CommissionService implements CommissionContract
{
    protected readonly string $path;
    private iterable $records;

    public function __construct(
        protected CsvImportContract $csvImportService,
        protected DepositeContract $depositeService,
        protected WithdrawContract $withdrawService
        )
    {
        $this->path = config('constants.CSV_FILE_PATH');
    }

    public function importCSV(): array
    {
        try {
            $this->records = $this->csvImportService->getCsvRecords($this->path);
            
            return $this->processRecords();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function processRecords(): array
    {
        if (!is_iterable($this->records)) {
            throw new Exception("Records are not iterable!");
        }

        $commissions = [];

        foreach ($this->records as $record) {
         
            if (isset($record[config('constants.OPERATION_TYPE')]) && $record[config('constants.OPERATION_TYPE')] == config('constants.DEPOSITE')) {
                $commissions[] = $this->deposite($record[config('constants.OPERATION_AMOUNT')]);
            } elseif (isset($record[config('constants.OPERATION_TYPE')]) && $record[config('constants.OPERATION_TYPE')] == config('constants.WITHDRAW')) {
                $commissions[] = $this->withdraw($record);
            } 
        }
  
        return $commissions;
    }

    public function deposite(int|float $amount): int|float
    {
        return $this->depositeService->clculateDepositeFee($amount);
    }

    public function withdraw(array $record): int|float
    {
        return $this->withdrawService->processWithdrawTransaction($record);
    }
}