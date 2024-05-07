<?php

namespace App\Http\Controllers;

use App\Jobs\ConsumerJob;
use App\Jobs\MeterMasterJob;
use App\Jobs\SalesJob;
use App\Jobs\UpdateMeterRegJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToServerController extends Controller
{
    public function uploadCollection(Request $request){
        
        $requestData = json_decode($request->data);
        $requestDataCheque = json_decode($request->dataCheque);
        $collection = collect($requestData->data);
        $collectionCheque = collect($requestDataCheque->data);
        $bills = $collection->whereNotNull('mr_id');
        $billIds = $bills->pluck('mr_id');
        $nonbills = $collection->where('f_id','!=',0);
        $nonbillsEwallet = $collection->where('s_mode_payment','=','Deposit_Ewallet');
        try {
            DB::beginTransaction();
            $output = $this->proccessChunk($bills,$nonbills,$nonbillsEwallet,$collectionCheque);
            // if($output == 1){
            //     // rolled back and stop processing
            //     return response(['info'=>'Already Uploaded'], 409);
            // }
            $this->processMeterReg($billIds);
            DB::commit(); // If everything is successful, commit the transaction
            return response(['info'=>'success'],200);
        } catch (\Exception $e) {
            DB::rollback(); // If an error occurs, roll back the transaction
            return response(['info'=>'Something went wrong'], 500);
        }
    }

    function processMeterReg($ids){
        DB::table('meter_reg')
            ->whereIn('mr_id',$ids)
            ->update([
                'mr_status'=> 1,
                'uploaded_at'=> Carbon::now(),
            ]);
    }
    function processEwalletAdded($id,$amount){
        DB::table('e_wallet')->where('cm_id', $id)->increment('ew_total_amount', $amount);
    }
    function processEwalletApplied($id,$amount){
        DB::table('e_wallet')->where('cm_id', $id)->decrement('ew_total_amount', $amount);
    }
    function proccessChunk($bills,$nonbills,$ewallet,$cheque){
        // $stopProcessing = false;
        // $proceed = 1;
        $chunkedBills = collect($bills)->chunk(300);
        $chunkedBills->each(function ($chunk) use($cheque){
            // if (!$stopProcessing) {
                $output = $this->processSaleBills($chunk,$cheque);
                // if($output == 'stop'){
                //     $stopProcessing = true;
                //     $proceed = 0;
                // }
            // }
        });

        // if($proceed == 1){
            $chunkedNonBills = collect($nonbills)->chunk(300);
            $chunkedNonBills->each(function ($chunk) use($cheque) {
                $this->processSaleNonbills($chunk,$cheque);
            });

            //process also ewallet deposit non bill
            $chunkedNonBillsEwallet = collect($ewallet)->chunk(300);
            $chunkedNonBillsEwallet->each(function ($chunk) {
                $this->processSalesEwalletBill($chunk);
            });
        // }else{
        //     return 1;
        // }
        
    }
    function processSaleBills($collections,$cheque){
        foreach($collections as $collection){
            $serverSales = DB::table('sales')->where('s_bill_no',$collection->s_bill_no)->first();
            if($serverSales){
                if($collection->s_bill_no == $serverSales->s_bill_no && 
                $collection->s_or_num == $serverSales->s_or_num && 
                $collection->teller_user_id == $serverSales->teller_user_id &&
                $collection->mr_id == $serverSales->mr_id){
                    // ignore (Duplicate/Uploaded already)
                    continue;
                }else if($collection->s_bill_no == $serverSales->s_bill_no && 
                    $collection->s_or_num != $serverSales->s_or_num){
                    // insert (Double Payment )
                    $this->insertSales('ewallet',$collection,'');
                    $this->processEwalletAdded($collection->cm_id,$collection->s_or_amount);
                    if($collection->e_wallet_applied != 0){
                        $this->processEwalletApplied($collection->cm_id,$collection->e_wallet_applied);
                    }
                }else{
                    // normal insert
                    $this->insertSales('bill',$collection,$cheque);
                    if($collection->e_wallet_added != 0){
                        $this->processEwalletAdded($collection->cm_id,$collection->e_wallet_added);
                    }
                    if($collection->e_wallet_applied != 0){
                        $this->processEwalletApplied($collection->cm_id,$collection->e_wallet_applied);
                    }
                }
            }else{
                $this->insertSales('bill',$collection,$cheque);
                if($collection->e_wallet_added != 0){
                    $this->processEwalletAdded($collection->cm_id,$collection->e_wallet_added);
                }
                if($collection->e_wallet_applied != 0){
                    $this->processEwalletApplied($collection->cm_id,$collection->e_wallet_applied);
                }
            }
        }
    }
    function processSaleNonbills($collections,$cheque){
        foreach($collections as $collection){
            $serverSales = DB::table('sales')->where('s_or_num',$collection->s_or_num)
            ->where('f_id',$collection->f_id)
            ->first();
            if($serverSales){
                if($collection->s_or_num == $serverSales->s_or_num || 
                    $collection->teller_user_id == $serverSales->teller_user_id || 
                    $collection->s_bill_date == $serverSales->s_bill_date){
                    // ignore (Duplicate/Uploaded already)
                    continue;
                }else{
                    $this->insertSales('nonbill',$collection,$cheque);
                    if($collection->e_wallet_added != 0){
                        $this->processEwalletAdded($collection->cm_id,$collection->e_wallet_added);
                    }
                    if($collection->e_wallet_applied != 0){
                        $this->processEwalletApplied($collection->cm_id,$collection->e_wallet_applied);
                    }
                }
            }else{
                $this->insertSales('nonbill',$collection,$cheque);
                if($collection->e_wallet_added != 0){
                    $this->processEwalletAdded($collection->cm_id,$collection->e_wallet_added);
                }
                if($collection->e_wallet_applied != 0){
                    $this->processEwalletApplied($collection->cm_id,$collection->e_wallet_applied);
                }
            }
            
        }
    }
    function processSalesEwalletBill($collections){
        foreach($collections as $collection){
            $serverSales = DB::table('sales')->where('s_or_num',$collection->s_or_num)
            ->where('s_mode_payment','Deposit_Ewallet')->first();
            if($serverSales){
                continue;
            }else{
                $this->insertSales('ewallet2',$collection,'');
                $this->processEwalletAdded($collection->cm_id,$collection->e_wallet_added);
            }
        }
    }
    function insertSales($type,$data,$cheque){
        $insertData = [
            'ct_id' => $data->ct_id,
            's_or_num' => $data->s_or_num,
            'cm_id' => $data->cm_id,
            'v_id' => $data->v_id,
            's_bill_amount' => $data->s_bill_amount,
            's_bill_date' => $data->s_bill_date,
            's_status' => $data->s_status,
            'teller_user_id' => $data->teller_user_id,
            'mr_arrear' => $data->mr_arrear,
            'e_wallet_added' => $data->e_wallet_added,
            's_cutoff' => $data->s_cutoff,
            'server_added' => 1,
            's_bill_date_time' => $data->s_bill_date_time,
            's_ref_no' => $data->s_ref_no,
        ];
        if($data->cheque_id != NULL || $data->cheque_id != ''){
            $result = collect($cheque)->filter(function ($item) use($data){
                return $item->s_or == $data->s_or_num;
            })->first();
            if($result){
                $checkingExist = DB::table('cheque')
                // ->whereDate('cheque_date',$result->cheque_date)
                ->where('s_or',$result->s_or)->first();
                if($checkingExist){
                    $insertData['cheque_id'] = $checkingExist->cheque_id;
                }else{
                    $id = DB::table('cheque')->insertGetId([
                        'cheque_no' => $result->cheque_no,
                        's_or' => $result->s_or,
                        'cheque_amount' => $result->cheque_amount,
                        'cheque_bank_acc' => $result->cheque_bank_acc,
                        'cheque_acc_name' =>$result->cheque_acc_name,
                        'cheque_bank' => $result->cheque_bank,
                        'cheque_bank_branch' => $result->cheque_bank_branch,
                        'cheque_date' => $result->cheque_date,
                        'cheque_status' => $result->cheque_status,
                        'cheque_posted' => $result->cheque_posted,
                        'teller_user_id' => $result->teller_user_id,
                        'temp_cheque_id' => $result->temp_cheque_id
                    ]);
                    
                    $insertData['cheque_id'] = $id;
                }
            }
            
        }

        if ($type == 'bill') {
            // Additional columns and values for the 'bill' case
            $insertData['mr_id'] = $data->mr_id;
            $insertData['s_bill_no'] = $data->s_bill_no;
            $insertData['s_or_amount'] = $data->s_or_amount;
            $insertData['e_wallet_applied'] = $data->e_wallet_applied;
            $insertData['s_mode_payment'] = $data->s_mode_payment;
            $insertData['e_wallet_added'] = $data->e_wallet_added;
        }else if($type == 'ewallet'){
            // for double payment
            $eWalletAdded = $data->e_wallet_added ?? 0;
            $insertData['s_mode_payment'] = 'Deposit_Ewallet';
            $insertData['e_wallet_added'] = round($data->s_bill_amount +  $eWalletAdded,2);
        }else if($type == 'ewallet2'){
            // for double payment
            $insertData['s_mode_payment'] = 'Deposit_Ewallet';
            $insertData['e_wallet_added'] = round($data->e_wallet_added,2);
        }else if($type == 'nonbill'){
            // Additional columns and values for the 'not bill' case
            $insertData['f_id'] = $data->f_id;
            $insertData['s_or_amount'] = $data->s_or_amount;
            $insertData['e_wallet_applied'] = $data->e_wallet_applied;
            $insertData['s_mode_payment'] = $data->s_mode_payment;
            $insertData['e_wallet_added'] = $data->e_wallet_added;
        }
        
        DB::table('sales')->insert($insertData);
    }
    public function toLocal(){
        
        // $latestSalesDate = DB::table('sales')->max('s_bill_date');

        // UpdateMeterRegJob::dispatch($latestSalesDate)->chain([
        //     new MeterMasterJob,
        //     new ConsumerJob,
        //     new SalesJob($latestSalesDate),
        // ]);
        // return 'ok';
    }
    public function checkReadySync(Request $request){
        $requestData = json_decode($request->data);
        $arrayExist = [];
        $arrayDoesntExist = [];
        foreach($requestData as $collection){
            $query = DB::table('sales')->select('mr_id','teller_user_id','s_bill_date')
            ->where('s_bill_date',$collection->s_bill_date)
            ->where('mr_id',$collection->mr_id)
            ->where('teller_user_id',$collection->teller_user_id)
            ->first();
            
            if($query){
                $arrayExist[] = $query;
            }else{
                $arrayDoesntExist[] = $collection;
            }
        }
        if ($arrayDoesntExist) {
            // $array is empty
            return response([
                'info'=>'Database Not Ready to Sync. Upload Collection First',
                'data'=>$arrayDoesntExist,
            ],409);
        } else {
            // $array is not empty
            return response(['info'=>'Proceed, Database Ready to Sync'],200);
            
        }
    }
    public function syncDB(Request $request){
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $toUpdateMRIDS = $this->getUpdateMeterReg($dateFrom,$dateTo);
        $getNewBills = $this->getNewMeterReg($request->mrid);
        $getSales = $this->getSales($dateFrom,$dateTo);
        $getCheque = $this->getCheque($dateFrom,$dateTo);
        $getAllEwallet = $this->getAllEwallet();
        $getAllNewMeters = $this->getNewMeterMaster($request->mmid);
        $getAllNewConsumers = $this->getNewConsMaster($request->cmid);
        $getNewAreaTownArea = $this->getAreaTownRoute($request->acid,$request->tcid,$request->rcid);
        $getNewBillRates = $this->getNewBillRates($request->brid);
        $getNewConstype = $this->getNewConstype($request->ctid);
        $getUpdatedAtConsumer = $this->getUpdatedAtConsumer($dateFrom,$dateTo);
        // $getAllUsers = json_encode($this->getAllUsers());
        return response()->json([
            'update_ids'=>$toUpdateMRIDS,
            'new_bills'=>$getNewBills,
            'sales'=>$getSales,
            'cheque'=>$getCheque,
            'ewallet'=>$getAllEwallet,
            'new_meters'=>$getAllNewMeters,
            'new_consumers'=>$getAllNewConsumers,
            'new_area'=>$getNewAreaTownArea['area'],
            'new_town'=>$getNewAreaTownArea['town'],
            'new_route'=>$getNewAreaTownArea['route'],
            'new_rates'=>$getNewBillRates,
            'new_constype'=>$getNewConstype,
            'update_consumer'=>$getUpdatedAtConsumer,
            // 'users'=>$getAllUsers,
        ]);
    }
    function getUpdateMeterReg($dateFrom,$dateTo){
        $dateFrom = carbon::parse($dateFrom)->subDays(1)->format('Y-m-d');
        $query = DB::table('meter_reg')->select('mr_id')
        ->whereDate('uploaded_at','>=',$dateFrom)
        ->whereDate('uploaded_at','<=',$dateTo)
        ->where('mr_status',1)
        ->pluck('mr_id');
        return $query;
    }
    function getNewMeterReg($mr_id){
        $query = DB::table('meter_reg')
        ->where('mr_id','>',$mr_id)
        ->get();
        return $query;
    }
    function getNewMeterMaster($mm_id){
        $query = DB::table('meter_master')
        ->where('mm_id','>',$mm_id)
        ->get();
        return $query;
    }
    function getNewConsMaster($cm_id){
        $query = DB::table('cons_master')
        ->where('cm_id','>',$cm_id)
        ->get();
        return $query;
    }
    function getUpdatedAtConsumer($dateFrom,$dateTo){
        $query = DB::table('cons_master')
        ->whereDate('updated_at','>=',$dateFrom)
        ->whereDate('updated_at','<=',$dateTo)
        ->get();
        return $query;
    }
    function getSales($dateFrom,$dateTo){
        // $cheque = ['Cheque','Cheque And Cash','Cheque And E-Wallet'];
        $query = DB::table('sales')
        ->whereDate('s_bill_date','>=',$dateFrom)
        ->whereDate('s_bill_date','<=',$dateTo)
        // ->whereIn('s_mode_payment',$cheque)
        ->get();
        return $query;
    }
    function getCheque($dateFrom,$dateTo){
        $query = DB::table('cheque')
        ->whereDate('cheque_date','>=',$dateFrom)
        ->whereDate('cheque_date','<=',$dateTo)
        ->get();
        return $query;
    }
    function getAllEwallet(){
        $query = DB::table('e_wallet')
        ->get();
        return $query;
    }
    function getAreaTownRoute($areaID,$townID,$routeID){
        $queryArea = DB::table('area_code')
        ->where('ac_id','>',$areaID)
        ->get();
        $queryTown = DB::table('town_code')
        ->where('tc_id','>',$townID)
        ->get();
        $queryRoute = DB::table('route_code')
        ->where('rc_id','>',$routeID)
        ->get();
        return ['area'=>$queryArea,'town'=>$queryTown,'route'=>$queryRoute];
    }
    function getNewBillRates($brid){
        $queryRates = DB::table('billing_rates')
        ->where('id','>',$brid)
        ->get();
        return $queryRates;
    }
    function getNewConstype($ctid){
        $queryCtype = DB::table('cons_type')
        ->where('ct_id','>',$ctid)
        ->get();
        return $queryCtype;
    }
    // function getAllUsers(){
    //     $query = DB::table('user')
    //     ->get();
    //     return $query;
    // }


}
