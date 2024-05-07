<?php

namespace App\Imports;

use App\Services\SalesImport;
use App\Services\ChequesImport;
use App\Services\ConsumersImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    
    public function sheets(): array
    {
        return [
            // 'Consumers' => new ConsumersImport(),
            'Cheques' => new ChequesImport(),
            'Sales' => new SalesImport(),
        ];
    }
}