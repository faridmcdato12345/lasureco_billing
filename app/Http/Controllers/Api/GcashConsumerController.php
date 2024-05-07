<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Consumer;
use App\Models\MeterReg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Services\GCashConsumerInquiry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GcashConsumerController extends Controller
{
    private $powerBillNumber = [];
    private $totalAmount;

    private function checkConsumer($accountNumber){
        $consumer = Consumer::where('cm_account_no',$accountNumber)->first();
        if(is_null($consumer)){
            return false;
        }
        return true;
    }
    public function consumerInquiry(Request $request){
        // return response()->json([
        //     'status_code' => 422,
        //     'message' => 'LASURECO online payment is under maintenance.'
        // ],422);
        $validator = Validator::make($request->all(),[
            'account_no' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message'=>$validator->errors()
            ],422);
        }
        $a = $request->header();
        if(!empty($a['authorization'])){
            $secretKey = substr($a['authorization'][0],0,5);
            if(strcmp($secretKey,'gcash') == 0){
                $consumer = Consumer::where('cm_account_no',$request->account_no)
                ->where('cm_con_status',1)
                ->first();
                if(is_null($consumer)){
                    return response()->json([
                        'status_code' => 404,
                        'message' => 'The account number is not a LASURECO member consumer or disconnected.'
                    ],404);
                }
                $data = (new GCashConsumerInquiry)->getConsumerPowerBills($request->account_no);
                $d = json_decode($data);
                //dd($d->data->bill_counts);
                if($d->data->bill_counts >= 7){
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'The account number has more than six arrears.Please settle your bill at paying centers.'
                    ],422);
                }
                $data = MeterReg::select(
                    'cons_account',
                    'mr_bill_no',
                    'mr_amount',
                    'mr_printed')
                ->where('cons_account',$request->account_no)
                ->where('mr_printed',1)
                ->where('mr_status',0)
                ->get();
                if($data->isNotEmpty()){
                    if($data->count() >= 2){
                        for($i = 0;$i < count($data);$i++){
                            array_push($this->powerBillNumber,$data[$i]->mr_bill_no);
                        }
                        $this->totalAmount = round(collect($data->take($data->count()))->sum('mr_amount'),2);
                        return response()->json([
                            'status_code' => 200,
                            'data' => $data->take($data->count()),
                            'total_amount' => round(collect($data->take($data->count()))->sum('mr_amount'),2)
                        ],200);
                    }else{
                        for($i = 0;$i < 1;$i++){
                            array_push($this->powerBillNumber,$data[$i]->mr_bill_no);
                        }
                        $this->totalAmount = round(collect($data->take(1))->sum('mr_amount'),2);
                        return response()->json([
                            'status_code' => 200,
                            'data' => $data->take(1),
                            'total_amount' => round(collect($data->take(1))->sum('mr_amount'),2)
                        ],200);
                    }
                }
                else{
                    return response()->json([
                        'status_code'=>404,
                        'message'=>'The account number has no unpaid bill/s.'
                    ],404);
                }
                return response()->json([
                    'status_code'=>404,
                    'message'=>'The account number provided does not have the inputted powerbill number or vice versa.'
                ],404);
            }else{
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Unauthorized request'
                ]);
            }
        }else{
            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized request'
            ],401);
        }
    }
    // public function gcashPayment(Request $request){
    //     // return response()->json([
    //     //     'status_code' => 422,
    //     //     'message' => 'LASURECO online payment is under maintenance.'
    //     // ],422);
    //     $validator = Validator::make($request->all(),[
    //         'account_no' => 'required',
    //         'amount' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status_code' => 422,
    //             'message'=>$validator->errors()
    //         ],422);
    //     }
    //     $a = $request->header();
    //     if(!empty($a['authorization'])){
    //         $secretKey = substr($a['authorization'][0],0,5);
    //         if(strcmp($secretKey,'gcash') == 0){
    //             $data = (new GCashConsumerInquiry)->getConsumerPowerBills($request->account_no);
    //             $d = json_decode($data);
    //             //dd($d->data->bill_counts);
    //             if($d->data->bill_counts >= 7){
    //                 return response()->json([
    //                     'status_code' => 422,
    //                     'message' => 'The account number has more than six arrears.Please settle your bill at paying centers.'
    //                 ],422);
    //             }
    //             $totalAmountToBePaid = $d->data->total_amount;
    //             // dd($d->data->bill_numbers);
    //             $explodeDate = Carbon::now()->toDateString();
    //             $dateRep = str_replace('-','',$explodeDate);
    //             $refNum = $request->account_no . $dateRep;
    //             if(count($d->data->bill_numbers) > 0){
    //                 //dd($d->data->total_amount);
    //                 if(!$this->checkConsumer($request->account_no)){
    //                     return response()->json([
    //                         'status_code' => 404,
    //                         'message' => 'The account number is not a LASURECO member consumer.'
    //                     ],404);
    //                 }
    //                 if((float)$request->amount == (float)$totalAmountToBePaid){
    //                     if(count($d->data->bill_numbers) == 1){
    //                         for($i = 0; $i <= count($d->data->bill_numbers) - 1; $i++){
    //                             //dd($powerBills[$i]);
    //                             // $billPaidMeterReg = MeterReg::select('cons_account','mr_bill_no')
    //                             //     ->where('cons_account',$request->account_no)
    //                             //     ->where('mr_bill_no',$d->data->bill_numbers[$i])
    //                             //     ->update([
    //                             //         'mr_status' => 1
    //                             //     ]);
    //                             $billPaidMeterReg = DB::table('cons_master as cm')
    //                             ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
    //                             ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
    //                             ->where('mr.cons_account',$request->account_no)
    //                             ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
    //                             ->where('cm.cm_con_status',1)
    //                             ->update([
    //                                 'mr.mr_status' => 1
    //                             ]);
                                
    //                             if($billPaidMeterReg){
    //                                 $meterReg = DB::table('cons_master as cm')
    //                                 ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
    //                                 ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
    //                                 ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
    //                                 ->first();
                                    
    //                                 try {
    //                                     $billPaidToSalesTable = DB::table('sales')->insert([
    //                                         'mr_id' => $meterReg->mr_id,
    //                                         'cm_id' => $meterReg->cm_id,
    //                                         'ct_id' => $meterReg->ct_id,
    //                                         'cons_accountno' => $meterReg->cm_account_no,
    //                                         's_or_amount' => $meterReg->mr_amount,
    //                                         's_bill_amount' => $meterReg->mr_amount,
    //                                         's_bill_no' => $meterReg->mr_bill_no,
    //                                         's_bill_date' => Carbon::now()->toDateString(),
    //                                         's_mode_payment' => 'Online',
    //                                         's_ref_num' => $refNum,
    //                                     ]);
    //                                 } catch (\Illuminate\Database\QueryException $ex) {
    //                                     MeterReg::select('cons_account','mr_bill_no')
    //                                     ->where('cons_account',$request->account_no)
    //                                     ->where('mr_bill_no',$d->data->bill_numbers[$i])
    //                                     ->update([
    //                                         'mr_status' => 0
    //                                     ]);
    //                                     return response()->json([
    //                                         'status_code' => 500,
    //                                         'message' => $ex
    //                                     ],500);
    //                                 }
    //                             }else{
    //                                 return response()->json([
    //                                     'status_code' => 422,
    //                                     'message' => 'The account number provided were currently disconnected'
    //                                 ],422);
    //                             }
    //                         }
    //                     }else{
    //                         for($i = 0; $i <= count($d->data->bill_numbers) - 1; $i++){
    //                             //dd($powerBills[$i]);
    //                             $billPaidMeterReg = DB::table('cons_master as cm')
    //                             ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
    //                             ->where('mr.cons_account',$request->account_no)
    //                             ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
    //                             ->where('cm.cm_con_status',1)
    //                             ->update([
    //                                 'mr.mr_status' => 1
    //                             ]);
                                
    //                             if($billPaidMeterReg){
    //                                 $meterReg = DB::table('cons_master as cm')
    //                                 ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
    //                                 ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
    //                                 ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
    //                                 ->where('cm.cm_con_status',1)
    //                                 ->first();
    //                                 try {
    //                                     $billPaidToSalesTable = DB::table('sales')->insert([
    //                                         'mr_id' => $meterReg->mr_id,
    //                                         'cm_id' => $meterReg->cm_id,
    //                                         'ct_id' => $meterReg->ct_id,
    //                                         'cons_accountno' => $meterReg->cm_account_no,
    //                                         's_or_amount' => $meterReg->mr_amount,
    //                                         's_bill_amount' => $meterReg->mr_amount,
    //                                         's_bill_no' => $meterReg->mr_bill_no,
    //                                         's_bill_date' => Carbon::now()->toDateString(),
    //                                         's_mode_payment' => 'Online',
    //                                         's_ref_num' => $refNum,
    //                                     ]);
    //                                 } catch (\Illuminate\Database\QueryException $ex) {
    //                                     MeterReg::select('cons_account','mr_bill_no')
    //                                     ->where('cons_account',$request->account_no)
    //                                     ->where('mr_bill_no',$d->data->bill_numbers[$i])
    //                                     ->update([
    //                                         'mr_status' => 0
    //                                     ]);
    //                                     return response()->json([
    //                                         'status_code' => 500,
    //                                         'message' => $ex
    //                                     ],500);
    //                                 }
    //                             }else{
    //                                 return response()->json([
    //                                     'status_code' => 422,
    //                                     'message' => 'The account number provided were currently disconnected'
    //                                 ],422);
    //                             }
    //                         }
    //                     }
    //                     return response()->json([
    //                         'status_code' => 201,
    //                         'data' => [
    //                             'account_number' => $request->account_no,
    //                             'account_name' => $meterReg->cm_full_name,
    //                             'reference_number' => $refNum,
    //                             'amount_paid' => $request->amount
    //                         ],
    //                         'message' => 'Payment is successful.'
    //                     ],201);
    //                 }else{
    //                     return response()->json([
    //                         'status_code' => 422,
    //                         'message' => 'The inputted amount must be exact.'
    //                     ],422);
    //                 }
    //             }else{
    //                 return response()->json([
    //                     'status_code' => 401,
    //                     'message' => 'No powerbill number.'
    //                 ],401);
    //             }
    //         }else{
    //             return response()->json([
    //                 'status_code' => 401,
    //                 'message' => 'Unauthorized request.'
    //             ],401);
    //         }
    //     }else{
    //         return response()->json([
    //             'status_code' => 401,
    //             'message' => 'Unauthorized request.'
    //         ],401);
    //     }
    // }
    // ------------with OR------------
    public function checkOR(){
      
        $today = Carbon::now();
        $dd = $today->format('d');
        $mm = $today->format('m');
        $yyyy = $today->format('Y');
        $lastTwoDigits = substr($yyyy, -2);

        $torNo = $mm.$dd.$lastTwoDigits;

        $query = DB::table('sales as s')
        ->select('s.s_or_num') 
        ->where('s.s_or_num', 'like', '%'.intval($torNo).'%')
        ->whereNull('s.teller_user_id')
        ->orderBy('s.s_or_num', 'desc')
        ->first();
        if($query == null){
            $orNum = $mm.$dd.$lastTwoDigits.'0001';
            return intval($orNum);
        }else{
            $orNum2 = $query->s_or_num;
            $orNum = intval($orNum2) + 1;
            return $orNum;
        }
  
    }
    public function gcashPayment(Request $request){
        $orNo = $this->checkOR();
        // dd($orNo);
        // dd($orNo);
        // return response()->json([
        //     'status_code' => 422,
        //     'message' => 'LASURECO online payment is under maintenance.'
        // ],422);
        $validator = Validator::make($request->all(),[
            'account_no' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message'=>$validator->errors()
            ],422);
        }
        $a = $request->header();
        if(!empty($a['authorization'])){
            $secretKey = substr($a['authorization'][0],0,5);
            if(strcmp($secretKey,'gcash') == 0){
                $data = (new GCashConsumerInquiry)->getConsumerPowerBills($request->account_no);
                $d = json_decode($data);
                //dd($d->data->bill_counts);
                if($d->data->bill_counts >= 7){
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'The account number has more than six arrears.Please settle your bill at paying centers.'
                    ],422);
                }
                $totalAmountToBePaid = $d->data->total_amount;
                // dd($d->data->bill_numbers);
                $explodeDate = Carbon::now()->toDateString();
                $dateRep = str_replace('-','',$explodeDate);
                $refNum = $request->account_no . $dateRep;
                if(count($d->data->bill_numbers) > 0){
                    //dd($d->data->total_amount);
                    if(!$this->checkConsumer($request->account_no)){
                        return response()->json([
                            'status_code' => 404,
                            'message' => 'The account number is not a LASURECO member consumer.'
                        ],404);
                    }
                    if((float)$request->amount == (float)$totalAmountToBePaid){
                        if(count($d->data->bill_numbers) == 1){
                            for($i = 0; $i <= count($d->data->bill_numbers) - 1; $i++){
                                //dd($powerBills[$i]);
                                // $billPaidMeterReg = MeterReg::select('cons_account','mr_bill_no')
                                //     ->where('cons_account',$request->account_no)
                                //     ->where('mr_bill_no',$d->data->bill_numbers[$i])
                                //     ->update([
                                //         'mr_status' => 1
                                //     ]);
                                $billPaidMeterReg = DB::table('cons_master as cm')
                                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                                ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
                                ->where('mr.cons_account',$request->account_no)
                                ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
                                ->where('cm.cm_con_status',1)
                                ->update([
                                    'mr.mr_status' => 1
                                ]);
                                
                                if($billPaidMeterReg){
                                    $meterReg = DB::table('cons_master as cm')
                                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                                    ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
                                    ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
                                    ->first();
                                    
                                    try {
                                        $billPaidToSalesTable = DB::table('sales')->insert([
                                            'mr_id' => $meterReg->mr_id,
                                            'cm_id' => $meterReg->cm_id,
                                            'ct_id' => $meterReg->ct_id,
                                            'cons_accountno' => $meterReg->cm_account_no,
                                            's_or_amount' => $meterReg->mr_amount,
                                            's_bill_amount' => $meterReg->mr_amount,
                                            's_bill_no' => $meterReg->mr_bill_no,
                                            's_bill_date' => Carbon::now()->toDateString(),
                                            's_mode_payment' => 'Online',
                                            's_ref_num' => $refNum,
                                            's_or_num' => $orNo,
                                        ]);
                                    } catch (\Illuminate\Database\QueryException $ex) {
                                        MeterReg::select('cons_account','mr_bill_no')
                                        ->where('cons_account',$request->account_no)
                                        ->where('mr_bill_no',$d->data->bill_numbers[$i])
                                        ->update([
                                            'mr_status' => 0
                                        ]);
                                        return response()->json([
                                            'status_code' => 500,
                                            'message' => $ex
                                        ],500);
                                    }
                                }else{
                                    return response()->json([
                                        'status_code' => 422,
                                        'message' => 'The account number provided were currently disconnected'
                                    ],422);
                                }
                            }
                        }else{
                            for($i = 0; $i <= count($d->data->bill_numbers) - 1; $i++){
                                //dd($powerBills[$i]);
                                $billPaidMeterReg = DB::table('cons_master as cm')
                                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                                ->where('mr.cons_account',$request->account_no)
                                ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
                                ->where('cm.cm_con_status',1)
                                ->update([
                                    'mr.mr_status' => 1
                                ]);
                                
                                if($billPaidMeterReg){
                                    $meterReg = DB::table('cons_master as cm')
                                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                                    ->select('cm.cm_account_no','cm.cm_full_name','mr.mr_id','mr.cm_id','cm.ct_id','mr.mr_amount','mr.mr_bill_no')
                                    ->where('mr.mr_bill_no',$d->data->bill_numbers[$i])
                                    ->where('cm.cm_con_status',1)
                                    ->first();
                                    try {
                                        $billPaidToSalesTable = DB::table('sales')->insert([
                                            'mr_id' => $meterReg->mr_id,
                                            'cm_id' => $meterReg->cm_id,
                                            'ct_id' => $meterReg->ct_id,
                                            'cons_accountno' => $meterReg->cm_account_no,
                                            's_or_amount' => $meterReg->mr_amount,
                                            's_bill_amount' => $meterReg->mr_amount,
                                            's_bill_no' => $meterReg->mr_bill_no,
                                            's_bill_date' => Carbon::now()->toDateString(),
                                            's_mode_payment' => 'Online',
                                            's_ref_num' => $refNum,
                                            's_or_num' => $orNo,
                                        ]);
                                    } catch (\Illuminate\Database\QueryException $ex) {
                                        MeterReg::select('cons_account','mr_bill_no')
                                        ->where('cons_account',$request->account_no)
                                        ->where('mr_bill_no',$d->data->bill_numbers[$i])
                                        ->update([
                                            'mr_status' => 0
                                        ]);
                                        return response()->json([
                                            'status_code' => 500,
                                            'message' => $ex
                                        ],500);
                                    }
                                }else{
                                    return response()->json([
                                        'status_code' => 422,
                                        'message' => 'The account number provided were currently disconnected'
                                    ],422);
                                }
                            }
                        }
                        return response()->json([
                            'status_code' => 201,
                            'data' => [
                                'account_number' => $request->account_no,
                                'account_name' => $meterReg->cm_full_name,
                                'reference_number' => $refNum,
                                'or_number' => $orNo,
                                'amount_paid' => $request->amount
                            ],
                            'message' => 'Payment is successful.'
                        ],201);
                    }else{
                        return response()->json([
                            'status_code' => 422,
                            'message' => 'The inputted amount must be exact.'
                        ],422);
                    }
                }else{
                    return response()->json([
                        'status_code' => 401,
                        'message' => 'No powerbill number.'
                    ],401);
                }
            }else{
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Unauthorized request.'
                ],401);
            }
        }else{
            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized request.'
            ],401);
        }
    }
    // ------------end with OR---------
}
