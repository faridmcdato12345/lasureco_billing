<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\GetCollectionService;
use App\Services\GetRateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function SummaryUniversalCharge(Request $request)
    {
        
        $billingPeriod = str_replace("-","",$request->date);
        $area = $request->area_id;
        $consType = $request->constype;
        // OLD CODE Need Adjust According To maam bobby 4/24/2023
        // if($request->selected == 'collection')
        // {
        //     if($request->constype == 'all')
        //     {
        //         //OK
        //         if($request->area_id == 'all'){
        //             $universalChargeSummary = collect(
        //                 DB::table('cons_master as cm')
        //                 ->leftJoin('meter_reg as mr', function($q) use($billingPeriod) {
        //                     $q->on('cm.cm_id', '=', 'mr.cm_id')
        //                        ->where('mr.mr_date_year_month', '=', $billingPeriod)
        //                        ->where('mr.mr_status', '=', 1);
        //                 }) 
        //                 ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
        //                 ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
        //                 ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
        //                 ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
        //                 ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
        //                     sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
        //                     sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
        //                 // ->where('mr.mr_date_year_month',$billingPeriod)
        //                 // ->where('mr.mr_status',1)
        //                 ->orderBy('ac.ac_id','asc')
        //                 ->groupBy('tc.tc_name')
        //                 ->get()
        //             );
        //         }else{
        //         //OK
        //             $universalChargeSummary = collect(
        //                 DB::table('cons_master as cm')
        //                 ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
        //                 ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
        //                 ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
        //                 ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$area) {
        //                     $q->on('cm.cm_id', '=', 'mr.cm_id')
        //                        ->where('mr.mr_date_year_month', '=', $billingPeriod)
        //                        ->where('mr.mr_status', '=', 1)
        //                        ->where('ac.ac_id',$area);
        //                 }) 
        //                 ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
        //                 ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
        //                     sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
        //                     sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
        //                 // ->where('mr.mr_date_year_month',$billingPeriod)
        //                 // ->where('mr.mr_status',1)
        //                 // ->where('ac.ac_id',$request->area_id)
        //                 ->orderBy('ac.ac_id','asc')
        //                 ->groupBy('tc.tc_name')
        //                 ->get()
        //             );
        //         }
                
        //     }else{
        //         if($request->area_id == 'all'){
        //             // OK
        //             $universalChargeSummary = collect(
        //                 DB::table('cons_master as cm')
        //                 ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$consType) {
        //                     $q->on('cm.cm_id', '=', 'mr.cm_id')
        //                        ->where('mr.mr_date_year_month', '=', $billingPeriod)
        //                        ->where('mr.mr_status', '=', 1)
        //                        ->where('cm.ct_id', '=', $consType);
        //                 }) 
        //                 ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
        //                 ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
        //                 ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
        //                 ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
        //                 // ->leftJoin('billing_rates as br', function($w) use($consType) {
        //                 //     $w->on('mr.br_id','=','br.id')
        //                 //         ->where('br.cons_type_id', '=', $consType);
        //                 // })
        //                 ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
        //                     sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
        //                     sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
        //                 // ->where('mr.mr_date_year_month',$billingPeriod)
        //                 // ->where('mr.mr_status',1)
        //                 // ->where('cm.ct_id',$request->constype)
        //                 ->orderBy('ac.ac_id','asc')
        //                 ->groupBy('tc.tc_name')
        //                 ->get()
        //             );
        //             // dd($universalChargeSummary);
        //         }else{
        //             // OK;
        //             $universalChargeSummary = collect(
        //                 DB::table('cons_master as cm')
        //                 ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
        //                 ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
        //                 ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
        //                 ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$consType,$area) {
        //                     $q->on('cm.cm_id', '=', 'mr.cm_id')
        //                        ->where('mr.mr_date_year_month', '=', $billingPeriod)
        //                        ->where('mr.mr_status', 1)
        //                        ->where('cm.ct_id', $consType)
        //                        ->where('ac.ac_id', $area);
        //                 }) 
        //                 ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
        //                 ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
        //                     sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
        //                     sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
        //                 // ->where('mr.mr_date_year_month',$billingPeriod)
        //                 // ->where('mr.mr_status',1)
        //                 // ->where('cm.ct_id',$request->constype)
        //                 // ->where('ac.ac_id',$request->area_id)
        //                 ->orderBy('ac.ac_id','asc')
        //                 ->groupBy('tc.tc_name')
        //                 ->get()
        //             );
        //         }
        //     }
        // }
        // NEW CODE HERE 
        if($request->selected == 'collection')
        {
            // $billPeriod = str_replace("-","",$request->date);
            $month = substr($billingPeriod,4,6);
            $year = substr($billingPeriod,0,4);
            if($request->constype == 'all')
            {
                //OK
                if($request->area_id == 'all'){
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                        ->join('sales as s','mr.mr_id','=','s.mr_id')
                        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->join('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        ->whereMonth('s.s_bill_date',$month)
                        ->whereYear('s.s_bill_date',$year)
                        ->where('mr.mr_status',1)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }else{
                //OK
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                        ->join('sales as s','mr.mr_id','=','s.mr_id')
                        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->join('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        ->whereMonth('s.s_bill_date',$month)
                        ->whereYear('s.s_bill_date',$year)
                        ->where('mr.mr_status',1)
                        ->where('ac.ac_id',$request->area_id)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }
                
            }else{
                if($request->area_id == 'all'){
                    // OK
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                        ->join('sales as s','mr.mr_id','=','s.mr_id')
                        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->join('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        ->whereMonth('s.s_bill_date',$month)
                        ->whereYear('s.s_bill_date',$year)
                        ->where('mr.mr_status',1)
                        ->where('cm.ct_id',$request->constype)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                    // dd($universalChargeSummary);
                }else{
                    // OK;
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                        ->join('sales as s','mr.mr_id','=','s.mr_id')
                        ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        ->whereMonth('s.s_bill_date',$month)
                        ->whereYear('s.s_bill_date',$year)
                        ->where('mr.mr_status',1)
                        ->where('cm.ct_id',$request->constype)
                        ->where('ac.ac_id',$request->area_id)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }
            }
        }else{
            if($request->constype == 'all')
            {
                if($request->area_id == 'all'){
                    // OK
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->leftJoin('meter_reg as mr', function($q) use($billingPeriod) {
                            $q->on('cm.cm_id', '=', 'mr.cm_id')
                               ->where('mr.mr_date_year_month', '=', $billingPeriod);
                        }) 
                        ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        // ->where('mr.mr_date_year_month',$billingPeriod)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                    
                }else{
                    // OK
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$area) {
                            $q->on('cm.cm_id', '=', 'mr.cm_id')
                               ->where('mr.mr_date_year_month', '=', $billingPeriod)
                               ->where('ac.ac_id', '=', $area);
                        }) 
                        ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        // ->where('mr.mr_date_year_month',$billingPeriod)
                        // ->where('ac.ac_id',$request->area_id)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }
            }else{
                // OK
                if($request->area_id == 'all'){
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$consType) {
                            $q->on('cm.cm_id', '=', 'mr.cm_id')
                               ->where('mr.mr_date_year_month', '=', $billingPeriod)
                               ->where('cm.ct_id',$consType);
                        }) 
                        ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        // ->where('mr.mr_date_year_month',$billingPeriod)
                        // ->where('cm.ct_id',$request->constype)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }else{
                    $universalChargeSummary = collect(
                        DB::table('cons_master as cm')
                        ->leftJoin('route_code as rc','cm.rc_id','=','rc.rc_id')
                        ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                        ->leftJoin('area_code as ac','tc.ac_id','=','ac.ac_id')
                        ->leftJoin('meter_reg as mr', function($q) use($billingPeriod,$consType,$area) {
                            $q->on('cm.cm_id', '=', 'mr.cm_id')
                               ->where('mr.mr_date_year_month', '=', $billingPeriod)
                               ->where('cm.ct_id',$consType)
                               ->where('ac.ac_id',$area);
                        }) 
                        ->leftJoin('billing_rates as br','mr.br_id','=','br.id')
                        ->select(DB::raw('sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as env,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                            sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npcScc,
                            sum(br.br_uc1_npcdebt_rate * mr.mr_kwh_used) as npcDebt,ac.ac_name,tc.tc_name'))
                        // ->where('mr.mr_date_year_month',$billingPeriod)
                        // ->where('cm.ct_id',$request->constype)
                        // ->where('ac.ac_id',$request->area_id)
                        ->orderBy('ac.ac_id','asc')
                        ->groupBy('tc.tc_name')
                        ->get()
                    );
                }
            }
        }

        if(!$universalChargeSummary->first())
        {
            return response(['Message'=> 'No Record Found'],422);
        }

        $mapped = $universalChargeSummary->groupBy('tc_name')->map(function($item){
            return[
                'Area_Name'=>$item->first()->ac_name,
                'Town_Name'=>$item->first()->tc_name,
                'Environmental'=>round(round($item->sum('env'),2),4),
                'ME_SPUG'=>round(round($item->sum('spug'),2),4),
                'ME_REDCI'=>round(round($item->sum('red'),2),4),
                'NPC_SCC'=>round(round($item->sum('npcScc'),2),4),
                'Stranded_Debt'=>round(round($item->sum('npcDebt'),2),4),
                'Total'=> round(round($item->sum('env'),2),4) + round(round($item->sum('spug'),2),4) + round(round($item->sum('red'),2),4) +
                round(round($item->sum('npcScc'),2),4) +  round(round($item->sum('npcDebt'),2),4),
            ];
        });

        $grouped = $mapped->groupBy('Area_Name')->map(function($group){
            return[
                'Sub_Total_Environmental'=>round(round($group->sum('Environmental'),3),2),
                'Sub_Total_ME_SPUG'=>round(round($group->sum('ME_SPUG'),3),2),
                'Sub_Total_ME_REDCI'=>round(round($group->sum('ME_REDCI'),3),2),
                'Sub_Total_NPC_SCC'=>round(round($group->sum('NPC_SCC'),3),2),
                'Sub_Total_Stranded_Debt'=>round(round($group->sum('Stranded_Debt'),3),2),
                'Sub_Total_Total'=> round(round($group->sum('Environmental'),3),2) + round(round($group->sum('ME_SPUG'),3),2) + 
                round(round($group->sum('ME_REDCI'),3),2) + round(round($group->sum('NPC_SCC'),3),2) + round(round($group->sum('Stranded_Debt'),3),2),
            ];
        });

        //Show all Town and Grouped
        // $queryTowns = collect(
        //     DB::table('area_code as ac')
        //         ->select('ac_name','tc_name')
        //         ->join('town_code as tc','ac.ac_id','=','tc.ac_id')
        //         ->get()
        // );
        // $groupedTown = $queryTowns->groupBy('ac_name');
        $grandTotal =[
            'Grand_Total_Environmental'=>round($grouped->sum('Sub_Total_Environmental'),2),
            'Grand_Total_ME_SPUG'=>round($grouped->sum('Sub_Total_ME_SPUG'),2),
            'Grand_Total_ME_REDCI'=>round($grouped->sum('Sub_Total_ME_REDCI'),2),
            'Grand_Total_NPC_SCC'=>round($grouped->sum('Sub_Total_NPC_SCC'),2),
            'Grand_Total_STRANDED_DEBT'=>round($grouped->sum('Sub_Total_Stranded_Debt'),2),
            'Grand_Total_TOTAL'=>round($grouped->sum('Sub_Total_Total'),2),
        ];
        return response([
            'Summary_Universal_Charges'=>$mapped->groupBy('Area_Name'),
            'Sub_Total'=>$grouped,
            'Grand_Total'=>$grandTotal,
        ],200);

    }
    public function vatSalesPerTown(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        if($request->selected == 'collections')
        {
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;
            $summEvat = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('sales as s','mr.mr_id','=','s.mr_id')
                ->select(DB::raw('
                        sum(ROUND(ROUND(br.br_vat_gen * mr.mr_kwh_used, 3),2)) as genVat,
                        sum(ROUND(ROUND(br.br_vat_trans * mr.mr_kwh_used, 3),2)) as transVat,
                        sum(ROUND(ROUND(br.br_vat_transdem * mr.mr_dem_kwh_used, 3),2)) as transDemVat,
                        sum(ROUND(ROUND(br.br_vat_systloss * mr.mr_kwh_used, 3),2)) as sysVat,
                        sum(ROUND(ROUND(br.br_vat_distrib_kwh * mr.mr_kwh_used, 3),2)) as distVat,
                        sum(ROUND(ROUND(br.br_vat_distdem * mr.mr_dem_kwh_used, 3),2)) as distDemVat,
                        sum(ROUND(ROUND(br.br_vat_loancondo * mr.mr_kwh_used, 3),2)) as loanCondVat,
                        sum(ROUND(br.br_vat_loancondofix,2)) as loanCondFixVat,
                        sum(ROUND(ROUND(br.br_vat_par * mr.mr_kwh_used, 3),2)) as parVat,
                        sum(ROUND(br.br_vat_supfix,2)) as supFixVat,
                        sum(ROUND(ROUND(br.br_vat_supsys * mr.mr_kwh_used, 3),2)) as supSysVat,
                        sum(ROUND(ROUND(br.br_vat_mtr_fix, 3),2)) as mtrFixVat,
                        sum(ROUND(ROUND(br.br_vat_metersys * mr.mr_kwh_used, 3),2)) as mtrSysVat,
                        sum(if(mr.mr_lfln_disc != 0,0,ROUND(ROUND(br.br_lfln_subs_rate * mr_kwh_used,3),2))) as lflnVat,
                        ac.ac_name,tc.tc_name'))
                ->whereBetween('s.s_bill_date',[$dateFrom,$dateTo])
                ->where('ac.ac_id',$request->area_id)
                ->where('mr.mr_status',1)
                ->groupBy('tc.tc_name')
                ->get()
            );

        }else{
            $summEvat = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('sales as s','mr.mr_id','=','s.mr_id')
                ->select(DB::raw('
                        sum(ROUND(ROUND(br.br_vat_gen * mr.mr_kwh_used, 3),2)) as genVat,
                        sum(ROUND(ROUND(br.br_vat_trans * mr.mr_kwh_used, 3),2)) as transVat,
                        sum(ROUND(ROUND(br.br_vat_transdem * mr.mr_dem_kwh_used, 3),2)) as transDemVat,
                        sum(ROUND(ROUND(br.br_vat_systloss * mr.mr_kwh_used, 3),2)) as sysVat,
                        sum(ROUND(ROUND(br.br_vat_distrib_kwh * mr.mr_kwh_used, 3),2)) as distVat,
                        sum(ROUND(ROUND(br.br_vat_distdem * mr.mr_dem_kwh_used, 3),2)) as distDemVat,
                        sum(ROUND(ROUND(br.br_vat_loancondo * mr.mr_kwh_used, 3),2)) as loanCondVat,
                        sum(ROUND(br.br_vat_loancondofix,2)) as loanCondFixVat,
                        sum(ROUND(ROUND(br.br_vat_par * mr.mr_kwh_used, 3),2)) as parVat,
                        sum(ROUND(br.br_vat_supfix,2)) as supFixVat,
                        sum(ROUND(ROUND(br.br_vat_supsys * mr.mr_kwh_used, 3),2)) as supSysVat,
                        sum(ROUND(ROUND(br.br_vat_mtr_fix, 3),2)) as mtrFixVat,
                        sum(ROUND(ROUND(br.br_vat_metersys * mr.mr_kwh_used, 3),2)) as mtrSysVat,
                        sum(if(mr.mr_lfln_disc != 0,0,ROUND(ROUND(br.br_lfln_subs_rate * mr_kwh_used,3),2))) as lflnVat,
                        ac.ac_name,tc.tc_name'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('ac.ac_id',$request->area_id)
                ->groupBy('tc.tc_name')
                ->get()
            );
        }

        if(!$summEvat->first())
        {
            return response(['Message'=> 'No Record Found'],422);
        }

        $mapped = $summEvat->map(function($item){
            return[
                'Area_Name'=>$item->ac_name,
                'Town_Name'=>$item->tc_name,
                'Generation_Vat'=>round($item->genVat,2),
                'Trans_Vat'=>round($item->transVat,2),
                'Dist_Vat'=>round($item->distVat,2),
                'Line_Loss_Vat'=>round($item->sysVat,2),
                'Others_Vat'=>0,
                'Trans_Dem_Vat'=>round($item->transDemVat,2),
                'Dist_Dem_Vat'=>round($item->distDemVat,2),
                'LCondo_Kwh_Vat'=>round($item->loanCondVat,2),
                'LCondo_Fix_Vat'=>round($item->loanCondFixVat,2),
                'Power_Act_Vat'=>round($item->parVat,2),
                'Supply_Fix_Vat'=>round($item->supFixVat,2),
                'Supply_Sys_Vat'=>round($item->supSysVat,2),
                'Meter_Fix_Vat'=>round($item->mtrFixVat,2),
                'Meter_Sys_Vat'=>round($item->mtrSysVat,2),
                'LifeLine_Vat'=>round($item->lflnVat,2),
                'Total'=> round(round($item->genVat,2) + round($item->transVat,2) + round($item->distVat,2) +
                    round($item->sysVat,2) + round($item->transDemVat,2) + round($item->distDemVat,2) +
                    round($item->loanCondVat,2) + round($item->loanCondFixVat,2) + round($item->parVat,2) + 
                    round($item->supSysVat,2) + round($item->mtrFixVat,2) + round($item->mtrSysVat,2) +
                    round($item->lflnVat,2),2),
            ];
        });

        $total = $mapped->groupBy('Area_Name')->map(function($group){
            return[
                'Generation_Vat_Total'=> round($group->sum('Generation_Vat'),2),
                'Trans_Vat_Total'=> round($group->sum('Trans_Vat'),2),
                'Dist_Vat_Total'=> round($group->sum('Dist_Vat'),2),
                'Line_Loss_Vat_Total'=> round($group->sum('Line_Loss_Vat'),2),
                'Others_Vat_Total'=> round($group->sum('Others_Vat'),2),
                'Trans_Dem_Vat_Total'=> round($group->sum('Trans_Dem_Vat'),2),
                'Dist_Dem_Vat_Total'=> round($group->sum('Dist_Dem_Vat'),2),
                'LCondo_Kwh_Vat_Total'=> round($group->sum('LCondo_Kwh_Vat'),2),
                'LCondo_Fix_Total'=> round($group->sum('LCondo_Fix_Vat'),2),
                'Power_Act_Vat_Total'=> round($group->sum('Power_Act_Vat'),2),
                'Supply_Fix_Vat_Total'=> round($group->sum('Supply_Fix_Vat'),2),
                'Supply_Sys_Vat_Total'=> round($group->sum('Supply_Sys_Vat'),2),
                'Meter_Fix_Vat_Total'=> round($group->sum('Meter_Fix_Vat'),2),
                'Meter_Sys_Vat_Total'=> round($group->sum('Meter_Sys_Vat'),2),
                'LifeLine_Vat_Total'=> round($group->sum('LifeLine_Vat'),2),
                'Total'=> round($group->sum('Total'),2),
            ];
        });
        
        return response([
            'Summary_EVAT'=>$mapped,
            'Total'=>$total->values()->all(),
        ],200);
    }
    public function vatSalesPerConstype(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        $areaFrom = $request->area_from;
        $areaTo = $request->area_to;
        $vatSalesPerConstype = collect(
            DB::table('meter_reg as mr')
            ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            // ->join('sales as s','mr.mr_id','=','s.mr_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->select(DB::raw('
                    sum(ROUND(ROUND(br.br_vat_gen * mr.mr_kwh_used, 3),2)) as genVat,
                    sum(ROUND(ROUND(br.br_vat_par * mr.mr_kwh_used, 3),2)) as parVat,
                    sum(ROUND(ROUND(br.br_vat_trans * mr.mr_kwh_used, 3),2)) as transVat,
                    sum(ROUND(ROUND(br.br_vat_transdem * mr.mr_dem_kwh_used, 3),2)) transDemVat,
                    sum(ROUND(ROUND(br.br_vat_systloss * mr.mr_kwh_used, 3),2)) as sysVat,
                    sum(ROUND(ROUND(br.br_vat_distrib_kwh * mr.mr_kwh_used, 3),2)) as distVat,
                    sum(ROUND(ROUND(br.br_vat_distdem * mr.mr_dem_kwh_used, 3),2)) as distDemVat,
                    sum(ROUND(br.br_vat_supfix,2)) as supplyFixVat,
                    sum(ROUND(ROUND(br.br_vat_supsys * mr.mr_kwh_used, 3),2)) as supSysVat,
                    sum(ROUND(ROUND(br.br_vat_mtr_fix, 3),2)) as mtrFixVat,
                    sum(ROUND(ROUND(br.br_vat_metersys * mr.mr_kwh_used, 3),2)) as mtrSysVat,
                    sum(if(mr.mr_lfln_disc != 0,0,ROUND(ROUND(br.br_lfln_subs_rate * mr_kwh_used,3),2))) as lflnVat,
                    sum(ROUND(ROUND(br.br_vat_loancondo * mr.mr_kwh_used, 3),2)) as loanCondVat,
                    sum(ROUND(br.br_vat_loancondofix,2)) as loanCondFixVat,
                    ac.ac_name,tc.tc_name,ct.ct_desc,sum(mr.mr_kwh_used) as kwh,count(cm.cm_id) as count'))
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->whereBetween('ac.ac_id',[$areaFrom,$areaTo])
            ->groupBy('ac.ac_id','ct.ct_id')
            ->get()
        );
        if(!$vatSalesPerConstype->first())
        {
            return response(['Message'=> 'No Record Found'],422);
        }

        $mapped = $vatSalesPerConstype->map(function($item){
            $totalVat = $item->genVat + $item->transVat + $item->distVat +
            $item->sysVat + $item->transDemVat + $item->distDemVat +
            $item->loanCondVat + $item->loanCondFixVat + $item->parVat + 
            $item->supSysVat + $item->mtrFixVat + $item->mtrSysVat +
            $item->lflnVat + $item->supplyFixVat;
            return[
                'Area_Name'=>$item->ac_name,
                'Consumer_Type'=>$item->ct_desc,
                'Consumer_Count'=>$item->count,
                'Generation_Vat'=>round($item->genVat,2),
                'Trans_Vat'=>round($item->transVat,2),
                'Dist_Vat'=>round($item->distVat,2),
                'Line_Loss_Vat'=>round($item->sysVat,2),
                'Others_Vat'=>0,
                'Trans_Dem_Vat'=>round($item->transDemVat,2),
                'Dist_Dem_Vat'=>round($item->distDemVat,2),
                'LCondo_Kwh_Vat'=>round($item->loanCondVat,2),
                'LCondo_Fix_Vat'=>round($item->loanCondFixVat,2),
                'Power_Act_Vat'=>round($item->parVat,2),
                'Supply_Fix_Vat'=>round($item->supplyFixVat,2),
                'Supply_Sys_Vat'=>round($item->supSysVat,2),
                'Meter_Fix_Vat'=>round($item->mtrFixVat,2),
                'Meter_Sys_Vat'=>round($item->mtrSysVat,2),
                'LifeLine_Vat'=>round($item->lflnVat,2),
                'Total'=> round($totalVat,2),
                'KWH_Used'=>round($item->kwh,2),
            ];
        });
        $mappedToGroup = $mapped->groupBy(['Area_Name','Consumer_Type']);

        $total = $mapped->groupBy('Area_Name')->map(function($group){
            return[
                'Generation_Vat_Total'=> round($group->sum('Generation_Vat'),2),
                'Consumer_Count_Total'=> round($group->sum('Consumer_Count'),2),
                'Trans_Vat_Total'=> round(round($group->sum('Trans_Vat'),3),2),
                'Dist_Vat_Total'=> round($group->sum('Dist_Vat'),2),
                'Line_Loss_Vat_Total'=> round($group->sum('Line_Loss_Vat'),2),
                'Others_Vat_Total'=> round($group->sum('Others_Vat'),2),
                'Trans_Dem_Vat_Total'=> round($group->sum('Trans_Dem_Vat'),2),
                'Dist_Dem_Vat_Total'=> round($group->sum('Dist_Dem_Vat'),2),
                'LCondo_Kwh_Vat_Total'=> round($group->sum('LCondo_Kwh_Vat'),2),
                'LCondo_Fix_Total'=> round($group->sum('LCondo_Fix_Vat'),2),
                'Power_Act_Vat_Total'=> round($group->sum('Power_Act_Vat'),2),
                'Supply_Fix_Vat_Total'=> round($group->sum('Supply_Fix_Vat'),2),
                'Supply_Sys_Vat_Total'=> round($group->sum('Supply_Sys_Vat'),2),
                'Meter_Fix_Vat_Total'=> round($group->sum('Meter_Fix_Vat'),2),
                'Meter_Sys_Vat_Total'=> round($group->sum('Meter_Sys_Vat'),2),
                'LifeLine_Vat_Total'=> round($group->sum('LifeLine_Vat'),2),
                'Total'=> round($group->sum('Total'),2),
                'KWH_Used_Total'=> round($group->sum('KWH_Used'),2),
            ];
        });

        $grandTotal = collect([
            'Consumer_Count_Grand_Total' => $total->sum('Consumer_Count_Total'),
            'Generation_Vat_Grand_Total'=>round(round($total->sum('Generation_Vat_Total'),3),2),
            'Trans_Vat_Grand_Total'=> round(round($total->sum('Trans_Vat_Total'),3),2),
            'Dist_Vat_Grand_Total'=> round(round($total->sum('Dist_Vat_Total'),3),2),
            'Line_Loss_Vat_Grand_Total'=> round(round($total->sum('Line_Loss_Vat_Total'),3),2),
            'Others_Vat_Grand_Total'=> 0,
            'Trans_Dem_Vat_Grand_Total'=> round(round($total->sum('Trans_Dem_Vat_Total'),3),2),
            'Dist_Dem_Vat_Grand_Total'=> round(round($total->sum('Dist_Dem_Vat_Total'),3),2),
            'LCondo_Kwh_Vat_Grand_Total'=> round(round($total->sum('LCondo_Kwh_Vat_Total'),3),2),
            'LCondo_Fix_Grand_Total'=> round(round($total->sum('LCondo_Fix_Total'),3),2),
            'Power_Act_Vat_Grand_Total'=> round(round($total->sum('Power_Act_Vat_Total'),3),2),
            'Supply_Fix_Vat_Grand_Total'=> round(round($total->sum('Supply_Fix_Vat_Total'),3),2),
            'Supply_Sys_Vat_Grand_Total'=> round(round($total->sum('Supply_Sys_Vat_Total'),3),2),
            'Meter_Fix_Vat_Grand_Total'=> round(round($total->sum('Meter_Fix_Vat_Total'),3),2),
            'Meter_Sys_Vat_Grand_Total'=> round(round($total->sum('Meter_Sys_Vat_Total'),3),2),
            'LifeLine_Vat_Grand_Total'=> round(round($total->sum('LifeLine_Vat_Total'),3),2),
            'Grand_Total'=> round(round($total->sum('Total'),3),2),
            'KWH_Used_Grand_Total'=>round(round($total->sum('KWH_Used_Total'),3),2),
        ]);
        return response([
            'Summary_EVAT_Constype'=>$mappedToGroup,
            'Total'=>$total,
            'Grand_Total2'=>$grandTotal
        ],200);
    }
    public function vatSalesCollectionFitAll(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        if($request->selected == 'collections')
        {
            $summaryFitAll = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->select(DB::raw('sum(mr.mr_kwh_used) as kwh,sum(ROUND(ROUND(br.br_fit_all * mr.mr_kwh_used, 3),2)) as amount,ac.ac_name,tc.tc_name,ac.ac_id,tc.tc_code'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('mr.mr_status',1)
                ->where('mr.mr_printed',1)
                ->where('ac.ac_id',$request->area_id)
                ->groupBy('tc.tc_name')
                ->get()
            );
        }else{
            $summaryFitAll = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','cm.cm_id','=','mr.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->select(DB::raw('sum(mr.mr_kwh_used) as kwh,sum(ROUND(ROUND(br.br_fit_all * mr.mr_kwh_used, 3),2)) as amount,ac.ac_name,tc.tc_name,ac.ac_id,tc.tc_code'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('ac.ac_id',$request->area_id)
                ->where('mr.mr_printed',1)
                ->groupBy('tc.tc_name')
                ->get()
            );
        }
        if(!$summaryFitAll->first())
        {
            return response(['Message'=> 'No Record Found'],422);
        }

        $mapped = $summaryFitAll->map(function($item){
            return[
                'Area'=>$item->ac_id,
                'Town_code'=>$item->tc_code,
                'Town'=>$item->tc_name,
                'KWH_USED'=>round($item->kwh,2),
                'Amount'=>round($item->amount,2),
            ];
        });

        $total = $mapped->groupBy('Area')->map(function($item){
            return[
                'KWH_USED'=>round($item->sum('KWH_USED'),2),
                'Amount'=>round($item->sum('Amount'),2),
            ];
        });
        return response([
            'Area_Name'=>'0'.$summaryFitAll->first()->ac_id.' '.$summaryFitAll->first()->ac_name,
            'Summary_Fit_All'=>$mapped,
            'Total'=>$total->values()->all(),
        ],200);
    }
    public function actualVat($bp)
    {
        $getYear = substr($bp,0,4);
        $getMonth = substr($bp,5);
        // dd($getYear, $getMonth);
        $consDetails = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->leftJoin('sales as s','mr.mr_id','=','s.mr_id')
            ->whereMonth('s.s_bill_date',$getMonth)
            ->whereYear('s.s_bill_date',$getYear)
            ->where('mr.mr_status',1)
            // ->where('ct.ct_id',7)
            ->get());
        // dd($consDetails);
        if($consDetails->isEmpty()){
            return response(['Message'=> 'No Record Found'],422);
        }

        $map = $consDetails->map(function ($item){
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
            $totalVat = round($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
                        $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat,2);


             return[
                'Code' =>'0'.$item->ac_id,
                'Description' =>$item->ac_name,
                'Generation' => $genVat,
                'Transmission_System' => $tranSysVat,
                'Transmission_Demand' => $transDemVat,
                'System_Loss' => $sysLossVat,
                'Distribution_System' => $distSysVat,
                'Distribution_Demand' => $distDemVat,
                'Loan_Condonation_KWH' => $loanCondVat,
                'Loan_Condonation_Fix' => $loanCondFixVat,
                'Power_Act_Red_Vat'=> $powerActRedVat, //new
                'Supply_Fix_Vat'=> $supplyFixVat, //new
                'Supply_Sys_Vat'=> $supplySysVat, //new
                'Meter_Fix_Vat'=> $meterFixVat, //new
                'Meter_Sys_Vat'=> $meterSysVat, //new
                'lfln_disc_subs_vat'=> $lflnDIscSubsVat, //new
                'Vat_Total'=> $totalVat, //new
             ];
         });
        //  dd(count($map));
        $grouped = $map->groupBy('Code')->map(function($item){
            return[
                'Code' =>$item[0]['Code'],
                'Description' =>$item[0]['Description'],
                'Generation' => round($item->sum('Generation'),2),
                'Transmission_System' => round($item->sum('Transmission_System'),2),
                'Transmission_Demand' => round($item->sum('Transmission_Demand'),2),
                'System_Loss' => round($item->sum('System_Loss'),2),
                'Distribution_System' => round($item->sum('Distribution_System'),2),
                'Distribution_Demand' => round($item->sum('Distribution_Demand'),2),
                'Loan_Condonation_KWH' => round($item->sum('Loan_Condonation_KWH'),2),
                'Loan_Condonation_Fix' => round($item->sum('Loan_Condonation_Fix'),2),
                'Power_Act_Red_Vat'=> round($item->sum('Power_Act_Red_Vat'),2), //new
                'Supply_Fix_Vat'=> round($item->sum('Supply_Fix_Vat'),2), //new
                'Supply_Sys_Vat'=> round($item->sum('Supply_Sys_Vat'),2), //new
                'Meter_Fix_Vat'=> round($item->sum('Meter_Fix_Vat'),2), //new
                'Meter_Sys_Vat'=> round($item->sum('Meter_Sys_Vat'),2), //new
                'lfln_disc_subs_vat'=> round($item->sum('lfln_disc_subs_vat'),2), //new
                'Vat_Total'=> round($item->sum('Vat_Total'),2), //new
            ];
        });

        $gTotal = collect([
            'Generation' => round($grouped->sum('Generation'),2),
            'Transmission_System' => round($grouped->sum('Transmission_System'),2),
            'Transmission_Demand' => round($grouped->sum('Transmission_Demand'),2),
            'System_Loss' => round($grouped->sum('System_Loss'),2),
            'Distribution_System' => round($grouped->sum('Distribution_System'),2),
            'Distribution_Demand' => round($grouped->sum('Distribution_Demand'),2),
            'Loan_Condonation_KWH' => round($grouped->sum('Loan_Condonation_KWH'),2),
            'Loan_Condonation_Fix' => round($grouped->sum('Loan_Condonation_Fix'),2),
            'Power_Act_Red_Vat'=> round($grouped->sum('Power_Act_Red_Vat'),2), //new
            'Supply_Fix_Vat'=> round($grouped->sum('Supply_Fix_Vat'),2), //new
            'Supply_Sys_Vat'=> round($grouped->sum('Supply_Sys_Vat'),2), //new
            'Meter_Fix_Vat'=> round($grouped->sum('Meter_Fix_Vat'),2), //new
            'Meter_Sys_Vat'=> round($grouped->sum('Meter_Sys_Vat'),2), //new
            'lfln_disc_subs_vat'=> round($grouped->sum('lfln_disc_subs_vat'),2), //new
            'Vat_Total'=> round($grouped->sum('Vat_Total'),2), //new
        ]);
         return response([
             'Details'=> $grouped->values()->all(),
             'Grand_Total'=> $gTotal
            ],200);


    }
    public function monthlyDCR(Request $request)
    {
        $type = 'bill_period';
        $date2 = '';
        $salesWRates = collect((new GetCollectionService())->salesWitRates($request->date,$date2,$type));
        $groupByDate = $salesWRates->groupBy('date');
        // dd($salesWRates->where('lgu2',1));
        $newSales = $groupByDate->map(function($salesWRates){
            return[
                'No'=> intval(substr($salesWRates[0]['date'],8)),
                'date' => $salesWRates[0]['date'],
                'total_amount' => round(round($salesWRates->sum('total_amount'),3),2),
                'number_of_bills' => $salesWRates->sum('countPB'),
                'kwh'=>round($salesWRates->sum('kwh'),2),
                'amount_PB'=>round($salesWRates->where('type','PB')->sum('total_amount'),2),
                // //Membership ..
                // 'powerbill_deposit'=>0,
                'membership'=>round($salesWRates->sum('membership'),2),
                'advance_payment'=>round($salesWRates->where('f_id','=',NULL)->where('type','PB')->sum('advance_payment'),2),
                'power_bill_deposit'=> $salesWRates->sum('pbill_deposit'),
                //GENERATION CHARGES
                'gensys'=>round($salesWRates->sum('gensys'),2),
                'par'=>round($salesWRates->sum('par'),2),
                'fbhc'=>round($salesWRates->sum('fbhc'),2),
                'forex'=>round($salesWRates->sum('forex'),2),
                //TRANSMISSION CHARGES
                'transys'=>round($salesWRates->sum('transys'),2),
                'transdem'=>round($salesWRates->sum('transdem'),2),
                'sysloss'=>round($salesWRates->sum('sysloss'),2),
                //DISTRIBUTION CHARGES              
                'distsys'=>round($salesWRates->sum('distsys'),2),
                'distdem'=>round($salesWRates->sum('distdem'),2),
                'supfix'=>round($salesWRates->sum('supfix'),2),
                'supsys'=>round($salesWRates->sum('supsys'),2),
                'meterfix'=>round($salesWRates->sum('meterfix'),2),
                'metersys'=>round($salesWRates->sum('metersys'),2),
                //OTHER CHARGES
                'lflnDisc'=>round($salesWRates->sum('lflnDisc'),2),
                'lflnsub'=>round($salesWRates->sum('lflnsub'),2),
                'sencitdiscsub'=>round($salesWRates->sum('sencitdiscsub'),2),
                'intClssCrssSubs'=>round($salesWRates->sum('intClssCrssSubs'),2),
                'capex'=>round($salesWRates->sum('capex'),2),
                'loancond'=>round($salesWRates->sum('loancond'),2),
                'loancondfix'=>round($salesWRates->sum('loancondfix'),2),
                //UNIVERSAL CHARGES
                'spug'=>round($salesWRates->sum('spug'),2),
                'red'=>round($salesWRates->sum('red'),2),
                'envichrge'=>round($salesWRates->sum('envichrge'),2),
                'equliroyal'=>round($salesWRates->sum('equliroyal'),2),
                'npccon'=>round($salesWRates->sum('npccon'),2),
                'npcdebt'=>round($salesWRates->sum('npcdebt'),2),
                'fitall'=>round($salesWRates->sum('fitall'),2),
                // VALUE ADDED TAX
                'genvat'=>round($salesWRates->sum('genvat'),2),
                'parvat'=>round($salesWRates->sum('parvat'),2),
                'transvat'=>round($salesWRates->sum('transvat'),2),
                'transdemvat'=>round($salesWRates->sum('transdemvat'),2),
                'syslossvat'=>round($salesWRates->sum('syslossvat'),2),
                'distsysvat'=>round($salesWRates->sum('distsysvat'),2),
                'distdemvat'=>round($salesWRates->sum('distdemvat'),2),
                'supplyfixvat'=>round($salesWRates->sum('supplyfixvat'),2),
                'supsysvat'=>round($salesWRates->sum('supsysvat'),2),
                'mtrfixvat'=>round($salesWRates->sum('mtrfixvat'),2),
                'mtrsysvat'=>round($salesWRates->sum('mtrsysvat'),2),
                'lflnDiscSubvat'=>round($salesWRates->sum('lflnDiscSubvat'),2),
                'loancondvat'=>round($salesWRates->sum('loancondvat'),2),
                'loancondifixvat'=>round($salesWRates->sum('loancondifixvat'),2),
                'others_vat'=>'0.00',
                // // WTax
                'lgu_2'=>round($salesWRates->sum('lgu_2'),2),
                'lgu_5'=>round($salesWRates->sum('lgu_5'),2),
                'sundries'=>round($salesWRates->sum('sundries'),2),
                // 'adv'=>round($advance,2),
            ];
        })->sortBy('date');
        $maxDate = new Carbon($request->date);
        $newDateLoop = Carbon::parse($maxDate->format('Y-m-d'))->daysInMonth;

        return response(['info'=>$newSales->keyBy('No'),'loop'=>$newDateLoop],200);
    }
    public function cashierDCRAcc(Request $request)
    {
        $query = collect(
            DB::table('sales AS s')
            ->leftJoin('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->where('s.s_bill_date',$request->bill_date)
            ->whereNotNull('s.s_ack_receipt')
            ->orderBy('teller_user_id','asc')
            ->get()
        );

        $check = $query->first();
        if(!$check)
        {
            return response(['Message'=>'No Record Found'],422);
        }

        $map = $query->map(function($item){
            return[
                'teller_name'=> $item->user_full_name,
                'total_amount'=> round($item->s_or_amount + $item->e_wallet_added,2),
                'current'=> ($item->mr_arrear == 'N') ? $item->s_or_amount : 0,
                'arrears'=> ($item->mr_arrear == 'Y') ? $item->s_or_amount : 0,
                'nb'=> ($item->mr_arrear == NULL) ? $item->s_or_amount : 0,
                'surcharge'=> 0,
                'others'=> $item->e_wallet_added,
            ];
        });

        $group = $map->groupBy('teller_name');
        $groupMapped = $group->map(function($item){
            return[
                'teller_name'=>$item->first()['teller_name'],
                'total_amount'=> round($item->sum('total_amount'),2),
                'current'=> round($item->sum('current'),2),
                'arrears'=> round($item->sum('arrears'),2),
                'surcharge'=> $item->sum('surcharge'),
                // 'nb'=> $item->sum('nb'),
                'others'=> round($item->sum('others') + $item->sum('nb'),2)
            ];
        });
        

        return response([
            'info'=>$groupMapped,
            'grand_total'=>round($groupMapped->sum('total_amount'),2),
            'total_current'=>round($groupMapped->sum('current'),2),
            'total_arrears'=>round($groupMapped->sum('arrears'),2),
            'total_surcharge'=>round($groupMapped->sum('surcharge'),2),
            'total_others'=>round($groupMapped->sum('others'),2),
        ],200);
    }
	public function operatingRevenue(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->bp);
        $loc = '';
        $revenue = collect((new GetRateService())->GetRate($billingPeriod,$loc));

        if($revenue->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }

        if($request->cons_type == 'all'){
            $mapped = $revenue->where('Area_ID',$request->area_id);
        }else{
            $mapped = $revenue->where('Area_ID',$request->area_id)->where('Ct_Desc',$request->cons_type);
        }

        $newMap = $mapped->groupBy('tc_name')->map(function($item){
            $evat = round($item->sum('Generation'),2) + round($item->sum('Transmission_System'),2) +
                    round($item->sum('Transmission_Demand'),2) + round($item->sum('System_Loss'),2) +
                    round($item->sum('Distribution_System'),2) + round($item->sum('Distribution_Demand'),2) +
                    round($item->sum('Loan_Condonation_KWH'),2) + round($item->sum('Loan_Condonation_Fix'),2) +
                    round($item->sum('Power_Act_Red_Vat'),2) + round($item->sum('Supply_Fix_Vat'),2) +
                    round($item->sum('Supply_Sys_Vat'),2) + round($item->sum('Meter_Fix_Vat'),2) +
                    round($item->sum('Meter_Sys_Vat'),2) + round($item->sum('lfln_disc_subs_vat'),2);
            return[
                'town'=>$item[0]['tc_name'],
                'count'=>$item->count('account_no'),
                'kwh_sold'=>round($item->sum('KWH_USED'),2),
                'spug'=>round($item->sum('UC_SPUG'),2),
                'redci'=>round($item->sum('UC_RED_Cash_Incentive'),2),
                'ec'=>round($item->sum('UC_Environmental_Charge'),2),
                'uc_scc'=>round($item->sum('UC_NPC_Stranded_Contract_Cost'),2),
                'e_vat'=>round($evat,2),
                'mcc'=>round($item->sum('Members_Contributed_Capital'),2),
                'fit_all'=>round($item->sum('Feed_in_Tariff_Allowance'),2),
                'bill_amount'=>round($item->sum('BILL_AMOUNT'),2),
            ];
        });
        
        $town = collect(DB::table('town_code')
            ->select('tc_name')
            ->where('ac_id',$request->area_id)
            ->get()
        );
        $newTown = $town->map(function($item){
            return [
                'town'=>$item->tc_name,
                'count'=> 0,
                'kwh_sold'=> 0,
                'spug'=> 0,
                'redci'=> 0,
                'ec'=> 0,
                'uc_scc'=> 0,
                'e_vat'=> 0,
                'mcc'=> 0,
                'fit_all'=> 0,
                'bill_amount'=> 0
            ];
        })->keyBy('town');

        $merge = $newTown->merge($newMap);

        return response(['info'=>$merge->sortBy('town')],200);
    }
    public function unbundledCollectionReport(Request $request)
    {
        $type = 'from_to';
        $sales = collect((new GetCollectionService())->salesWitRates($request->date_from,$request->date_to,$type));
        $groupByTeller = $sales->groupBy('teller_id');
        // dd($groupByTeller);
        $newSales = $groupByTeller->map(function($item){
            if($item[0]['teller_id'] == 1000){
                $teller = 'Online Payment';
            }else{
                $teller = DB::table('user')
                        ->select('user_full_name')
                        ->where('user_id',$item[0]['teller_id'])
                        ->first();
                $teller = $teller->user_full_name;
            }
            return[
                'teller'=>$teller,
                'no_bill'=>$item->sum('countPB'),
                'kwh'=>round($item->sum('kwh'),2),
                'amount_paid'=>round($item->sum('total_amount'),2),
                'actual_power_bill'=>round($item->where('type','PB')->sum('total_amount'),2),
                'membership'=>round($item->sum('membership'),2),
                'advance_payment'=>round($item->where('f_id','=',NULL)->where('type','PB')->sum('advance_payment'),2),
                'power_bill_deposit'=> $item->sum('pbill_deposit'),
                //GENERATION CHARGES
                'gensys'=>round($item->sum('gensys'),2),
                'par'=>round($item->sum('par'),2),
                'fbhc'=>round($item->sum('fbhc'),2),
                'forex'=>round($item->sum('forex'),2),
                //TRANSMISSION CHARGES
                'transys'=>round($item->sum('transys'),2),
                'transdem'=>round($item->sum('transdem'),2),
                'sysloss'=>round($item->sum('sysloss'),2),
                //DISTRIBUTION CHARGES              
                'distsys'=>round($item->sum('distsys'),2),
                'distdem'=>round($item->sum('distdem'),2),
                'supfix'=>round($item->sum('supfix'),2),
                'supsys'=>round($item->sum('supsys'),2),
                'meterfix'=>round($item->sum('meterfix'),2),
                'metersys'=>round($item->sum('metersys'),2),
                //OTHER CHARGES
                'lflnDisc'=>round($item->sum('lflnDisc'),2),
                'lflnsub'=>round($item->sum('lflnsub'),2),
                'sencitdiscsub'=>round($item->sum('sencitdiscsub'),2),
                'intClssCrssSubs'=>round($item->sum('intClssCrssSubs'),2),
                'capex'=>round($item->sum('capex'),2),
                'loancond'=>round($item->sum('loancond'),2),
                'loancondfix'=>round($item->sum('loancondfix'),2),
                //UNIVERSAL CHARGES
                'spug'=>round($item->sum('spug'),2),
                'red'=>round($item->sum('red'),2),
                'envichrge'=>round($item->sum('envichrge'),2),
                'equliroyal'=>round($item->sum('equliroyal'),2),
                'npccon'=>round($item->sum('npccon'),2),
                'npcdebt'=>round($item->sum('npcdebt'),2),
                'fitall'=>round($item->sum('fitall'),2),
                // VALUE ADDED TAX
                'genvat'=>round($item->sum('genvat'),2),
                'parvat'=>round($item->sum('parvat'),2),
                'transvat'=>round($item->sum('transvat'),2),
                'transdemvat'=>round($item->sum('transdemvat'),2),
                'syslossvat'=>round($item->sum('syslossvat'),2),
                'distsysvat'=>round($item->sum('distsysvat'),2),
                'distdemvat'=>round($item->sum('distdemvat'),2),
                'supplyfixvat'=>round($item->sum('supplyfixvat'),2),
                'supsysvat'=>round($item->sum('supsysvat'),2),
                'mtrfixvat'=>round($item->sum('mtrfixvat'),2),
                'mtrsysvat'=>round($item->sum('mtrsysvat'),2),
                'lflnDiscSubvat'=>round($item->sum('lflnDiscSubvat'),2),
                'loancondvat'=>round($item->sum('loancondvat'),2),
                'loancondifixvat'=>round($item->sum('loancondifixvat'),2),
                'others_vat'=>'0.00',
                'lgu_2'=>round($item->sum('lgu_2'),2),
                'lgu_5'=>round($item->sum('lgu_5'),2),
                'sundries'=>round($item->sum('sundries'),2),
            ];
        });

        return response(['info'=>$newSales->values()->all()],200);


    }
    public function lguListing(Request $request)
    {
        $query = collect(
            DB::table('cons_master as cm')
                ->select('cm.cm_account_no','cm.cm_full_name','rc.rc_code','rc.rc_desc')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereBetween('ac.ac_id',[$request->area_from,$request->area_to])
                ->where('cm.cm_lgu2',1)
                ->orWhere('cm.cm_lgu5',1)
                ->orderBy('cm.cm_account_no','asc')
                ->get()
        );

        if($query->isEmpty()){
            return response(['info'=>'No  Record Found'],422);
        }

        $map = $query->map(function($item){
            return[
                'route'=> $item->rc_code.'-'.$item->rc_desc,
                'account_no'=> substr($item->cm_account_no,6,10), 
                'account_no1'=> $item->cm_account_no, 
                'consumer_name'=> $item->cm_full_name,
            ];
        });
        $group = $map->groupBy('route')->sortBy('route')->sortBy('account_no');

        return response(['info'=>$group],200);

    }
    public function billDeposit(Request $request)
    {
        $query = collect(
            DB::table('sales as s')
                ->select('cm.cm_account_no','cm.cm_full_name','s.s_or_num','s.s_bill_date','s.e_wallet_added')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->where('s_mode_payment','Deposit_Ewallet')
                ->whereBetween('s.s_bill_date',[$request->date_from,$request->date_to])
                ->get()
        );

        if($query->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        $map = $query->map(function($item){
            return[
                'account_no'=> $item->cm_account_no,
                'consumer_name'=> $item->cm_full_name,
                'or_no'=> $item->s_or_num,
                'or_date'=> $item->s_bill_date,
                'bill_deposit_amt'=> round($item->e_wallet_added,2)
            ];
        })->sortBy('or_date');

        return response(['info'=>$map->values()->all()],200);
    }
    public function collectionVat(Request $request)
    {
        $type = 'from_to';
        $sales = collect((new GetCollectionService())->salesWitRates($request->date_from,$request->date_to,$type));

        if($sales->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        $groupByPerDate =$sales->groupBy('date');
        $newSales = $groupByPerDate->map(function($item){
            return[
                'date'=> $item[0]['date'],
                // VALUE ADDED TAX
                'genvat'=>round($item->sum('genvat'),2),
                'parvat'=>round($item->sum('parvat'),2),
                'transvat'=>round($item->sum('transvat'),2),
                'transdemvat'=>round($item->sum('transdemvat'),2),
                'syslossvat'=>round($item->sum('syslossvat'),2),
                'distsysvat'=>round($item->sum('distsysvat'),2),
                'distdemvat'=>round($item->sum('distdemvat'),2),
                'supplyfixvat'=>round($item->sum('supplyfixvat'),2),
                'supsysvat'=>round($item->sum('supsysvat'),2),
                'mtrfixvat'=>round($item->sum('mtrfixvat'),2),
                'mtrsysvat'=>round($item->sum('mtrsysvat'),2),
                'lflnDiscSubvat'=>round($item->sum('lflnDiscSubvat'),2),
                'loancondvat'=>round($item->sum('loancondvat'),2),
                'loancondifixvat'=>round($item->sum('loancondifixvat'),2),
                'others_vat'=>'0.00',
            ];
        });

         return response(['info'=> $newSales],200);

    }
    public function salesClosing(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        $query = collect(
            DB::table('meter_reg')
            ->where('mr_date_year_month',$billingPeriod)
            ->get()
        );
        
        if($query->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        
        $check = $query->where('sale_closed',1);
        
        if($check->isNotEmpty()){

            return response(['info'=>'Bill Period: '.$request->date_period.' is Already Closed'],422);
        }

        $update = DB::table('meter_reg')
        ->where('mr_date_year_month',$billingPeriod)
        ->update(['sale_closed'=>1]);

        return response(['info'=>'Bill Period: '.$request->date_period.' Succesfully Closed'],200);

    }
    public function CollectionWithNonBillPerDate(Request $request)
    {
        $query = collect(
            DB::table('sales as s')
            ->join('user as u','s.teller_user_id','u.user_id')
            ->join('fees as f','s.f_id','f.f_id')
            ->whereBetween('s.s_bill_date',[$request->from,$request->to])
            ->whereNotNull('s.f_id')
            ->get()
        );

        // Define constant values for the different fees
        define("MEMBERSHIP_FEE_ID", 9);
        define("APPLICATION_FEE_DESCRIPTION_ID", 113);
        define("INSTALLATION_TRANSFORMER_FEE_ID", 137);
        define("INSTALLATION_NEW_CONSUMER_FEE_ID", 119);
        define("RECONNECTION_FEE_ID", 2);
        define("PENALTY_FEE_ID", 138);
        define("OTHERS", 0);

        $transactionDetails = $query->map(function($transaction){
            $sales = DB::table('sales as s')
            ->select(DB::raw('round(sum(COALESCE(s.s_or_amount,0) + COALESCE(s.e_wallet_added,0)),2) as amount'))
            ->where('s.teller_user_id',$transaction->teller_user_id)
            ->where('s.s_bill_date',$transaction->s_bill_date)->first();
            
            // Calculate the amount by adding two fields together and rounding the result to 2 decimal places
            $amount = round($transaction->s_or_amount + $transaction->e_wallet_added, 2);

            // Check if the transaction matches each fee type, returning a boolean value for each one
            $isMembershipFee = $transaction->f_id == MEMBERSHIP_FEE_ID;
            $isApplicationFee = $transaction->f_id == APPLICATION_FEE_DESCRIPTION_ID;
            $isInstallationTransformerFee = $transaction->f_id == INSTALLATION_TRANSFORMER_FEE_ID;
            $isNewConsumerFee = $transaction->f_id == INSTALLATION_NEW_CONSUMER_FEE_ID;
            $isReconnectionFee = $transaction->f_id == RECONNECTION_FEE_ID;
            $isPenaltyFee = $transaction->f_id == PENALTY_FEE_ID;

            // Set default values for fee amounts
            $membershipFeeAmount = 0;
            $applicationFeeAmount = 0;
            $installationTransformerFeeAmount = 0;
            $newConsumerFeeAmount = 0;
            $reconnectionFeeAmount = 0;
            $penaltyFeeAmount = 0;
            $othersFeeAmount = 0;

            // Add fee amounts if the transaction matches each fee type
            if ($isMembershipFee) {
                $membershipFeeAmount = $amount;
            }if ($isApplicationFee) {
                $applicationFeeAmount = $amount;
            }if ($isInstallationTransformerFee) {
                $installationTransformerFeeAmount = $amount;
            }if ($isNewConsumerFee) {
                $newConsumerFeeAmount = $amount;
            }if ($isReconnectionFee) {
                $reconnectionFeeAmount = $amount;
            }if($isPenaltyFee){
                $penaltyFeeAmount = $amount;
            }

            if (!$isMembershipFee && !$isApplicationFee && !$isInstallationTransformerFee && !$isNewConsumerFee && !$isReconnectionFee && !$isPenaltyFee) {
                $othersFeeAmount = $amount;
            }

            // Return an array with specific data about the transaction, including the fee amounts
            return [
                'teller' => $transaction->user_full_name,
                'date' => $transaction->s_bill_date,
                'amount' => $sales->amount,
                'ar_no' => $transaction->s_ack_receipt,
                'F_ID' => $transaction->f_id,
                'MEMBERSHIP_FEE' => $membershipFeeAmount,
                'APPLICATION_FEE' => $applicationFeeAmount,
                'INSTALLATION_FEE_Transformer' => $installationTransformerFeeAmount,
                'INSTALLATION_FEE_New_Consumer' => $newConsumerFeeAmount,
                'RECONNECTION_FEE' => $reconnectionFeeAmount,
                'PENALTY_FEE' => $penaltyFeeAmount,
                'OTHERS_FEE' => $othersFeeAmount,
            ];
        })->sortBy('date')->groupBy(['teller','date']);

        return response(['info'=>$transactionDetails]);
    }
}
