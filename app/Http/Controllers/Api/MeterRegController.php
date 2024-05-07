<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeterRegResource;
use App\Http\Controllers\Api\BillRatesController;
use App\Models\MeterReg;
use Carbon\Traits\Timestamp;
use DateTime;
use Hamcrest\Core\HasToString;
use App\Models\AdjustedPowerBill;
use App\Models\Consumer;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;

use function PHPSTORM_META\map;

use function PHPUnit\Framework\isEmpty;

class MeterRegController extends Controller
{
    public function index()
    {
        return  MeterRegResource::collection(
            DB::table('meter_reg')
            ->whereNull('deleted_at')
            ->paginate(10));
    }
    public function getPrevReadDate($readDate)
    {
        date_default_timezone_set('Asia/Manila');
        $cur_date = new DateTime();
        date_modify($cur_date, "-1 month");
        $cur_date = $cur_date->format('Ym');
        $cur_date = 202010;
        // dd($cur_date);
        // $prevReadDate = date_create(DB::table('meter_reg AS mr')
        //     ->groupBy('mr.mr_date_reg')
        //     ->having(DB::RAW('MAX(DATE(mr.mr_date_reg))'))
        //     ->first();
        //     // ->max('mr.mr_date_reg'));
        
        //     $prevReadDate  = date_format($prevReadDate, 'Ymd');
            
        // return response([$prevReadDate],200);
    }
    public function showMRS(Request $request)
    {
        $billPeriod = str_replace("-","",$request->billingPeriod);

        $consumers = collect(DB::table('cons_master AS cm')
            ->leftJoin('cons_type AS ct', 'cm.ct_id', '=', 'ct.ct_id')
            ->leftJoin('meter_master AS mm', 'cm.mm_id', '=', 'mm.mm_id')
            ->leftJoin('meter_reg AS mr', function($join) use ($billPeriod)
            {
                $join->on('cm.cm_id','=', 'mr.cm_id')
                ->when($billPeriod, function ($query, $billPeriod) {
                    return $query->where('mr_date_year_month', $billPeriod);
                });
            })
            ->select(DB::raw("(SELECT mr.cm_id WHERE mr_printed = 1) AS cmmr"),'cm.cm_seq_no','mr.mr_id', 'cm.cm_id',
                'cm.cm_full_name','cm_con_status','ct.ct_desc','ct.ct_id','cm.cm_kwh_mult', 'mr.mr_printed',
                'cm.cm_account_no','mm.mm_serial_no','mr.mr_pres_reading', 'cm.cm_lgu2', 'cm.cm_lgu5', 'mr.mr_date_year_month',
                'mr.mr_wrap', 'mr.mr_digit', 'mr.mr_kwh_used', 'mr.mr_pres_dem_reading')
            ->where('rc_id',$request->routeID)
            ->havingRaw('cmmr IS NULL')
            ->whereNull('mr.deleted_at')
            ->orderBy('cm.cm_seq_no')
            ->get());
        // dd($consumers);
        $mapped = $consumers->map(function($item){
            return [
                'acctNo' => $item->cm_account_no,
                'status' => ($item->cm_con_status == 1) ? 'Active' : 'Disconnected',
                'fullName' => str_replace(' ','_', $item->cm_full_name),
                'consID' => $item->cm_id,
                'multi' => $item->cm_kwh_mult,
                'lgu2' => $item->cm_lgu2,
                'lgu5' => $item->cm_lgu5,
                'cmmr' => $item->cmmr,
                'consType' => $item->ct_desc,
                'consType_id' => $item->ct_id,
                'serial' => $item->mm_serial_no,
                'DYM' => ($item->mr_date_year_month == null) ? '' : $item->mr_date_year_month,
                'digit' => ($item->mr_digit == null) ? '' : $item->mr_digit,
                'mrID' => ($item->mr_id == null) ? $item->mr_id : $item->mr_id,
                'kwhUsed' => ($item->mr_kwh_used == null) ? '' : $item->mr_kwh_used,
                'presDmd' => ($item->mr_pres_dem_reading == null) ? '' : $item->mr_pres_dem_reading,
                'presRead' => ($item->mr_pres_reading == null) ? '' : $item->mr_pres_reading,
                'wrap' => ($item->mr_wrap == null) ? '' : $item->mr_wrap,
                'seqNo' => ($item->cm_seq_no == 0) ? 0 : $item->cm_seq_no,
                'exist' => ($item->mr_id > 0) ? 'checked' : ''
                // 'exist' => ($item->mr_date_year_month > 0 && $item->mr_printed == 0) ? 'checked' : ''
            ];
        });
        
        return response(['count'=>count($consumers),'data' => $mapped], 200);
    }
    

    public function bulkRead(Request $request)
    {
        $billPeriod = str_replace("-","",$request->bulk['bp']);
        $bulkKWH = $request->bulk['bKWH'];
        $ff = $request->bulk['ff'];
        $dateReg = $request->bulk['dateReg'];
        $rID = $request->bulk['rID'];
        $uID = $request->bulk['userID'];
        $meterReader = $request->bulk['meterReader'];
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        $billNoPrefix = new DateTime($request->bulk['bp']);
        $billNoPrefix = substr($billNoPrefix->format('Y'), -2).$billNoPrefix->format('m');
        $amount = array();
        $totalAmount = 0;
        $msg = "";

        if($bulkKWH == NULL || $ff == NULL){
            $msg = "Bulk KWH and field finding is required!";
            return response(['Message'=>$msg], 422);
        }

        $rates = collect(
            DB::table('billing_rates')
            ->where('br_billing_ym',$billPeriod)
            ->get()
        );

        $consumers = collect(DB::table('cons_master AS cm')
            ->leftJoin('cons_type AS ct', 'cm.ct_id', '=', 'ct.ct_id')
            ->leftJoin('meter_master AS mm', 'cm.mm_id', '=', 'mm.mm_id')
            ->leftJoin('meter_reg AS mr', function($join) use ($billPeriod)
            {
                $join->on('cm.cm_id','=', 'mr.cm_id')
                ->when($billPeriod, function ($query, $billPeriod) {
                    return $query->where('mr_date_year_month', $billPeriod);
                });
            })
            ->select(DB::raw("(SELECT mr.cm_id WHERE mr_printed = 1) AS cmmr"),'cm.cm_seq_no','mr.mr_id', 'cm.cm_id',
                'cm.cm_full_name','cm_con_status','ct.ct_desc','ct.ct_id','cm.cm_kwh_mult', 'mr.mr_printed',
                'cm.cm_account_no','mm.mm_serial_no','mr.mr_pres_reading', 'mr.mr_prev_reading', 'cm.cm_lgu2', 'cm.cm_lgu5', 'mr.mr_date_year_month',
                'mr.mr_wrap', 'mr.mr_digit', 'mr.mr_kwh_used', 'mr.mr_pres_dem_reading')
            ->where('rc_id',$rID)
            ->havingRaw('cmmr IS NULL')
            ->whereNull('mr.deleted_at')
            ->orderBy('cm.cm_seq_no')
            ->get());
        
        foreach($rates as $rate){
            //As billed(original Bill) starts here
            $gen = round(round($rate->br_gensys_rate * $bulkKWH,3),2);
            $par = round(round($rate->br_par_rate * $bulkKWH,3),2);
            $fbhc = round(round($rate->br_fbhc_rate * $bulkKWH,3),2);
            $forex = round(round($rate->br_forex_rate * $bulkKWH,3),2);
            $fitAll = round(round($rate->br_fit_all * $bulkKWH,3),2);
            $tranSys = round(round($rate->br_transsys_rate * $bulkKWH,3),2);
            $transDem = round(round($rate->br_transdem_rate * 0,3),2);
            $sysLoss = round(round($rate->br_sysloss_rate * $bulkKWH,3),2);
            $distSys = round(round($rate->br_distsys_rate * $bulkKWH,3),2);
            $distDem = round(round($rate->br_distdem_rate * 0,3),2) ;
            $supRtlCharge = round(round($rate->br_suprtlcust_fixed,3),2);  //per cst
            $supSys = round(round($rate->br_supsys_rate * $bulkKWH,3),2);
            $meterFix = round(round($rate->br_mtrrtlcust_fixed,3),2); //per cst
            $meterSys = round(round($rate->br_mtrsys_rate * $bulkKWH,3),2); 
            $lflineDiscSub = round(round($rate->br_lfln_subs_rate * $bulkKWH,3),2);
            $senCitDS = round(round($rate->br_sc_subs_rate * $bulkKWH,3),2);
            $intl = round(round($rate->br_intrclscrssubrte * $bulkKWH,3),2);
            $mccCapex = round(round($rate->br_capex_rate * $bulkKWH,3),2);
            $loanCond = round(round($rate->br_loancon_rate_kwh * $bulkKWH,3),2);
            $loanCondFix = round(round($rate->br_loancon_rate_fix,3),2);
            $spug = round(round($rate->br_uc4_miss_rate_spu * $bulkKWH,3),2);
            $red = round(round($rate->br_uc4_miss_rate_red * $bulkKWH,3),2);
            $envCharge = round(round($rate->br_uc6_envi_rate * $bulkKWH,3),2);
            $equaliRoyalty = round(round($rate->br_uc5_equal_rate * $bulkKWH,3),2);
            $npcStrCC = round(round($rate->br_uc2_npccon_rate * $bulkKWH,3),2);
            $npcStrDebt = round(round($rate->br_uc1_npcdebt_rate * $bulkKWH,3),2);
            $genVat = round(round($rate->br_vat_gen * $bulkKWH,3),2);
            $transVat = round(round($rate->br_vat_trans * $bulkKWH,3),2);
            $transDemVat = round(round($rate->br_vat_transdem * 0,3),2);
            $sysLossVat = round(round($rate->br_vat_systloss * $bulkKWH,3),2);
            $distSysVat = round(round($rate->br_vat_distrib_kwh * $bulkKWH,3),2);
            $distDemVat = round(round($rate->br_vat_distdem * 0,3),2) ;
            $loanCondVat = round(round($rate->br_vat_loancondo * $bulkKWH,3),2);
            $loanCondFixVat = round(round($rate->br_vat_loancondofix,3),2);
            $powerActVat = round(round($rate->br_vat_par * $bulkKWH,3),2) ;
            $supplySysVat = round(round($rate->br_vat_supsys * $bulkKWH,3),2);
            $supplyFixVat = round(round($rate->br_vat_supfix,3),2);
            $meterFixVat = round(round($rate->br_vat_mtr_fix,3),2);
            $meterSysVat = round(round($rate->br_vat_metersys * $bulkKWH,3),2);
            $lflnDiscVat = round(round($rate->br_vat_lfln * $bulkKWH,3),2);
            $otherVat = 0;
            $eVat = $genVat + $transVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + $loanCondVat + 
                $loanCondFixVat + $powerActVat + $supplySysVat + $supplyFixVat + $meterFixVat + $meterSysVat + $lflnDiscVat + $otherVat;
            
            $totalAmount = $gen+$par+$fbhc+$forex+$fitAll+$tranSys+$transDem+$sysLoss+$distSys+$distDem+$supRtlCharge+$supSys+
                            $meterFix+$meterSys+$lflineDiscSub+$senCitDS+$intl+$mccCapex+$loanCond+$loanCondFix+$spug+$red+$envCharge+
                            $equaliRoyalty+$npcStrCC+$npcStrDebt+$eVat;
            
            array_push($amount, array('rateID' => $rate->id,'constype' => $rate->cons_type_id, 'amount' => $totalAmount));
        }

        $bulkCount = 0;
        $result = null;

        foreach($consumers as $consumer){
            foreach($amount as $a){
                if($consumer->ct_id == $a['constype'] && $consumer->mr_kwh_used > 0){
                    break;
                }else if($consumer->ct_id == $a['constype']){
                // }else if($consumer->ct_id == $a['constype'] && $consumer->ct_desc == "RESIDENTIAL"){
               
                    $result = DB::table('meter_reg')->insert([ 
                        'br_id' => $a['rateID'],
                        'cm_id' => $consumer->cm_id,
                        'ff_id' => $ff,
                        'cons_account' => $consumer->cm_account_no,
                        'mr_bill_no' => $consumer->cm_id.$billNoPrefix.$consumer->cm_account_no,
                        // 'mr_bill_no' => substr($consumer->cm_id,0,2).$billNoPrefix.$consumer->cm_account_no,
                        'mr_amount' =>  $a['amount'],
                        'mr_prev_reading' => ($consumer->mr_prev_reading == null) ? 0 : $consumer->mr_prev_reading,
                        'mr_pres_reading' => ($consumer->mr_pres_reading == null) ? 0 : $consumer->mr_pres_reading,
                        'mr_prev_dem_reading' => 0,
                        'mr_pres_dem_reading' => 0,
                        'mr_kwh_used' => $bulkKWH,
                        'mr_dem_kwh_used' => 0,
                        'mr_date_year_month' => $billPeriod,    
                        'mr_date_reg' => date("Y-m-d", strtotime($dateReg))." ".$date->format('h:i:s'),
                        'mr_status' => 0,
                        'mr_printed' => 2,
                        'mr_mtrReader' => $meterReader,
                        'user_id' => $uID
                    ]);
            
                    $bulkCount += 1;
                    break;
                }
            }
        }

        if($result > 0){
            $msg = $bulkCount." Consumers successfully overrided!";
        }else{
            $msg = "Failed to bulk override!";
        }

        return response(['Message'=>$msg, 'success'=>$result], 200);
    }


    public function updateMRS(Request $request){

        
        $billPeriod = str_replace("-","",$request->storage['billPeriod']);
        // $pdf = PDF::loadView('/Layout.print');
        // return $pdf->stream('invoice.pdf');
        $dateReg = $request->storage['dateReg'];
        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        // $dueDate = new DateTime();
        // $discDate = new DateTime();
        $billNoPrefix = new DateTime($request->storage['billPeriod']);
        // date_modify($dueDate, "+15 days");
        // date_modify($discDate, "+30 days");

        $billNoPrefix = substr($billNoPrefix->format('Y'), -2).$billNoPrefix->format('m');
        $prevRead = 0;
        if(intval($request['storage']['newPrevRead']) > 0 && intval($request['storage']['prevEdit']) ==  1){
            $prevRead = $request['storage']['newPrevRead'];
        }else{
            $prevRead = $request['storage']['prevRead'];
        }
        
        // dd($prevRead);
        $msg = "";

        if($request['storage']['mrid'] == null or $request['storage']['mrid'] == 0){
            $result = null;
            $msg = "Failed to update";
        }else{
            $result = DB::table('meter_reg')
            ->where('mr_id', $request['storage']['mrid'])
            ->update([ 
                'cm_id' => $request['storage']['consumerID'],
                'ff_id' => $request['storage']['ff'],
                'mr_amount' =>  $request['storage']['curbill'],
                'mr_kwh_used' => $request['storage']['kwhUsed'],
                'mr_prev_reading' => $prevRead,
                'mr_pres_reading' => $request['storage']['presRead'],  
                'mr_date_reg' => date("Y-m-d", strtotime($dateReg))." ".$date->format('h:i:s'),
                'mr_pres_dem_reading' => $request['storage']['presDmd'],
                'mr_prev_dem_reading' => $request['storage']['prevDmd'],
                'mr_dem_kwh_used' => $request['storage']['dmdKwhUsed'],
                'mr_wrap' => $request['storage']['wrap'],
                'mr_digit'=> ($request['storage']['digits'] == null) ? 0 : $request['storage']['digits'],
                'mr_mtrReader' => $request['storage']['meterReader'],
                'mr_lfln_disc' => $request['storage']['lfln_disc'],
                'user_id' => $request['storage']['userID']
            ]);
            $msg = "Successfully to updated";
        }
        return response(['Message'=>$msg, 'id'=>$result, 'action'=>'update'], 200);
    }
    
    public function saveMRS(Request $request){

        $dateReg = $request->storage['dateReg'];
        // dd($dateReg );  
        $billPeriod = str_replace("-","",$request->storage['billPeriod']);
        // $pdf = PDF::loadView('/Layout.print');
        // return $pdf->stream('invoice.pdf');

        date_default_timezone_set('Asia/Manila');
        $date = new DateTime();
        // $dueDate = new DateTime();
        // $discDate = new DateTime();
        $billNoPrefix = new DateTime($request->storage['billPeriod']);
        // date_modify($dueDate, "+15 days");
        // date_modify($discDate, "+30 days");

        $billNoPrefix = substr($billNoPrefix->format('Y'), -2).$billNoPrefix->format('m');
        $prevRead = 0;
        if($request->newPrevRead > 0 || $request->prevEdit ==  1){
            $prevRead = $request['storage']['newPrevRead'];
        }else{
            $prevRead = $request['storage']['prevRead'];
        }

        // dd($request->all(), $billNoPrefix, $prevRead);
        $msg = "";

        $result = DB::table('meter_reg')->insertGetId([ 
            'br_id' => $request['storage']['rate'],
            'cm_id' => $request['storage']['consumerID'],
            'ff_id' => $request['storage']['ff'],
            'cons_account' => $request['storage']['acctNo'],
            'mr_bill_no' => $request['storage']['consumerID'].$billNoPrefix. $request['storage']['acctNo'],
            // 'mr_bill_no' => substr($request['storage']['consumerID'],0,2).$billNoPrefix. $request['storage']['acctNo'],
            'mr_amount' =>  $request['storage']['curbill'],
            'mr_kwh_used' => $request['storage']['kwhUsed'],
            'mr_prev_reading' => $prevRead,
            'mr_pres_reading' => $request['storage']['presRead'],
            'mr_date_year_month' => $billPeriod,    
            'mr_date_reg' => date("Y-m-d", strtotime($dateReg))." ".$date->format('h:i:s'),
            // 'mr_due_date' => $dueDate->format('Y-m-d h:i:s'),
            // 'mr_discon_date' => $discDate->format('Y-m-d h:i:s'),
            'mr_pres_dem_reading' => $request['storage']['presDmd'],
            'mr_prev_dem_reading' => $request['storage']['prevDmd'],
            'mr_dem_kwh_used' => $request['storage']['dmdKwhUsed'],
            'mr_status' => 0,
            'mr_printed' => 2,
            'mr_wrap' => $request['storage']['wrap'],
            'mr_digit'=> ($request['storage']['digits'] == null) ? 0 : $request['storage']['digits'],
            'mr_mtrReader' => $request['storage']['meterReader'],
            'mr_lfln_disc' => $request['storage']['lfln_disc'],
            'user_id' => $request['storage']['userID']
        ]);

        if($result > 0){
            $msg = "Successfully Inserted";
        }else{
            $msg = "Failed to Insert";
        }
        return response(['Message'=>$msg, 'id'=>$result, 'action'=>'insert'], 200);
    }
    
    public function showConsCollection($id)
    {
        $checkConsCollection = DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','mr.cm_id','=','cm.cm_id')
            ->select('cm.cm_id')
            ->where('cm.cm_id',$id) 
            ->where('mr.mr_status',0)
            ->whereNull('mr.deleted_at')
            ->first();
        $currentDate = Carbon::now(); 
        // $totalDailyCollection = collect(
        //         DB::table('sales as s')
        //         ->select(DB::raw('sum(s.s_or_amount) as or_amount, sum(s.e_wallet_added) as ewAdded, s.s_or_num,s.s_bill_date'))
        //         ->whereDate('s.s_bill_date',$currentDate)
        //         ->groupBy('s.s_or_num')
        //         ->get()
        // );

        // $mappedTotalDailyCollection = $totalDailyCollection->map(function($item){
        //     return[
        //         'Total_Collection'=>$item->or_amount + $item->ewAdded,
        //         'OR_NUM'=> $item->s_or_num,
        //     ];
        // });
        // // dd($mappedTotalDailyCollection);
        // $countOR = $mappedTotalDailyCollection->count();
        // $sum = $mappedTotalDailyCollection->sum('Total_Collection');
        // $voidCount = collect(
        //     DB::table('or_void')
        //     ->whereDate('v_date',$currentDate)
        //     ->groupBy('v_or')
        //     ->get()
        // );
        // $countVoid = $voidCount->count();
        if(!$checkConsCollection)
        {
            $consDetails = Consumer::where('cons_master.cm_id',$id)
            ->leftJoin('meter_reg','meter_reg.cm_id','=','cons_master.cm_id')
            ->join('cons_type','cons_master.ct_id','=','cons_type.ct_id')
            ->leftJoin('meter_master','cons_master.mm_id','=','meter_master.mm_id')
            ->leftJoin('e_wallet','cons_master.cm_id','=','e_wallet.cm_id')
            ->select('cons_master.cm_id','cons_type.ct_id','cons_master.cm_full_name','cons_master.cm_address','cons_type.ct_desc',
                'cons_master.cm_con_status','meter_master.mm_serial_no','e_wallet.ew_total_amount','e_wallet.ew_id')
            ->first();

            //Cons Notification
            $consNotify = DB::table('cons_master as cm')
            ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
            ->where('cm.cm_id',$id)
            ->get();
            $mapNotify = $consNotify->map(function($items){
                return [
                    'Remarks'=>$items->cn_remarks
                ];
            });
            return response([
                'Msg'=>'No remaining balance',
                'Consumer_Details'=>$consDetails,
                'Cons_Notification'=>$mapNotify
                // 'Total_Collection'=>$sum,
                // 'OR_No_Used'=>$countOR,
                // 'Void_count'=>$countVoid
            ],422);
        }
        // PBIlls
        $consCollection = DB::table('meter_reg')
            ->join('cons_master','meter_reg.cm_id','=','cons_master.cm_id')
            ->join('cons_type', 'cons_master.ct_id','=','cons_type.ct_id')
            ->select(DB::raw("(select 'PB') AS type,cast(meter_reg.mr_bill_no AS char) AS mr_bill_no"),'meter_reg.mr_id','meter_reg.mr_date_year_month','cons_type.ct_code',
                'meter_reg.mr_kwh_used','meter_reg.mr_amount','meter_reg.mr_date_reg','cons_master.cm_lgu2','cons_master.cm_lgu5','meter_reg.mr_partial')
            ->where('meter_reg.mr_status',0)
            ->where('meter_reg.cm_id',$id)
            ->where('meter_reg.mr_printed',1)
            ->whereNull('meter_reg.deleted_at')
            ->orderBy('meter_reg.mr_date_year_month','asc')
            ->get();
        // Cons Details
        $consDetails = DB::table('meter_reg')
            ->join('cons_master','meter_reg.cm_id','=','cons_master.cm_id')
            ->join('cons_type','cons_master.ct_id','=','cons_type.ct_id')
            ->leftJoin('meter_master','cons_master.mm_id','=','meter_master.mm_id')
            ->leftJoin('e_wallet','cons_master.cm_id','=','e_wallet.cm_id')
            ->select('cons_master.cm_id','cons_type.ct_id','cons_master.cm_full_name','cons_master.cm_address','cons_type.ct_desc',
                'cons_master.cm_con_status','meter_master.mm_serial_no','e_wallet.ew_total_amount','e_wallet.ew_id')
            // ->where('meter_reg.mr_id',$id)
            ->where('cons_master.cm_id',$id)
            ->whereNull('meter_reg.deleted_at')
            ->first();

        //Cons Notification
        $consNotify = DB::table('cons_master as cm')
            ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
            ->where('cm.cm_id',$id)
            ->get();
        $mapNotify = $consNotify->map(function($items){
            return[
                'Notify'=> $items->cn_remarks
            ];
        });

        $consTotalAmount = DB::table('meter_reg AS mr')
            ->where('cm_id',$id)
            ->where('mr.mr_status',0)
            ->where('mr.mr_printed', 1)
            ->whereNull('mr.deleted_at')
            ->sum('mr.mr_amount');
            // dd($consDetails->ct_id);
        if($consDetails->ct_id == 3){
            $getPartial = DB::table('meter_reg')
                ->where('cm_id',$id)
                ->where('mr_status',0)
                ->where('mr_printed',1)
                ->sum('mr_partial');
            $totalArrears = round($consTotalAmount - $getPartial, 2);
            
        }else{
            $totalArrears = round($consTotalAmount, 2);
        }
        

        return response([
            'Consumer_Details' => $consDetails,
            'Total_Arrears'=> $totalArrears,
            'Bills_to_Pay'=> $consCollection,
            'Cons_Notification'=>$mapNotify
            // 'Total_Collection'=>$sum,
            // 'OR_No_Used'=>$countOR,
            // 'Void_count'=>$countVoid
        ],200);
    }

    public function showDisconDueDate($id)
    {
        $disconDueDate = DB::table('meter_reg')
            ->select('mr_discon_date','mr_due_date')
            ->where('mr_id',$id)
            ->first();
        
        $disconDate = date("m/d/Y", strtotime($disconDueDate->mr_discon_date));
        $dueDate = date("m/d/Y", strtotime($disconDueDate->mr_due_date));

        return response([
            'Discon_Date' =>$disconDate,
            'Due_Date'=>$dueDate
        ]);
    }

    public function getRecords($mrid){
        $query = DB::table('meter_reg AS mr')
            ->where('mr.mr_id', $mrid)
            // ->where('mr.mr_printed', 2)
            ->whereNull('mr.deleted_at')
            ->first();
        return response(['records'=>$query], 200);
    }

    public function cancelReading(Request $request){
        $query = DB::table('meter_reg')
            ->where('mr_id', '=' ,$request->mrid)
            ->delete();
        
        return response(["msg"=>"Reading successfully canceled"], 200);
    }


    public function getComputation(Request $request){
        $cm_id = intval($request['storage']['consumerID']);
        $query = null;
        $query = DB::table('meter_reg AS mr')
        ->join('cons_master As cm', 'mr.cm_id','=','cm.cm_id')
        ->select('mr.mr_id', 'mr.cm_id','mr.mr_kwh_used','mr.mr_pres_reading',
                'mr.mr_dem_kwh_used','mr.mr_pres_dem_reading','mr.mr_prev_dem_reading',
                'cm.cm_lgu5','cm.cm_lgu2','cm.cm_discount_name','cm.cm_discount_percent','mr.mr_printed') 
        ->where('mr.cm_id', $cm_id)
        ->where('mr.mr_printed', 1)
        ->whereNull('mr.deleted_at')
        ->orderBy('mr.mr_date_year_month', 'desc')
        ->first();

        $totalArrears = DB::table('meter_reg AS mr')
            ->where('mr.cm_id', $cm_id)
            ->where('mr.mr_status', 0)
            ->where('mr.mr_date_year_month', '<' , str_replace("-","",$request['storage']['billPeriod']))
            ->whereNull('mr.deleted_at')
            ->sum('mr.mr_amount');

        $ewallet = DB::table('e_wallet AS ew')
        ->select('ew_total_amount')
        ->where('ew.cm_id', $cm_id)
        ->get();

        $a = new BillRatesController;
        $rates = collect($a->getRate(intval($request['storage']['consType']), str_replace("-","",$request['storage']['billPeriod'])));

        $multi = $request['storage']['multiplier'];
        $total = 0;
        $lgu7 = 0;
        $dmdKwhConsumed = 0;
        $presRead = $request['storage']['presRead'];
        $presDemRead = $request['storage']['presDmd']*$multi;

        if($query == null){
            $prevRead = $request['storage']['prevRead'];
            $prevKWH = 0;
            $prevDmd = 0;
        }else if($request['storage']['mrid'] > 0 && $query != null){
            $prevRead = $request['storage']['prevRead'];
            $prevKWH = $query->mr_kwh_used;
            $prevDmd = $query->mr_pres_dem_reading;
        }else{
            $prevRead = $query->mr_pres_reading;
            $prevKWH = $query->mr_kwh_used;
            $prevDmd = $query->mr_pres_dem_reading;
        }

        $lgu2 = $request['storage']['lgu2'];
        $lgu5 = $request['storage']['lgu5'];
        $newPrevRead = intval($request['storage']['newPrevRead']);
        $newKwh = $request['storage']['newKwh'];
        // $prevEdit = $request['storage']['prevEdit'];
        $temp = 0;
        $curbill = 0;
        $genChrges = 0;
        $transChrges = 0;
        $distChrges = 0;
        $govChrges = 0;
        $otherChrges = 0;
        $vat = 0;
        $rateSubTotal = 0;
        $pesoSubTotal = 0;
        $pesoDemand = 0;
        $uGenChrges = 0;
        $uTransChrges = 0;
        $uDistChrges = 0;
        $uOtherChrges = 0;
        $uGovChrges = 0;
        $uVat  = 0;
        $lifeline_deduct = 0;
        $lfln_disc = 0;

        // dd($request->all());
        // if($request['storage']['wrap'] == 1 && $request['storage']['digits'] > 3 && ($presRead < $prevRead)){
        if($request['storage']['wrap'] == 1 && $request['storage']['digits'] > 3){
            if($request['storage']['prevEdit'] == 1 && $newPrevRead > 0){
                $prevRead = $newPrevRead;
            }

            if($request['storage']['digits'] == 6){
               $kwhConsumed = (((1000000+$presRead)-$prevRead)*$multi)+floatVal($request['storage']['addEnergy']);
            }else if($request['storage']['digits'] == 5){
                $kwhConsumed = (((100000+$presRead)-$prevRead)*$multi)+floatVal($request['storage']['addEnergy']);
            }else if($request['storage']['digits'] == 4){
                $kwhConsumed = (((10000+$presRead)-$prevRead)*$multi)+floatVal($request['storage']['addEnergy']);
            }
        }
        else if($request['storage']['override'] > 0){
            if($request['storage']['prevEdit'] == 1){
                $prevRead = $newPrevRead;
            }
            $kwhConsumed = ($request['storage']['override'] == 1) ? ($request['storage']['kwhUsed']) : $newKwh; 
            //+floatVal($request['storage']['addEnergy']);
            // $kwhConsumed = ($newKwh/$multi)*$multi;
        }
        else if($request['storage']['prevEdit'] == 1 && $newPrevRead >= 0){
            $kwhConsumed = (($presRead-$newPrevRead)*$multi)+floatVal($request['storage']['addEnergy']);
            $prevRead = $newPrevRead;
        }else{
            // dd('HERE AKIE S');
            $kwhConsumed = (($presRead-$prevRead)*$multi)+floatVal($request['storage']['addEnergy']);
        }
        
        $dmdKwhConsumed = $presDemRead;
        $lifeline = new LifeLineRatesController;
        $lifeline = ($lifeline->getLifeline($kwhConsumed) == null ? 0 : $lifeline->getLifeline($kwhConsumed)->ll_discount);
        
        $computeStatus = 0;

        if(count($rates) == 0){
            return response(['No rates'=>$request['storage']['consType']], 404);
        }
        else if($presRead != 0 || $kwhConsumed != 0){
                $computeStatus = 0;
                    //general charges
                    $temp = round(round(($rates[0]->br_gensys_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_par_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_fbhc_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_forex_rate*$kwhConsumed),3),2);
                    $curbill += floatval($temp);
                    $genChrges = $temp;
                    $rateSubTotal = floatval($rates[0]->br_gensys_rate)+floatval($rates[0]->br_par_rate)+floatval($rates[0]->br_fbhc_rate)+floatval($rates[0]->br_forex_rate);
                    // $pesoSubTotal = $rateSubTotal*$kwhConsumed;

                    $uGenChrges = array("gsc"=>$rates[0]->br_gensys_rate, "gsc_peso"=>round(round(($rates[0]->br_gensys_rate*$kwhConsumed),3),2),
                        "par"=>$rates[0]->br_par_rate, "par_peso"=>round(round(($rates[0]->br_par_rate*$kwhConsumed),3),2),
                        "fbh"=>$rates[0]->br_fbhc_rate, "fbh_peso"=>round(round(($rates[0]->br_fbhc_rate*$kwhConsumed),3),2),
                        "fac"=>$rates[0]->br_forex_rate, "fac_peso"=>round(round(($rates[0]->br_forex_rate*$kwhConsumed),3),2),
                        "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($genChrges,2));
                    
                    $lifeline_deduct = ($rates[0]->br_gensys_rate+$rates[0]->br_fbhc_rate)*$kwhConsumed;
                    

                    //transmission charges
                    $temp = round(round(($rates[0]->br_transsys_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_transdem_rate*$presDemRead),3),2);
                    $temp += round(round(($rates[0]->br_sysloss_rate*$kwhConsumed),3),2);
                    $curbill += floatval($temp);
                    $transChrges = $temp;
                    $rateSubTotal = floatval($rates[0]->br_transsys_rate)+floatval($rates[0]->br_sysloss_rate);
                    // $pesoDemand = $rates[0]->br_transdem_rate*$presDemRead;
                    // $pesoSubTotal = ($rateSubTotal*$kwhConsumed)+$pesoDemand;
                    $rateSubTotal += floatval($rates[0]->br_transdem_rate);
                    
                    $uTransChrges = array("tsc"=>$rates[0]->br_transsys_rate, "tsc_peso"=>round(round(($rates[0]->br_transsys_rate*$kwhConsumed),3),2), 
                    "tdc"=>$rates[0]->br_transdem_rate, "tdc_peso"=>round(round(($rates[0]->br_transdem_rate*$presDemRead),3),2),
                    "slc"=>$rates[0]->br_sysloss_rate, "slc_peso"=>round(round(($rates[0]->br_sysloss_rate*$kwhConsumed),3),2),
                    "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($transChrges,2));

                    $lifeline_deduct += ($rates[0]->br_transsys_rate+$rates[0]->br_sysloss_rate)*$kwhConsumed;
                    $lifeline_deduct += $rates[0]->br_transdem_rate*$presDemRead;
                    
                    //distribution charges
                    $temp = round(round(($rates[0]->br_distsys_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_distdem_rate*$presDemRead),3),2);
                    $temp += round(round(($rates[0]->br_suprtlcust_fixed),3),2);
                    $temp += round(round(($rates[0]->br_supsys_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_mtrrtlcust_fixed),3),2);
                    $temp += round(round(($rates[0]->br_mtrsys_rate*$kwhConsumed),3),2);
                    $curbill += floatval($temp);
                    $distChrges = $temp;

                    $rateSubTotal = floatval($rates[0]->br_distsys_rate)+floatval($rates[0]->br_supsys_rate)+floatval($rates[0]->br_mtrsys_rate);
                    // $pesoDemand = $rates[0]->br_distdem_rate*$presDemRead;
                    // $pesoSubTotal = ($rateSubTotal*$kwhConsumed)+$pesoDemand+$rates[0]->br_suprtlcust_fixed+$rates[0]->br_mtrrtlcust_fixed;
                    $rateSubTotal += floatval($rates[0]->br_distdem_rate)+floatval($rates[0]->br_suprtlcust_fixed)+floatval($rates[0]->br_mtrrtlcust_fixed);

                    $uDistChrges = array("dsc"=>$rates[0]->br_distsys_rate, "dsc_peso"=>round(round(($rates[0]->br_distsys_rate*$kwhConsumed),3),2),
                    "ddc"=>$rates[0]->br_distdem_rate, "ddc_peso"=>round(round(($rates[0]->br_distdem_rate*$presDemRead),3),2),
                    "sfc"=>$rates[0]->br_suprtlcust_fixed, "sfc_peso"=>round(round($rates[0]->br_suprtlcust_fixed,3),2),
                    "ssc"=>$rates[0]->br_supsys_rate, "ssc_peso"=>round(round(($rates[0]->br_supsys_rate*$kwhConsumed),3),2),
                    "mfc"=>$rates[0]->br_mtrrtlcust_fixed, "mfc_peso"=>round(round($rates[0]->br_mtrrtlcust_fixed,3),2),
                    "msc"=>$rates[0]->br_mtrsys_rate, "msc_peso"=>round(round(($rates[0]->br_mtrsys_rate*$kwhConsumed),3),2),
                    "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($distChrges,2));

                    $lifeline_deduct += ($rates[0]->br_distsys_rate+$rates[0]->br_supsys_rate+$rates[0]->br_mtrsys_rate)*$kwhConsumed;
                    $lifeline_deduct += ($rates[0]->br_distdem_rate*$presDemRead)+($rates[0]->br_suprtlcust_fixed)+($rates[0]->br_mtrrtlcust_fixed);


                    //other charges
                    // $temp = round(round(($rates[0]->br_lfln_subs_rate*$kwhConsumed),3),2);
                    $temp = ($lifeline > 0 && $request['storage']['consType'] == 8) ? round(round((-1)*($lifeline_deduct*floatval($lifeline/100)),3),2) : round(round(($rates[0]->br_lfln_subs_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_sc_subs_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_intrclscrssubrte*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_capex_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_loancon_rate_kwh*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_loancon_rate_fix*$kwhConsumed),3),2);
                    $curbill += floatval($temp);
                    $otherChrges = $temp;

                    $rateSubTotal = floatval($rates[0]->br_lfln_subs_rate)+floatval($rates[0]->br_sc_subs_rate);
                    $rateSubTotal += floatval($rates[0]->br_intrclscrssubrte)+floatval($rates[0]->br_capex_rate);
                    $rateSubTotal += floatval($rates[0]->br_loancon_rate_kwh)+floatval($rates[0]->br_loancon_rate_fix);
                    // $pesoSubTotal = $rateSubTotal*$kwhConsumed;

                    $uOtherChrges = array("lds"=>$rates[0]->br_lfln_subs_rate, 
                    "lds_peso"=>($lifeline > 0 && $request['storage']['consType'] == 8) ? round(round((-1)*($lifeline_deduct*floatval($lifeline/100)),3),2) : round(round(($rates[0]->br_lfln_subs_rate*$kwhConsumed),3),2),
                    "scd"=>$rates[0]->br_sc_subs_rate, "scd_peso"=>round(round(($rates[0]->br_sc_subs_rate*$kwhConsumed),3),2),
                    "iccs"=>$rates[0]->br_intrclscrssubrte, "iccs_peso"=>round(round(($rates[0]->br_intrclscrssubrte*$kwhConsumed),3),2),
                    "mcapex"=>$rates[0]->br_capex_rate, "mcapex_peso"=>round(round(($rates[0]->br_capex_rate*$kwhConsumed),3),2),
                    "lc"=>$rates[0]->br_loancon_rate_kwh, "lc_peso"=>round(round(($rates[0]->br_loancon_rate_kwh*$kwhConsumed),3),2),
                    "lcf"=>$rates[0]->br_loancon_rate_fix, "lcf_peso"=>round(round(($rates[0]->br_loancon_rate_fix*$kwhConsumed),3),2),
                    "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($otherChrges,2));
                    

                    //gov't charges
                    $temp = round(round(($rates[0]->br_uc4_miss_rate_spu*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_uc4_miss_rate_red*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_uc6_envi_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_uc5_equal_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_uc2_npccon_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_uc1_npcdebt_rate*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_fit_all*$kwhConsumed),3),2);
                    $curbill += floatval($temp);
                    $govChrges = $temp;

                    $rateSubTotal = floatval($rates[0]->br_uc4_miss_rate_spu)+floatval($rates[0]->br_uc4_miss_rate_red);
                    $rateSubTotal += floatval($rates[0]->br_uc6_envi_rate)+floatval($rates[0]->br_uc5_equal_rate);
                    $rateSubTotal += floatval($rates[0]->br_uc2_npccon_rate)+floatval($rates[0]->br_uc1_npcdebt_rate);
                    $rateSubTotal += floatval($rates[0]->br_fit_all);
                    // $pesoSubTotal = $rateSubTotal*$kwhConsumed;

                    $uGovChrges = array("spug"=>$rates[0]->br_uc4_miss_rate_spu, "spug_peso"=>round(round(($rates[0]->br_uc4_miss_rate_spu*$kwhConsumed),3),2),
                    "red"=>$rates[0]->br_uc4_miss_rate_red, "red_peso"=>round(round(($rates[0]->br_uc4_miss_rate_red*$kwhConsumed),3),2),
                    "ec"=>$rates[0]->br_uc6_envi_rate, "ec_peso"=>round(round(($rates[0]->br_uc6_envi_rate*$kwhConsumed),3),2),
                    "etr"=>$rates[0]->br_uc5_equal_rate, "etr_peso"=>round(round(($rates[0]->br_uc5_equal_rate*$kwhConsumed),3),2),
                    "npcsc"=>$rates[0]->br_uc2_npccon_rate, "npcsc_peso"=>round(round(($rates[0]->br_uc2_npccon_rate*$kwhConsumed),3),2),
                    "npcsd"=>$rates[0]->br_uc1_npcdebt_rate, "npcsd_peso"=>round(round(($rates[0]->br_uc1_npcdebt_rate*$kwhConsumed),3),2),
                    "fitAll"=>$rates[0]->br_fit_all, "fitAll_peso"=>round(round(($rates[0]->br_fit_all*$kwhConsumed),3),2),
                    "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($govChrges,2));

                    //vat
                    $temp = round(round(($rates[0]->br_vat_gen*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_par*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_trans*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_transdem*$presDemRead),3),2);
                    $temp += round(round(($rates[0]->br_vat_systloss*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_distrib_kwh*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_distdem*$presDemRead),3),2);
                    $temp += round(round(($rates[0]->br_vat_supsys*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_mtr_fix),3),2);
                    $temp += round(round(($rates[0]->br_vat_metersys*$kwhConsumed),3),2);
                    $temp += ($lifeline > 0 && $request['storage']['consType'] == 8) ? 0.00 : round(round(($rates[0]->br_vat_lfln*$kwhConsumed),3),2); 
                    $temp += round(round(($rates[0]->br_vat_loancondo*$kwhConsumed),3),2);
                    $temp += round(round(($rates[0]->br_vat_loancondofix),3),2);
                    $temp += round(round(($rates[0]->br_vat_supfix),3),2);
                    $temp += 0;
                    $curbill += floatval($temp);
                    $vat = $temp;

                    $rateSubTotal = floatval($rates[0]->br_vat_gen)+floatval($rates[0]->br_vat_par);
                    $rateSubTotal += floatval($rates[0]->br_vat_trans)+floatval($rates[0]->br_vat_systloss);
                    $rateSubTotal += floatval($rates[0]->br_vat_distrib_kwh)+floatval($rates[0]->br_vat_supsys);
                    $rateSubTotal += floatval($rates[0]->br_vat_mtr_fix)+floatval($rates[0]->br_vat_metersys);
                    $rateSubTotal += floatval($rates[0]->br_vat_lfln)+floatval($rates[0]->br_vat_loancondo);
                    $rateSubTotal += floatval($rates[0]->br_vat_loancondofix)+floatval($rates[0]->br_vat_supfix);
                    // $pesoDemand = (floatval($rates[0]->br_vat_transdem)+floatval($rates[0]->br_vat_distdem))*$presDemRead;
                    // $pesoSubTotal = (($rateSubTotal-(floatval($rates[0]->br_vat_loancondofix)+floatval($rates[0]->br_vat_mtr_fix)))*$kwhConsumed)+$pesoDemand;
                    $rateSubTotal += floatval($rates[0]->br_vat_transdem)+floatval($rates[0]->br_vat_distdem);
                    // $pesoSubTotal += (floatval($rates[0]->br_vat_loancondofix)+floatval($rates[0]->br_vat_mtr_fix)+floatval($rates[0]->br_vat_supfix));

                    $uVat = array("genvat"=>$rates[0]->br_vat_gen, "genvat_peso"=>round(round(($rates[0]->br_vat_gen*$kwhConsumed),3),2),
                    "parvat"=>$rates[0]->br_vat_par, "parvat_peso"=>round(round(($rates[0]->br_vat_par*$kwhConsumed),3),2),
                    "tsvat"=>$rates[0]->br_vat_trans, "tsvat_peso"=>round(round(($rates[0]->br_vat_trans*$kwhConsumed),3),2),
                    "tdvat"=>$rates[0]->br_vat_transdem, "tdvat_peso"=>round(round(($rates[0]->br_vat_transdem*$presDemRead),3),2),
                    "slvat"=>$rates[0]->br_vat_systloss, "slvat_peso"=>round(round(($rates[0]->br_vat_systloss*$kwhConsumed),3),2),
                    "dsvat"=>$rates[0]->br_vat_distrib_kwh, "dsvat_peso"=>round(round(($rates[0]->br_vat_distrib_kwh*$kwhConsumed),3),2),
                    "ddvat"=>$rates[0]->br_vat_distdem, "ddvat_peso"=>round(round(($rates[0]->br_vat_distdem*$presDemRead),3),2),
                    "ssvat"=>$rates[0]->br_vat_supsys, "ssvat_peso"=>round(round(($rates[0]->br_vat_supsys*$kwhConsumed),3),2),
                    "mfvat"=>$rates[0]->br_vat_mtr_fix, "mfvat_peso"=>round(round(($rates[0]->br_vat_mtr_fix),3),2),
                    "msvat"=>$rates[0]->br_vat_metersys, "msvat_peso"=>round(round(($rates[0]->br_vat_metersys*$kwhConsumed),3),2),
                    "ldsvat"=>$rates[0]->br_vat_lfln, "ldsvat_peso"=>($lifeline > 0 && $request['storage']['consType'] == 8) ? 0.00 : round(round(($rates[0]->br_vat_lfln*$kwhConsumed),3),2),
                    "lcvat"=>$rates[0]->br_vat_loancondo, "lcvat_peso"=>round(round(($rates[0]->br_vat_loancondo*$kwhConsumed),3),2),
                    "lcfvat"=>$rates[0]->br_vat_loancondofix, "lcfvat_peso"=>round(round(($rates[0]->br_vat_loancondofix),3),2),
                    "sfvat"=>$rates[0]->br_vat_supfix, "sfvat_peso"=>round(round(($rates[0]->br_vat_supfix),3),2),
                    "othervat"=>0, "othervat_peso"=>0,
                    "rateSubTotal"=>number_format($rateSubTotal,4), "pesoSubTotal"=>number_format($vat,2));

                
                    if($lgu2 == 1 && $lgu5 == 1){
                        $lgu2 = ($curbill/1.12)*0.02;
                        $lgu5 = ($curbill/1.12)*0.05;
                        $lgu7 = 1;
                    }else if($lgu2 == 1){
                        $lgu2 = ($curbill/1.12)*0.02;
                    }else if($lgu5 == 1){
                        $lgu5 = ($curbill/1.12)*0.05;
                    }
                    
        }else{
                // 'not ok! contact your software engineers.'
                $computeStatus = 1;
        }
            
            $total = floatval($totalArrears);
            
            if($lifeline > 0 && $request['storage']['consType'] == 8){
                $lfln_disc = $lifeline_deduct*floatval($lifeline/100);
            }

            // if(isEmpty($request['storage']['mosqueDisc'])){
            //     $curbill -= ($curbill*(floatval($request['storage']['mosqueDisc'])/100));
            // }else if(isEmpty($request['storage']['seniorDisc'])){
            //     $curbill -= ($curbill*(floatval($request['storage']['seniorDisc'])/100));
            // }
            
            $data = array("kwhConsumed"=>$kwhConsumed, "prevRead"=>$prevRead, "prevKwh"=>$prevKWH, 
                    "totalArrears"=>round(round($totalArrears,3),2), "ewallet"=>$ewallet[0]->ew_total_amount,
                    "lifeline_disc"=>round(round($lfln_disc,3),2),
                    "lgu7"=>$lgu7,"lgu2"=>number_format(round(round($lgu2,3),2),2),"lgu5"=>number_format(round(round($lgu5,3),2),2),
                    "genchrges"=>number_format(round(round($genChrges,3),2),2),"distchrges"=>number_format(round(round($distChrges,3),2),2),
                    "transchrges"=>number_format(round(round($transChrges,3),2),2),
                    "otherchrges"=>number_format(round(round($otherChrges,3),2),2),"govtchrges"=>number_format(round(round($govChrges,3),2),2),
                    "vat"=>number_format(round(round($vat,3),2),2),
                    "total"=>round(round($total,3),2), "curbill"=>round(round($curbill,3),2), "lifeline"=>$lifeline, "rates"=>$rates[0]->id,
                    "prevDmd"=>$prevDmd, "dmdKwhUsed"=>$dmdKwhConsumed, 'compute_stat'=>$computeStatus);

            if(count($rates) > 0){
                return response(["data"=>$data, "uGenChrges"=>$uGenChrges, "uTransChrges"=>$uTransChrges,
                    "uDistChrges"=>$uDistChrges, "uOtherChrges"=>$uOtherChrges, "uGovChrges"=>$uGovChrges,
                    "uVat"=>$uVat], 200);
            }
    }
    
    
    public function adjustPowerBill(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        
        $date = $data['Data']['Date'];
        $pBillID = $data['Data']['PB_ID'];
        $presRead = $data['Data']['Pres_Reading'];
        $prevRead = $data['Data']['Prev_Reading'];
        $kwh = $data['Data']['Kwh_Used'];
        $demKwh = $data['Data']['Total_Demand_Kwh_Used'];
        $presdemKwh = $data['Data']['Demand_Kwh_Used'];
        $cmID = $data['Data']['Cons_ID'];
        $userID = $data['Data']['User_ID'];
        $currBill = $data['Data']['Current_Bill'];
        $brID = $data['Data']['BR_ID'];
        $lfline = $data['Data']['lifeline'];
        $current_date_time = Carbon::now()->toDateTimeString();

        $billPeriod = str_replace("-","",$date);
        $getPBill = collect(
            DB::table('meter_reg')
            ->where('cm_id',$cmID)
            ->where('mr_id',$pBillID)
            ->where('mr_date_year_month',$billPeriod)
            ->where('mr_status',0)
            ->first()
        );
        if(!$getPBill)
        {
            return response(['Msg'=>'Invalid Data Input'],422);
        }
        //Insert Original Bill to Adjusted PowerBill Table AND new value
        $adjPB = new AdjustedPowerBill;
        $adjPB->mr_id = $pBillID;
        $adjPB->ap_old_kwh = $getPBill['mr_kwh_used'];
        $adjPB->ap_New_kwh = $kwh;
        $adjPB->ap_old_amount = $getPBill['mr_amount'];
        $adjPB->ap_New_amount = $currBill;
        $adjPB->ap_old_pres_reading = $getPBill['mr_pres_reading'];
        $adjPB->ap_New_pres_reading = $presRead;
        $adjPB->ap_old_prev_reading = $getPBill['mr_prev_reading'];
        $adjPB->ap_New_prev_reading = $prevRead;
        $adjPB->ap_old_dem_kwh_used = $getPBill['mr_dem_kwh_used'];
        $adjPB->ap_New_dem_kwh_used = $demKwh;
        $adjPB->ap_date = $current_date_time;
        $adjPB->ap_user = $userID;
        $adjPB->save();

        //Update PBill(meter_reg) based on sent adjusted data
        MeterReg::where('mr_id',$pBillID)
            ->where('cm_id',$cmID)
            ->update([
                'mr_pres_reading' => $presRead,
                'mr_prev_reading' => $prevRead,
                'mr_kwh_used' => $kwh,
                'mr_dem_kwh_used' => $demKwh,
                'mr_pres_dem_reading' => $presdemKwh,
                'mr_amount' => $currBill,
                'mr_lfln_disc' => $lfline,
                'br_id' => $brID,
            ]);
        //For Audit Trail
        $at_old_value = $getPBill['mr_kwh_used'];
        $at_new_value = $kwh;
        $at_action = 'Adjust PowerBill';
        $at_table = 'Meter_Reg';
        $at_auditable = 'KWH';
        $user_id = $userID;
        $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$cmID);
        return response(['Message'=>'Succesfully Adjusted'],200);
    }
    public function showAdjustPowerBill(Request $request)
    {
        $billPeriod = str_replace("-","",$request->date);
        $showPowerBill = collect(
            DB::table('cons_master as cm')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->where('cm.cm_id',$request->cons_id)
            ->where('mr.mr_date_year_month',$billPeriod)
            ->where('mr.mr_status',0)
            ->whereNull('mr.deleted_at')
            ->where('mr.mr_printed', 1)
            ->get()
        );
        $check = $showPowerBill->first();
        if(!$check)
        {
            return response(['Message'=>'No Record Found'],422);
        }
        
            $mapped = $showPowerBill->map(function($item){
                $status = ($item->cm_con_status == 1)?'Active':'Deactivated';
                $adjDate = Carbon::now()->toDateTimeString();

                //Query Total Arrears
                $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();

                //Adjust No
                $adjustNo = collect(DB::table('adjusted_powerbill as ap')
                    ->join('meter_reg as mr','ap.mr_id','=','mr.mr_id')
                    ->where('mr.cm_id',$item->cm_id)
                    ->get()
                );

                $count = count($adjustNo);

                //New BR/Constype Rates
                $nBRCtype = collect(DB::table('billing_rates')
                    ->where('cons_type_id',$item->ct_id)
                    ->where('br_billing_ym',$item->mr_date_year_month)
                    ->first()
                );

                //As billed(original Bill) starts here
                $gen = round($item->br_gensys_rate,4) * $item->mr_kwh_used;
                $par = round($item->br_par_rate,4) * $item->mr_kwh_used;
                $fbhc = round($item->br_fbhc_rate,4) * $item->mr_kwh_used;
                $forex = round($item->br_forex_rate,4) * $item->mr_kwh_used;
                $fitAll = round($item->br_fit_all,4) * $item->mr_kwh_used;
                $tranSys = round($item->br_transsys_rate,4) * $item->mr_kwh_used;
                $transDem = round($item->br_transdem_rate,4) * $item->mr_dem_kwh_used;
                $sysLoss = round($item->br_sysloss_rate,4) * $item->mr_kwh_used;
                $distSys = round($item->br_distsys_rate,4) * $item->mr_kwh_used;
                $distDem = round($item->br_distdem_rate,4) * $item->mr_dem_kwh_used;
                $supRtlCharge = round($item->br_suprtlcust_fixed,4);  //per cst
                $supSys = round($item->br_supsys_rate,4) * $item->mr_kwh_used;
                $meterFix = round($item->br_mtrrtlcust_fixed,4); //per cst
                $meterSys = round($item->br_mtrsys_rate,4) * $item->mr_kwh_used; 
                $lflineDiscSub = round($item->br_lfln_subs_rate,4) * $item->mr_kwh_used;
                $senCitDS = round($item->br_sc_subs_rate,4) * $item->mr_kwh_used;
                $intl = round($item->br_intrclscrssubrte,4) * $item->mr_kwh_used;
                $mccCapex = round($item->br_capex_rate,4) * $item->mr_kwh_used;
                $loanCond = round($item->br_loancon_rate_kwh,4) * $item->mr_kwh_used;
                $loanCondFix = round($item->br_loancon_rate_fix,4);
                $spug = round($item->br_uc4_miss_rate_spu,4) * $item->mr_kwh_used;
                $red = round($item->br_uc4_miss_rate_red,4) * $item->mr_kwh_used;
                $envCharge = round($item->br_uc6_envi_rate,4) * $item->mr_kwh_used;
                $equaliRoyalty = round($item->br_uc5_equal_rate,4) * $item->mr_kwh_used;
                $npcStrCC = round($item->br_uc2_npccon_rate,4) * $item->mr_kwh_used;
                $npcStrDebt = round($item->br_uc1_npcdebt_rate,4) * $item->mr_kwh_used;
                $genVat = round($item->br_vat_gen,4) * $item->mr_kwh_used;
                $transVat = round($item->br_vat_trans,4) * $item->mr_kwh_used;
                $transDemVat = round($item->br_vat_transdem,4) * $item->mr_dem_kwh_used;
                $sysLossVat = round($item->br_vat_systloss,4) * $item->mr_kwh_used;
                $distSysVat = round($item->br_vat_distrib_kwh,4) * $item->mr_kwh_used;
                $distDemVat = round($item->br_vat_distdem,4) * $item->mr_dem_kwh_used;
                $loanCondVat = round($item->br_vat_loancondo,4) * $item->mr_kwh_used;
                $loanCondFixVat = round($item->br_vat_loancondofix,4);
                $powerActVat = round($item->br_vat_par,4) * $item->mr_kwh_used;
                $supplySysVat = round($item->br_vat_supsys,4) * $item->mr_kwh_used;
                $supplyFixVat = round($item->br_vat_supfix,4);
                $meterFixVat = round($item->br_vat_mtr_fix,4);
                $meterSysVat = round($item->br_vat_metersys,4) * $item->mr_kwh_used;
                $lflnDiscVat = round($item->br_vat_lfln,4) * $item->mr_kwh_used;
                $otherVat = 0;
                $eVat = $genVat + $transVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + $loanCondVat + 
                    $loanCondFixVat + $powerActVat + $supplySysVat + $supplyFixVat + $meterFixVat + $meterSysVat + $lflnDiscVat + $otherVat;
                    // $total = 
                    // $gen +
                    // $par +
                    // $fbhc +
                    // $forex +
                    // $tranSys +
                    // $transDem +
                    // $sysLoss +
                    // $distSys +
                    // $distDem +
                    // $supRtlCharge +
                    // $supSys +
                    // $meterFix +
                    // $meterSys +
                    // $lflineDiscSub +
                    // $senCitDS +
                    // $intl +
                    // $mccCapex +
                    // $loanCond +
                    // $loanCondFix +
                    // $spug +
                    // $red +
                    // $envCharge +
                    // $equaliRoyalty +
                    // $npcStrCC +
                    // $npcStrDebt +
                    // $fitAll +
                    // $genVat +
                    // $transVat +
                    // $transDemVat +
                    // $sysLossVat +
                    // $distSysVat +
                    // $distDemVat +
                    // $loanCondVat +
                    // $loanCondFixVat +
                    // $powerActVat +
                    // $supplyFixVat +
                    // $meterFixVat +
                    // $meterSysVat +
                    // $lflnDiscVat;
                    // dd($total);
                return[
                    'PowerBill_ID'=>$item->mr_id,
                    'BillRates_ID'=>$nBRCtype['id'],
                    'Adjust_No'=>$count,
                    'Cons_ID'=>$item->cm_id,
                    'Account_Name'=>$item->cm_full_name,
                    'Account_Address'=>$item->cm_address,
                    'Account_Type'=>$item->ct_desc,
                    'Account_Status'=>$status,
                    'Adjustment_Date'=>$adjDate,
                    'Bill_No'=>$item->mr_bill_no,
                    'Present_Reading'=>$item->mr_pres_reading,
                    'Previous_Reading'=>$item->mr_prev_reading,
                    'Kwh_Used'=>$item->mr_kwh_used,
                    'Demand_Kwh_Used'=>$item->mr_dem_kwh_used,
                    'Due_Date'=>$item->mr_due_date,
                    'Mult'=>$item->cm_kwh_mult,
                    // ---------------------------------------------------------------------------------------------------------- //
                    'Gen_Charge'=> round(round($gen,3),2).'@'.round($item->br_gensys_rate,4),
                    'Gen_Charge1'=> '0'.'@'.round($nBRCtype['br_gensys_rate'],4),
                    'Power_Act_Red'=> round(round($par,3),2).'@'.round($item->br_par_rate,4),
                    'Power_Act_Red1'=> '0'.'@'.round($nBRCtype['br_par_rate'],4),
                    'Fran_Ben_To_Host'=> round(round($fbhc,3),2).'@'.round($item->br_fbhc_rate,4),
                    'Fran_Ben_To_Host1'=> '0'.'@'.round($nBRCtype['br_fbhc_rate'],4),
                    'Forex_Adjust_Charge'=> round(round($forex,3),2).'@'.round($item->br_forex_rate,4),
                    'Forex_Adjust_Charge1'=> '0'.'@'.round($nBRCtype['br_forex_rate'],4),
                    'Trans_Sys_Charge'=> round(round($tranSys,3),2).'@'.round($item->br_transsys_rate,4),
                    'Trans_Sys_Charge1'=> '0'.'@'.round($nBRCtype['br_transsys_rate'],4),
                    'Trans_Dem_Charge'=> round(round($transDem,3),2).'@'.round($item->br_transdem_rate,4),
                    'Trans_Dem_Charge1'=> '0'.'@'.round($nBRCtype['br_transdem_rate'],4),
                    'System_Loss_Charge'=> round(round($sysLoss,3),2).'@'.round($item->br_sysloss_rate,4),
                    'System_Loss_Charge1'=> '0'.'@'.round($nBRCtype['br_sysloss_rate'],4),
                    'Dist_Sys_Charge'=> round(round($distSys,3),2).'@'.round($item->br_distsys_rate,4),
                    'Dist_Sys_Charge1'=> '0'.'@'.round($nBRCtype['br_distsys_rate'],4),
                    'Dist_Dem_Charge'=> round(round($distDem,3),2).'@'.round($item->br_distdem_rate,4),
                    'Dist_Dem_Charge1'=> '0'.'@'.round($nBRCtype['br_distdem_rate'],4),
                    'Supply_Fix_Charge'=> round(round($supRtlCharge,3),2).'@'.round($item->br_suprtlcust_fixed,4),
                    'Supply_Fix_Charge1'=> '0'.'@'.round($nBRCtype['br_suprtlcust_fixed'],4),
                    'Supply_Sys_Charge'=> round(round($supSys,3),2).'@'.round($item->br_supsys_rate,4),
                    'Supply_Sys_Charge1'=> '0'.'@'.round($nBRCtype['br_supsys_rate'],4),
                    'Metering_Fix_Charge'=> round(round($meterFix,3),2).'@'.round($item->br_mtrrtlcust_fixed,4),
                    'Metering_Fix_Charge1'=> '0'.'@'.round($nBRCtype['br_mtrrtlcust_fixed'],4),
                    'Metering_Sys_Charge'=> round(round($meterSys,3),2).'@'.round($item->br_mtrsys_rate,4),
                    'Metering_Sys_Charge1'=> '0'.'@'.round($nBRCtype['br_mtrsys_rate'],4),
                    'Lifeline_Disc_Subs'=> round(round($lflineDiscSub,3),2).'@'.round($item->br_lfln_subs_rate,4),
                    'Lifeline_Disc_Subs1'=> '0'.'@'.round($nBRCtype['br_lfln_subs_rate'],4),
                    'Sen_Cit_Disc_Subs'=> round(round($senCitDS,3),2).'@'.round($item->br_sc_subs_rate,4),
                    'Sen_Cit_Disc_Subs1'=> '0'.'@'.round($nBRCtype['br_sc_subs_rate'],4),
                    'Int_Clss_Crss_Subs'=> round(round($intl,3),2).'@'.round($item->br_intrclscrssubrte,4),
                    'Int_Clss_Crss_Subs1'=> '0'.'@'.round($nBRCtype['br_intrclscrssubrte'],4),
                    'MCC_Capex'=> round(round($mccCapex,3),2).'@'.round($item->br_capex_rate,4),
                    'MCC_Capex1'=> '0'.'@'.round($nBRCtype['br_capex_rate'],4),
                    'Loan_Condonation'=> round(round($loanCond,3),2).'@'.round($item->br_loancon_rate_kwh,4),
                    'Loan_Condonation1'=> '0'.'@'.round($nBRCtype['br_loancon_rate_kwh'],4),
                    'Loan_Condonation_Fix'=> round(round($loanCondFix,3),2).'@'.round($item->br_loancon_rate_fix,4),
                    'Loan_Condonation_Fix1'=> '0'.'@'.round($nBRCtype['br_loancon_rate_fix'],4),
                    'Miss_Elect_SPUG'=> round(round($spug,3),2).'@'.round($item->br_uc4_miss_rate_spu,4),
                    'Miss_Elect_SPUG1'=> '0'.'@'.round($nBRCtype['br_uc4_miss_rate_spu'],4),
                    'Miss_Elect_RED'=> round(round($red,3),2).'@'.round($item->br_uc4_miss_rate_red,4),
                    'Miss_Elect_RED1'=> '0'.'@'.round($nBRCtype['br_uc4_miss_rate_red'],4),
                    'Envi_Charge'=> round(round($envCharge,3),2).'@'.round($item->br_uc6_envi_rate,4),
                    'Envi_Charge1'=> '0'.'@'.round($nBRCtype['br_uc6_envi_rate'],4),
                    'Equali_Taxes_Royalty'=> round(round($equaliRoyalty,3),2).'@'.round($item->br_uc5_equal_rate,4),
                    'Equali_Taxes_Royalty1'=> '0'.'@'.round($nBRCtype['br_uc5_equal_rate'],4),
                    'NPC_Str_Cons_Cost'=> round(round($npcStrCC,3),2).'@'.round($item->br_uc2_npccon_rate,4),
                    'NPC_Str_Cons_Cost1'=> '0'.'@'.round($nBRCtype['br_uc2_npccon_rate'],4),
                    'NPC_Stranded_Debt'=> round(round($npcStrDebt,3),2).'@'.round($item->br_uc1_npcdebt_rate,4),
                    'NPC_Stranded_Debt1'=> '0'.'@'.round($nBRCtype['br_uc1_npcdebt_rate'],4),
                    'FIT_All_Renew'=> round(round($fitAll,3),2).'@'.round($item->br_fit_all,4),
                    'FIT_All_Renew1'=> '0'.'@'.round($nBRCtype['br_fit_all'],4),
                    'Generation_Vat'=> round(round($genVat,3),2).'@'.round($item->br_vat_gen,4),
                    'Generation_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_gen'],4),
                    'Trans_Sys_Vat'=> round(round($transVat,3),2).'@'.round($item->br_vat_trans ,4),
                    'Trans_Sys_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_trans'],4),
                    'Trans_Dem_Vat'=> round(round($transDemVat,3),2).'@'.round($item->br_vat_transdem,4),
                    'Trans_Dem_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_transdem'],4),
                    'System_Loss_Vat'=> round(round($sysLossVat,3),2).'@'.round($item->br_vat_systloss,4),
                    'System_Loss_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_systloss'],4),
                    'Dist_Sys_Vat'=> round(round($distSysVat,3),2).'@'.round($item->br_vat_distrib_kwh,4),
                    'Dist_Sys_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_distrib_kwh'],4),
                    'Dist_Dem_Vat'=> round(round($distDemVat,3),2).'@'.round($item->br_vat_distdem,4),
                    'Dist_Dem_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_distdem'],4),
                    'Loan_Condo_Vat'=> round(round($loanCondVat,3),2).'@'.round($item->br_vat_loancondo,4),
                    'Loan_Condo_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_loancondo'],4),
                    'Loan_Cond_Fixed_Vat'=> round(round($loanCondFixVat,3),2).'@'.round($item->br_vat_loancondofix,4),
                    'Loan_Cond_Fixed_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_loancondofix'],4),
                    'Power_Act_Red_Vat'=> round(round($powerActVat,3),2).'@'.round($item->br_vat_par,4),
                    'Power_Act_Red_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_par'],4),
                    'Supply_Fix_Vat'=> round(round($supplyFixVat,3),2).'@'.round($item->br_vat_supfix,4),
                    'Supply_Fix_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_supfix'],4),
                    'Supply_Sys_Vat'=> round(round($supplySysVat,3),2).'@'.round($item->br_vat_supsys,4),
                    'Supply_Sys_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_supsys'],4),
                    'Metering_Fix_Vat'=> round(round($meterFixVat,3),2).'@'.round($item->br_vat_mtr_fix,4),
                    'Metering_Fix_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_mtr_fix'],4),
                    'Metering_Sys_Vat'=> round(round($meterSysVat,3),2).'@'.round($item->br_vat_metersys,4),
                    'Metering_Sys_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_metersys'],4),
                    'Lfln_Disc_Vat'=> round(round($lflnDiscVat,3),2).'@'.round($item->br_vat_lfln,4),
                    'Lfln_Disc_Vat1'=> '0'.'@'.round($nBRCtype['br_vat_lfln'],4),
                    'Other_Vat'=> 0,
                    'E_VAT'=> round(round($eVat,3),2),
                    'M_Arrears'=> round(round($totalArrear,3),2),
                    'Surcharge'=>0,
                    'Total_Due'=> round(round($totalArrear,3),2) + 0, // + surcharge
                    'Current_Bill'=>$item->mr_amount
                ];
            })->first();

        return response([
            'Adjusted_PBill'=>$mapped,
            ''
        ],200);
    }

    public function showConsCollecionTransaction($id)
    {
        $currentDate = Carbon::now(); 
        $totalDailyCollection = collect(
                DB::table('sales as s')
                ->select(DB::raw('sum(s.s_or_amount) as or_amount, sum(s.e_wallet_added) as ewAdded, s.s_or_num,s.s_bill_date'))
                ->whereDate('s.s_bill_date',$currentDate)
                ->where('s.teller_user_id',$id)
                ->groupBy('s.s_or_num')
                ->get()
        );

        $mappedTotalDailyCollection = $totalDailyCollection->map(function($item){
            return[
                'Total_Collection'=>$item->or_amount + $item->ewAdded,
                'OR_NUM'=> $item->s_or_num,
            ];
        });
        // dd($mappedTotalDailyCollection);
        $countOR = $mappedTotalDailyCollection->count();
        $sum = $mappedTotalDailyCollection->sum('Total_Collection');
        $voidCount = collect(
            DB::table('or_void')
            ->whereDate('v_date',$currentDate)
            ->where('v_user',$id)
            ->groupBy('v_or')
            ->get()
        );
        $countVoid = $voidCount->count();

        return response([
            'Total_Collection'=>$sum,
            'OR_No_Used'=>$countOR,
            'Void_count'=>$countVoid
        ]);
    }
    public function kwhThisYear(Request $request){

        $Kwh = DB::table('meter_reg')
            ->select('mr_kwh_used','mr_date_year_month')
            ->where(DB::raw('substr(mr_date_year_month,1,4)'),$request->year)
            ->where('cm_id',$request->id)
            ->get();

        $count=12;
        $thisYearArrayData = [];
        for($i=1;$i <= $count;$i++){
            if($i < 10){
                $year = substr($request->year,0,4).'0'.$i;
                $ins = $Kwh->where('mr_date_year_month',$year)->pluck('mr_kwh_used');
                

            }else{
                $year = substr($request->year,0,4).$i;
                $ins = $Kwh->where('mr_date_year_month',$year)->pluck('mr_kwh_used');
                // $thisYearArrayData[$i] = $ins;
            }

            if($ins->isEmpty()){
                array_push($thisYearArrayData,0);
            }else{
                array_push($thisYearArrayData,$ins);
            }
            
        }
        // $countKwh = count($Kwh);
        // $sum = $Kwh->sum('mr_kwh_used');
        // $avg = $sum / $countKwh;
        // dd($avg);

        $collection = collect($thisYearArrayData);
        $flat = $collection->flatten();
        
        return response()->json($flat,200);

    }
}