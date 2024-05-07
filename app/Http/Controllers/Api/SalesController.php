<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesResource;
use App\Models\Cheque;
use App\Models\EWALLET;
use App\Models\EWALLET_LOG;
use App\Models\MeterReg;
use App\Models\Or_Void;
use App\Models\Sales;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
	public $tellerUserId;
    public function index()
    {
        return SalesResource::collection(
            DB::table('sales')
            ->whereNull('deleted_at')
            ->paginate(10));
    }
    public function show($id)
    {
        return SalesResource::collection(
            DB::table('sales')
            ->where('s_id',$id)
            ->whereNull('deleted_at')
            ->get());
    }
    public function payBills(Request $request)
    {

        $data = json_decode($request->getContent(),true);
        $this->tellerUserId = $data['Consumer']['user_id'];
        // Consumer Details
        $consID = $data['Consumer']['cm_id'];
        $consTypeID = $data['Consumer']['ct_id'];
        $tellerID = $data['Consumer']['user_id']; // teller id
        //Amount/Bill Details
        $amountTBpaid = round($data['Amounts']['Amount_TB_Paid'],2);
        // $orNo = $data['TOR_No']['or_no'];
        // ----------ramos
        $orNo2 = $data['TOR_No']['or_no'];
        $orNo = intval($orNo2);
        // ----------end ramos
        
        $checkOR = DB::table('sales')
        ->where('s_or_num',$data['TOR_No']['or_no'])
        ->get();
        // if($consID == NULL){
        //     return response(['Message'=>''],422);
        // }

        if($checkOR->first())
        {
            return response(['Message'=>'Official Receipt Already Exist, Please End Session'],422);
        }

        if($data['teller']['cutoff'] == 1)
        {
            return response(['Message'=>'Session CutOff'],422);
        }
        
        $host = DB::table('server_connections')
                ->select('ip_address')
                ->first();
        if(!$host){
            return response(['Message'=>'Connection Error, No IP Address Detected'],500);
        }
        $current_date_time = Carbon::now()->toDateTimeString();

        /* ---------------------------------------------------------( BAPA/MUPA Transaction )------------------------------------------------------------------------ */
        /* ---------------------------------------------------------( BAPA/MUPA Transaction )------------------------------------------------------------------------ */
        if($consTypeID == 3 && !isset($data['NB']))
        {
            // For BAPA/MUPA 1 BILL Per Transaction Only
            $count = count($data['PB']);
            if($count > 1){
                return response(['Message'=>'Error, For BAPA/MUPA Transaction : 1 Bill Per Transaction'],422);
            }
            
            //Consumer Pays Cash with E-wallet
            if(isset($data['Amounts']['E_Wallet']) && isset($data['Amounts']['Cash_Amount']) 
            && !isset($data['Cheque']['Cheque_Amount']) && ($data['Amounts']['E_Wallet'] <= $data['Consumer']['ew_total_amount']))
            {
                
                $eWalletID = $data['Consumer']['ew_id'];
                $eWallet = $data['Amounts']['E_Wallet'];
                $eWalletTAmount = round($data['Consumer']['ew_total_amount'] - $eWallet,2);
                $cash = $data['Amounts']['Cash_Amount'];
                $totalPaid = round($eWallet + $cash,2);
                $ewalletBalance = round($totalPaid - $amountTBpaid,2);
                $partial = $eWallet + $cash;

                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                    ->orderBy('mr_date_year_month','desc')
                    ->where('cm_id',$consID)
                    ->first();
                //IF PB is included in Transaction
                if(isset($data['PB']))
                {
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Cash And E-Wallet';
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            $sales->e_wallet_applied = $eWallet;
                            // $sales->s_or_amount = $partial;
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id){
                                $sales->mr_arrear = "Y";
                            }
                            else{
                                $sales->mr_arrear = "N";
                            }
                            // Check if meter_reg is fully paid (+-)
                            $checkMRFPaid = MeterReg::find($value['mr_id']);
                            $newPartial = $checkMRFPaid->mr_partial + $partial;
                            // dd($newPartial);
                            if($newPartial > $amountTBpaid){
                                $addToEwallet = $newPartial - $amountTBpaid;
                                $this->addEwallet($eWalletID,$addToEwallet);
                                $sales->e_wallet_added = $addToEwallet;
                                $sales->s_or_amount = $cash;
                            }else{
                                $sales->s_or_amount = $partial - $eWallet; 
                            }

                            $this->updateEwallet($eWalletID,$eWallet);
                            $this->updateBMBillStatus($value['mr_id'],$partial);
                            $sales->save();
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                        // 1000 = 500 + 700
                    }

                }

                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }

                $totalAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('cm_id',$consID)
                        ->where('s_or_num',$orNo)
                        ->first()
                );

                return response([
                    'Msg'=> 'Succesfully Paid CASH&EWALLET',
                    'Total_Amount'=>$cash,
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>0,
                    'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                ],201);
                
            }
            // Consumer Pays Cash Only ---------------------------------------------------------------------------------------------------
            else if(isset($data['Amounts']['Cash_Amount']) && !isset($data['Amounts']['E_Wallet']) 
            && !isset($data['Cheque']['Cheque_Amount']))
            {
                //Consumer Detail
                $eWalletID = $data['Consumer']['ew_id'];
                //Ewallet Bal
                $consEwallet = $data['Consumer']['ew_total_amount'];
                // Amounts Details
                $cash = $data['Amounts']['Cash_Amount'];
                $amountChange = round($data['Amounts']['Cash_Amount'] - $amountTBpaid, 2);
                $getChange = $data['Amounts']['getChange'];
                
                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                    ->orderBy('mr_date_year_month','desc')
                    ->where('cm_id',$consID)
                    ->first();
                
                //ALL PB data
                if(isset($data['PB']))
                {
                    
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    //dd($consTypeID);
                    
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->s_or_amount = $value['mr_amount'];
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Cash';
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id)
                            {
                                $sales->mr_arrear = "Y";
                            }
                            else
                            {
                                $sales->mr_arrear = "N";
                            }

                            // Check if meter_reg is fully paid (+-)
                            $checkMRFPaid = MeterReg::find($value['mr_id']);
                            $newPartial = $checkMRFPaid->mr_partial + $cash;
                            // dd($newPartial);
                            if($newPartial > $amountTBpaid){
                                if($getChange == 'yes'){
                                    $addToEwallet = $newPartial - $amountTBpaid;
                                    $this->addEwallet($eWalletID,$addToEwallet);
                                    $sales->e_wallet_added = $addToEwallet;
                                    $sales->s_or_amount = $cash - $addToEwallet;
                                    $this->storeEWalletLogUnposted($eWalletID,$addToEwallet,$orNo,$current_date_time);
                                }else{
                                    $addToEwallet = $newPartial - $amountTBpaid;
                                    $sales->s_or_amount = $cash - $addToEwallet;
                                }

                            }else{
                                $sales->s_or_amount = $cash;
                                $addToEwallet = 0;
                            }

                            $sales->save();
                            $this->updateBMBillStatus($value['mr_id'],$cash);
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                    }
                }

                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }

                $totalAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('cm_id',$consID)
                        ->where('s_or_num',$orNo)
                        ->first()
                );

                // $partialAmount = DB::table('meter_reg')
                //     ->where('cm_id',$consID)
                //     ->where('mr_id',$datas = $data['PB']['mr_id'])
                //     ->first();
                // dd($partialAmount);

                return response([
                    'Msg'=> 'Succesfully Paid CASH ONLY NO CHANGE',
                    'Total_Amount'=>$totalAmount->values()->all(),
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>$addToEwallet,
                    'Ewallet_Applied'=>0,
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                ],201);
            }
            // Consumer Pays Ewallet Only -------------------------------------------------------------------------------------------------
            else if(isset($data['Amounts']['E_Wallet']) && !isset($data['Amounts']['Cash_Amount']) 
            && !isset($data['Cheque']['Cheque_Amount']))
            {
                //Consumer Detail
                $eWalletID = $data['Consumer']['ew_id'];
                //Consumer Ewallet 
                $eWallet = $data['Amounts']['E_Wallet'];
                $consEwallet = $data['Consumer']['ew_total_amount'];
                $consEWRemainBal = $consEwallet - $data['Amounts']['E_Wallet'];
                $consPaidEw = $data['Amounts']['E_Wallet'];
                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                    ->orderBy('mr_date_year_month','desc')
                    ->where('cm_id',$consID)
                    ->first();

                
                //ALL PB data
                if(isset($data['PB']))
                {
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Ewallet';
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            $sales->s_or_amount = 0;
                            $sales->e_wallet_applied = $eWallet;
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id)
                            {
                                $sales->mr_arrear = "Y";
                            }
                            else
                            {
                                $sales->mr_arrear = "N";
                            }
                            $this->storeEWLogApplied($eWalletID,$eWallet,$orNo,$current_date_time);
                            $this->updateEwallet($eWalletID,$eWallet);
                            $this->updateBMBillStatus($value['mr_id'],$eWallet);
                            $sales->save();
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                    }
                }

                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }

                $totalAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('cm_id',$consID)
                        ->where('s_or_num',$orNo)
                        ->first()
                );

                return response([
                    'Msg'=> 'Succesfully Paid EWALLET ONLY',
                    'Total_Amount'=>$totalAmount->values()->all(),
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>0,
                    'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                
                ],201);
            }
            // Consumer Pays Cheque Only
            else if(isset($data['Cheque']['Cheque_Amount']) && !isset($data['Amounts']['E_Wallet']) 
            && !isset($data['Amounts']['Cash_Amount']))
            {
                //Consumer Detail
                $eWalletID = $data['Consumer']['ew_id'];
                //Consumer Ewallet 
                $consEwallet = $data['Consumer']['ew_total_amount'];
                $cheque = $data['Cheque']['Cheque_Amount'];
                $chequeChange = round($cheque - $amountTBpaid,2);
                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                    ->orderBy('mr_date_year_month','desc')
                    ->where('cm_id',$consID)
                    ->first();

                //Insert Cheque First to get the ID  
                $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
                $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'] ,
                $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
                //ALL PB data
                if(isset($data['PB']))
                {
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->s_or_amount = $value['mr_amount'];
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Cheque';
                            $sales->cheque_id = $chequeID;
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id)
                            {
                                $sales->mr_arrear = "Y";
                            }
                            else
                            {
                                $sales->mr_arrear = "N";
                            }

                            // Check if meter_reg is fully paid (+-)
                            $checkMRFPaid = MeterReg::find($value['mr_id']);
                            $newPartial = $checkMRFPaid->mr_partial + $cheque;
                            // dd($newPartial);
                            if($newPartial > $amountTBpaid){
                                $addToEwallet = $newPartial - $amountTBpaid;
                                $this->addEwallet($eWalletID,$addToEwallet);
                                $sales->e_wallet_added = $addToEwallet;
                                $sales->s_or_amount = $cheque - $addToEwallet;
                                $this->storeEWalletLogUnposted($eWalletID,$addToEwallet,$orNo,$current_date_time);

                            }else{
                                $sales->s_or_amount = $cheque;
                                $addToEwallet = 0;
                            }
                            //
                            $sales->save();
                            $this->updateBMBillStatus($value['mr_id'],$cheque);
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                    }
                }

                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }
                    
                return response([
                    'Msg'=> 'Succesfully Paid Cheque ONLY',
                    'Total_Amount'=>$cheque,
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>$addToEwallet,
                    'Ewallet_Applied'=>0,
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                ],201);
            }
            // Consumer Pays Cheque With Cash
            else if(isset($data['Cheque']['Cheque_Amount']) && !isset($data['Amounts']['E_Wallet'])
            && isset($data['Amounts']['Cash_Amount']))
            {
                //Consumer Detail
                $eWalletID = $data['Consumer']['ew_id'];
                //Consumer Ewallet 
                
                $consEwallet = $data['Consumer']['ew_total_amount'];
                //added cash to cheque
                $cheque = $data['Cheque']['Cheque_Amount'] + $data['Amounts']['Cash_Amount'];
                $chequeChange = $cheque - $amountTBpaid;
                $partial = $cheque;
                
                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
                //Insert Cheque First to get the ID  
                $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
                $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'] ,
                $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
                //ALL PB data
                if(isset($data['PB']))
                {
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->s_or_amount = $value['mr_amount'];
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Cheque And Cash';
                            $sales->cheque_id = $chequeID;
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id)
                            {
                                $sales->mr_arrear = "Y";
                            }
                            else
                            {
                                $sales->mr_arrear = "N";
                            }
                            // Check if meter_reg is fully paid (+-)
                            $checkMRFPaid = MeterReg::find($value['mr_id']);
                            $newPartial = $checkMRFPaid->mr_partial + $partial;
                            // dd($newPartial);
                            if($newPartial > $amountTBpaid){
                                $addToEwallet = $newPartial - $amountTBpaid;
                                $this->addEwallet($eWalletID,$addToEwallet);
                                $sales->e_wallet_added = $addToEwallet;
                                $sales->s_or_amount = $partial - $addToEwallet;
                                $this->storeEWalletLogUnposted($eWalletID,$addToEwallet,$orNo,$current_date_time);

                            }else{
                                $sales->s_or_amount = $partial;
                                $addToEwallet = 0;
                            }
                            //
                            $sales->save();
                            $this->updateBMBillStatus($value['mr_id'],$cheque);
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                            
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                    }
                }
                
                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }
                    
                return response([
                    'Msg'=> 'Succesfully Paid Cheque & Cash ONLY',
                    'Total_Amount'=>$data['Cheque']['Cheque_Amount'],
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>$addToEwallet,
                    'Ewallet_Applied'=>0,
                    'Cash'=>$data['Amounts']['Cash_Amount'],
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                ],201);
            }
            // Consumer Pays Cheque With Ewallet
            else if(isset($data['Cheque']['Cheque_Amount']) && isset($data['Amounts']['E_Wallet'])
            && !isset($data['Amounts']['Cash_Amount']))
            {
                $eWalletID = $data['Consumer']['ew_id'];
                $eWallet = $data['Amounts']['E_Wallet'];
                // $eWalletTAmount = $data['Consumer']['ew_total_amount'] - $eWallet;
                $cheque = $data['Cheque']['Cheque_Amount'];
                $totalPaid = $eWallet + $cheque;
                // $ewalletBalance = $totalPaid - $amountTBpaid;
                $partial = $totalPaid;
                //check if sales are Arrear
                $arrear = DB::table('meter_reg')
                    ->orderBy('mr_date_year_month','desc')
                    ->where('cm_id',$consID)
                    ->first();
                //Insert Cheque First to get the ID  
                $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
                $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'],
                $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
                //IF PB is included in Transaction
                if(isset($data['PB']))
                {
                    $collection = collect($data['PB']);
                    //Replacing (-)sign on Dates of data['PB']
                    $dataModed = $collection->map(function ($item, $key)
                    {
                        return str_replace("-","",$item);
                    });
                    //sorting to get the latest Date from the arrays
                    $setDateMrid = $dataModed
                        ->sortByDesc('mr_date_reg')
                        ->first();
                    $latestDateMrid = $setDateMrid['mr_id'];
                    $datas = $data['PB'];
                    
                    foreach($datas as $key =>$value)
                    {
                        //ping the server if online(0)
                        exec("ping -n 1 " .$host->ip_address, $response, $result);
                        if($result == 0)
                        {
                            //for Sales-
                            $sales = new Sales;
                            $sales->mr_id = $value['mr_id'];
                            $sales->s_bill_date = $current_date_time;
                            $sales->ct_id =$consTypeID;
                            $sales->cm_id = $consID;
                            $sales->s_bill_no = intval($value['mr_billno']);
                            $sales->s_status = 0;
                            $sales->s_bill_amount = $value['mr_amount'];
                            $sales->s_or_num = $orNo;
                            $sales->cheque_id = $chequeID;
                            $sales->teller_user_id = $tellerID;
                            $sales->s_mode_payment = 'Cheque And E-Wallet';
                            $sales->s_bill_date_time = Carbon::now()->toTimeString();
                            $sales->e_wallet_applied = $eWallet;
                            //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                            if($value['mr_id'] == $arrear->mr_id)
                            {
                                $sales->mr_arrear = "Y";
                            }
                            else
                            {
                                $sales->mr_arrear = "N";
                            }
                            // Check if meter_reg is fully paid (+-)
                            $checkMRFPaid = MeterReg::find($value['mr_id']);
                            $newPartial = $checkMRFPaid->mr_partial + $partial;
                            // dd($newPartial);
                            if($newPartial > $amountTBpaid){
                                $addToEwallet = $newPartial - $amountTBpaid;
                                $this->addEwallet($eWalletID,$addToEwallet);
                                $sales->e_wallet_added = $addToEwallet;
                                $sales->s_or_amount = $cheque - $addToEwallet;
                                $this->storeEWalletLogUnposted($eWalletID,$addToEwallet,$orNo,$current_date_time);

                            }else{
                                $sales->s_or_amount = $cheque;
                            }
                            
                            $sales->save();
                            $this->updateBMBillStatus($value['mr_id'],$partial);
                            $this->updateEwallet($eWalletID,$eWallet);
                            $this->storeEWLogApplied($eWalletID,$eWallet,$orNo,$current_date_time);
                            $partialAmount = DB::table('meter_reg')
                            ->where('cm_id',$consID)
                            ->where('mr_id',$value['mr_id'])
                            ->sum('mr_partial');
                        }else{
                            return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                        }
                        
                    }
                }

                $totaArrears = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_amount');
                if(!$totaArrears)
                {
                    $totaArrears = 0;
                }else{
                    $getPartial = DB::table('meter_reg')
                    ->where('cm_id',$consID)
                    ->where('mr_status',0)
                    ->where('mr_printed',1)
                    ->sum('mr_partial');

                    $totaArrears = round($totaArrears - $getPartial,2);
                }

                $totalAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('cm_id',$consID)
                        ->where('s_or_num',$orNo)
                        ->first()
                );

                return response([
                    'Msg'=> 'Succesfully Paid CASH&EWALLET',
                    'Total_Amount'=>$totalAmount->values()->all(),
                    'Details'=>$data,
                    'Partial'=>$partialAmount,
                    'Ewallet_Added'=>0,
                    'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                    'Date_Paid'=>$current_date_time,
                    'Total_Arrears_Amount'=>$totaArrears
                ],201);
            }
        }
        
        /* ---------------------------------------------------------( Normal Transaction )------------------------------------------------------------------------ */
        /* ---------------------------------------------------------( Normal Transaction )------------------------------------------------------------------------ */
        
        //Consumer Pays Cash with E-wallet
        if(isset($data['Amounts']['E_Wallet']) && isset($data['Amounts']['Cash_Amount']) 
            && !isset($data['Cheque']['Cheque_Amount']) && ($data['Amounts']['E_Wallet'] <= $data['Consumer']['ew_total_amount']))
        {
            $eWalletID = $data['Consumer']['ew_id'];
            $eWallet = $data['Amounts']['E_Wallet'];
            $eWalletTAmount = $data['Consumer']['ew_total_amount'] - $eWallet;
            $cash = $data['Amounts']['Cash_Amount'];
            $totalPaid = round($eWallet + $cash,2);
            $ewalletBalance = $totalPaid - $amountTBpaid;
            if($totalPaid < $amountTBpaid)
            {
                return response(['Message'=>'Need Full Payment'],422);
                // return response(['Message'=>$amountTBpaid.'<=type=>'.getType($amountTBpaid).' *** '.$totalPaid.'<=type=>'.getType($amountTBpaid)],422);
            }
            //check if sales are Arrear
            $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
            //IF PB is included in Transaction
            if(isset($data['PB']))
            {
                // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Cash And E-Wallet';
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        $tbAppAmount = $eWallet;
                        if($tbAppAmount >= $value['mr_amount'])
                        {
                            $newTBApp = $tbAppAmount - $value['mr_amount'];
                            $sales->s_or_amount = 0;
                            $sales->e_wallet_applied = $value['mr_amount'];
                            $this->updateEwallet($eWalletID,$value['mr_amount']);
                            $this->storeEWLogApplied($eWalletID,$value['mr_amount'],$orNo,$current_date_time);
                        }
                        else
                        {
                            $newTBApp = $tbAppAmount;
                            if($newTBApp <= $value['mr_amount'])
                            {
                                $temp = $value['mr_amount'] - $newTBApp;
                                $sales->s_or_amount = $temp;
                                $sales->e_wallet_applied = $newTBApp;
                                if($newTBApp != 0){
                                    $this->updateEwallet($eWalletID,$newTBApp);
                                    $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                                }
                                $newTBApp = 0;
                            }
                            else
                            {
                                $sales->s_or_amount = $value['mr_amount'] - $newTBApp;
                                $sales->e_wallet_applied = $newTBApp;
                                if($newTBApp != 0){
                                    $this->updateEwallet($eWalletID,$newTBApp);
                                    $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                                }
                            }
                            
                        }
                        $sales->save();
                        $eWallet = $newTBApp;
                        $nbRem = $eWallet;
                        $this->updateBillStatus($value['mr_id']);
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                    
                }
            }
           
            // If Non-Bill is included in transaction
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->teller_user_id = $tellerID;
                        $nbs->s_mode_payment = 'Cash And E-Wallet';
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']))
                        {
                            $newNBRem = $eWallet;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }
                                
                            }
                        }
                        else
                        {
                            $newNBRem = $nbRem;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }   
                            }
                        }
                        $nbs->save();
                        if(isset($data['NB']) && !isset($data['PB'])){
                            $eWallet =$newTBAppNB;
                        }else{
                            $nbRem = $newTBAppNB;
                        }
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }

            if(isset($data['ewallet_credit'])){
                $ewc = new Sales;
                $ewc->ct_id = $consTypeID;
                $ewc->cm_id = $consID;
                $ewc->s_or_num = $orNo;
                $ewc->s_bill_amount = $data['ewallet_credit']['ewc'];
                $ewc->s_or_amount = $data['ewallet_credit']['ewc'];
                $ewc->teller_user_id = $tellerID;
                $ewc->s_mode_payment = 'Cash And E-Wallet';
                $ewc->s_bill_date = $current_date_time;
                $ewc->s_status = 1;
                $ewc->s_bill_date_time = Carbon::now()->toTimeString();
                $ewc->save();
                
                $this->addEwallet($eWalletID,$data['ewallet_credit']['ewc']);
                $this->storeEWalletLogUnposted($eWalletID,$data['ewallet_credit']['ewc'],$orNo,$current_date_time);

            }
            

            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->where('mr_printed',1)
                ->sum('mr_amount');
            if(!$totaArrears)
            {
                $totaArrears = 0;
            }

            $totalAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('cm_id',$consID)
                    ->where('s_or_num',$orNo)
                    ->first()
            );

            return response([
                'Msg'=> 'Succesfully Paid CASH&EWALLET',
                'Total_Amount'=>$totalAmount->values()->all(),
                'Details'=>$data,
                'Ewallet_Added'=>0,
                'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            ],201);
            
        }
        // Consumer Pays Cash Only ---------------------------------------------------------------------------------------------------
        else if(isset($data['Amounts']['Cash_Amount']) && !isset($data['Amounts']['E_Wallet']) 
            && !isset($data['Cheque']['Cheque_Amount']))
        {
            //Consumer Detail
            $eWalletID = $data['Consumer']['ew_id'];
            //Ewallet Bal
            $consEwallet = $data['Consumer']['ew_total_amount'];
            // Amounts Details
            $cash = round($data['Amounts']['Cash_Amount'],2);
            $amountChange = round($data['Amounts']['Cash_Amount'] - $amountTBpaid, 2);
            $getChange = $data['Amounts']['getChange'];
            
            if($cash < $amountTBpaid)
            {
                return response(['Message'=>"NEED FULL PAYMENT"],422);
            }
            //check if sales are Arrear
            $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
            
            // ALL PB data
            if(isset($data['PB']))
            {
                 // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                //dd($consTypeID);
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->s_or_amount = $value['mr_amount'];
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Cash';
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        //If there is still a change and consumer wants to add change to ewallet, looks for latest date and add ewallet to the row
                        if($amountChange > 0 && $getChange == 'yes' && $latestDateMrid == $value['mr_id'])
                        {
                            $sales->e_wallet_added = $amountChange;
                            $this->addEwallet($eWalletID,$amountChange);
                            $this->storeEWalletLogUnposted($eWalletID,$amountChange,$orNo,$current_date_time);
                        }
                        $sales->save();
                        $this->updateBillStatus($value['mr_id']);
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->s_or_amount = $value['Fee_Amount'];
                        $nbs->teller_user_id = $tellerID;
                        $nbs->s_mode_payment = 'Cash';
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']) && $amountChange > 0 && $getChange == 'yes')
                        {
                            if($key === array_key_last($dataNBs)) {
                                $nbs->e_wallet_added = $amountChange;
                                $this->addEwallet($eWalletID,$amountChange);
                                $this->storeEWalletLogUnposted($eWalletID,$amountChange,$orNo,$current_date_time);
                            }
                        }
                        $nbs->save();
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }
            
            if(isset($data['ewallet_credit'])){
                $ewc = new Sales;
                $ewc->ct_id = $consTypeID;
                $ewc->cm_id = $consID;
                $ewc->s_or_num = $orNo;
                $ewc->s_bill_amount = $data['ewallet_credit']['ewc'];
                $ewc->s_or_amount = $data['ewallet_credit']['ewc'];
                $ewc->teller_user_id = $tellerID;
                $ewc->s_mode_payment = 'Cash';
                $ewc->s_bill_date = $current_date_time;
                $ewc->s_status = 1;
                $ewc->s_bill_date_time = Carbon::now()->toTimeString();
                $ewc->save();
                
                $this->addEwallet($eWalletID,$data['ewallet_credit']['ewc']);
                $this->storeEWalletLogUnposted($eWalletID,$data['ewallet_credit']['ewc'],$orNo,$current_date_time);

            }

            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->where('mr_printed',1)
                ->sum('mr_amount');
            if(!$totaArrears)
            {
                $totaArrears = 0;
            }

            $totalAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('cm_id',$consID)
                    ->where('s_or_num',$orNo)
                    ->first()
            );

            return response([
                'Msg'=> 'Succesfully Paid CASH ONLY NO CHANGE',
                'Total_Amount'=>$totalAmount->values()->all(),
                'Details'=>$data,
                'Ewallet_Added'=>$amountChange,
                'Ewallet_Applied'=>0,
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            ],201);
        }
        // Consumer Pays Ewallet Only -------------------------------------------------------------------------------------------------
        else if(isset($data['Amounts']['E_Wallet']) && !isset($data['Amounts']['Cash_Amount']) 
            && !isset($data['Cheque']['Cheque_Amount']))
        {
            //Consumer Detail
            $eWalletID = $data['Consumer']['ew_id'];
            //Consumer Ewallet 
            $eWallet = $data['Amounts']['E_Wallet'];
            $consEwallet = $data['Consumer']['ew_total_amount'];
            $consEWRemainBal = $consEwallet - $data['Amounts']['E_Wallet'];
            $consPaidEw = round($data['Amounts']['E_Wallet'],2);
            if($consPaidEw < $amountTBpaid)
            {
                return response(['Message'=>"NEED FULL PAYMENT"],422);
            }
            //check if sales are Arrear
            $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
            //ALL PB data
            if(isset($data['PB']))
            {
                 // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Ewallet';
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        $tbAppAmount = $consPaidEw;
                        if($tbAppAmount >= $value['mr_amount'])
                        {
                            
                            $newTBApp = $tbAppAmount - $value['mr_amount'];
                            $sales->s_or_amount = 0;
                            $sales->e_wallet_applied = $value['mr_amount'];
                            $this->updateEwallet($eWalletID,$value['mr_amount']);
                            $this->storeEWLogApplied($eWalletID,$value['mr_amount'],$orNo,$current_date_time);
                        }
                        else
                        {
                            $newTBApp = $tbAppAmount;
                            if($newTBApp <= $value['mr_amount'])
                            {
                                $temp = $value['mr_amount'] - $newTBApp;
                                $sales->s_or_amount = $temp;
                                $sales->e_wallet_applied = $newTBApp;
                                if($newTBApp != 0){
                                    $this->updateEwallet($eWalletID,$newTBApp);
                                    $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                                }
                                $newTBApp = 0;
                            }
                            else
                            {
                                $sales->s_or_amount = $value['mr_amount'] - $newTBApp;
                                $sales->e_wallet_applied = $newTBApp;
                                $this->updateEwallet($eWalletID,$newTBApp);
                                $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                            }
                            
                        }
                        $sales->save();
                        $this->updateBillStatus($value['mr_id']);
                        $eWallet = $newTBApp;
                        $nbRem = $eWallet;
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->s_or_amount = $value['Fee_Amount'];
                        $nbs->teller_user_id = $tellerID;
                        $nbs->s_mode_payment = 'Ewallet';
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']))
                        {
                            $newNBRem = $eWallet;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }
                                
                            }
                        }
                        else
                        {
                            $newNBRem = $nbRem;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }
                            }
                        }
                        $nbs->save();
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                    
                    if(isset($data['NB']) && !isset($data['PB'])){
                        $eWallet =$newTBAppNB;
                    }else{
                        $nbRem = $newTBAppNB;
                    }
                }
            }

            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->where('mr_printed',1)
                ->sum('mr_amount');
            if(!$totaArrears)
            {
                $totaArrears = 0;
            }

            $totalAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('cm_id',$consID)
                    ->where('s_or_num',$orNo)
                    ->first()
            );

            return response([
                'Msg'=> 'Succesfully Paid EWALLET ONLY',
                'Total_Amount'=>$totalAmount->values()->all(),
                'Details'=>$data,
                'Ewallet_Added'=>0,
                'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            
            ],201);
        }
        // Consumer Pays Cheque Only
        else if(isset($data['Cheque']['Cheque_Amount']) && !isset($data['Amounts']['E_Wallet']) 
            && !isset($data['Amounts']['Cash_Amount']))
        {
            //Consumer Detail
            $eWalletID = $data['Consumer']['ew_id'];
            //Consumer Ewallet 
            $consEwallet = $data['Consumer']['ew_total_amount'];
            $cheque = round($data['Cheque']['Cheque_Amount'],2);
            $chequeChange = round($cheque - $amountTBpaid,2);
            if($cheque < $amountTBpaid)
            {
                return response(["Message"=>"NEED FULL PAYMENT"],422);
            }
            //check if sales are Arrear
            $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
            //Insert Cheque First to get the ID  
            $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
            $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'] ,
            $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
            //ALL PB data
            if(isset($data['PB']))
            {
                 // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->s_or_amount = $value['mr_amount'];
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Cheque';
                        $sales->cheque_id = $chequeID;
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        if($chequeChange > 0 && $latestDateMrid == $value['mr_id'])
                        {
                            $sales->e_wallet_added = $chequeChange;
                            $this->addEwallet($eWalletID,$chequeChange);
                            $this->storeEWalletLogUnposted($eWalletID,$chequeChange,$orNo,$current_date_time);
                        }
                        $sales->save();
                        $this->updateBillStatus($value['mr_id']);
                        
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->s_or_amount = $value['Fee_Amount'];
                        $nbs->teller_user_id = $tellerID;
                        $nbs->s_mode_payment = 'Cheque';
                        $nbs->cheque_id = $chequeID;
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']) && $chequeChange > 0)
                        {
                            if($key === array_key_last($dataNBs)) {
                                $nbs->e_wallet_added = $chequeChange;
                                $this->addEwallet($eWalletID,$chequeChange);
                                $this->storeEWalletLogUnposted($eWalletID,$chequeChange,$orNo,$current_date_time);
                            }
                        }
                        $nbs->save();
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }

            if(isset($data['ewallet_credit'])){
                $ewc = new Sales;
                $ewc->ct_id = $consTypeID;
                $ewc->cm_id = $consID;
                $ewc->s_or_num = $orNo;
                $ewc->s_bill_amount = $data['ewallet_credit']['ewc'];
                $ewc->s_or_amount = $data['ewallet_credit']['ewc'];
                $ewc->teller_user_id = $tellerID;
                $ewc->s_mode_payment = 'Cheque';
                $ewc->s_status = 1;
                $ewc->s_bill_date = $current_date_time;
                $ewc->s_bill_date_time = Carbon::now()->toTimeString();
                $ewc->save();
                
                $this->addEwallet($eWalletID,$data['ewallet_credit']['ewc']);
                $this->storeEWalletLogUnposted($eWalletID,$data['ewallet_credit']['ewc'],$orNo,$current_date_time);

            }
            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->sum('mr_amount');
                
            return response([
                'Msg'=> 'Succesfully Paid Cheque ONLY',
                'Total_Amount'=>$cheque,
                'Details'=>$data,
                'Ewallet_Added'=>$chequeChange,
                'Ewallet_Applied'=>0,
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            ],201);
        }
        // Consumer Pays Cheque With Cash
        else if(isset($data['Cheque']['Cheque_Amount']) && !isset($data['Amounts']['E_Wallet'])
            && isset($data['Amounts']['Cash_Amount']))
        {
             //Consumer Detail
             $eWalletID = $data['Consumer']['ew_id'];
             //Consumer Ewallet 
             
             $consEwallet = $data['Consumer']['ew_total_amount'];
             //added cash to cheque
             $cheque = round($data['Cheque']['Cheque_Amount'] + $data['Amounts']['Cash_Amount'],2);
             $chequeChange = $cheque - $amountTBpaid;
             if($cheque < $amountTBpaid)
             {
                 return response(["Message"=>"NEED FULL PAYMENT"],422);
             }
             
             //check if sales are Arrear
            $arrear = DB::table('meter_reg')
            ->orderBy('mr_date_year_month','desc')
            ->where('cm_id',$consID)
            ->first();
            //Insert Cheque First to get the ID  
            $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
            $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'] ,
            $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
            //ALL PB data
            if(isset($data['PB']))
            {
                 // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->s_or_amount = $value['mr_amount'];
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Cheque And Cash';
                        $sales->cheque_id = $chequeID;
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        if($chequeChange > 0 && $latestDateMrid == $value['mr_id'])
                        {
                            $sales->e_wallet_added = $chequeChange;
                            $this->addEwallet($eWalletID,$chequeChange);
                            $this->storeEWalletLogUnposted($eWalletID,$chequeChange,$orNo,$current_date_time);
                        }
                        $sales->save();
                        $this->updateBillStatus($value['mr_id']);
                        
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->s_or_amount = $value['Fee_Amount'];
                        $nbs->teller_user_id = $tellerID;
                        $nbs->s_mode_payment = 'Cheque And Cash';
                        $nbs->cheque_id = $chequeID;
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']) && $chequeChange > 0)
                        {
                            if($key === array_key_last($dataNBs)) {
                                $nbs->e_wallet_added = $chequeChange;
                                $this->addEwallet($eWalletID,$chequeChange);
                                $this->storeEWalletLogUnposted($eWalletID,$chequeChange,$orNo,$current_date_time);
                            }
                        }
                        $nbs->save();
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }

            if(isset($data['ewallet_credit'])){
                $ewc = new Sales;
                $ewc->ct_id = $consTypeID;
                $ewc->cm_id = $consID;
                $ewc->s_or_num = $orNo;
                $ewc->s_bill_amount = $data['ewallet_credit']['ewc'];
                $ewc->s_or_amount = $data['ewallet_credit']['ewc'];
                $ewc->teller_user_id = $tellerID;
                $ewc->s_mode_payment = 'Cheque And Cash';
                $ewc->s_bill_date = $current_date_time;
                $ewc->s_status = 1;
                $ewc->s_bill_date_time = Carbon::now()->toTimeString();
                $ewc->save();
                
                $this->addEwallet($eWalletID,$data['ewallet_credit']['ewc']);
                $this->storeEWalletLogUnposted($eWalletID,$data['ewallet_credit']['ewc'],$orNo,$current_date_time);

            }

            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->sum('mr_amount');
                
            return response([
                'Msg'=> 'Succesfully Paid Cheque & Cash ONLY',
                'Total_Amount'=>$data['Cheque']['Cheque_Amount'],
                'Details'=>$data,
                'Ewallet_Added'=>$chequeChange,
                'Ewallet_Applied'=>0,
                'Cash'=>$data['Amounts']['Cash_Amount'],
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            ],201);
        }
        // Consumer Pays Cheque With Ewallet
        else if(isset($data['Cheque']['Cheque_Amount']) && isset($data['Amounts']['E_Wallet'])
            && !isset($data['Amounts']['Cash_Amount']))
        {
            $eWalletID = $data['Consumer']['ew_id'];
            $eWallet = $data['Amounts']['E_Wallet'];
            // $eWalletTAmount = $data['Consumer']['ew_total_amount'] - $eWallet;
            $cheque = $data['Cheque']['Cheque_Amount'];
            $totalPaid = round($eWallet + $cheque,2);
            // $ewalletBalance = $totalPaid - $amountTBpaid;
            if($totalPaid < $amountTBpaid)
            {
                return response(['Message'=>'Need Full Payment'],422);
                // return response(['Message'=>$amountTBpaid.'<=type=>'.getType($amountTBpaid).' *** '.$totalPaid.'<=type=>'.getType($amountTBpaid)],422);
            }
            //check if sales are Arrear
            $arrear = DB::table('meter_reg')
                ->orderBy('mr_date_year_month','desc')
                ->where('cm_id',$consID)
                ->first();
            //Insert Cheque First to get the ID  
            $chequeID =  $this->storeCheque($data['Cheque']['Cheque_Amount'],$data['Cheque']['Cheque_Bank'],
            $data['Cheque']['Cheque_Bank_Branch'],$data['Cheque']['Cheque_Bank_Acc'],$data['Cheque']['Cheque_Acc_Name'],
            $data['Cheque']['Cheque_No'],$orNo,$current_date_time);
            //IF PB is included in Transaction
            if(isset($data['PB']))
            {
                // Validate Power Bill ID -> check if it is already paid(based on mr_id of sales table). if one ID exist, transaction will fail.
                request()->validate([
                    'PB.*.mr_id' => 'required|unique:sales,mr_id'
                ]);
                $collection = collect($data['PB']);
                //Replacing (-)sign on Dates of data['PB']
                $dataModed = $collection->map(function ($item, $key)
                {
                    return str_replace("-","",$item);
                });
                //sorting to get the latest Date from the arrays
                $setDateMrid = $dataModed
                    ->sortByDesc('mr_date_reg')
                    ->first();
                $latestDateMrid = $setDateMrid['mr_id'];
                $datas = $data['PB'];
                
                foreach($datas as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        //for Sales-
                        $sales = new Sales;
                        $sales->mr_id = $value['mr_id'];
                        $sales->s_bill_date = $current_date_time;
                        $sales->ct_id =$consTypeID;
                        $sales->cm_id = $consID;
                        $sales->s_bill_no = intval($value['mr_billno']);
                        $sales->s_status = 0;
                        $sales->s_bill_amount = $value['mr_amount'];
                        $sales->s_or_num = $orNo;
                        $sales->cheque_id = $chequeID;
                        $sales->teller_user_id = $tellerID;
                        $sales->s_mode_payment = 'Cheque And E-Wallet';
                        $sales->s_bill_date_time = Carbon::now()->toTimeString();
                        //$arrear = Latest PowerBill, if true sales.mr_arrear is set to Y else N
                        if($value['mr_id'] == $arrear->mr_id)
                        {
                            $sales->mr_arrear = "Y";
                        }
                        else
                        {
                            $sales->mr_arrear = "N";
                        }
                        $tbAppAmount = $eWallet;
                        if($tbAppAmount >= $value['mr_amount'])
                        {
                            $newTBApp = $tbAppAmount - $value['mr_amount'];
                            $sales->s_or_amount = 0;
                            $sales->e_wallet_applied = $value['mr_amount'];
                            $this->updateEwallet($eWalletID,$value['mr_amount']);
                            $this->storeEWLogApplied($eWalletID,$value['mr_amount'],$orNo,$current_date_time);
                        }
                        else
                        {
                            $newTBApp = $tbAppAmount;
                            if($newTBApp <= $value['mr_amount'])
                            {
                                $temp = $value['mr_amount'] - $newTBApp;
                                $sales->s_or_amount = $temp;
                                $sales->e_wallet_applied = $newTBApp;
                                if($newTBApp != 0){
                                    $this->updateEwallet($eWalletID,$newTBApp);
                                    $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                                }
                                $newTBApp = 0;
                            }
                            else
                            {
                                $sales->s_or_amount = $value['mr_amount'] - $newTBApp;
                                $sales->e_wallet_applied = $newTBApp;
                                if($newTBApp != 0){
                                    $this->updateEwallet($eWalletID,$newTBApp);
                                    $this->storeEWLogApplied($eWalletID,$newTBApp,$orNo,$current_date_time);
                                }
                            }
                            
                        }
                        $sales->save();
                        $eWallet = $newTBApp;
                        $nbRem = $eWallet;
                        $this->updateBillStatus($value['mr_id']);
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                    
                }
            }
           
            // If Non-Bill is included in transaction
            if(isset($data['NB']))
            {
                $dataNBs = $data['NB'];
                foreach($dataNBs as $key =>$value)
                {
                    //ping the server if online(0)
                    exec("ping -n 1 " .$host->ip_address, $response, $result);
                    if($result == 0)
                    {
                        $nbs = new Sales;
                        $nbs->ct_id = $consTypeID;
                        $nbs->cm_id = $consID;
                        $nbs->s_or_num = $orNo;
                        $nbs->f_id = $value['Fee_ID'];
                        $nbs->s_bill_no = 0;
                        $nbs->s_bill_amount = $value['Fee_Amount'];
                        $nbs->s_bill_date = $current_date_time;
                        $nbs->teller_user_id = $tellerID;
                        $nbs->cheque_id = $chequeID;
                        $nbs->s_mode_payment = 'Cheque And E-Wallet';
                        $nbs->s_bill_date_time = Carbon::now()->toTimeString();
                        if(!isset($data['PB']))
                        {
                            $newNBRem = $eWallet;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }
                                
                            }
                        }
                        else
                        {
                            $newNBRem = $nbRem;
                            if($newNBRem >= $value['Fee_Amount'])
                            {
                                $newTBAppNB = $newNBRem - $value['Fee_Amount'];
                                $nbs->s_or_amount = 0;
                                $nbs->e_wallet_applied = $value['Fee_Amount'];
                                $this->updateEwallet($eWalletID,$value['Fee_Amount']);
                                $this->storeEWLogApplied($eWalletID,$value['Fee_Amount'],$orNo,$current_date_time);
                            }
                            else
                            {
                                $newTBAppNB = $newNBRem;
                                if($newTBAppNB <= $value['Fee_Amount'])
                                {
                                    $temp = $value['Fee_Amount'] - $newTBAppNB;
                                    $nbs->s_or_amount = $temp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    if($newTBAppNB != 0){
                                        $this->updateEwallet($eWalletID,$newTBAppNB);
                                        $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                    }
                                    $newTBAppNB = 0;
                                }
                                else
                                {
                                    $nbs->s_or_amount = $value['Fee_Amount'] - $newTBApp;
                                    $nbs->e_wallet_applied = $newTBAppNB;
                                    $this->updateEwallet($eWalletID,$newTBAppNB);
                                    $this->storeEWLogApplied($eWalletID,$newTBAppNB,$orNo,$current_date_time);
                                }   
                            }
                        }
                        $nbs->save();
                        if(isset($data['NB']) && !isset($data['PB'])){
                            $eWallet =$newTBAppNB;
                        }else{
                            $nbRem = $newTBAppNB;
                        }
                    }else{
                        return response(['Message'=>'Connection Error! No Response From the Server, Please Try again Later'],500);
                    }
                }
            }

            if(isset($data['ewallet_credit'])){
                $ewc = new Sales;
                $ewc->ct_id = $consTypeID;
                $ewc->cm_id = $consID;
                $ewc->s_or_num = $orNo;
                $ewc->s_bill_amount = $data['ewallet_credit']['ewc'];
                $ewc->s_or_amount = $data['ewallet_credit']['ewc'];
                $ewc->teller_user_id = $tellerID;
                $ewc->s_mode_payment = 'Cheque And E-Wallet';
                $ewc->s_status = 1;
                $ewc->s_bill_date = $current_date_time;
                $ewc->s_bill_date_time = Carbon::now()->toTimeString();
                $ewc->save();
                
                $this->addEwallet($eWalletID,$data['ewallet_credit']['ewc']);
                $this->storeEWalletLogUnposted($eWalletID,$data['ewallet_credit']['ewc'],$orNo,$current_date_time);

            }

            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$consID)
                ->where('mr_status',0)
                ->where('mr_printed',1)
                ->sum('mr_amount');
            if(!$totaArrears)
            {
                $totaArrears = 0;
            }

            $totalAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('cm_id',$consID)
                    ->where('s_or_num',$orNo)
                    ->first()
            );

            return response([
                'Msg'=> 'Succesfully Paid CASH&EWALLET',
                'Total_Amount'=>$totalAmount->values()->all(),
                'Details'=>$data,
                'Ewallet_Added'=>0,
                'Ewallet_Applied'=>$data['Amounts']['E_Wallet'],
                'Date_Paid'=>$current_date_time,
                'Total_Arrears_Amount'=>$totaArrears
            ],201);
        }
        return response(['Msg'=>'ERROR CASH/EWALLET/CHEQUE CALL ADMIN']);
        
    }

    public function showVoidAmount(Request $request)
    {
        $showCollectionAmount = DB::table('sales')
            ->where('s_or_num',$request->s_or) 
            ->where('s_bill_date',$request->s_bill_date)
            ->where('cm_id',$request->cm_id)
            ->whereNull('deleted_at')
            ->sum('s_or_amount');
        if(!$showCollectionAmount)
        {
            $showCollectionAmount2 = DB::table('sales')
            ->where('s_or_num',$request->s_or) 
            ->where('s_bill_date',$request->s_bill_date)
            ->where('cm_id',$request->cm_id)
            ->whereNull('deleted_at')
            ->sum('e_wallet_applied');

            if(!$showCollectionAmount2)
            {
                return response(['Message'=>"Collection Doesnt Exist"],422);
            }

            $showCollectionAmount = $showCollectionAmount2;
        }

        return response(['Total_Collection'=>$showCollectionAmount],200);
    }
    public function voidOR(Request $request)
    {
        $orDetails = collect(DB::table('sales')
            ->select('s_id','s_status','s_or_num','s_bill_date','cm_id','mr_id','ackn_date')
            ->where('s_or_num',$request->s_or)
            ->whereDate('s_bill_date',$request->s_bill_date)
            ->whereNull('deleted_at')
            ->first());
        if($orDetails->isEmpty())
        {
            return response(['MSG'=>"Official Receipt Number Doesnt Exist"],422);
        }
        if($orDetails['ackn_date'] != NULL)
        {
            return response(['Message'=>"Posted Collection Cannot be Void"],422);
        }
        /* ----------------------------------------------------------------------------------------------*/
        
        $current_date_time = Carbon::now()->toDateTimeString();
        //GET PAID POWER BILL ID
        $powerBillID = DB::table('sales')
        // ->select('mr_id','s_bill_no','s_or_amount','teller_user_id','e_wallet_added','ct_id')
        ->where('s_or_num',$request->s_or)
        // ->where('s_status',0)
        ->where('cm_id',$request->cm_id)
        ->whereNotNull('mr_id')
        ->whereNull('deleted_at')
        ->get();

        //GET PAID Non BILL
        $nonBill = DB::table('sales')
        ->select('f_id','s_bill_no','s_or_amount','teller_user_id','e_wallet_added')
        ->where('s_or_num',$request->s_or)
        ->where('cm_id',$request->cm_id)
        ->whereNull('mr_id')
        ->whereNotNull('f_id')
        ->whereNull('deleted_at')
        ->get();

        $depositBill = DB::table('sales')
        ->select('f_id','s_bill_no','s_or_amount','teller_user_id','e_wallet_added')
        ->where('s_or_num',$request->s_or)
        ->where('cm_id',$request->cm_id)
        ->where('s_mode_payment','Deposit_Ewallet')
        ->whereNull('deleted_at')
        ->get();

        if($powerBillID->isNotEmpty())
        {
            //Revert Changes to Not paid Power BIll
            foreach($powerBillID as $pbID=>$value)
            {
                if($value->ct_id == 3){
                    $mtrReg = MeterReg::find($value->mr_id);
                    $mtrReg->mr_partial = $mtrReg->mr_partial - $value->s_or_amount - (($value->e_wallet_applied == NULL) ? 0 : $value->e_wallet_applied);
                    $mtrReg->mr_status = 0;
                    $mtrReg->save();
                    // MeterReg::where('mr_id',$value->mr_id)
                    // ->where('cm_id',$orDetails['cm_id'])
                    // ->whereNotNull('mr_id')
                    // ->update([
                    //     'mr_status' => 0,
                    //     'mr_partial' => $value->s_or_amount + $value->e_wallet_added
                    // ]);

                }else{
                    MeterReg::where('mr_id',$value->mr_id)
                    ->where('cm_id',$orDetails['cm_id'])
                    ->whereNotNull('mr_id')
                    ->update([
                        'mr_status' => 0
                    ]);
                }
                

                //Create Voided Data on OR_Void Table
                $data = new Or_Void();
                $data->v_or = $orDetails['s_or_num'];
                $data->mr_id = $value->mr_id;
                $data->cm_id = $request->cm_id;
                $data->v_bill_num = $value->s_bill_no;
                $data->v_sale_amount = $value->s_or_amount + $value->e_wallet_added;
                $data->v_remark = $request->remark;
                $data->v_user = $value->teller_user_id;
                $data->v_date = $current_date_time;
                $data->save();
            }
        }
        if($nonBill->isNotEmpty())
        {
            //GET PAID Non BILL
            $nonBill = DB::table('sales')
            ->select('f_id','s_bill_no','s_or_amount','teller_user_id')
            ->where('s_or_num',$request->s_or)
            ->where('s.cm_id',$request->cm_id)
            ->whereNull('mr_id')
            ->whereNull('deleted_at')
            ->get();
            
            foreach($nonBill as $nbID=>$value1)
            {
                //Create Voided Data on OR_Void Table
                $data2 = new Or_Void();
                $data2->v_or = $orDetails['s_or_num'];
                $data2->mr_id = $value1->f_id;
                $data2->cm_id = $request->cm_id;
                $data2->v_sale_amount = $value1->s_or_amount + $value1->e_wallet_added;
                $data2->v_remark = $request->remark;
                $data2->v_user = $value1->teller_user_id;
                $data2->v_date = $current_date_time;
                $data2->save();
            }
        }
        if($depositBill->isNotEmpty()){
            foreach($depositBill as $nbID=>$value2)
            {
                //Create Voided Data on OR_Void Table
                $data3 = new Or_Void();
                $data3->v_or = $orDetails['s_or_num'];
                // $data3->f_id = $value1->f_id;
                $data3->v_sale_amount = $value2->s_or_amount + $value2->e_wallet_added;
                $data3->cm_id = $request->cm_id;
                $data3->v_remark = $request->remark;
                $data3->v_user = $value2->teller_user_id;
                $data3->v_date = $current_date_time;
                $data3->save();
            }
        }
        // select the Applied and Added Ewallet (To Revert Changes)
        $ewalletTransactApplied = collect(DB::table('sales')
            ->select(DB::raw('sum(e_wallet_applied) as e_wallet_applied'))
            ->where('s_or_num',$orDetails['s_or_num'])
            ->Where('e_wallet_applied','!=',0)
            ->where('cm_id',$orDetails['cm_id'])
            ->Where('e_wallet_applied','!=','')
            ->first());
        $ewalletTransactAdded = collect(DB::table('sales')
            ->select(DB::raw('sum(e_wallet_added) as e_wallet_added'))
            ->where('s_or_num',$orDetails['s_or_num'])
            ->Where('e_wallet_added','!=',0)
            ->where('cm_id',$orDetails['cm_id'])
            ->Where('e_wallet_added','!=','')
            ->first());
        
        $ewApplied = isset($ewalletTransactApplied['e_wallet_applied'])? $ewalletTransactApplied['e_wallet_applied'] : 0;
        $ewAdded = isset($ewalletTransactAdded['e_wallet_added'])? $ewalletTransactAdded['e_wallet_added'] : 0;
        //GET CURRENT E WALLET TOTAL AMOUNT
        $ewallet = collect(DB::table('e_wallet')
            ->where('cm_id',$orDetails['cm_id'])
            ->first());

        //ADD APPLIED EWALLET TO EWALLET TOTAL AMOUNT(revert changes)
        if($ewApplied != 0)
        {
            $newWalletTotal = $ewallet['ew_total_amount'] + $ewApplied;
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal
            ]);

            EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            ->where('ewl_status','A')
            ->where('ew_id',$ewallet['ew_id'])
            ->update([
                'deleted_at' => $current_date_time,
            ]);
        }
        
        //DEDUCT ADDED EWALLET TO EWALLET TOTAL AMOUNT(revert changes)
        if($ewAdded != 0)
        {
            $newWalletTotal2 = $ewallet['ew_total_amount'] - $ewAdded;
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal2
            ]);

            EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            ->where('ewl_status','U')
            ->where('ew_id',$ewallet['ew_id'])
            ->update([
                'deleted_at' => $current_date_time,
            ]);
        }
        
        //For Audit Trail
        // $at_old_value = '';
        // $at_new_value = '';
        // $at_action = 'Void';
        // $at_table = 'Sales';
        // $at_auditable = $orDetails['s_or_num'];
        // $user_id = Auth::user()->user_id;
        // $user_id = 41;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$orDetails['cm_id']);
        // Void Sale Transaction
        // ADDITIONAL CODE FOR EWALLET CREDIT REVERT CHANGES
        $ewalletCreditApplied = collect(DB::table('sales')
        ->select(DB::raw('sum(s_or_amount) as amount'))
        ->where('s_or_num',$orDetails['s_or_num'])
        ->where('cm_id',$orDetails['cm_id'])
        ->whereNull('e_wallet_applied')
        ->whereNull('e_wallet_added')
        ->where('s_status',1)
        ->first());

        $ewCreditApplied = isset($ewalletCreditApplied['amount']) ? $ewalletCreditApplied['amount'] : 0;
        if($ewCreditApplied != 0)
        {
            //GET CURRENT E WALLET TOTAL AMOUNT
            $ewallet2 = collect(DB::table('e_wallet')
            ->where('cm_id',$orDetails['cm_id'])
            ->first());

            $newWalletTotal3 = $ewallet2['ew_total_amount'] - $ewCreditApplied;
           
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal3
            ]);

            // EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            // ->where('ewl_status','U')
            // ->where('ew_id',$ewallet['ew_id'])
            // ->update([
            //     'deleted_at' => $current_date_time,
            // ]);
        }

        DB::table('sales')
        ->where('s_or_num',$orDetails['s_or_num'])
        ->where('s_bill_date',$orDetails['s_bill_date'])
        ->where('cm_id',$orDetails['cm_id'])
        ->delete();

        return response([
            'Message'=>'Successfully Voided'
        ],200);
    }
    public function getThisYearSale(){
        $thisYear = Sales::select('s_id','s_bill_amount','s_bill_date')
        ->whereYear('s_bill_date',date("Y"))
        ->orderBy('s_bill_date','asc')
        ->get()
        ->groupBy(function($data){
            return Carbon::parse($data->s_bill_date)->format('M');
        });
        $thisYearArrayData = [];
        foreach ($thisYear as $key => $value) {
            $thisYearArrayData[] = $value->sum('s_bill_amount');
        }
        return response()->json($thisYearArrayData,200);
    }

    public function getLastYearSale(){
        $lastYear = Sales::select('s_id','s_bill_amount','s_bill_date')
        ->whereYear('s_bill_date',date("Y",strtotime("-1 year")))
        ->orderBy('s_bill_date','asc')
        ->get()
        ->groupBy(function($data){
            return Carbon::parse($data->s_bill_date)->format('M');
        });
        $lastYearArrayData = [];
        foreach ($lastYear as $key => $value) {
            $lastYearArrayData[] = $value->sum('s_bill_amount');
        }
        return response()->json($lastYearArrayData,200);
    }

    public function totalSales(){
        $totalSales = Sales::select('s_bill_amount')->sum('s_bill_amount');
        return response()->json(number_format($totalSales,2),200);
    }
    public function officialReceipt(Request $request)
    {
        $checkOR = DB::table('sales')
        ->where('s_or_num',$request->or_num)
        ->get();

        if($checkOR->first())
        {
            return response(['Message'=>'Official Receipt Already Exist'],200);
        }

        return response(['Message'=>'Proceed'],404);
    }
    public function updateBillStatus($mrid)
    {
        //Updating status to 1 after payment
        $updateMR = MeterReg::find($mrid);
        $updateMR->mr_status = 1;
        $updateMR->uploaded_at = Carbon::now();
        $updateMR->save();
    }
    public function updateBMBillStatus($mrid,$partial)
    {   
        //Updating mr_partial and mr_status
        $updateMR = MeterReg::find($mrid);
        $newPartialAmount = $updateMR->mr_partial + $partial;
        // dd($updateMR->mr_amount);   
        // dd($newPartialAmount);
        if($newPartialAmount >= $updateMR->mr_amount){
            $updateMR->mr_status = 1;
            $updateMR->mr_partial = $updateMR->mr_amount;
        }else{
            $updateMR->mr_partial = $updateMR->mr_partial + $partial;
        }
        $updateMR->uploaded_at = Carbon::now();
        $updateMR->save();
    }
    public function updateEwallet($id,$billAmount)
    {
        $updateEWallet = EWALLET::find($id);
        $updateEWallet->ew_total_amount = $updateEWallet->ew_total_amount - $billAmount;
        $updateEWallet->save();
    }
    public function addEwallet($id,$addBill)
    {
        $updateEWallet = EWALLET::find($id);
        $updateEWallet->ew_total_amount = $updateEWallet->ew_total_amount + $addBill;
        $updateEWallet->save();
    }
    public function storeEWLogApplied($ewID,$ewAmount,$or,$date)
    {
        $ewlog2 = new EWALLET_LOG;
        $ewlog2->ew_id = $ewID;
        $ewlog2->ewl_amount = $ewAmount;
        $ewlog2->ewl_or = $or;
        $ewlog2->ewl_status = 'A';
        $ewlog2->ewl_or_date = $date;
        $ewlog2->save();
    }
    public function storeEWalletLogUnposted($eWalletID,$amountChange,$orNo,$current_date_time)
    {
        $ewlog = new EWALLET_LOG;
        $ewlog->ew_id = $eWalletID;
        $ewlog->ewl_amount = $amountChange;
        $ewlog->ewl_or = $orNo;
        $ewlog->ewl_status = 'U';
        $ewlog->ewl_or_date = $current_date_time;
        $ewlog->save();
    }
    public function storeCheque($chequeAmount,$chequeBank,$chequeBranch,$chequeAcc,$chequeAccName,$chequeNo,$or,$date)
    {
        //For Cheque
        $chequeDetails = new Cheque;
        $chequeDetails->cheque_amount = $chequeAmount;
        $chequeDetails->cheque_bank = $chequeBank;
        $chequeDetails->cheque_bank_branch = $chequeBranch;
        $chequeDetails->cheque_bank_acc = $chequeAcc;
        $chequeDetails->cheque_acc_name = $chequeAccName;
        $chequeDetails->cheque_no = $chequeNo;
        $chequeDetails->s_or = $or;
        $chequeDetails->cheque_date = $date;
        $chequeDetails->teller_user_id = $this->tellerUserId;
        $chequeDetails->save();
		Cheque::where('cheque_id',$chequeDetails->cheque_id)->update(['temp_cheque_id' => $chequeDetails->cheque_id]);
        return $chequeDetails->cheque_id;
        
    }
    public function checkPayBillsCutOff(Request $request)
    {
        $check = DB::table('sales')
            ->where('teller_user_id',$request->user_id)
            ->whereDate('s_bill_date',$request->date)
            ->where('s_cutoff',1)
            ->get();

        if(!$check->first())
        {
            return response(['Message'=>'Cut off is 0'],404);
        }else{
            return response(['Message'=>'Cut off is 1'],200);
        }
    }
    public function depositEwallet(Request $request)
    {
        $checkEwallet = collect(DB::table('e_wallet')
            ->where('cm_id',$request->cons_id)
            ->first());
        
        if($checkEwallet->isEmpty()){
            return response(['Message'=>'Error! No Consumer Wallet']);
        }
        // dd($checkEwallet['ew_id']);
        $current_date_time = Carbon::now()->toDateTimeString();
        //Deposit To Ewallet Goes to sales also
        $sales = new Sales;
        $sales->s_bill_date = $current_date_time;
        $sales->ct_id =$request->cons_type_id;
        $sales->cm_id = $request->cons_id;
        $sales->s_status = 0;
        // $sales->s_or_amount = $request->or_amount;
        $sales->s_bill_amount = $request->or_amount;
        $sales->s_or_num = intval($request->or_num);
        $sales->teller_user_id = $request->user_id;
        $sales->s_mode_payment = 'Deposit_Ewallet';
        $sales->e_wallet_added = $request->or_amount;
        $sales->s_bill_date_time = Carbon::now()->toTimeString();
        if($sales->save()){
            $this->storeEWalletLogUnposted($checkEwallet['ew_id'],$request->or_amount,intval($request->or_num),$current_date_time);
            $this->addEwallet($checkEwallet['ew_id'],$request->or_amount);
            $totaArrears = DB::table('meter_reg')
                ->where('cm_id',$request->cons_id)
                ->where('mr_status',0)
                ->sum('mr_amount');

            return response([
                'Message'=>'Transaction Success',
                'Date_Paid'=>$current_date_time.' '.Carbon::now()->toTimeString(),
                'Total_Arrears_Amount'=>$totaArrears
            ],200);
        }else{
            return response(['Message'=>'Ewallet Transaction Failed'],422);
        }
    }
    public function getYearMonth($billPeriod,$billToPay){
        $payMonths = $billToPay;
        $year = substr($billPeriod,0,4);
        $month = substr($billPeriod,4,6);
        $getMonth = ($month + $payMonths) -1;
        if($getMonth > 12){
            $year = $year + 1;
            $month = (12 - $getMonth) * -1;
        }else{
            $month = $getMonth;
        }

        return $year.''.sprintf('%02d',$month);
    }
    public function onetimepayment(Request $request)
    {
        set_time_limit(0);
        if(is_null($this->tellerUserId)){
            $this->tellerUserId = $request->teller_id;
        }
        $billingPeriod = str_replace("-","",$request->bill_period_from);
        $billPeriodStart =  $billingPeriod; // EG. 202011
        $billPeriodTo = $request->bill_period_months; // EG. 5(Months)
        $yearMonth = $this->getYearMonth($billPeriodStart,$billPeriodTo);
        $teller_id = $request->teller_id;
        $or = $request->or_start;
        $chequeDate = $request->cheque_date;
        $query = collect(
            DB::table('cons_master as cm')
            ->select('cm.cm_id','cm_account_no','rc.rc_desc','mr.mr_date_year_month','cm.ct_id','mr.mr_bill_no','mr.mr_id',
            'mr.mr_amount')
            ->join('meter_reg as mr','cm.cm_id','mr.cm_id')
            ->join('route_code as rc','cm.rc_id','rc.rc_id')
            ->whereBetween('mr.mr_date_year_month',[$billPeriodStart,$yearMonth])
            ->where('rc.rc_id',$request->route_id)
            ->where('mr_status',0)
            ->orderBy('cm.cm_account_no','asc')
            ->get()
        );
        $count = count($query);
        $amount = 0;
        for($i = 0;$i < $count;$i++){
            // ($i==0) ? $or : $or++;
            $sales = new Sales();
            if($i == 0){
                $or = $or;
                $amount = $query[$i]->mr_amount;
                // Insert Cheque First to get the ID  
                $chequeID =  $this->storeCheque($amount,$request->cheque_bank,
                NULL,$request->cheque_bank_acc,$request->cheque_acc_name,$request->cheque_no,$or,$chequeDate);
            }else{
                if($query[$i-1]->cm_account_no == $query[$i]->cm_account_no){ // Compare Previous to Current Loop
                    $or = $or;
                    $amount = $amount + $query[$i]->mr_amount;
                    $check = collect(Cheque::where('s_or',$or)->first());
                    if($check->isEmpty()){
                        //Insert Cheque First to get the ID  
                        $chequeID =  $this->storeCheque($amount,$request->cheque_bank,
                        NULL,$request->cheque_bank_acc,$request->cheque_acc_name,$request->cheque_no,$or,$chequeDate);
                    }else{
                        Cheque::where('s_or',$or)->update(['cheque_amount' => $amount]);
                    }
                }else{
                    $or = $or + 1;
                    $amount = $query[$i]->mr_amount;
                    // Insert Cheque First to get the ID  
                    $chequeID =  $this->storeCheque($amount,$request->cheque_bank,
                    NULL,$request->cheque_bank_acc,$request->cheque_acc_name,$request->cheque_no,$or,$chequeDate);
                }
            }
            $sales->s_or_num = $or;
            $sales->mr_id = $query[$i]->mr_id;
            $sales->cons_accountno = $query[$i]->cm_account_no;
            $sales->s_bill_date = Carbon::now()->toDateTimeString();
            $sales->ct_id =$query[$i]->ct_id;
            $sales->cm_id = $query[$i]->cm_id;
            $sales->s_bill_no = $query[$i]->mr_bill_no;
            $sales->s_status = 0;
            $sales->cheque_id = $chequeID;
            $sales->s_or_amount = $query[$i]->mr_amount;
            $sales->s_bill_amount = $query[$i]->mr_amount;
            $sales->teller_user_id = $teller_id;
            $sales->s_mode_payment = 'Cheque';
            $sales->mr_arrear = 'N';
            $sales->s_bill_date_time = Carbon::now()->toTimeString();
            $sales->save();
            $this->updateBillStatus($query[$i]->mr_id);
        }

        return response(['info'=>'Okay'],200);
    }
    //check OR latest
    public function formatId($user_id) {
        if ($user_id < 100) {
            $user_id = sprintf('%03d', $user_id);
        }
        return $user_id;
    }
    public function checkLatestOR($id){
      
        $today = Carbon::now();
        $dd = $today->format('d');
        $mm = $today->format('m');
        $yyyy = $today->format('Y');
        $lastTwoDigits = substr($yyyy, -2);

        $id2 = $this->formatId($id);
        
        $tornumber = $id . $mm . $dd . $lastTwoDigits;
        $tornumber2 = $id2 . '-' . $mm . $dd . $lastTwoDigits;
        $torNo = intval($tornumber);
        $orNumber = '';
        $query = DB::table('sales')
        ->select('s_or_num')
        ->where('s_or_num', 'like', '%'.$torNo.'%')
        ->where('teller_user_id', $id)
        ->orderBy('s_or_num', 'desc')
        ->first();

        $query1 = DB::table('or_void')
        ->select('v_or')
        ->where('v_or', 'like', '%'.$torNo.'%')
        ->where('v_user', $id)
        ->orderBy('v_or', 'desc')
        ->first();
        if($query1 === null){
            if ($query === null) {
                $newTOR = $tornumber2 . '-0001';
                // $test = '0412003240016';

                // dd(intval($test));
                return response()->json(['data' => $newTOR], 404);
            } else {
                $existingOR = $query->s_or_num;
                // dd($existingOR . ' - ' . $tornumber);
                $orNumber = substr($existingOR, strlen($tornumber));
                // dd($orNumber);
                return response()->json(['data' => $orNumber], 200);
            }
        }else{
            if($query === null){
                $existingOR = $query1->v_or;
                $orNumber = substr($existingOR, strlen($tornumber));
                return response()->json(['data' => $orNumber], 200);
                
            }
            else if($query1->v_or > $query->s_or_num){
                $existingOR = $query1->v_or;
                $orNumber = substr($existingOR, strlen($tornumber));
                return response()->json(['data' => $orNumber], 200);
            }else{
                $existingOR = $query->s_or_num;
                $orNumber = substr($existingOR, strlen($tornumber));
                return response()->json(['data' => $orNumber], 200);
            }
        }
  
    }
}
