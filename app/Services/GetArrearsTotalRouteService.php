<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class GetArrearsTotalRouteService {

    public function GetRGetArrearsTotalRoute($bp,$loc){
        $billingPeriod = str_replace("-","",$bp);
        $summaryBills = collect(DB::table('cons_master AS cm')
        ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
        ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
        ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
        ->join('billing_rates AS br','mr.br_id','=','br.id')
        // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
        ->where('rc.rc_id',$loc)
        ->where('mr.mr_printed',1)
        ->where('mr.mr_status',0)
        ->where('mr.mr_date_year_month',$billingPeriod)
        ->where('br.br_billing_ym',$billingPeriod)
        ->groupBy('cm.cm_id','mr.mr_id')
        ->orderBy('mr.mr_bill_no','asc')
        ->get());

        $summReport = $summaryBills->map(function ($group){
            
            $arrear = DB::table('meter_reg')
            // ->select('mr_amount')
            ->where('cm_id',$group->cm_id)
            ->where('mr_status',0)
            ->where('mr_date_year_month','<',$group->mr_date_year_month)
            // ->orderBy('mr_date_year_month', 'desc')
            ->sum('mr_amount');

            return[
                'W_ARREAR'=> round($arrear,2),
            ];
        });

        $total = collect([
            'Total_ARREAR'=>round($summReport->sum('W_ARREAR'),2)
        ]);
        // dd($total);
        return $total['Total_ARREAR'];
    }

}