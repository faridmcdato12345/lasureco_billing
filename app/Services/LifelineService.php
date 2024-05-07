<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class LifelineService {

    public function lifeline($date){
        $billingPeriod = str_replace("-","",$date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());
        
        $llMax = $llineCollection->max()->ll_max_kwh;
        $llMin = $llineCollection->min()->ll_min_kwh;

        $consCollection = collect(DB::table('cons_master as cm')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
        ->join('billing_rates as br','mr.br_id','=','br.id')
        ->whereBetween('mr.mr_kwh_used',[$llMin,$llMax])
        ->where('mr.mr_date_year_month',$billingPeriod)
        ->orderBy('cm.cm_account_no','asc')
        ->get());
        

        $check = $consCollection->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        
        $mappedConsCollection = $consCollection->map(function($item)use($llineCollection){
            for($i=0;$i<count($llineCollection);$i++)
            {
                //Computation lifeline(Discount)
                $genCharge[$i] = $item->br_gensys_rate * $item->mr_kwh_used;
                $franBenCharge[$i] = $item->br_fbhc_rate * $item->mr_kwh_used;
                $transCharge[$i] = $item->br_transsys_rate * $item->mr_kwh_used;
                $transDemCharge[$i] = $item->br_transdem_rate * $item->mr_kwh_used;
                $syslossCharge[$i] = $item->br_sysloss_rate * $item->mr_kwh_used;
                $distSysCharge[$i] = $item->br_distsys_rate * $item->mr_kwh_used;
                $distDemCharge[$i] = $item->br_distdem_rate * $item->mr_kwh_used;
                $supFixCharge[$i] = $item->br_suprtlcust_fixed; //fix 0perCst
                $supSysCharge[$i] = $item->br_supsys_rate * $item->mr_kwh_used;
                $meterFixCharge[$i] = $item->br_mtrrtlcust_fixed; //fix 5perCst
                $meterSysCharge[$i] = $item->br_mtrsys_rate * $item->mr_kwh_used;
                $totalCharge[$i] = $genCharge[$i] + $franBenCharge[$i] + $transCharge[$i] + $transDemCharge[$i] + $syslossCharge[$i] + 
                    $distSysCharge[$i] + $distDemCharge[$i] + $supFixCharge[$i] + $supSysCharge[$i] + $meterFixCharge[$i] + $meterSysCharge[$i];
                //min and max
                $discountMin[$i] = $llineCollection[$i]->ll_min_kwh;
                $discountMax[$i] = $llineCollection[$i]->ll_max_kwh;
                if($item->mr_kwh_used >= $discountMin[$i] && $item->mr_kwh_used <= $discountMax[$i])
                {
                    $discount = $totalCharge[$i] * $llineCollection[$i]->ll_discount;
                }
            }
            
            return[
                'LDISCOUNT'=>round($discount,2) * -1,
            ];
        });
        $total = [
            'Total_LDiscount'=>round($mappedConsCollection->sum('LDISCOUNT'),2),
        ];
        
        return $total;
    }
}