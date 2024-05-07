<?php

namespace App\Services;
use App\Models\Consumer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\InsertToDbDuplicate;
use App\Services\InsertToDbNotDuplicate;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        $numItems = count($rows);
        $i = 0;
        foreach ($rows as $row) {
            if(Crypt::decryptString($row['mr_id']) > 0){
                if(DB::table('sales')->where('mr_id',Crypt::decryptString($row['mr_id']))
                ->where('teller_user_id','!=',Crypt::decryptString($row['teller_user_id']))
                ->exists()){
                // if(DB::table('sales')->where('mr_id',Crypt::decryptString($row['mr_id']))->first())
                    (new InsertToDbDuplicate($row));
                }
                elseif(DB::table('sales')->where('mr_id',Crypt::decryptString($row['mr_id']))->doesntExist()){
                    (new InsertToDbNotDuplicate($row));
                }
            }else{
                (new InsertToDbNotDuplicate($row));
            }
            if(++$i === $numItems) {
                Consumer::where('temp_cons_id','>',0)->update(['temp_cons_id' => 0]);
            }
        }

    }
}