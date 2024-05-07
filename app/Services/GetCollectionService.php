<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\Role;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class GetCollectionService {
    public function MonthlyCollectionPBRates($date)
    {
        //Power Bill Only
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $collection = collect(
            DB::table('sales as s')
            ->leftJoin('meter_reg as mr','s.mr_id','=','mr.mr_id')
            ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->select(DB::raw('sum(s.s_or_amount) + sum(s.e_wallet_added) as amount,s.s_bill_date as date,sum(mr.mr_kwh_used) as kwh,count(s.s_id) as count,
                sum(ROUND(ROUND(br.br_gensys_rate * mr.mr_kwh_used, 3),2)) as gensys,
                sum(ROUND(ROUND(br.br_par_rate * mr.mr_kwh_used, 3),2)) as par,
                sum(ROUND(ROUND(br.br_fbhc_rate * mr.mr_kwh_used, 3),2)) as fbhc,
                sum(ROUND(ROUND(br.br_forex_rate * mr.mr_kwh_used, 3),2)) as forex,

                sum(ROUND(ROUND(br.br_transsys_rate * mr.mr_kwh_used, 3),2)) as transys,
                sum(ROUND(ROUND(br.br_transdem_rate * mr.mr_dem_kwh_used, 3),2)) as transdem,
                sum(ROUND(ROUND(br.br_sysloss_rate * mr.mr_kwh_used, 3),2)) sysloss,

                sum(ROUND(ROUND(br.br_distsys_rate * mr.mr_kwh_used, 3),2)) as distsys,
                sum(ROUND(ROUND(br.br_distdem_rate * mr.mr_dem_kwh_used, 3),2)) as distdem,
                sum(ROUND(ROUND(br.br_suprtlcust_fixed, 3),2)) as supfix,
                sum(ROUND(ROUND(br.br_supsys_rate * mr.mr_kwh_used, 3),2)) as supsys,
                sum(ROUND(ROUND(br.br_mtrrtlcust_fixed, 3),2)) as meterfix,
                sum(ROUND(ROUND(br.br_mtrsys_rate * mr.mr_kwh_used, 3),2)) as metersys,

                sum(if(mr.mr_lfln_disc != 0,mr.mr_lfln_disc * -1,0)) as lflnDisc,
                sum(if(mr.mr_lfln_disc = 0,ROUND(ROUND(br.br_lfln_subs_rate * mr_kwh_used,3),2),0)) as lflnsub,
                sum(ROUND(ROUND(br.br_sc_subs_rate * mr.mr_kwh_used, 3),2)) as sencitdiscsub,
                sum(ROUND(ROUND(br.br_intrclscrssubrte * mr.mr_kwh_used, 3),2)) as intClssCrssSubs,
                sum(ROUND(ROUND(br.br_capex_rate * mr.mr_kwh_used, 3),2)) as capex,
                sum(ROUND(ROUND(br.br_loancon_rate_kwh * mr.mr_kwh_used, 3),2)) as loancond,
                sum(ROUND(br.br_loancon_rate_fix,2)) as loancondfix,

                sum(ROUND(ROUND(br.br_uc4_miss_rate_spu * mr.mr_kwh_used, 3),2)) as spug,
                sum(ROUND(ROUND(br.br_uc4_miss_rate_red * mr.mr_kwh_used, 3),2)) as red,
                sum(ROUND(ROUND(br.br_uc6_envi_rate * mr.mr_kwh_used, 3),2)) as envichrge,
                sum(ROUND(ROUND(br.br_uc5_equal_rate * mr.mr_kwh_used, 3),2)) as equliroyal,
                sum(ROUND(ROUND(br.br_uc2_npccon_rate * mr.mr_kwh_used, 3),2)) as npccon,
                sum(ROUND(ROUND(br.br_uc1_npcdebt_rate * mr.mr_kwh_used, 3),2)) as npcdebt,
                sum(ROUND(ROUND(br.br_fit_all * mr.mr_kwh_used, 3),2)) as fitall,

                sum(ROUND(ROUND(br.br_vat_gen * mr.mr_kwh_used, 3),2)) as genvat,
                sum(ROUND(ROUND(br.br_vat_par * mr.mr_kwh_used, 3),2)) as parvat,
                sum(ROUND(ROUND(br.br_vat_trans * mr.mr_kwh_used, 3),2)) as transvat,
                sum(ROUND(ROUND(br.br_vat_transdem * mr.mr_dem_kwh_used, 3),2)) transdemvat,
                sum(ROUND(ROUND(br.br_vat_systloss * mr.mr_kwh_used, 3),2)) as syslossvat,
                sum(ROUND(ROUND(br.br_vat_distrib_kwh * mr.mr_kwh_used, 3),2)) as distsysvat,
                sum(ROUND(ROUND(br.br_vat_distdem * mr.mr_dem_kwh_used, 3),2)) as distdemvat,
                sum(ROUND(br.br_vat_supfix,2)) as supplyfixvat,
                sum(ROUND(ROUND(br.br_vat_supsys * mr.mr_kwh_used, 3),2)) as supsysvat,
                sum(ROUND(ROUND(br.br_vat_mtr_fix, 3),2)) as mtrfixvat,
                sum(ROUND(ROUND(br.br_vat_metersys * mr.mr_kwh_used, 3),2)) as mtrsysvat,
                sum(if(mr.mr_lfln_disc != 0,0,ROUND(ROUND(br.br_lfln_subs_rate * mr_kwh_used,3),2))) as lflnDiscSubvat,
                sum(ROUND(ROUND(br.br_vat_loancondo * mr.mr_kwh_used, 3),2)) as loancondvat,
                sum(ROUND(br.br_vat_loancondofix,2)) as loancondifixvat,

                sum(if(cm.cm_lgu2 != 0,round((mr.mr_amount / 1.12) * 0.02,2),0)) as lgu2,
                sum(if(cm.cm_lgu5 != 0,round((mr.mr_amount / 1.12) * 0.05,2),0)) as lgu5
                '))
            ->whereMonth('s.s_bill_date',$month)
            ->whereYear('s.s_bill_date',$year)
            // ->where('s.f_id','=',0)
            ->whereNull('s.f_id')
            // ->where('mr.mr_status',1)
            // ->where('s.s_cutoff',1)
            ->groupBy('s.s_bill_date')
            ->get()
        );
        return $collection->values()->all();
    }
    public function MonthlyCollectionEwalletDeposit($date)
    {
        //For Advance Payment (Ewallet Deposit)
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));

        $collection = collect(
            DB::table('sales as s')
                ->select(DB::raw('s.s_bill_date as date,COALESCE(sum(s.e_wallet_added),0) as amount'))
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->where('s.e_wallet_added','!=',0)
                // ->where('s.s_cutoff',1)
                ->whereNotNull('s.e_wallet_added')
                ->groupBy('s.s_bill_date')
                ->get()
        );

        return $collection->values()->all();
    }
    public function MonthlyCollectionNonBill($date)
    {
        //For Advance Payment (Ewallet Deposit)
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $collection = collect(
            DB::table('sales as s')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->select(DB::raw('s.s_bill_date as date,sum(s.s_or_amount) as amount,s.f_id,tc.tc_id,cm.cm_id,ac.ac_id,rc.rc_id'))
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->whereNotNull('s.f_id')
                ->where('s.f_id','!=',0)
                // ->where('s.s_cutoff',1)
                ->groupBy('s.s_bill_date','s.f_id','cm.cm_id')
                ->get()
                
        );
        return $collection->values()->all();
    }
    public function nonBillCollection($date_from,$date_to)
    {
        $collection = collect(
            DB::table('sales as s')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                ->whereBetween('s.s_bill_date',[$date_from,$date_to])
                ->whereNotNull('s.f_id')
                ->where('s.f_id','!=',0)
                // ->where('s.s_cutoff',1)
                ->get()
        );
        // dd($collection);
        return $collection->values()->all();
    }
    public function sales($date){

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $sales = collect(
            DB::table('sales as s')
            ->join('cons_master as cm','cm.cm_id','=','s.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->whereMonth('s.s_bill_date',$month)
            ->whereYear('s.s_bill_date',$year)
            // ->where('s.s_cutoff',1)
            // ->groupBy('s.s_bill_date')
            ->get()
        );

        $map = $sales->map(function($item){
            if($item->f_id == NULL || $item->f_id == 0){
                // $type = 'PB';
                if($item->mr_arrear == 'Y'){
                    $current_arrear = 'Current';
                    $current = round($item->s_or_amount + $item->e_wallet_added,2);
                    $arrears = 0;
                    $nonbill = 0;
                }else{
                    $current_arrear = 'Arrear';
                    $current = 0;
                    $arrears = round($item->s_or_amount + $item->e_wallet_added,2);
                    $nonbill = 0;
                }
            }else if($item->f_id != NULL){
                // $type = 'NB';
                $current = 0;
                $arrears = 0;
                $nonbill = round($item->s_or_amount + $item->e_wallet_added,2);
            }else{
                return response(['info'=>'Theres an error'],422);
            }

            return[
                'area_id'=> $item->ac_id,
                'area_code'=> $item->ac_id,
                'town_code'=> $item->tc_code,
                'route_code'=> $item->rc_code,
                'area_name'=> $item->ac_name,
                'town_id'=> $item->tc_id,
                'town_name'=> $item->tc_name,
                'route_id'=> $item->rc_id,
                'route_name'=> $item->rc_desc,
                'date'=> $item->s_bill_date,
                'current'=> $current,
                'arrear'=> $arrears,
                'nonbill'=> $nonbill,
                // 'bill_type'=> $type,
                'Collection'=> round($current + $arrears + $nonbill,2),
            ];
        });
        // dd($map->where('area_id',4));
        return $map;
    }
    public function salesWitRates($date,$date2,$type)
    {
        if($type == 'bill_period'){
            $year = date('Y', strtotime($date));
            $month = date('m', strtotime($date));
            $sales = Sales::with([
                'meter_reg','meter_reg.rates','consumer',
                // 'consumer'=> function($query3){
                //     $query3->select('cm_id','cm_lgu2','cm_lgu5');
                // },
                // 'meter_reg'=> function($query1){
                //     $query1->select('cm_id','mr_kwh_used','mr_id','br_id','mr_dem_kwh_used','mr_lfln_disc','cons_account');
                // },
                // 'meter_reg.rates'=>function($query2){
                //     $query2->select('id','br_gensys_rate','br_par_rate','br_fbhc_rate','br_forex_rate','br_transsys_rate','br_transdem_rate','br_sysloss_rate',
                //     'br_distsys_rate','br_distdem_rate','br_suprtlcust_fixed','br_supsys_rate','br_mtrrtlcust_fixed','br_mtrsys_rate','br_sc_subs_rate',
                //     'br_intrclscrssubrte','br_capex_rate','br_loancon_rate_kwh','br_loancon_rate_fix','br_uc4_miss_rate_spu','br_uc4_miss_rate_red',
                //     'br_uc6_envi_rate','br_uc5_equal_rate','br_uc2_npccon_rate','br_uc1_npcdebt_rate','br_fit_all','br_vat_gen','br_vat_par','br_vat_trans',
                //     'br_vat_transdem','br_vat_systloss','br_vat_distrib_kwh','br_vat_distdem','br_vat_supfix','br_vat_supsys','br_vat_mtr_fix','br_vat_metersys',
                //     'br_vat_loancondo','br_vat_loancondofix','br_lfln_subs_rate','br_vat_lfln');
                // },
            ])->whereYear('s_bill_date',$year)->whereMonth('s_bill_date',$month)->whereRaw('IFNULL(cons_accountno, 0)NOT LIKE "99%"')->get();
            
        }else if($type == 'from_to'){
            $sales = Sales::with([
                'meter_reg','meter_reg.rates','consumer',
                // 'meter_reg'=> function($query1){
                //     $query1->select('mr_kwh_used','mr_id','br_id','mr_dem_kwh_used','mr_lfln_disc','cons_account');
                // },
                // 'meter_reg.rates'=>function($query2){
                //     $query2->select('id','br_gensys_rate','br_par_rate','br_fbhc_rate','br_forex_rate','br_transsys_rate','br_transdem_rate','br_sysloss_rate',
                //     'br_distsys_rate','br_distdem_rate','br_suprtlcust_fixed','br_supsys_rate','br_mtrrtlcust_fixed','br_mtrsys_rate','br_sc_subs_rate',
                //     'br_intrclscrssubrte','br_capex_rate','br_loancon_rate_kwh','br_loancon_rate_fix','br_uc4_miss_rate_spu','br_uc4_miss_rate_red',
                //     'br_uc6_envi_rate','br_uc5_equal_rate','br_uc2_npccon_rate','br_uc1_npcdebt_rate','br_fit_all','br_vat_gen','br_vat_par','br_vat_trans',
                //     'br_vat_transdem','br_vat_systloss','br_vat_distrib_kwh','br_vat_distdem','br_vat_supfix','br_vat_supsys','br_vat_mtr_fix','br_vat_metersys',
                //     'br_vat_loancondo','br_vat_loancondofix','br_lfln_subs_rate','br_vat_lfln');
                // },
                // 'consumer'=> function($query3){
                //     $query3->select('cm_lgu2','cm_lgu5');
                // },
            ])->whereBetween('s_bill_date',[$date,$date2])->whereRaw('IFNULL(cons_accountno, 0)NOT LIKE "99%"')->get();

            
        }else if($type == 'AR'){
            $sales = Sales::with([
                'meter_reg','meter_reg.rates','consumer',
                // 'meter_reg'=> function($query1){
                //     $query1->select('mr_kwh_used','mr_id','br_id','mr_dem_kwh_used','mr_lfln_disc','cons_account');
                // },
                // 'meter_reg.rates'=>function($query2){
                //     $query2->select('id','br_gensys_rate','br_par_rate','br_fbhc_rate','br_forex_rate','br_transsys_rate','br_transdem_rate','br_sysloss_rate',
                //     'br_distsys_rate','br_distdem_rate','br_suprtlcust_fixed','br_supsys_rate','br_mtrrtlcust_fixed','br_mtrsys_rate','br_sc_subs_rate',
                //     'br_intrclscrssubrte','br_capex_rate','br_loancon_rate_kwh','br_loancon_rate_fix','br_uc4_miss_rate_spu','br_uc4_miss_rate_red',
                //     'br_uc6_envi_rate','br_uc5_equal_rate','br_uc2_npccon_rate','br_uc1_npcdebt_rate','br_fit_all','br_vat_gen','br_vat_par','br_vat_trans',
                //     'br_vat_transdem','br_vat_systloss','br_vat_distrib_kwh','br_vat_distdem','br_vat_supfix','br_vat_supsys','br_vat_mtr_fix','br_vat_metersys',
                //     'br_vat_loancondo','br_vat_loancondofix','br_lfln_subs_rate','br_vat_lfln');
                // },
                // 'consumer'=> function($query3){
                //     $query3->select('cm_lgu2','cm_lgu5');
                // },
            ])->whereBetween('s_bill_date',[$date,$date2])->whereNotNull('s_ack_receipt')->where('s_ack_receipt','!=',0)->whereRaw('IFNULL(cons_accountno, 0)NOT LIKE "99%"')->get();
        }
        else{
            return response(['info'=>'Type of Collection Service Unknown'],422);
        }

        // dd($check);
        if($sales->isEmpty()){
            return $sales;
        }

        $newSales[] = array();
        $count = $sales->count();

        for($i=0;$i<$count;$i++){
            $newSales[$i]['mr_id'] = $sales[$i]->mr_id;
            if($sales[$i]->mr_id == NULL){
                // $newSales[$i]['type'] = 'NB';
                $countPB = 0;
                $countNB = 1;
                $pb_nb = 'NB';
            }else{
                // $newSales[$i]['type'] = 'PB';
                $countPB = 1;
                $countNB = 0;
                $pb_nb = 'PB';
            }

            // if($sales[$i]->mr_id == NULL && $sales[$i]->s_mode_payment == 'Deposit_Ewallet'){
            //     $advancePB = 0;
            //     $advanceDepo = $sales[$i]->e_wallet_added;
            //     $advanceNB = 0;
            // }else if($sales[$i]->mr_id != NULL){
            //     $advancePB = $sales[$i]->e_wallet_added;
            //     $advanceNB = 0;
            //     $advanceDepo = 0;
            // }else{
            //     $advancePB = 0;
            //     $advanceNB = $sales[$i]->e_wallet_added;
            //     $advanceDepo = 0;
            // }

            if($sales[$i]->f_id == 9){
                $membership = round($sales[$i]->s_or_amount + $sales[$i]->e_wallet_added,2);
                $sundries = 0;
            }else if($sales[$i]->f_id != 9 && $sales[$i]->f_id != NULL){
                $membership = 0;
                $sundries = round($sales[$i]->s_or_amount + $sales[$i]->e_wallet_added,2);
            }else{
                $membership = 0;
                $sundries = 0;
            }

            if($sales[$i]->s_mode_payment == 'Deposit_Ewallet'){
                $deposit = round($sales[$i]->e_wallet_added,2);
            }else{
                $deposit = 0;
            }

            $mr_id = !isset($sales[$i]->mr_id) ? 0 : $sales[$i]->mr_id;
            $kwh = !isset($sales[$i]->meter_reg->mr_kwh_used) ? 0 : $sales[$i]->meter_reg->mr_kwh_used;
            $kwhdem = !isset($sales[$i]->meter_reg->mr_dem_kwh_used) ? 0 : $sales[$i]->meter_reg->mr_dem_kwh_used;
            $mr_amount = !isset($sales[$i]->meter_reg->mr_amount) ? 0 : $sales[$i]->meter_reg->mr_amount;
            $lgu2 = !isset($sales[$i]->consumer->cm_lgu2) ? 0 : $sales[$i]->consumer->cm_lgu2;
            $lgu5 = !isset($sales[$i]->consumer->cm_lgu5) ? 0 : $sales[$i]->consumer->cm_lgu5;

            $setlflinDisc = (!isset($sales[$i]->meter_reg->mr_lfln_disc) ? 0 : $sales[$i]->meter_reg->mr_lfln_disc);
            // dd($setlflinDisc);
            // $lflinDisc = ($setlflinDisc == 0) ? 0 : $setlflinDisc * -1;
            $setlflnSub = $kwh * (!isset($sales[$i]->meter_reg->rates->br_lfln_subs_rate) ? 0 : $sales[$i]->meter_reg->rates->br_lfln_subs_rate);
            $setlflnSubVat = $kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_lfln) ? 0 : $sales[$i]->meter_reg->rates->br_vat_lfln);
            // dd($setlflinDisc,$setlflnSub,$setlflnSubVat,$mr_id,$kwh);
            if($setlflinDisc != 0){
                $lflinDisc = $sales[$i]->meter_reg->mr_lfln_disc * -1;
                $setlflnSub = 0;
                $setlflnSubVat = 0;
            }else{
                $lflinDisc = 0;
                $setlflnSub = $kwh * (!isset($sales[$i]->meter_reg->rates->br_lfln_subs_rate) ? 0 : $sales[$i]->meter_reg->rates->br_lfln_subs_rate);
                $setlflnSubVat = $kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_lfln) ? 0 : $sales[$i]->meter_reg->rates->br_vat_lfln);
            }
            
            // $lflnSub = ($setlflinDisc == 0) ? $setlflnSub : 0; // lflnsub and lflnvat
            // $lflnsubVat = ($setlflinDisc > 0) ? 0 :$setlflnSubVat;

            $newSales[$i]['mr_id'] = $mr_id;
            $newSales[$i]['ct_id'] = $sales[$i]->ct_id;
            $newSales[$i]['ar'] = isset($sales[$i]->s_ack_receipt) ? $sales[$i]->s_ack_receipt : 0;
            $newSales[$i]['total_amount'] =  round($sales[$i]->s_or_amount + $sales[$i]->e_wallet_added,2); // PB and NB
            $newSales[$i]['kwh'] =  $kwh;
            $newSales[$i]['date'] =  $sales[$i]->s_bill_date;
            $newSales[$i]['s_mode_payment'] =  $sales[$i]->s_mode_payment;
            $newSales[$i]['teller_id'] =  ($sales[$i]->teller_user_id == Null) ? 1000 : $sales[$i]->teller_user_id;
            $newSales[$i]['lgu_2'] =  ($lgu2 == 1) ? round($mr_amount / 1.12 * 0.02,2): 0;
            $newSales[$i]['lgu_5'] =  ($lgu5 == 1) ? round($mr_amount / 1.12 * 0.05,2): 0;


            $newSales[$i]['countPB'] =  $countPB;
            $newSales[$i]['countNB'] =  $countNB;
            $newSales[$i]['type'] = $pb_nb;
            $newSales[$i]['f_id'] = $sales[$i]->f_id;
            $newSales[$i]['advance_payment'] = round($sales[$i]->e_wallet_added,2);
            $newSales[$i]['pbill_deposit'] = $deposit;
            $newSales[$i]['membership'] = $membership;
            $newSales[$i]['sundries'] = $sundries;

            $newSales[$i]['gensys'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_gensys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_gensys_rate),3),2);
            $newSales[$i]['par'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_par_rate) ? 0 : $sales[$i]->meter_reg->rates->br_par_rate),3),2);
            $newSales[$i]['fbhc'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_fbhc_rate) ? 0 : $sales[$i]->meter_reg->rates->br_fbhc_rate),3),2);
            $newSales[$i]['forex'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_forex_rate) ? 0 : $sales[$i]->meter_reg->rates->br_forex_rate),3),2);
            
            $newSales[$i]['transys'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_transsys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_transsys_rate),3),2);
            $newSales[$i]['transdem'] =  round(round($kwhdem * (!isset($sales[$i]->meter_reg->rates->br_transdem_rate) ? 0 : $sales[$i]->meter_reg->rates->br_transdem_rate),3),2);
            $newSales[$i]['sysloss'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_sysloss_rate) ? 0 : $sales[$i]->meter_reg->rates->br_sysloss_rate),3),2);
            
            $newSales[$i]['distsys'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_distsys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_distsys_rate),3),2);
            $newSales[$i]['distdem'] =  round(round($kwhdem * (!isset($sales[$i]->meter_reg->rates->br_distdem_rate) ? 0 : $sales[$i]->meter_reg->rates->br_distdem_rate),3),2);
            $newSales[$i]['supfix'] =   round(round((!isset($sales[$i]->meter_reg->rates->br_suprtlcust_fixed) ? 0 : $sales[$i]->meter_reg->rates->br_suprtlcust_fixed),3),2);
            $newSales[$i]['supsys'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_supsys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_supsys_rate),3),2);
            $newSales[$i]['meterfix'] = round(round((!isset($sales[$i]->meter_reg->rates->br_mtrrtlcust_fixed) ? 0 : $sales[$i]->meter_reg->rates->br_mtrrtlcust_fixed),3),2);
            $newSales[$i]['metersys'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_mtrsys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_mtrsys_rate),3),2);
            
            $newSales[$i]['lflnDisc'] =  $lflinDisc;
            $newSales[$i]['lflnsub'] =  $setlflnSub;
            $newSales[$i]['sencitdiscsub'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_sc_subs_rate) ? 0 : $sales[$i]->meter_reg->rates->br_sc_subs_rate),3),2);
            $newSales[$i]['intClssCrssSubs'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_intrclscrssubrte) ? 0 : $sales[$i]->meter_reg->rates->br_intrclscrssubrte),3),2);
            $newSales[$i]['capex'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_capex_rate) ? 0 : $sales[$i]->meter_reg->rates->br_capex_rate),3),2);
            $newSales[$i]['loancond'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_loancon_rate_kwh) ? 0 : $sales[$i]->meter_reg->rates->br_loancon_rate_kwh),3),2);
            $newSales[$i]['loancondfix'] =  round(round((!isset($sales[$i]->meter_reg->rates->br_loancon_rate_fix) ? 0 : $sales[$i]->meter_reg->rates->br_loancon_rate_fix),3),2);
            
            $newSales[$i]['spug'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc4_miss_rate_spu) ? 0 : $sales[$i]->meter_reg->rates->br_uc4_miss_rate_spu),3),2);
            $newSales[$i]['red'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc4_miss_rate_red) ? 0 : $sales[$i]->meter_reg->rates->br_uc4_miss_rate_red),3),2);
            $newSales[$i]['envichrge'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc6_envi_rate) ? 0 : $sales[$i]->meter_reg->rates->br_uc6_envi_rate),3),2);
            $newSales[$i]['equliroyal'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc5_equal_rate) ? 0 : $sales[$i]->meter_reg->rates->br_uc5_equal_rate),3),2);
            $newSales[$i]['npccon'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc2_npccon_rate) ? 0 : $sales[$i]->meter_reg->rates->br_uc2_npccon_rate),3),2);
            $newSales[$i]['npcdebt'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_uc1_npcdebt_rate) ? 0 : $sales[$i]->meter_reg->rates->br_uc1_npcdebt_rate),3),2);
            $newSales[$i]['fitall'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_fit_all) ? 0 : $sales[$i]->meter_reg->rates->br_fit_all),3),2);
            
            $newSales[$i]['genvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_gen) ? 0 : $sales[$i]->meter_reg->rates->br_vat_gen),3),2);
            $newSales[$i]['parvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_par) ? 0 : $sales[$i]->meter_reg->rates->br_vat_par),3),2);
            $newSales[$i]['transvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_trans) ? 0 : $sales[$i]->meter_reg->rates->br_vat_trans),3),2);
            $newSales[$i]['transdemvat'] =  round(round($kwhdem * (!isset($sales[$i]->meter_reg->rates->br_vat_transdem) ? 0 : $sales[$i]->meter_reg->rates->br_vat_transdem),3),2);
            $newSales[$i]['syslossvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_systloss) ? 0 : $sales[$i]->meter_reg->rates->br_vat_systloss),3),2);
            $newSales[$i]['distsysvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_distrib_kwh) ? 0 : $sales[$i]->meter_reg->rates->br_vat_distrib_kwh),3),2);
            $newSales[$i]['distdemvat'] =  round(round($kwhdem * (!isset($sales[$i]->meter_reg->rates->br_vat_distdem) ? 0 : $sales[$i]->meter_reg->rates->br_vat_distdem),3),2);
            $newSales[$i]['supplyfixvat'] =  round(round(!isset($sales[$i]->meter_reg->rates->br_vat_supfix) ? 0 : $sales[$i]->meter_reg->rates->br_vat_supfix,3),2);
            $newSales[$i]['supsysvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_supsys) ? 0 : $sales[$i]->meter_reg->rates->br_vat_supsys),3),2);
            $newSales[$i]['mtrfixvat'] =  round(round(!isset($sales[$i]->meter_reg->rates->br_vat_mtr_fix) ? 0 : $sales[$i]->meter_reg->rates->br_vat_mtr_fix,3),2);
            $newSales[$i]['mtrsysvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_metersys) ? 0 : $sales[$i]->meter_reg->rates->br_vat_metersys),3),2);
            $newSales[$i]['lflnDiscSubvat'] =  $setlflnSubVat;
            $newSales[$i]['loancondvat'] =  round(round($kwh * (!isset($sales[$i]->meter_reg->rates->br_vat_loancondo) ? 0 : $sales[$i]->meter_reg->rates->br_vat_loancondo),3),2);
            $newSales[$i]['loancondifixvat'] =  round(round(!isset($sales[$i]->meter_reg->rates->br_vat_loancondofix) ? 0 : $sales[$i]->meter_reg->rates->br_vat_loancondofix,3),2);
            
            // $newSales[$i]['lgu2'] =  (!isset($sales[$i]->meter_reg->mr_kwh_used) ? 0 : $sales[$i]->meter_reg->mr_kwh_used) * (!isset($sales[$i]->meter_reg->rates->br_gensys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_gensys_rate);
            // $newSales[$i]['lgu5'] =  (!isset($sales[$i]->meter_reg->mr_kwh_used) ? 0 : $sales[$i]->meter_reg->mr_kwh_used) * (!isset($sales[$i]->meter_reg->rates->br_gensys_rate) ? 0 : $sales[$i]->meter_reg->rates->br_gensys_rate);
                                       
        }
        // dd($newSales->where('lgu2',1));
        // $newSales1 = collect($newSales);
        // dd($newSales1->where('s_mode_payment','Online'));
        return $newSales;
    }
    public function salesNew($date_from,$date_to,$bill_period){

        $billingPeriod = str_replace("-","",$bill_period);
        $sales = collect(
            DB::table('sales as s')
            ->join('cons_master as cm','cm.cm_id','=','s.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->where('mr_id','!=',NULL)
            ->where('mr_id','!=',0)
            ->whereBetween('s.s_bill_date',[$date_from,$date_to])
            ->get()
        );

        $map = $sales->map(function($item) use($billingPeriod){
            
            $powerBill = collect(
                DB::table('meter_reg')
                ->where('mr_id',$item->mr_id)
                ->first());

            if($powerBill['mr_date_year_month'] == $billingPeriod){
                $current = round($item->s_or_amount + $item->e_wallet_added,2);
            }else{
                $current = 0;
            }
            return[
                'area_id'=> $item->ac_id,
                'area_name'=> $item->ac_name,
                'town_id'=> $item->tc_id,
                'town_name'=> $item->tc_name,
                'route_id'=> $item->rc_id,
                'route_name'=> $item->rc_desc,
                'date'=> $item->s_bill_date,
                'current'=> $current,
            ];
        });
        return $map->values()->all();
    }
    public function collectionByBillPeriodQuery($bill_period,$date_from,$date_to)
    {
        $billingPeriod = str_replace("-","",$bill_period);
        $yearTwoDec = substr($billingPeriod,2,-2);
        $monthTwoDec = substr($billingPeriod,-2);
        $newBPeriod = intval($yearTwoDec.''.$monthTwoDec);
        $sales = collect(
            DB::table('sales as s')
            ->join('cons_master as cm','cm.cm_id','=','s.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->leftJoin('meter_master as mm','cm.cm_id','=','mm.mm_id')
            ->select('cm.cm_account_no','cm.cm_full_name','ac.ac_id','ac.ac_name','tc.tc_id','tc.tc_name','rc.rc_id','rc.rc_desc','s_bill_no','mm.mm_id','mm.mm_serial_no',
                DB::raw('(coalesce(s.s_or_amount,0) + coalesce(s.e_wallet_added,0)) as amount'),'s.s_or_amount','s.e_wallet_added','ct.ct_id','ct.ct_desc')
            ->where('mr_id','!=',NULL)
            ->where('mr_id','!=',0)
            ->where(DB::raw("SUBSTR(s.s_bill_no, 1, 4)"),$newBPeriod)
            ->whereBetween('s.s_bill_date',[$date_from,$date_to])
            // ->limit(1000)
            ->get()
        );
        return $sales;
        
    }
    public function collectionBetweenDate($date_from,$date_to,$rcId)
    {
        $sales = collect(
            DB::table('sales as s')
            ->join('cons_master as cm','cm.cm_id','=','s.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->leftJoin('meter_master as mm','cm.cm_id','=','mm.mm_id')
            ->select('s.s_bill_date','cm.cm_account_no','cm.cm_full_name','rc.rc_desc','mm.mm_id','mm.mm_serial_no',
                DB::raw('(coalesce(s.s_or_amount,0) + coalesce(s.e_wallet_added,0)) as amount'))
            ->whereBetween('s.s_bill_date',[$date_from,$date_to])
            ->where('rc.rc_id',$rcId)
            ->orderBy('s_bill_date')
            ->get()
        );
        return $sales;
          
    }


}