<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Models\Sales;
use App\Models\EWALLET;
use Illuminate\Support\Facades\Storage;

class OnlineComController extends Controller
{
    public function onlineRead(Request $request){
        $cmID = $request->cons_id;
        $bp = intval(str_replace("-","",$request->billPeriod));
        $kwh = $request->reading;
        $kwh2 = $request->reading;
        $remarks = $request->remarks;
        $type = $request->type;
        $demand = intval($request->demand);
        $forBillNum = intval(substr($bp, 2));
        // dd($request->reading_attach);
        $readingAttachment= $request->reading_attach;
        $readingAttachmentDB = $request->accntNo . '_' . $bp . "" . $readingAttachment->getClientOriginalName();
        // dd($readingAttachmentDB);
        
        
        $checkExist = collect(DB::table('meter_reg')
        ->where('cm_id',$cmID)
        ->where('mr_date_year_month',$bp)
        ->where('mr_printed',1)
        ->first());

        if($checkExist->isNotEmpty()){
            return response()->json(['info'=>'Record Exist and Printed.', 'status_code'=>422],422);
        }
        $prevReadingRecord = 0;
        if($type == 'reading'){
            $date = Carbon::createFromFormat('Ym', $bp);
            // Calculate the previous month
            $previousBillPeriod = $date->subMonth()->format('Ym');
            $getKwhFromReading = DB::table('meter_reg')
            ->where('cm_id',$cmID)
            ->where('mr_date_year_month','<=',$previousBillPeriod)
            ->orderByDesc('mr_date_year_month')
            ->first();
            $prevReadingRecord = intval($getKwhFromReading->mr_pres_reading);
            $kwh = intval($kwh - $prevReadingRecord);
            if($kwh < 0){
                return response()->json(['info'=>'Invalid Reading. kWh: '.$kwh, 'status_code'=>422],422);
            }
            if($prevReadingRecord <= 0){
                return response()->json(['info'=>'Invalid Reading, Previous Reading Record is 0.', 'status_code'=>422],422);
            }
        }else if($type == 'overide'){
            $kwh = $request->reading;
        }else{
            return response()->json(['info'=>'Invalid Type of Reading.', 'status_code'=>422],422);
        }
        $ctype = DB::table('cons_master as cm')
        ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
        ->where('cm_id',$cmID)
        ->first();
        if(!$ctype){
            return response()->json(['info'=>'Fetching Consumer Type!', 'status_code'=>422],422);
        }

        $ctID = $ctype->ct_id;

        $rates = collect(
            DB::table('billing_rates')
            ->where('br_billing_ym',$bp)
            ->where('cons_type_id',$ctID)
            ->get()
        );
        // dd($bp . '-' . $ctID);
        if($rates->isEmpty()){
            return response()->json(['info'=>'Fetching Rates!', 'status_code'=>422],422);
        }

        $map = $rates->map(function($item) use($kwh,$demand,$ctID,$ctype,$cmID,$type,$forBillNum,$prevReadingRecord,$kwh2,$remarks){
            $genSysCharge = round(round($item->br_gensys_rate * $kwh,3),2);
            $powerActRed = round(round($item->br_par_rate * $kwh,3),2);
            $franBenToHost = round(round($item->br_fbhc_rate * $kwh,3),2);
            $forexAdjCharge = round(round($item->br_forex_rate * $kwh,3),2);
            // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
            $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
            // dd($subTotalGc);
            /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
            $tranSysCharge = round(round($item->br_transsys_rate * $kwh,3),2);
            $tranDemCharge = round(round($item->br_transdem_rate * $demand,3),2);
            $sysLossCharge = round(round($item->br_sysloss_rate * $kwh,3),2);
            $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
            // dd($subTotalTc);
            /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
            $distSysCharge = round(round($item->br_distsys_rate * $kwh,3),2);
            $distDemCharge = round(round($item->br_distdem_rate * $demand,3),2);
            $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
            $supSysCharge = round(round($item->br_supsys_rate * $kwh,3),2);
            $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
            $meterSysCharge = round(round($item->br_mtrsys_rate * $kwh,3),2);
            $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
            // dd($subTotalDc);
            /*----------------------------------------- OTHER CHARGES --------------------------------------*/
            if($kwh <= 25 && $ctID == 8){
                $setLifeline = DB::table('lifeline_rates')
                ->where('ll_min_kwh','<=',floatval($kwh))
                ->where('ll_max_kwh','>=',floatval($kwh))
                ->first();
                $lifeline = round($genSysCharge + $franBenToHost + $forexAdjCharge + $tranSysCharge + $sysLossCharge
                + $distSysCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2) * ($setLifeline->ll_discount /100);
                $lifeline = $lifeline * -1;
            }else{
                $lifeline = round(round($item->br_lfln_subs_rate * $kwh,3),2);
            }
            $lflnDiscSubs = $lifeline;
            // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $kwh,3),2);
            $senCitDiscSubs = round(round($item->br_sc_subs_rate * $kwh,3),2);
            $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $kwh,3),2);
            $mccCapex = round(round($item->br_capex_rate * $kwh,3),2);
            $loanCond = round(round($item->br_loancon_rate_kwh * $kwh,3),2);
            $loanConFix = round($item->br_loancon_rate_fix,2); //fix
            $subTotal = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
            /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
            $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $kwh,3),2);
            $missElectRED = round(round($item->br_uc4_miss_rate_red * $kwh,3),2);
            $enviChrg = round(round($item->br_uc6_envi_rate * $kwh,3),2);
            $equaliRoyalty = round(round($item->br_uc5_equal_rate * $kwh,3),2);
            $npcStrCC = round(round($item->br_uc2_npccon_rate * $kwh,3),2);
            $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $kwh,3),2);
            $fitAll = round(round($item->br_fit_all * $kwh,3),2);
            $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
            /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
            if($kwh <= 25 && $ctID == 8){
                $lflnDIscSubsVat = 0;
            }else{
                $lflnDIscSubsVat = round(round($item->br_vat_lfln * $kwh,3),2);
            }
            $genVat = round(round($item->br_vat_gen * $kwh,3),2);
            $powerActRedVat = round(round($item->br_vat_par * $kwh,3),2);
            $tranSysVat = round(round($item->br_vat_trans * $kwh,3),2);
            $transDemVat = round(round($item->br_vat_transdem * $demand,3),2);
            $sysLossVat = round(round($item->br_vat_systloss * $kwh,3),2);
            $distSysVat = round(round($item->br_vat_distrib_kwh * $kwh,3),2);
            $distDemVat = round(round($item->br_vat_distdem * $demand,3),2);
            $supplyFixVat = round(round($item->br_vat_supfix,3),2);
            $supplySysVat = round(round($item->br_vat_supsys * $kwh,3),2);
            $meterFixVat = round($item->br_vat_mtr_fix,2);
            $meterSysVat = round(round($item->br_vat_metersys * $kwh,3),2);
            // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $kwh,3),2);
            // $lflnDIscSubsVat = ($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $kwh,3),2);
            $loanCondVat = round(round($item->br_vat_loancondo * $kwh,3),2);
            $loanCondFixVat = round($item->br_vat_loancondofix,2);
            $otherVat = 0;
            $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
                $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');
        
            $totalAmount = round($subTotalGc + $subTotalTc + $subTotalDc + $subTotal + $subTotalUc + $subTotalVat,2);
            if($type == 'overide'){
                $type = 6;
            }else{
                $type = 0;
            }
            $date =  Carbon::now();
            $dateDue =  $date->addDays(20)->format('Y-m-d');
            $dateDisco =  carbon::parse($dateDue)->addDays(3)->format('Y-m-d');

            $createOrInset = DB::table('meter_reg')->updateOrInsert(
                ['mr_bill_no' => $forBillNum.$ctype->cm_account_no], // Conditions to find the record
                [
                    'br_id'=>$item->id,
                    'cm_id'=>$cmID,
                    // 'ff_id'=>$type,
                    'cons_account'=>$ctype->cm_account_no,
                    'mr_bill_no'=>$forBillNum.$ctype->cm_account_no,
                    'mr_amount'=>$totalAmount,
                    'mr_kwh_used'=>$kwh,
                    'mr_prev_reading'=>$prevReadingRecord,
                    'mr_pres_reading'=>($type == 6) ? 0 : $kwh2,
                    'mr_date_year_month'=> $item->br_billing_ym,
                    'mr_status'=> 0,
                    'mr_date_reg'=> Carbon::now()->format('Y-m-d'),
                    'mr_due_date'=> $dateDue,
                    'mr_discon_date'=> $dateDisco,
                    'mr_printed'=> 0,
                    'mr_pres_dem_reading'=> $demand,
                    'temp_user_id'=> $remarks
                ]
            );

            $id = DB::table('meter_reg')
                ->where('mr_bill_no', $forBillNum.$ctype->cm_account_no)
                ->value('mr_id');
            return[
                'mr_amount'=>$totalAmount,
                'id'=>$id,
            ];
        });
        $destinationPath = 'online_app';
        Storage::disk('disk_d')->putFileAs($destinationPath.'/meter_reading_attachment/', $readingAttachment, $readingAttachmentDB);
        return response()->json(['info'=>'Reading Successful!', 'status_code'=>200],200);
    }
    public function verifyAccount(Request $request){
        $query = collect(DB::table('cons_master as cm')
        ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
        ->where('cm_account_no',$request->account_no)
        ->limit(1)
        ->get());
        if($query->isEmpty()){
            return response()->json(['info'=> 'No Record Found'],422);
        }
        $map = $query->mapWithKeys(function($item){
            if(!$item->mm_serial_no){
                $meter = 'No Meter';
            }else{
                $meter = $item->mm_serial_no;
            }
            if($item->cm_con_status == 1){
                $status ='Active';
            }else if($item->cm_con_status == 0){
                $status ='Deactivated';
            }else{
                $status ='Pending';
            }
            return[
                'cons_id'=>$item->cm_id,
                'name'=>$item->cm_full_name,
                'meter'=>$meter,
                'status'=>$status,
                'address'=>$item->cm_address,
            ];
        });

        return response()->json(['info'=>$map],200);
    }
    public function printOnline(Request $request){
        $billPeriod = $request->billing_period;
        $id = $request->bill_id;
        $printDetails = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates AS br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->where('mr.mr_date_year_month',$billPeriod)
            ->whereNotNull('mr.mr_date_reg')
            ->where('mr.mr_id',$id)
            ->get());
        $check = $printDetails->first();
        if(!$check)
        {
            return response()->json(['Message' => 'No Records Found'], 422);
        }
        $consUpdateDueNDiscs = DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->where('cm.cm_id',$printDetails->first()->cm_id)
            ->where('mr.mr_printed',0)
            ->where('mr.mr_date_year_month',$billPeriod)
            ->update([
                'mr_printed' => 1,
            ]);
        $mapped = $printDetails->map(function($item) {
            $yearMonthConv = date('F, Y', strtotime($item->mr_date_year_month.'01'));
            $dueDate1 = date('F d, Y', strtotime($item->mr_due_date));
            //Query for prev Month $periodFrom
            $periodFrom = collect(DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('cm.cm_id',$item->cm_id)
                ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
                ->whereNotNull('mr.mr_date_reg')
                ->orderBy('mr.mr_date_year_month','desc')
                ->first());
            
            if(!isset($periodFrom['mr_date_reg']))
            {
                $periodFrom = $item->mr_date_reg;
                // $item->mr_date_reg = 0;
            }else{
                $periodFrom = $periodFrom['mr_date_reg'];
            }

            $periodFromConv = date('m/d/Y', strtotime($periodFrom));
            $periodToConv = date('m/d/Y', strtotime($item->mr_date_reg));

            $datetime1 = new DateTime($periodFromConv);
            $datetime2 = new DateTime($periodToConv);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');

            //Query Total Arrears
            $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();
            //Query Consumer E_Wallet
            $ewallet = collect(DB::table('e_wallet')
                ->where('cm_id',$item->cm_id)
                ->select('ew_total_amount')
                ->first());
            //Query Last Payment
            $lastPayment = collect(DB::table('sales as s')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
                // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
                ->where('s.cm_id',$item->cm_id)
                ->where('mr.mr_status',1)
                ->orderByDesc('s.s_bill_date')
                ->groupBy('s.s_bill_date')
                ->first());
            
            $checkLastPayment = $lastPayment->first();
            if(!$checkLastPayment)
            {
                $lastPayment = 'No Payment Received';
            }else{
                // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

                $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
            }

            $genSysCharge = round(round($item->br_gensys_rate * $item->mr_kwh_used,3),2);
            $powerActRed = round(round($item->br_par_rate * $item->mr_kwh_used,3),2);
            $franBenToHost = round(round($item->br_fbhc_rate * $item->mr_kwh_used,3),2);
            $forexAdjCharge = round(round($item->br_forex_rate * $item->mr_kwh_used,3),2);
            // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
            $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
            /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
            $tranSysCharge = round(round($item->br_transsys_rate * $item->mr_kwh_used,3),2);
            $tranDemCharge = round(round($item->br_transdem_rate * $item->mr_dem_kwh_used,3),2);
            $sysLossCharge = round(round($item->br_sysloss_rate * $item->mr_kwh_used,3),2);
            $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
            /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
            $distSysCharge = round(round($item->br_distsys_rate * $item->mr_kwh_used,3),2);
            $distDemCharge = round(round($item->br_distdem_rate * $item->mr_dem_kwh_used,3),2);
            $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
            $supSysCharge = round(round($item->br_supsys_rate * $item->mr_kwh_used,3),2);
            $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
            $meterSysCharge = round(round($item->br_mtrsys_rate * $item->mr_kwh_used,3),2);
            $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
            /*----------------------------------------- OTHER CHARGES --------------------------------------*/
            $lflnDiscSubs = ($item->mr_lfln_disc != 0) ? $item->mr_lfln_disc * -1 : round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
            // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
            $senCitDiscSubs = round(round($item->br_sc_subs_rate * $item->mr_kwh_used,3),2);
            $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $item->mr_kwh_used,3),2);
            $mccCapex = round(round($item->br_capex_rate * $item->mr_kwh_used,3),2);
            $loanCond = round(round($item->br_loancon_rate_kwh * $item->mr_kwh_used,3),2);
            $loanConFix = round($item->br_loancon_rate_fix,2); //fix
            $subTotalOc = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
            /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
            $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $item->mr_kwh_used,3),2);
            $missElectRED = round(round($item->br_uc4_miss_rate_red * $item->mr_kwh_used,3),2);
            $enviChrg = round(round($item->br_uc6_envi_rate * $item->mr_kwh_used,3),2);
            $equaliRoyalty = round(round($item->br_uc5_equal_rate * $item->mr_kwh_used,3),2);
            $npcStrCC = round(round($item->br_uc2_npccon_rate * $item->mr_kwh_used,3),2);
            $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $item->mr_kwh_used,3),2);
            $fitAll = round(round($item->br_fit_all * $item->mr_kwh_used,3),2);
            $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
            /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
            $genVat = round(round($item->br_vat_gen * $item->mr_kwh_used,3),2);
            $powerActRedVat = round(round($item->br_vat_par * $item->mr_kwh_used,3),2);
            $tranSysVat = round(round($item->br_vat_trans * $item->mr_kwh_used,3),2);
            $transDemVat = round(round($item->br_vat_transdem * $item->mr_dem_kwh_used,3),2);
            $sysLossVat = round(round($item->br_vat_systloss * $item->mr_kwh_used,3),2);
            $distSysVat = round(round($item->br_vat_distrib_kwh * $item->mr_kwh_used,3),2);
            $distDemVat = round(round($item->br_vat_distdem * $item->mr_dem_kwh_used,3),2);
            $supplyFixVat = round(round($item->br_vat_supfix,3),2);
            $supplySysVat = round(round($item->br_vat_supsys * $item->mr_kwh_used,3),2);
            $meterFixVat = round($item->br_vat_mtr_fix,2);
            $meterSysVat = round(round($item->br_vat_metersys * $item->mr_kwh_used,3),2);
            // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            $lflnDIscSubsVat = ($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            $loanCondVat = round(round($item->br_vat_loancondo * $item->mr_kwh_used,3),2);
            $loanCondFixVat = round($item->br_vat_loancondofix,2);
            $otherVat = 0;
            $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
                $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');
            return[
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                'BILL_NO'=>$item->mr_bill_no,
                'LAST_PAYMENT'=>$lastPayment,
                'Name'=>$item->cm_full_name,
                'Account_No'=>$item->cm_account_no,
                'Seq'=>$item->cm_seq_no,
                'Address'=>$item->cm_address,
                'Bill_Period'=>$yearMonthConv,
                'Meter_Number'=>$item->mm_serial_no,
                'Period_From'=>$periodFromConv,
                'Period_To'=>$periodToConv,
                'No_Days_Covered'=>$days,
                'Rate_Type'=>$item->ct_desc,
                'Multiplier'=>number_format($item->cm_kwh_mult, 2, '.', ''),
                'Pres_Reading'=>$item->mr_pres_reading,
                'Prev_Reading'=>$item->mr_prev_reading,
                'Total_KWH_Used'=>$item->mr_kwh_used,
                'Demand_Pres_Reading'=>$item->mr_pres_dem_reading,
                'Demand_Kwh_used'=>$item->mr_dem_kwh_used,
                /* --------------------------------------------------- GENERATION CHARGES -----------------------------------------------*/
                // 'Gen_Sys_Chrg'=>number_format(round($item->br_gensys_rate,4),4,'.','').'/kwh'.'@'.number_format($genSysCharge,2,'.',''),
                // 'Power_Act_Red'=>number_format(round($item->br_par_rate,4),4,'.','').'/kwh'.'@'.number_format($powerActRed,2,'.',''),
                // 'Fran_Ben_To_Host'=>number_format(round($item->br_fbhc_rate,4),4,'.','').'/kwh'.'@'.number_format($franBenToHost,2,'.',''),
                // 'FOREX_Adjust_Charge'=>number_format(round($item->br_forex_rate,4),4,'.','').'/kwh'.'@'.number_format($forexAdjCharge,2,'.',''),
                'SUB_TOTAL_GENERATION_CHARGE'=>round($subTotalGc,2),
                /*---------------------------------------------------- TRANSMISSION CHARGES ----------------------------------------------*/
                // 'Trans_Sys_Charge'=>number_format(round($item->br_transsys_rate,4),4,'.','').'/kwh'.'@'.number_format($tranSysCharge,2,'.',''),
                // 'Trans_Dem_Charge'=>number_format(round($item->br_transdem_rate,4),4,'.','').'/kW'.'@'.number_format($tranDemCharge,2,'.',''),
                // 'System_Loss_Charge'=>number_format(round($item->br_sysloss_rate,4),4,'.','').'/kwh'.'@'.number_format($sysLossCharge,2,'.',''),
                'SUB_TOTAL_TRANSMISSION_CHARGE'=>round($subTotalTc,2),
                /*----------------------------------------------------- DISTRIBUTION CHARGES ---------------------------------------------*/
                // 'Dist_Sys_Chrg'=>number_format(round($item->br_distsys_rate,4),4,'.','').'/kwh'.'@'.number_format($distSysCharge,2,'.',''),
                // 'Dist_Dem_Chrg'=>number_format(round($item->br_distdem_rate,4),4,'.','').'/kW'.'@'.number_format($distDemCharge,2,'.',''),
                // 'Supply_Fix_Chrg'=>number_format(round($item->br_suprtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($supFixCharge,2,'.',''),
                // 'Supply_Sys_Chrg'=>number_format(round($item->br_supsys_rate,4),4,'.','').'/kwh'.'@'.number_format($supSysCharge,2,'.',''),
                // 'Metering_Fix_Chrg'=>number_format(round($item->br_mtrrtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($meterFixCharge,2,'.',''),
                // 'Metering_Sys_Chrg'=>number_format(round($item->br_mtrsys_rate,4),4,'.','').'/kwh'.'@'.number_format($meterSysCharge,2,'.',''),
                'Sub_Total_DISTRIBUTION_CHARGE'=>round($subTotalDc,2),
                /*-------------------------------------------------------- OTHER CHARGES -------------------------------------------------*/
                // 'Lfln_Disc_Subs'=>($item->mr_lfln_disc != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($item->mr_lfln_disc,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
                // // 'Lfln_Disc_Subs'=>number_format(round($lflnDiscSubs,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
                // 'Sen_Cit_Disc_Subs'=>number_format(round($item->br_sc_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($senCitDiscSubs,2,'.',''),
                // 'Int_Clss_Crss_Subs'=>number_format(round($item->br_intrclscrssubrte,4),4,'.','').'/kwh'.'@'.number_format($intClssCrssSubs,2,'.',''),
                // 'MCC_CAPEX'=>number_format(round($item->br_capex_rate,4),4,'.','').'/kwh'.'@'.number_format($mccCapex,2,'.',''),
                // 'Loan_Condonation'=>number_format(round($item->br_loancon_rate_kwh,4),4,'.','').'/kwh'.'@'.number_format($loanCond,2,'.',''),
                // 'Loan_Condon_Fix'=>number_format(round($item->br_loancon_rate_fix,4),4,'.','').'/cst'.'@'.number_format($loanConFix,2,'.',''),
                'SUB_TOTAL_OTHERS_CHARGE'=>round($subTotalOc,2),
                /*------------------------------------------------------ UNIVERSAL CHARGES -----------------------------------------------*/
                // 'Miss_Elect_SPUG'=>number_format(round($item->br_uc4_miss_rate_spu,4),4,'.','').'/kwh'.'@'.number_format($missElectSPUG,2,'.',''),
                // 'Miss_Elect_RED'=>number_format(round($item->br_uc4_miss_rate_red,4),4,'.','').'/kwh'.'@'.number_format($missElectRED,2,'.',''),
                // 'Envi_Chrg'=>number_format(round($item->br_uc6_envi_rate,4),4,'.','').'/kwh'.'@'.number_format($enviChrg,2,'.',''),
                // 'Equali_Of_Taxes_Royalty'=>number_format(round($item->br_uc5_equal_rate,4),4,'.','').'/kwh'.'@'.number_format($equaliRoyalty,2,'.',''),
                // 'NPC_Str_Cons_Cost'=>number_format(round($item->br_uc2_npccon_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrCC,2,'.',''),
                // 'NPC_Str_Debt'=>number_format(round($item->br_uc1_npcdebt_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrDebt,2,'.',''),
                // 'Fit_All_RENEW'=>number_format(round($item->br_fit_all,4),4,'.','').'/kwh'.'@'.number_format($fitAll,2,'.',''),
                'SUB_TOTAL_UNIVERSAL_CHARGE'=>round($subTotalUc,2),
                /*------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------*/
                // 'Generation_Vat'=>number_format(round($item->br_vat_gen,4),4,'.','').'/kwh'.'@'.number_format($genVat,2,'.',''),
                // 'Power_Act_Red_Vat' => number_format(round($item->br_vat_par,4),4,'.','').'/kwh'.'@'.number_format($powerActRedVat,2,'.',''),
                // 'Trans_Sys_Vat'=>number_format(round($item->br_vat_trans,4),4,'.','').'/kwh'.'@'.number_format($tranSysVat,2,'.',''),
                // 'Trans_Dem_Vat'=>number_format(round($item->br_vat_transdem,4),4,'.','').'/kW'.'@'.number_format($transDemVat,2,'.',''),
                // 'Sys_Loss_Vat'=>number_format(round($item->br_vat_systloss,4),4,'.','').'/kwh'.'@'.number_format($sysLossVat,2,'.',''),
                // 'Dist_Sys_Vat'=>number_format(round($item->br_vat_distrib_kwh,4),4,'.','').'/kwh'.'@'.number_format($distSysVat,2,'.',''),
                // 'Dist_Dem_Vat'=>number_format(round($item->br_vat_distdem,4),4,'.','').'/kW'.'@'.number_format($distDemVat,2,'.',''),
                // 'Supply_Fix_Vat' => number_format(round($item->br_vat_supfix,4),4,'.','').'/cst'.'@'.number_format($supplyFixVat,2,'.',''), // display supply sys
                // 'Supply_Sys_Vat' => number_format(round($item->br_vat_supsys,4),4,'.','').'/kwh'.'@'.number_format($supplySysVat,2,'.',''), // display supply sys
                // 'Metering_Fix_Vat' => number_format(round($item->br_vat_mtr_fix,4),4,'.','').'/cst'.'@'.number_format($meterFixVat,2,'.',''), //edit
                // 'Metering_Sys_Vat' => number_format(round($item->br_vat_metersys,4),4,'.','').'/kwh'.'@'.number_format($meterSysVat,2,'.',''),
                // // 'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''), //edit
                // 'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''),
                // 'Loan_Cond_Vat'=>number_format(round($item->br_vat_loancondo,4),4,'.','').'/kwh'.'@'.number_format($loanCondVat,2,'.',''),
                // 'Loan_Cond_Fix_Vat'=>number_format(round($item->br_vat_loancondofix,4),4,'.','').'/cst'.'@'.number_format($loanCondFixVat,2,'.',''),
                // 'Other_Vat'=>number_format(round($otherVat,4),4,'.','').'/kwh'.'@'.number_format($otherVat,2,'.',''),
                'SUB_TOTAL_GENERATION_VAT'=>round($subTotalVat,2),
                /* ------------------------------------------------------- TOTALS DETAILS -----------------------------------------------*/
                'CURRENT_MONTH'=>$item->mr_amount,
                'Total_Arrears'=>round($totalArrear,2),
                'Material_Cost_Integ'=>0,
                'Transformer_Rental'=>0,
                'E_Wallet'=>$ewallet['ew_total_amount'],
                'Total_Amount_Due'=>round($item->mr_amount + $totalArrear,2),
                'LGU_2'=>($item->cm_lgu2 == 1) ? round(($item->mr_amount/1.12) * 0.02,2) : 0, //add column going to store
                'LGU_5'=>($item->cm_lgu5 == 1) ? round(($item->mr_amount/1.12) * 0.05,2) : 0, //add column going to store
                'Total_7%_Discount'=>0, //add column going to store
                'Due_Date'=>$dueDate1
                ];
        });

        return response()->json(['info'=>$mapped], 200);

    }
    public function script(Request $request){
        $orNum = $request->or;
        $newOr = 0;
        $amount = $request->amount;
        $accountFrom = $request->acc_from;
        $newAccntFrom = 0;
        $count = $request->count;
        $current_date_time = Carbon::now()->toDateTimeString();
        for($i = 0;$i < $count;$i++){
            $newOr = $orNum + $i;
            $newAccntFrom = $accountFrom + $i;
            $cmID = DB::table('cons_master')
            ->where('cm_account_no',$newAccntFrom)
            ->first();

            $sales = new Sales;
            $sales->s_bill_date = $current_date_time;
            $sales->ct_id =$cmID->ct_id;
            $sales->cm_id = $cmID->cm_id;
            $sales->s_status = 0;
            // $sales->s_or_amount = $request->or_amount;
            $sales->s_bill_amount = $amount;
            $sales->s_or_num = $newOr;
            $sales->teller_user_id = 41;
            $sales->s_mode_payment = 'Deposit_Ewallet';
            $sales->e_wallet_added = $amount;
            $sales->s_bill_date_time = Carbon::now()->toTimeString();
            if($sales->save()){
                $checkEwallet = DB::table('e_wallet')
                ->where('cm_id',$cmID->cm_id)
                ->first();

                $updateEWallet = EWALLET::find($checkEwallet->ew_id);
                $updateEWallet->ew_total_amount = $updateEWallet->ew_total_amount + $amount;
                $updateEWallet->save();
            }
        }

        return response(['info'=>'succesfully'],200);

    }
}
