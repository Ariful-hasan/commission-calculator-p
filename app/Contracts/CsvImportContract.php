<?php

namespace App\Contracts;

interface CsvImportContract
{    
    /**
     * load the CSV document from a file path
     *
     * @param  mixed $path is the file path
     * @return csv records as array
     */
    public function getCsvRecords(string $path): iterable;
}
