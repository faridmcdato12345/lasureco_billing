<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EWalletLogResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EWalletController extends Controller
{
    // public function getEwallet($id)
    // {
    //     $getEwallet = collect(DB::table('meter_reg as mr')
    //         ->join('sales as s','mr.mr_id','=','s.mr_id')
    //         ->join('e_wallet_log as ewl',function($join) use ($id)
    //         {
    //             $join->on('s.s_or_num','=', 'ewl.ewl_or')
    //             // $join->on('ewl.ew_id','=',$id);
    //             ->when($id, function ($query, $id) {
    //                 return $query->where('ewl.ew_id', $id);
    //             });
    //         })
    //         ->whereNull('s.deleted_at')
    //         ->where('s.e_wallet_applied','!=',0)
    //         ->orWhere('s.e_wallet_added','!=',0)
    //         ->get());
        
    //     $collection = $getEwallet->map(function($item){
    //         if($item->ewl_status == 'P' || $item->ewl_status == 'U')
    //         {
    //             $newYearMonth = '000000';
    //         }else{
    //             $newYearMonth = $item->mr_date_year_month;
    //         }
    //         return[
    //             'Trans_Amount'=>$item->ewl_amount,
    //             'Year_Month'=>$newYearMonth,
    //             'Status'=>$item->ewl_status,
    //             'OR_Num'=>$item->ewl_or,
    //         ];
    //     });


    //     return response(['Ewallet_Log'=>$collection],200);
    // }
    public function getEwallet($id)
    {
        // $getEwallet = collect(DB::table('meter_reg as mr')
        //     ->join('sales as s','mr.mr_id','=','s.mr_id')
        //     ->join('e_wallet_log as ewl',function($join) use ($id)
        //     {
        //         $join->on('s.s_or_num','=', 'ewl.ewl_or')
        //         // $join->on('ewl.ew_id','=',$id);
        //         ->when($id, function ($query, $id) {
        //             return $query->where('ewl.ew_id', $id);
        //         });
        //     })
        //     ->whereNull('s.deleted_at')
        //     ->where('s.e_wallet_applied','!=',0)
        //     ->orWhere('s.e_wallet_added','!=',0)
        //     ->get());
        //For EWALLET ADDED
        $getEwalletAdded = collect(
            DB::table('sales as s')
            ->leftjoin('meter_reg as mr','mr.mr_id','=','s.mr_id')
            ->leftjoin('e_wallet as ew','s.cm_id','=','ew.cm_id')
            ->whereNull('s.deleted_at')
            ->where('ew.ew_id',$id)
            ->where('s.e_wallet_added','>',0)
            ->get()
        );
        //For EWALLET APPLIED
        $getEwalletApplied = collect(
            DB::table('sales as s')
            ->leftjoin('meter_reg as mr','mr.mr_id','=','s.mr_id')
            ->leftjoin('e_wallet as ew','s.cm_id','=','ew.cm_id')
            ->whereNull('s.deleted_at')
            ->where('ew.ew_id',$id)
            ->where('s.e_wallet_applied','>',0)
            ->get()
        );
        //For EWALLET DEPOSIT
        // $getEwalletDeposit = collect(
        //     DB::table('sales as s')
        //     ->leftjoin('e_wallet as ew','s.cm_id','=','ew.cm_id')
        //     ->whereNull('s.deleted_at')
        //     ->where('ew.ew_id',$id)
        //     ->where('s.s_mode_payment','Deposit_Ewallet')
        //     ->get()
        // );
        // dd($getEwalletDeposit);
        // Collection For Added
        $collectionAdded = $getEwalletAdded->map(function($item){
            if($item->e_wallet_added > 0){
                $amount = $item->e_wallet_added;
                if($item->ackn_date == NULL){
                    $status = 'U';
                }else{
                    $status = 'P';
                }
            }
            return[
                'Trans_Amount'=>$amount,
                'Year_Month'=>$item->mr_date_year_month,
                'Status'=>$status,
                'OR_Num'=>$item->s_or_num,
            ];
        });

        // Collection For Applied
        $collectionApplied = $getEwalletApplied->map(function($item){
            return[
                'Trans_Amount'=>$item->e_wallet_applied,
                'Year_Month'=>$item->mr_date_year_month,
                'Status'=>'A',
                'OR_Num'=>$item->s_or_num,
            ];
        });

        // Collection For EWallet Deposit
        // $collectionDeposit = $getEwalletDeposit->map(function($item){
        //         $amount = $item->e_wallet_added;
        //         if($item->ackn_date == NULL){
        //             $status = 'U';
        //         }else{
        //             $status = 'P';
        //         }
        //     return[
        //         'Trans_Amount'=>$amount,
        //         'Year_Month'=>'000000',
        //         'Status'=>$status,
        //         'OR_Num'=>$item->s_or_num,
        //     ];
        // });
        // dd($collectionDeposit);
        //Merge Added and Applied Collection
        $merged = $collectionAdded->merge($collectionApplied);
        // $merged2 = $merged->merge($collectionDeposit);
        $rMerge = $merged->sortBy('Year_Month');
        $sMerge = $rMerge->values()->all();
        $posNeg = $rMerge->map(function($item){
            if($item['Status'] == 'A'){
                $amount = round($item['Trans_Amount'] * -1,2);
            }else{
                $amount = round($item['Trans_Amount'],2);
            }
            return[
                'amount'=>$amount
            ];
        });
        $ewLogAmount = $posNeg->sum('amount');

        $getEwBalance = DB::table('e_wallet')
                        ->where('ew_id',$id)
                        ->sum('ew_total_amount');

        if($ewLogAmount == $getEwBalance){
            $repAmount = 0;
        }else if($ewLogAmount != $getEwBalance){
            if($ewLogAmount > $getEwBalance){
                $calc = round($ewLogAmount - $getEwBalance,2);
                $repAmount = $calc * -1;
            }else if($ewLogAmount < $getEwBalance){
                $calc = round($getEwBalance - $ewLogAmount,2);
                $repAmount = $calc;
            }else{
                $repAmount = 0;
            }
        }else{
            $repAmount = 0;
        }
        return response(['Ewallet_Log'=>$sMerge,'additional'=>$repAmount],200);
    }
    public function tagConsConsent(Request $request){
        $query = collect(
            DB::table('cons_master')
            ->where('cm_id',$request->cons_id)
            ->first()
        );

        if($query->isEmpty()){
            return response(['info'=>'No Consumer Found'],422);
        }

        // Check if consumer exist in the consented table
        $query2 = collect(
            DB::table('e_wallet_consent as ewc')
            ->join('cons_master as cm','ewc.cm_id','=','cm.cm_id')
            ->where('cm.cm_id',$request->cons_id)
            ->first()
        );
        
        if($query2->isNotEmpty()){
            return response(['info'=>'Already in the List of Consumers Ewallet Pay Consent'],422);
        }

        // Insert Consumer to table e_wallet_consent
        DB::table('e_wallet_consent')
        ->insert(
            ['cm_id' => $query['cm_id'],'teller_id' => $request->user_id]
        );

        return response(['info'=>'Succesfully Added To the Consented List For Ewallet Payments'],200);
    }
    public function ewalletPayConsentList($id){
        $query = collect(
            DB::table('cons_master as cm')
            ->join('e_wallet as ew','cm.cm_id','=','ew.cm_id')
            ->join('e_wallet_consent as ewc','ewc.cm_id','=','cm.cm_id')
            ->where('ewc.teller_id',$id)
            ->get()
        );

        if($query->isEmpty()){
            return response(['Message'=> 'No Record Found'],422);
        }
        
        $map = $query->map(function($item){
            $canPayCurrent = collect(
                DB::table('meter_reg as mr')
                ->select('mr.mr_amount','mr.mr_date_year_month')
                ->where('mr.cm_id',$item->cm_id)
                ->where('mr.mr_status',0)
                ->orderBy('mr.mr_date_year_month','asc')
                ->first()
            );

            // Wether to show Consent Consumer in the list if there is a bill to pay.
            if($canPayCurrent->isEmpty()){
                // show or not, No Current Bill (TBA)
                $show = 0;
            }else{
                // Allowed to Pay
                // check to see if e_wallet is enough for the current Bill
                $currBill = $canPayCurrent['mr_amount'];
                if($item->ew_total_amount >= $currBill){
                    // $canPayEwallet = 1;
                    $show = 1;
                }else{
                    $canPayEwallet = 0;
                    $show = 0;
                }
            }
            return[
                'account_no'=> $item->cm_account_no,
                'name'=>$item->cm_full_name,
                'bill_period_to_pay'=>$canPayCurrent['mr_date_year_month'],
                'amount_to_pay'=>$currBill,
                'remaining_ewallet'=>$item->ew_total_amount,
                'show'=>$show,
            ];
        });

        $newMap = $map->where('show',1);
        if($newMap->isEmpty()){
            return response(['Message'=> 'Consumers (hidden) Dont Have Enough Ewallet to Pay'],422);
        }

        return response(['info'=> $newMap],200);
    }
}
