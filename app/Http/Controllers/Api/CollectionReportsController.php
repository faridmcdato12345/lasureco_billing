<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionReportsController extends Controller
{
    public function collectionPerTown(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        if($request->all_areas == 'yes')
        {
            $collPerTown = collect(
                DB::table('cons_master as cm')
                ->join('sales as s','cm.cm_id','=','s.cm_id')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->leftJoin('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->select(DB::raw('ac.ac_name,tc.tc_name,ct.ct_desc,count(cm.cm_id) as count,sum(s.s_bill_amount) as amount'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->whereBetween('s.s_bill_date',[$request->Date_From,$request->Date_To])
                ->groupBy('tc.tc_id','ct.ct_desc')
                ->get()
            );
        }else{

            $collPerTown = collect(
                DB::table('cons_master as cm')
                ->join('sales as s','cm.cm_id','=','s.cm_id')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->select(DB::raw('ac.ac_name,tc.tc_name,ct.ct_desc,count(cm.cm_id) as count,sum(s.s_bill_amount) as amount'))
                ->where('ac.ac_id',$request->area_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->whereBetween('s.s_bill_date',[$request->Date_From,$request->Date_To])
                ->groupBy('tc.tc_id','ct.ct_desc')
                ->get()
            );
        }
        
        $check = $collPerTown->first();
        if(!$check)
        {
            return response(['Message'=>'No Record Found'],422);
        }

        $mapped = $collPerTown->map(function($item)
        {
            return[
                'Area_Name'=>$item->ac_name,
                'Municipality'=> $item->tc_name,
                'Cons_Type'=>$item->ct_desc,
                'Num_Consumer'=> $item->count,
                'Amount'=> $item->amount,
            ];
        });
        $grouped = $mapped->groupBy(['Municipality','Cons_Type']);

        return response([
            'Billed'=>$grouped
        ]);
        
    }
}
