<?php

namespace App\Services;

use App\Contracts\CsvImportContract;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\Csv\Reader;

class CsvImportService implements CsvImportContract
{
    public function getCsvRecords(string $path): iterable
    {
        if (!is_file($path)) {
            throw new FileNotFoundException("CSV file not found.");
        }

        //load the CSV document from a file path
        $csv = Reader::createFromPath($path, 'r');
        
        // returns the CSV header record
        // $csv->setHeaderOffset(0);
        // $header = $csv->getHeader();

        // returns all the CSV records as an Iterator object
        $records = $csv->getRecords(); 

        return $records;
    }
}