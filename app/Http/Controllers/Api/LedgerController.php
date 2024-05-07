<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    // public function showConsLedger($cmid)
    // {
    //     $consUPaidBill = collect(DB::table('meter_reg AS mr')
    //         ->join('cons_master AS cm','mr.cm_id','=','cm.cm_id')
    //         ->select('mr.mr_id','mr.mr_bill_no','mr.mr_date_year_month','mr.mr_prev_reading',
    //         'mr.mr_pres_reading','mr.mr_kwh_used','mr.mr_amount','mr.mr_applied','mr.ff_id')
    //         ->where('cm.cm_id',$cmid)
    //         ->where('mr.mr_status',0)
    //         ->where('mr.mr_printed',1)
    //         ->whereNull('mr.deleted_at')
    //         ->orderBy('mr.mr_date_year_month')
    //         ->get());
    //     $collectionUPB = $consUPaidBill->map(function($consUPaidBill){
    //         //for Adjusted Bill
    //         $queryAdj = collect(
    //             DB::table('adjusted_powerbill')
    //             ->where('mr_id',$consUPaidBill->mr_id)
    //             ->orderBy('ap_date','asc')
    //             ->get());
    //         if($queryAdj->isEmpty()){
    //             $adjBillAmount = '';
    //             $kwhUsed = '';
    //             $adjDate = '';
    //         }else{
    //             // dd($queryAdj);
    //             $adjBillAmount = $consUPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
    //             $kwhUsed = $consUPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
    //             $adjDate = $queryAdj->last()->ap_date;
    //         }
            
    //         if($consUPaidBill->ff_id == "" || $consUPaidBill->ff_id == NULL || $consUPaidBill->ff_id == 0){
    //             $overide = "no";
    //         }else{
    //             $overide = "yes";
    //         }
            
    //         return[
    //             'mr_id'=>$consUPaidBill->mr_id,
    //             'mr_bill_no'=>$consUPaidBill->mr_bill_no,
    //             'mr_date_year_month'=>$consUPaidBill->mr_date_year_month,
    //             'mr_amount'=>$consUPaidBill->mr_amount,
    //             'mr_prev_reading'=>$consUPaidBill->mr_prev_reading,
    //             'mr_pres_reading'=>$consUPaidBill->mr_pres_reading,
    //             'mr_kwh_used'=>$consUPaidBill->mr_kwh_used,
    //             'or_no'=>'',
    //             'or_date'=>'',
    //             'or_amount'=>'',
    //             'Adj_Date'=>$adjDate,
    //             'Adj_KWH_Used'=>round($kwhUsed,2),
    //             'Adj_Bill_Amt'=>round($adjBillAmount,2),
    //             'Current_Bill_Bal'=>$consUPaidBill->mr_amount,
    //             'Sur_Charge'=>'',
    //             'Posted'=>'',
    //             'Collected_Not_Posted'=>'',
    //             'E_Wallet_Applied'=> ($consUPaidBill->mr_applied > 0) ? $consUPaidBill->mr_applied : '' ,
    //             'overide'=> $overide,
    //         ];
    //     });

    //     $totalAmount = round(($collectionUPB->sum('Current_Bill_Bal')), 2);

    //     $consPaidBill = collect(DB::table('sales AS s')
    //         ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
    //         ->join('meter_reg AS mr','s.mr_id','=','mr.mr_id')
    //         ->select('s.mr_id','mr.mr_date_year_month','s.s_bill_no','s.s_bill_amount','mr.mr_kwh_used','mr.mr_amount',
    //             'mr.mr_prev_reading','mr.mr_pres_reading','s.s_or_num','s.s_bill_date','s.s_or_amount','s.s_ack_receipt',
    //             's.e_wallet_applied','s.ackn_date','s.s_mode_payment','s.e_wallet_added','mr.ff_id')
    //         ->where('cm.cm_id',$cmid)
    //         ->where('mr.mr_printed',1)
    //         ->whereNull('mr.deleted_at')
    //         ->orderBy('mr.mr_date_year_month')
    //         ->get());
    //     $ans='';
    //     $collectionPB = $consPaidBill->map(function($consPaidBill)use($ans){
    //         if($consPaidBill->ackn_date != NULL){
    //             $ans = 'YES';
    //         }else{
    //             $ans = 'NO';
    //         }
    //         //for Adjusted Bill
    //         $queryAdj = collect(
    //             DB::table('adjusted_powerbill')
    //             ->where('mr_id',$consPaidBill->mr_id)
    //             ->orderBy('ap_date','asc')
    //             ->get());
    //         if($queryAdj->isEmpty()){
    //             $adjBillAmount = '';
    //             $kwhUsed = '';
    //             $adjDate = '';
    //         }else{
    //             // dd($queryAdj);
    //             $adjBillAmount = $consPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
    //             $kwhUsed = $consPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
    //             $adjDate = $queryAdj->last()->ap_date;
    //         }

    //         if($consPaidBill->ff_id == "" || $consPaidBill->ff_id == NULL || $consPaidBill->ff_id == 0){
    //             $overide = "no";
    //         }else{
    //             $overide = "yes";
    //         }
    //         return[
    //             'mr_id'=>$consPaidBill->mr_id,
    //             'mr_bill_no'=>$consPaidBill->s_bill_no,
    //             'mr_date_year_month'=>$consPaidBill->mr_date_year_month,
    //             'mr_amount'=>$consPaidBill->mr_amount,
    //             'mr_prev_reading'=>$consPaidBill->mr_prev_reading,
    //             'mr_pres_reading'=>$consPaidBill->mr_pres_reading,
    //             'mr_kwh_used'=>$consPaidBill->mr_kwh_used,
    //             'or_no'=>($consPaidBill->s_or_num == '') ? $consPaidBill->s_mode_payment : $consPaidBill->s_or_num,
    //             'or_date'=>$consPaidBill->s_bill_date,
    //             'or_amount'=>($consPaidBill->s_or_amount == NULL) ? 0 : round($consPaidBill->s_or_amount + $consPaidBill->e_wallet_added,2),
    //             'Adj_Date'=>$adjDate,
    //             'Adj_KWH_used'=>round($kwhUsed,2),
    //             'Adj_Bill_Amt'=>round($adjBillAmount,2),
    //             'Current_Bill_Bal'=>'',
    //             'Sur_Charge'=>'',
    //             'Posted'=>$ans,
    //             'Collected_Not_Posted'=>$ans,
    //             'E_Wallet_Applied'=>($consPaidBill->e_wallet_applied == NULL) ? '' : $consPaidBill->e_wallet_applied,
    //             'overide'=> $overide,
    //         ];
    //     });
        
    //     $consumer_details = collect(
    //         DB::table('cons_master as cm')
    //         ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
    //         ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
    //         ->where('cm_id',$cmid)
    //         ->get()
    //     );
    //     $getEwalletID = DB::table('e_wallet')
    //         ->where('cm_id',$cmid)
    //         ->select('ew_id as ewallet_id')
    //         ->first();
    //     $consumerDetailsMapped = $consumer_details->map(function($item){
    //         return[
    //             'Account_No'=>$item->cm_account_no,
    //             'Account_Name'=>$item->cm_full_name,
    //             'Address'=>$item->cm_address,
    //             'Consumer_Type'=>$item->ct_desc,
    //             'Status'=>($item->cm_con_status == 1) ?'Active':'Disconnected',
    //             'Meter_Serial_No'=>($item->mm_serial_no == NULL) ?'N.A':$item->mm_serial_no,
    //         ];
    //     });

    //     // Update Satrts Here 10/26/2022
    //     $consEWalletLog = DB::table('sales AS s')
    //         ->select('s.s_or_num','s.e_wallet_applied','s.s_bill_date','s.e_wallet_added','s.ackn_date')
    //         ->where('s.cm_id',$cmid)
    //         ->whereNull('s.deleted_at')
    //         // ->where('s.e_wallet_added','>',0)
    //         // ->where('s.e_wallet_applied','>',0)
    //         ->where(function($query){
    //             $query->where('s.e_wallet_added','>',0)
    //             ->orWhere('s.e_wallet_applied','>',0);
    //         })
    //         ->orderBy('s.s_bill_date','asc')
    //         ->get();
        
    //     $map = $consEWalletLog->map(function($item){
    //         if($item->e_wallet_added > 0){
    //             $amount = $item->e_wallet_added;
    //             $status = 'U';
                
    //         }else{
    //             $amount = $item->e_wallet_applied;
    //             $status = 'A';
    //         } 


    //         return[
    //             'ewl_or'=> $item->s_or_num,
    //             'ewl_amount'=> $amount,
    //             'ewl_or_date'=> $item->s_bill_date,
    //             'ewl_status'=> $status,
    //         ];
    //     });
    //     // Add Another rows to the collection from meter_reg Applied Ewallet
    //     $mrApplied = DB::table('meter_reg AS mr')
    //                 ->select('mr_applied')
    //                 ->where('mr.cm_id',$cmid)
    //                 ->where('mr.mr_applied','>',0)
    //                 ->get();

    //     $appliedMap = $mrApplied->map(function($item){
    //         return[
    //             'ewl_or'=> '',
    //             'ewl_amount'=> $item->mr_applied,
    //             'ewl_or_date'=> '',
    //             'ewl_status'=> '',
    //         ];
    //     });
    //     $newmap = $map->merge($appliedMap);
    //     $log = round($map->where('ewl_status','U')->sum('ewl_amount') - $map->where('ewl_status','A')->sum('ewl_amount'),2);

    //     $ewalletBal = DB::table('e_wallet')
    //         ->select('ew_total_amount')
    //         ->where('cm_id',$cmid)
    //         ->first();

    //     $bal = $ewalletBal->ew_total_amount;
    //     if($log == $bal){
    //         $repAmount = 0;
    //     }else if($log != $bal){
    //         if($log > $bal){
    //             $calc = round($log - $bal,2);
    //             $repAmount = $calc * -1;
    //         }else if($log < $bal){
    //             $calc = round($bal - $log,2);
    //             $repAmount = $calc;
    //         }else{
    //             $repAmount = 0;
    //         }
    //     }else{
    //         $repAmount = 0;
    //     }
    //     // ends here

    //     $ewalletBal = DB::table('e_wallet')
    //         ->select('ew_total_amount')
    //         ->where('cm_id',$cmid)
    //         ->first();
    //     //merge paid and unPaid collection
    //     $merged = $collectionUPB->merge($collectionPB);

    //     //Cons Notification
    //     $consNotify = DB::table('cons_master as cm')
    //     ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
    //     ->where('cm.cm_id',$cmid)
    //     ->get();
    //     $mapNotify = $consNotify->map(function($items){
    //         return [
    //             'Remarks'=>$items->cn_remarks
    //         ];
    //     });
    //     return response([
    //         'Consumer_Details'=>$consumerDetailsMapped,
    //         'Consumer_Ewallet'=>$getEwalletID,
    //         'Total_Unpaid_Bills'=> $totalAmount,
    //         'Reconnection_FEE'=> 0,
    //         'Collectible'=> $totalAmount,
    //         'PB_Details'=> $merged->sortBy('mr_date_year_month')->values()->all(),
    //         // 'PB_Paid'=> $merged->sortBy('mr_date_year_month')->values()->all(),
    //         // 'PB_Paid'=> $collectionPB,
    //         'E_Wallet_Payments'=>$newmap,
    //         'E_Wallet_Balance'=>$ewalletBal,
    //         'additional'=>$repAmount,
    //         'Cons_Notify'=>$mapNotify
    //     ],200);
    // }
    public function showConsLedger($cmid)
    {
        $consUPaidBill = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','mr.cm_id','=','cm.cm_id')
            ->select('mr.mr_id','mr.mr_bill_no','mr.mr_date_year_month','mr.mr_prev_reading',
            'mr.mr_pres_reading','mr.mr_kwh_used','mr.mr_amount','mr.mr_applied','mr.ff_id')
            ->where('cm.cm_id',$cmid)
            ->where('mr.mr_status',0)
            ->where('mr.mr_printed',1)
            ->whereNull('mr.deleted_at')
            ->orderBy('mr.mr_date_year_month')
            ->get());
        $collectionUPB = $consUPaidBill->map(function($consUPaidBill){
            //for Adjusted Bill
            $queryAdj = collect(
                DB::table('adjusted_powerbill')
                ->where('mr_id',$consUPaidBill->mr_id)
                ->orderBy('ap_date','asc')
                ->get());
            if($queryAdj->isEmpty()){
                $adjBillAmount = '';
                $kwhUsed = '';
                $adjDate = '';
            }else{
                // dd($queryAdj);
                $adjBillAmount = $consUPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
                $kwhUsed = $consUPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
                $adjDate = $queryAdj->last()->ap_date;
            }
            
            if($consUPaidBill->ff_id == "" || $consUPaidBill->ff_id == NULL || $consUPaidBill->ff_id == 0){
                $overide = "no";
            }else{
                $overide = "yes";
            }
            
            return[
                'mr_id'=>$consUPaidBill->mr_id,
                'mr_bill_no'=>$consUPaidBill->mr_bill_no,
                'mr_date_year_month'=>$consUPaidBill->mr_date_year_month,
                'mr_amount'=>$consUPaidBill->mr_amount,
                'mr_prev_reading'=>$consUPaidBill->mr_prev_reading,
                'mr_pres_reading'=>$consUPaidBill->mr_pres_reading,
                'mr_kwh_used'=>$consUPaidBill->mr_kwh_used,
                'or_no'=>'',
                'or_date'=>'',
                'or_amount'=>'',
                'Adj_Date'=>$adjDate,
                'Adj_KWH_Used'=>round($kwhUsed,2),
                'Adj_Bill_Amt'=>round($adjBillAmount,2),
                'Current_Bill_Bal'=>$consUPaidBill->mr_amount,
                'Sur_Charge'=>'',
                'Posted'=>'',
                'Collected_Not_Posted'=>'',
                'E_Wallet_Applied'=> ($consUPaidBill->mr_applied > 0) ? $consUPaidBill->mr_applied : '' ,
                'overide'=> $overide,
            ];
        });

        $totalAmount = round(($collectionUPB->sum('Current_Bill_Bal')), 2);

        $consPaidBill = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->leftJoin('sales AS s','s.mr_id','=','mr.mr_id')
            ->leftJoin('archive as s2','s2.mr_id','=','mr.mr_id')
            // ()
            ->select(DB::raw('coalesce(s.mr_id,s2.mr_id) as mr_id'),'mr.mr_date_year_month',
            DB::raw('coalesce(s.s_bill_no,s2.s_bill_no) as s_bill_no'),DB::raw('coalesce(s.s_bill_amount,s2.s_bill_amount) as s_bill_amount'),
            'mr.mr_kwh_used','mr.mr_amount','mr.mr_prev_reading','mr.mr_pres_reading',
            DB::raw('coalesce(s.s_or_num,s2.s_or_num) as s_or_num'),
            DB::raw('coalesce(s.s_bill_date,s2.s_bill_date) as s_bill_date'),
            DB::raw('coalesce(s.s_or_amount,s2.s_or_amount) as s_or_amount'),
            DB::raw('coalesce(s.s_ack_receipt,s2.s_ack_receipt) as s_ack_receipt'),
            DB::raw('coalesce(s.e_wallet_applied,s2.e_wallet_applied) as e_wallet_applied'),
            DB::raw('coalesce(s.ackn_date,s2.ackn_date) as ackn_date'),
            DB::raw('coalesce(s.s_mode_payment,s2.s_mode_payment) as s_mode_payment'),
            DB::raw('coalesce(s.e_wallet_added,s2.e_wallet_added) as e_wallet_added'),
            'mr.ff_id')
            ->where('cm.cm_id',$cmid)
            ->where('mr.mr_printed',1)
            ->whereNull('mr.deleted_at')
            ->where(function ($query) {
                $query->whereNotNull('s.mr_id')->orWhereNotNull('s2.mr_id');
            })
            ->orderBy('mr.mr_date_year_month')
            ->get());
        // dd($consPaidBill);
        $ans='';
        $collectionPB = $consPaidBill->map(function($consPaidBill)use($ans){
            if($consPaidBill->ackn_date != NULL){
                $ans = 'YES';
            }else{
                $ans = 'NO';
            }
            //for Adjusted Bill
            $queryAdj = collect(
                DB::table('adjusted_powerbill')
                ->where('mr_id',$consPaidBill->mr_id)
                ->orderBy('ap_date','asc')
                ->get());
            if($queryAdj->isEmpty()){
                $adjBillAmount = '';
                $kwhUsed = '';
                $adjDate = '';
            }else{
                // dd($queryAdj);
                $adjBillAmount = $consPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
                $kwhUsed = $consPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
                $adjDate = $queryAdj->last()->ap_date;
            }

            if($consPaidBill->ff_id == "" || $consPaidBill->ff_id == NULL || $consPaidBill->ff_id == 0){
                $overide = "no";
            }else{
                $overide = "yes";
            }
            return[
                'mr_id'=>$consPaidBill->mr_id,
                'mr_bill_no'=>$consPaidBill->s_bill_no,
                'mr_date_year_month'=>$consPaidBill->mr_date_year_month,
                'mr_amount'=>$consPaidBill->mr_amount,
                'mr_prev_reading'=>$consPaidBill->mr_prev_reading,
                'mr_pres_reading'=>$consPaidBill->mr_pres_reading,
                'mr_kwh_used'=>$consPaidBill->mr_kwh_used,
                'or_no'=>($consPaidBill->s_or_num == '') ? $consPaidBill->s_mode_payment : $consPaidBill->s_or_num,
                'or_date'=>$consPaidBill->s_bill_date,
                'or_amount'=>($consPaidBill->s_or_amount == NULL) ? 0 : round($consPaidBill->s_or_amount + $consPaidBill->e_wallet_added,2),
                'Adj_Date'=>$adjDate,
                'Adj_KWH_used'=>round($kwhUsed,2),
                'Adj_Bill_Amt'=>round($adjBillAmount,2),
                'Current_Bill_Bal'=>'',
                'Sur_Charge'=>'',
                'Posted'=>$ans,
                'Collected_Not_Posted'=>$ans,
                'E_Wallet_Applied'=>($consPaidBill->e_wallet_applied == NULL) ? '' : $consPaidBill->e_wallet_applied,
                'overide'=> $overide,
            ];
        });
        
        $consumer_details = collect(
            DB::table('cons_master as cm')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
            ->where('cm_id',$cmid)
            ->get()
        );
        $getEwalletID = DB::table('e_wallet')
            ->where('cm_id',$cmid)
            ->select('ew_id as ewallet_id')
            ->first();
        $consumerDetailsMapped = $consumer_details->map(function($item){
            return[
                'Account_No'=>$item->cm_account_no,
                'Account_Name'=>$item->cm_full_name,
                'Address'=>$item->cm_address,
                'Consumer_Type'=>$item->ct_desc,
                'Status'=>($item->cm_con_status == 1) ?'Active':'Disconnected',
                'Meter_Serial_No'=>($item->mm_serial_no == NULL) ?'N.A':$item->mm_serial_no,
            ];
        });

        // Update Satrts Here 10/26/2022
        $consEWalletLog = DB::table('sales AS s')
            // ->join('archive as s2','s2.mr_id','=','mr.mr_id')
            ->select('s.s_or_num','s.e_wallet_applied','s.s_bill_date','s.e_wallet_added','s.ackn_date')
            ->where('s.cm_id',$cmid)
            ->whereNull('s.deleted_at')
            // ->where('s.e_wallet_added','>',0)
            // ->where('s.e_wallet_applied','>',0)
            ->where(function($query){
                $query->where('s.e_wallet_added','>',0)
                ->orWhere('s.e_wallet_applied','>',0);
            })
            ->orderBy('s.s_bill_date','asc')
            ->get();
        
        $map = $consEWalletLog->map(function($item){
            if($item->e_wallet_added > 0){
                $amount = $item->e_wallet_added;
                $status = 'U';
                
            }else{
                $amount = $item->e_wallet_applied;
                $status = 'A';
            } 


            return[
                'ewl_or'=> $item->s_or_num,
                'ewl_amount'=> $amount,
                'ewl_or_date'=> $item->s_bill_date,
                'ewl_status'=> $status,
            ];
        });
        // Add Another rows to the collection from meter_reg Applied Ewallet
        $mrApplied = DB::table('meter_reg AS mr')
                    ->select('mr_applied')
                    ->where('mr.cm_id',$cmid)
                    ->where('mr.mr_applied','>',0)
                    ->get();

        $appliedMap = $mrApplied->map(function($item){
            return[
                'ewl_or'=> '',
                'ewl_amount'=> $item->mr_applied,
                'ewl_or_date'=> '',
                'ewl_status'=> '',
            ];
        });
        $newmap = $map->merge($appliedMap);
        $log = round($map->where('ewl_status','U')->sum('ewl_amount') - $map->where('ewl_status','A')->sum('ewl_amount'),2);

        $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$cmid)
            ->first();

        $bal = $ewalletBal->ew_total_amount;
        if($log == $bal){
            $repAmount = 0;
        }else if($log != $bal){
            if($log > $bal){
                $calc = round($log - $bal,2);
                $repAmount = $calc * -1;
            }else if($log < $bal){
                $calc = round($bal - $log,2);
                $repAmount = $calc;
            }else{
                $repAmount = 0;
            }
        }else{
            $repAmount = 0;
        }
        // ends here

        $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$cmid)
            ->first();
        //merge paid and unPaid collection
        $merged = $collectionUPB->merge($collectionPB);

        //Cons Notification
        $consNotify = DB::table('cons_master as cm')
        ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
        ->where('cm.cm_id',$cmid)
        ->get();
        $mapNotify = $consNotify->map(function($items){
            return [
                'Remarks'=>$items->cn_remarks
            ];
        });
        return response([
            'Consumer_Details'=>$consumerDetailsMapped,
            'Consumer_Ewallet'=>$getEwalletID,
            'Total_Unpaid_Bills'=> $totalAmount,
            'Reconnection_FEE'=> 0,
            'Collectible'=> $totalAmount,
            'PB_Details'=> $merged->sortBy('mr_date_year_month')->values()->all(),
            // 'PB_Paid'=> $merged->sortBy('mr_date_year_month')->values()->all(),
            // 'PB_Paid'=> $collectionPB,
            'E_Wallet_Payments'=>$newmap,
            'E_Wallet_Balance'=>$ewalletBal,
            'additional'=>$repAmount,
            'Cons_Notify'=>$mapNotify
        ],200);
    }
    public function showConsLedgerRates($mrid)
    {
        $ledgerRates = collect(DB::table('meter_reg AS mr')
        ->join('cons_master AS cm','mr.cm_id','=','cm.cm_id')
        ->join('billing_rates AS br','mr.br_id','=','br.id')
        ->select('br.br_uc4_miss_rate_spu','br.br_uc4_miss_rate_red','br.br_uc6_envi_rate',
            'br.br_uc2_npccon_rate','mr.mr_date_reg','mr.mr_due_date')
        ->where('mr.mr_id',$mrid)
        ->whereNull('mr.deleted_at')
        ->get());

        $resource = $ledgerRates->map(function($ledgerRates){
            return [
                'UC_ME_SPUG'=>$ledgerRates->br_uc4_miss_rate_spu,
                'UC_ME_RED'=>$ledgerRates->br_uc4_miss_rate_red,
                'UC_EC'=>$ledgerRates->br_uc6_envi_rate,
                'UC_NPC_SCC'=>$ledgerRates->br_uc2_npccon_rate,
                'Advance_Payment'=>0.00,
                'E_VAT'=>0.00,
                'Total_UnPaid_Integ'=>0.00,
                'TSF_Rental'=>0.00,
                'Meter_Deposit'=>'',
                'Senior_Citizen_Discount'=>'',
                'Bill_Date'=>$ledgerRates->mr_date_reg,
                'Bill_Due_Date'=>$ledgerRates->mr_due_date,
            ];
        });
        return response([
            'Rates'=>$resource
        ],200);
    }
    public function printConsLedger($id)
    {
        $consDetails = DB::table('cons_master AS cm')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->select('cm.cm_account_no','cm.cm_full_name','cm_address','ct.ct_desc','cm.cm_con_status','mm.mm_serial_no')
            ->where('cm.cm_id',$id)
            ->first();

        ($consDetails->cm_con_status == 1) ? $consDetails->cm_con_status = 'Active' : $consDetails->cm_con_status = 'Deactivated';
        
        $consPaidBill = DB::table('sales AS s')
            ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
            ->join('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            ->select('s.mr_id','mr.mr_date_year_month','s.s_bill_no','mr.mr_kwh_used',
                'mr.mr_prev_reading','mr.mr_pres_reading','s.s_bill_amount','s.s_or_num',
                's.s_bill_date','s.s_or_amount','s.e_wallet_applied')
            ->where('cm.cm_id',$id)
            ->orderBy('s.s_bill_no', 'asc')
            ->get();

        $consUnPaidBill = DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','mr.cm_id','=','cm.cm_id')
            ->select('mr.mr_id','mr.mr_bill_no','mr.mr_date_year_month','mr.mr_prev_reading',
            'mr.mr_pres_reading','mr.mr_kwh_used','mr.mr_amount')
            ->where('cm.cm_id',$id)
            ->where('mr.mr_status',0)
            ->orderBy('mr.mr_date_year_month')
            ->get();
        
        $total_unpaidBill = DB::table('meter_reg')
            ->where('cm_id',$id)
            ->where('mr_status',0)
            ->sum('mr_amount');
            
        $consEWalletLog = DB::table('e_wallet AS ew')
            ->join('e_wallet_log AS ewl','ew.ew_id','=','ewl.ew_id')
            ->select('ewl.ewl_or','ewl.ewl_amount','ewl_or_date','ewl.ewl_status')
            ->where('ew.cm_id',$id)
            ->whereNull('ewl.deleted_at')
            ->orderBy('ewl_or_date','asc')
            ->get();
        
        $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$id)
            ->first();

        return response([
            'Consumer_Details'=>$consDetails,
            'Paid_Bills'=>$consPaidBill,
            'UnPaid_Bills'=>$consUnPaidBill,
            'E_Wallet_Payments'=>$consEWalletLog,
            'Total_UnPaid_Bills'=>$total_unpaidBill,
            'E_Wallet_Balance'=>$ewalletBal
        ],200);

    }
    public function printLedgerPerRoute(Request $request)
    {
        $consumerRoute = collect(
            DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
            ->where('rc.rc_id',$request->route_id)
            ->whereBetween(DB::raw('substr(cm.cm_account_no,-4)'),[$request->seq_from,$request->seq_to])
            ->orderBy('cm.cm_account_no', 'asc')
            ->get()
        );

        if($consumerRoute->isEmpty()){
            return response(['info'=>'No Consumer Found'],422);
        }

        $map = $consumerRoute->map(function($item){
            // Get Power Bills Paid or Not Paid
            $meter_reg = collect(
                DB::table('meter_reg as mr')
                    ->leftJoin('sales as s','mr.mr_id','=','s.mr_id')
                    ->leftJoin('adjusted_powerbill as ap','mr.mr_id','=','ap.mr_id')
                    ->where('mr.cm_id',$item->cm_id)
                    ->orderBy('mr.mr_date_year_month')
                    ->get()
            );
            // Map Bills (with Payments)and Add Algho
            $mapBill = $meter_reg->map(function($item){
                // for Adjusted Bill
                $queryAdj = collect(
                    DB::table('adjusted_powerbill')
                    ->where('mr_id',$item->mr_id)
                    ->orderBy('ap_date','asc')
                    ->get());
                if($queryAdj->isEmpty()){
                    $adjBillAmount = '';
                    $kwhUsed = '';
                    $adjDate = '';
                }else{
                    // dd($queryAdj);
                    $adjBillAmount = round($item->mr_amount - $queryAdj->last()->ap_old_amount,2);
                    $kwhUsed = $item->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
                    $adjDate = $queryAdj->last()->ap_date;
                }

                if($item->s_ack_receipt == NULL || $item->s_ack_receipt == '' || $item->s_ack_receipt == 0){
                    $post = 'No';
                }else{
                    $post = 'Yes';
                }
                return[
                    'year_month'=> $item->mr_date_year_month,
                    'bill_no'=> $item->mr_bill_no,
                    'pres_reading'=> $item->mr_pres_reading,
                    'prev_reading'=> $item->mr_prev_reading,
                    'kwh_used'=> $item->mr_kwh_used,
                    'bill_amount'=> $item->mr_amount,
                    'or_no'=> $item->s_or_num,
                    'or_date'=> $item->s_bill_date,
                    'or_amount'=> round($item->s_or_amount + $item->e_wallet_added,2),
                    'adj_date'=> $adjDate,
                    'adj_kwh_used'=> $kwhUsed,
                    'posted'=> $post,
                    'ewallet_applied' => ($item->e_wallet_applied > 0) ? $item->e_wallet_applied : ' ',
                    'adj_bill_amount'=> $adjBillAmount,
                    'current_bill_bal'=> $item->mr_amount,
                    'sur_charge'=> 0,
                ];
            });

            // Get total unpaid bills
            $totalUnpaidBill = round($mapBill->whereNull('or_no')->sum('bill_amount'),2);

            // GET  EWALLET_PAYMENT DETAILS
            $consEWalletLog = DB::table('sales AS s')
            ->select('s.s_or_num','s.e_wallet_applied','s.s_bill_date','s.e_wallet_added','s.ackn_date')
            ->where('s.cm_id',$item->cm_id)
            ->whereNull('s.deleted_at')
            // ->where('s.e_wallet_added','>',0)
            // ->where('s.e_wallet_applied','>',0)
            ->where(function($query){
                $query->where('s.e_wallet_added','>',0)
                ->orWhere('s.e_wallet_applied','>',0);
            })
            ->orderBy('s.s_bill_date','asc')
            ->get();
        
            $mapEWPAYMENT = $consEWalletLog->map(function($item){
                if($item->e_wallet_added > 0){
                    $amount = $item->e_wallet_added;
                    $status = 'U';
                    
                }else{
                    $amount = $item->e_wallet_applied;
                    $status = 'A';
                } 


                return[
                    'ewl_or'=> $item->s_or_num,
                    'ewl_amount'=> $amount,
                    'ewl_or_date'=> $item->s_bill_date,
                    'ewl_status'=> $status,
                ];
            });
            
            $log = round($mapEWPAYMENT->where('ewl_status','U')->sum('ewl_amount') - $mapEWPAYMENT->where('ewl_status','A')->sum('ewl_amount'),2);
            // GET THE EWALLET BALANCE
            $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$item->cm_id)
            ->first();

            $bal = $ewalletBal->ew_total_amount;
            if($log == $bal){
                $repAmount = 0;
            }else if($log != $bal){
                if($log > $bal){
                    $calc = round($log - $bal,2);
                    $repAmount = $calc * -1;
                }else if($log < $bal){
                    $calc = round($bal - $log,2);
                    $repAmount = $calc;
                }else{
                    $repAmount = 0;
                }
            }else{
                $repAmount = 0;
            }

            
            return[
                'account_no'=> $item->cm_account_no,
                'name'=> $item->cm_full_name,
                'address'=> $item->cm_address,
                'cons_type'=> $item->ct_desc,
                'cons_type'=> $item->ct_desc,
                'status'=> ($item->cm_con_status == 1) ? 'Active' : 'Disconnected',
                'meter'=> ($item->mm_serial_no == NULL) ? 'N.A' : $item->mm_serial_no,
                'pb_details'=> $mapBill,
                'ewallet_balance'=> $bal,
                'total_unpaid'=>$totalUnpaidBill,
                'surcharge' => 0,
                'reconnection_fee'=> 0,
                'total'=> $totalUnpaidBill,
                'ewallet_payment_details'=> $mapEWPAYMENT,
                'additional'=> $repAmount

            ];
        });
        
        return response(['info'=>$map],200);
    }
    public function showConsLedgerOnline($accountNo)
    {
        // dd($accountNo);
        $consId = DB::table('cons_master AS cm')
        ->select('cm.cm_id','cm.cm_full_name')
        ->where('cm.cm_account_no',$accountNo)
        ->first();

        $cmid=$consId->cm_id;
        $consUPaidBill = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','mr.cons_account','=','cm.cm_account_no')
            ->select('mr.mr_id','mr.mr_bill_no','mr.mr_date_year_month','mr.mr_prev_reading',
            'mr.mr_pres_reading','mr.mr_kwh_used','mr.mr_amount','mr.mr_applied')
            ->where('cm.cm_id',$cmid)
            ->where('mr.mr_status',0)
            ->where('mr.mr_printed',1)
            ->whereNull('mr.deleted_at')
            ->orderBy('mr.mr_date_year_month','desc')
            ->get());
            
        $collectionUPB = $consUPaidBill->map(function($consUPaidBill){
            // dd($consUPaidBill);
            $queryAdj = collect(
                DB::table('adjusted_powerbill')
                ->where('mr_id',$consUPaidBill->mr_id)
                ->orderBy('ap_date','desc')
                ->get());
            if($queryAdj->isEmpty()){
                $adjBillAmount = '';
                $kwhUsed = '';
                $adjDate = '';
            }else{
                // dd($queryAdj);
                $adjBillAmount = $consUPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
                $kwhUsed = $consUPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
                $adjDate = $queryAdj->last()->ap_date;
            }
            
            
            return[
                // 'cm_id' => $consUPaidBill->cm_id,
                'mr_id'=>$consUPaidBill->mr_id,
                'mr_bill_no'=>strval($consUPaidBill->mr_bill_no),
                'mr_date_year_month'=>$consUPaidBill->mr_date_year_month,
                'mr_amount'=>$consUPaidBill->mr_amount,
                'mr_prev_reading'=>$consUPaidBill->mr_prev_reading,
                'mr_pres_reading'=>$consUPaidBill->mr_pres_reading,
                'mr_kwh_used'=>$consUPaidBill->mr_kwh_used,
                'or_no'=>'',
                'or_date'=>'',
                'or_amount'=>'',
                'Adj_Date'=>$adjDate,
                'Adj_KWH_Used'=>round($kwhUsed,2),
                'Adj_Bill_Amt'=>round($adjBillAmount,2),
                'Current_Bill_Bal'=>$consUPaidBill->mr_amount,
                'Sur_Charge'=>'',
                'Posted'=>'',
                'Collected_Not_Posted'=>'',
                'E_Wallet_Applied'=> ($consUPaidBill->mr_applied > 0) ? $consUPaidBill->mr_applied : '' ,
            ];
        });
        
        $totalAmount = round(($collectionUPB->sum('Current_Bill_Bal')), 2);

        // $consPaidBill = collect(DB::table('sales AS s')
        //     ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
        //     ->join('meter_reg AS mr','s.mr_id','=','mr.mr_id')
        //     ->select('s.mr_id','mr.mr_date_year_month','s.s_bill_no','s.s_bill_amount','mr.mr_kwh_used','mr.mr_amount',
        //         'mr.mr_prev_reading','mr.mr_pres_reading','s.s_or_num','s.s_bill_date','s.s_or_amount','s.s_ack_receipt',
        //         's.e_wallet_applied','s.ackn_date','s.s_mode_payment','s.e_wallet_added')
        //     ->where('cm.cm_id',$cmid)
        //     ->where('mr.mr_printed',1)
        //     ->whereNull('mr.deleted_at')
        //     ->orderBy('mr.mr_date_year_month','desc')
        //     ->get());
        $consPaidBill = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->leftJoin('sales AS s','s.mr_id','=','mr.mr_id')
            ->leftJoin('archive as s2','s2.mr_id','=','mr.mr_id')
            // ()
            ->select(DB::raw('coalesce(s.mr_id,s2.mr_id) as mr_id'),'mr.mr_date_year_month',
            DB::raw('coalesce(s.s_bill_no,s2.s_bill_no) as s_bill_no'),DB::raw('coalesce(s.s_bill_amount,s2.s_bill_amount) as s_bill_amount'),
            'mr.mr_kwh_used','mr.mr_amount','mr.mr_prev_reading','mr.mr_pres_reading',
            DB::raw('coalesce(s.s_or_num,s2.s_or_num) as s_or_num'),
            DB::raw('coalesce(s.s_bill_date,s2.s_bill_date) as s_bill_date'),
            DB::raw('coalesce(s.s_or_amount,s2.s_or_amount) as s_or_amount'),
            DB::raw('coalesce(s.s_ack_receipt,s2.s_ack_receipt) as s_ack_receipt'),
            DB::raw('coalesce(s.e_wallet_applied,s2.e_wallet_applied) as e_wallet_applied'),
            DB::raw('coalesce(s.ackn_date,s2.ackn_date) as ackn_date'),
            DB::raw('coalesce(s.s_mode_payment,s2.s_mode_payment) as s_mode_payment'),
            DB::raw('coalesce(s.e_wallet_added,s2.e_wallet_added) as e_wallet_added'),
            'mr.ff_id')
            ->where('cm.cm_id',$cmid)
            ->where('mr.mr_printed',1)
            ->whereNull('mr.deleted_at')
            ->where(function ($query) {
                $query->whereNotNull('s.mr_id')->orWhereNotNull('s2.mr_id');
            })
            ->orderBy('mr.mr_date_year_month')
            ->get());
        $ans='';
        $collectionPB = $consPaidBill->map(function($consPaidBill)use($ans){
            if($consPaidBill->ackn_date != NULL){
                $ans = 'YES';
            }else{
                $ans = 'NO';
            }
            //for Adjusted Bill
            $queryAdj = collect(
                DB::table('adjusted_powerbill')
                ->where('mr_id',$consPaidBill->mr_id)
                ->orderBy('ap_date','desc')
                ->get());
            if($queryAdj->isEmpty()){
                $adjBillAmount = '';
                $kwhUsed = '';
                $adjDate = '';
            }else{
                // dd($queryAdj);
                $adjBillAmount = $consPaidBill->mr_amount - $queryAdj->last()->ap_old_amount ;
                $kwhUsed = $consPaidBill->mr_kwh_used - $queryAdj->last()->ap_old_kwh;
                $adjDate = $queryAdj->last()->ap_date;
            }
            return[
                'mr_id'=>$consPaidBill->mr_id,
                'mr_bill_no'=>strval($consPaidBill->s_bill_no),
                'mr_date_year_month'=>$consPaidBill->mr_date_year_month,
                'mr_amount'=>$consPaidBill->mr_amount,
                'mr_prev_reading'=>$consPaidBill->mr_prev_reading,
                'mr_pres_reading'=>$consPaidBill->mr_pres_reading,
                'mr_kwh_used'=>$consPaidBill->mr_kwh_used,
                'or_no'=>($consPaidBill->s_or_num == '') ? $consPaidBill->s_mode_payment : $consPaidBill->s_or_num,
                'or_date'=>$consPaidBill->s_bill_date,
                'or_amount'=>($consPaidBill->s_or_amount == NULL) ? 0 : round($consPaidBill->s_or_amount + $consPaidBill->e_wallet_added,2),
                'Adj_Date'=>$adjDate,
                'Adj_KWH_used'=>round($kwhUsed,2),
                'Adj_Bill_Amt'=>round($adjBillAmount,2),
                'Current_Bill_Bal'=>'',
                'Sur_Charge'=>'',
                'Posted'=>$ans,
                'Collected_Not_Posted'=>$ans,
                'E_Wallet_Applied'=>($consPaidBill->e_wallet_applied == NULL) ? '' : $consPaidBill->e_wallet_applied,
            ];
        });
        
        $consumer_details = collect(
            DB::table('cons_master as cm')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
            ->where('cm_id',$cmid)
            ->get()
        );
        $getEwalletID = DB::table('e_wallet')
            ->where('cm_id',$cmid)
            ->select('ew_id as ewallet_id')
            ->first();
        $consumerDetailsMapped = $consumer_details->map(function($item){
            return[
                'Account_No'=>$item->cm_account_no,
                'Account_Name'=>$item->cm_full_name,
                'Address'=>$item->cm_address,
                'Consumer_Type'=>$item->ct_desc,
                'Status'=>($item->cm_con_status == 1) ?'Active':'Disconnected',
                'Meter_Serial_No'=>($item->mm_serial_no == NULL) ?'N.A':$item->mm_serial_no,
            ];
        });

        // Update Satrts Here 10/26/2022
        $consEWalletLog = DB::table('sales AS s')
            ->select('s.s_or_num','s.e_wallet_applied','s.s_bill_date','s.e_wallet_added','s.ackn_date')
            ->where('s.cm_id',$cmid)
            ->whereNull('s.deleted_at')
            // ->where('s.e_wallet_added','>',0)
            // ->where('s.e_wallet_applied','>',0)
            ->where(function($query){
                $query->where('s.e_wallet_added','>',0)
                ->orWhere('s.e_wallet_applied','>',0);
            })
            ->orderBy('s.s_bill_date','asc')
            ->get();
        
        $map = $consEWalletLog->map(function($item){
            if($item->e_wallet_added > 0){
                $amount = $item->e_wallet_added;
                $status = 'U';
                
            }else{
                $amount = $item->e_wallet_applied;
                $status = 'A';
            } 


            return[
                'ewl_or'=> $item->s_or_num,
                'ewl_amount'=> $amount,
                'ewl_or_date'=> $item->s_bill_date,
                'ewl_status'=> $status,
            ];
        });
        // Add Another rows to the collection from meter_reg Applied Ewallet
        $mrApplied = DB::table('meter_reg AS mr')
                    ->select('mr_applied')
                    ->where('mr.cm_id',$cmid)
                    ->where('mr.mr_applied','>',0)
                    ->get();

        $appliedMap = $mrApplied->map(function($item){
            return[
                'ewl_or'=> '',
                'ewl_amount'=> $item->mr_applied,
                'ewl_or_date'=> '',
                'ewl_status'=> '',
            ];
        });
        $newmap = $map->merge($appliedMap);
        $log = round($map->where('ewl_status','U')->sum('ewl_amount') - $map->where('ewl_status','A')->sum('ewl_amount'),2);

        $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$cmid)
            ->first();

        $bal = $ewalletBal->ew_total_amount;
        if($log == $bal){
            $repAmount = 0;
        }else if($log != $bal){
            if($log > $bal){
                $calc = round($log - $bal,2);
                $repAmount = $calc * -1;
            }else if($log < $bal){
                $calc = round($bal - $log,2);
                $repAmount = $calc;
            }else{
                $repAmount = 0;
            }
        }else{
            $repAmount = 0;
        }
        // ends here

        $ewalletBal = DB::table('e_wallet')
            ->select('ew_total_amount')
            ->where('cm_id',$cmid)
            ->first();
        //merge paid and unPaid collection
        $merged = $collectionUPB->merge($collectionPB);

        //Cons Notification
        $consNotify = DB::table('cons_master as cm')
        ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
        ->where('cm.cm_id',$cmid)
        ->get();
        $mapNotify = $consNotify->map(function($items){
            return [
                'Remarks'=>$items->cn_remarks
            ];
        });
        return response([
            'Consumer_Details'=>$consumerDetailsMapped,
            'Consumer_Ewallet'=>$getEwalletID,
            'Total_Unpaid_Bills'=> $totalAmount,
            'Reconnection_FEE'=> 0,
            'Collectible'=> $totalAmount,
            'PB_Details'=> $merged->sortByDesc('mr_date_year_month')->values()->all(),
            // 'PB_Paid'=> $merged->sortBy('mr_date_year_month')->values()->all(),
            // 'PB_Paid'=> $collectionPB,
            'E_Wallet_Payments'=>$newmap,
            'E_Wallet_Balance'=>$ewalletBal,
            'additional'=>$repAmount,
            'Cons_Notify'=>$mapNotify
        ],200);
    }
}