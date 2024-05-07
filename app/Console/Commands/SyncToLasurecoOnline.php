<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncToLasurecoOnline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:lasureco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync/Update Database of Lasureco Online';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // set_time_limit(0);
        $label = 'lasureco.com';
        $lastSync = DB::table('sync_date')
        ->where('label_sync',$label)
        ->first();
        if(!$lastSync){
            $this->line('No Record Found'.' '.Carbon::now());
            return;
        }

        $lastSync = $lastSync->succesful_sync;
        $tables = DB::getDoctrineSchemaManager()->listTableNames();
        $dataToSync = [];
        foreach($tables as $table){
            $data = collect((new SyncService($lastSync,$table,'server'))->fetchTableData());
            if(!$data->has($table) || $data[$table]->isEmpty()){
                continue;
            }
            array_push($dataToSync,$data);
        }
        if(!$dataToSync){
            $this->line('No Record To Sync'.' '.Carbon::now());
            return;
        }
        
        try{
        DB::connection('remote_mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::connection('remote_mysql')->beginTransaction();
        $test = $this->processToSync($dataToSync,$label);
        DB::connection('remote_mysql')->commit();
        }catch (\Exception $e) {
            DB::connection('remote_mysql')->rollBack();
            $this->line($e.' '.Carbon::now());
            return;
        } finally {
            DB::connection('remote_mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->line('Succesfully Updated'.' '.Carbon::now());
        return;
    }

    function processToSync($dataToSync,$label){
    
        foreach ($dataToSync as $data) {
            
            foreach ($data as $table => $tableData) {
                $chunkSize = 300; 
                
                foreach ($tableData->chunk($chunkSize) as $chunks) {
                    $hasPrimaryKey = DB::getDoctrineSchemaManager()->listTableDetails($table)->hasPrimaryKey();
                    $convertedData = collect($chunks)->map(function ($item) {
                        return (array) $item;
                    })->toArray();

                    if($table == 'sales2'){
                        continue;
                    }
                    if($table == 'archive'){
                        continue;
                    }

                    if ($hasPrimaryKey) {
                        $queryPrimaryKey = DB::getDoctrineSchemaManager()->listTableDetails($table)->getPrimaryKeyColumns();
                        $primaryKey = array_keys($queryPrimaryKey)[0];
                        
                        DB::connection('remote_mysql')->table($table)
                        ->upsert($convertedData,$primaryKey);
                        
                    } else {
                        if($table == 'e_wallet_log'){
                            continue;
                        }
                        $foreignKeys = DB::getDoctrineSchemaManager()->listTableForeignKeys($table);
                        $fkLocalName = [];
                        foreach ($foreignKeys as $foreignKey) {
                            $localColumns = $foreignKey->getLocalColumns();
                            array_push($fkLocalName,$localColumns);
                        }
                        $pk1 = $fkLocalName[0][0];
                        $pk2 = $fkLocalName[1][0];
                        // $this->line($pk1,$pk2);
                        // return;
                        DB::connection('remote_mysql')->table($table)
                            ->upsert($convertedData,[$pk1,$pk2]);
                        continue;
                    }
                    
                    if($table == 'deleted_data'){
                        $pluckedID = $tableData->pluck('data_id');
                        DB::connection('remote_mysql')->table('meter_reg')->whereIn('mr_id',$pluckedID)->delete();
                    }
                }
            }
        }
        DB::table('sync_date')
        ->where('label_sync',$label)
        ->update([
            'succesful_sync'=> Carbon::now()
        ]);
    }
}
