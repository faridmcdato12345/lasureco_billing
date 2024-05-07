<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SyncService {

    private $lastSyncDate;
    private $lastSyncTime;
    private $table;
    private $type;

    public function __construct($lastSync,$table,$type)
    {
        $this->lastSyncDate = Carbon::parse($lastSync)->format('Y-m-d');
        $this->lastSyncTime = Carbon::parse($lastSync)->toTimeString();
        $this->table = $table;
        $this->type = $type;
    }
    
    public function fetchTableData(){
        if($this->type == 'server'){
            $data2 = collect(DB::table($this->table)
            ->where('store_mod_sync','>',$this->lastSyncDate.' '.$this->lastSyncTime)
            ->get());

            $data = $data2->map(function($item){
                $item->store_mod_sync = Carbon::now();
                return $item;
            });
        }else{
            $data2 = collect(DB::connection('remote_mysql')->table($this->table)
            ->where('store_mod_sync','>',$this->lastSyncDate.' '.$this->lastSyncTime)
            ->get());
        }
        

        return [$this->table =>$data];
    }

}