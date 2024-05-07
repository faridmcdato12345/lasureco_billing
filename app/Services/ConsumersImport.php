<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Services\InsertToDbConsumer;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ConsumersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            new InsertToDbConsumer($row);
        }
    }
}
