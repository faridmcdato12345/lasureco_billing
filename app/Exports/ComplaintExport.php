<?php

namespace App\Exports;
use App\Services\ChequeExport;
use App\Services\ConsumersExport;
use App\Services\SalesExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataExport implements WithMultipleSheets
{

    use Exportable;

    private $fromDate;
    private $toDate;
    private $userId;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($fromDate,$toDate,$userId){
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->userId = $userId;
    }
    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        //$sheets[] = new ConsumersExport($this->fromDate,$this->toDate,$this->userId);
        $sheets[] = new SalesExport($this->fromDate,$this->toDate,$this->userId);
        $sheets[] = new ChequeExport($this->fromDate,$this->toDate,$this->userId);
        return $sheets;
    }
}
