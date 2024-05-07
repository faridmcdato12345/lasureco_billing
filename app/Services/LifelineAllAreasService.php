<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class LifelineAllAreasService {

    public function lifelineAllAreas($area,$date){
        $billingPeriod = str_replace("-","",$date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());

        $lifelinePerArea = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('ac.ac_id',$area)
            ->whereBetween('mr.mr_kwh_used',[0,25])
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        
        $check = $lifelinePerArea->first();
        if(!$check)
        {
            $area = collect(DB::table('area_code')
                ->where('ac_id',$area)
                ->get());
            $mapped = $area->map(function($item){
                return[
                    'Area'=>$item->ac_name,
                    'Lifeline_Per_Area'=>[
                        'Range_From'=>[
                            "0.00",
                            "1.00",
                            "2.00",
                            "3.00",
                            "4.00",
                            "5.00",
                            "6.00",
                            "7.00",
                            "8.00",
                            "9.00",
                            "10.00",
                            "11.00",
                            "12.00",
                            "13.00",
                            "14.00",
                            "15.00",
                            "16.00",
                            "17.00",
                            "18.00",
                            "19.00",
                            "20.00",
                            "21.00",
                            "22.00",
                            "23.00",
                            "24.00",
                            "25.00"
                        ],'Range_To'=>[
                            "0.99",
                            "1.99",
                            "2.99",
                            "3.99",
                            "4.99",
                            "5.99",
                            "6.99",
                            "7.99",
                            "8.99",
                            "9.99",
                            "10.99",
                            "11.99",
                            "12.99",
                            "13.99",
                            "14.99",
                            "15.99",
                            "16.99",
                            "17.99",
                            "18.99",
                            "19.99",
                            "20.99",
                            "21.99",
                            "22.99",
                            "23.99",
                            "24.99",
                            "25.99"
                        ],'Count'=>[
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0'
                        ],'Kwh_Used'=>[
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0'
                        ],'Lifeline_Amount'=>[
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0'
                        ],'Bill_Amount'=>[
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0',
                            '0'
                        ]
                    ],
                    'Total_Count'=>0,
                    'Total_Kwh_Used'=>0,
                    'Total_Lifeline_Amount'=>0,
                    'Total_Bill_Amount'=>0,

                ];
            });

            return $mapped;
        }else{
            for($i=0;$i<=25;$i++)
            {
                if($i == 25){
                    $rangeFrom[$i] = $i.'.00';
                    $rangeTo[$i] = $i.'.99';
                }else{
                    $rangeFrom[$i] = $i.'.00';
                    $rangeTo[$i] = $i.'.99';
                }
                
                
                $count[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->count('cm_id');
                $sumKwh[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_kwh_used');
                $sumBillAmount[$i] = round($lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_amount'),2);
                //loop for per consumer discount
                $consDiscount[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->map(function ($item) use($llineCollection,$sumKwh){
                    
                //Computation lifeline(Discount) Per Consumer
                $genCharge = $item->br_gensys_rate * $item->mr_kwh_used;
                $franBenCharge = $item->br_fbhc_rate * $item->mr_kwh_used;
                $transCharge = $item->br_transsys_rate * $item->mr_kwh_used;
                $transDemCharge = $item->br_transdem_rate * $item->mr_kwh_used;
                $syslossCharge = $item->br_sysloss_rate * $item->mr_kwh_used;
                $distSysCharge = $item->br_distsys_rate * $item->mr_kwh_used;
                $distDemCharge = $item->br_distdem_rate * $item->mr_kwh_used;
                $supFixCharge = $item->br_suprtlcust_fixed; //fix 0perCst
                $supSysCharge = $item->br_supsys_rate * $item->mr_kwh_used;
                $meterFixCharge = $item->br_mtrrtlcust_fixed; //fix 5perCst
                $meterSysCharge = $item->br_mtrsys_rate * $item->mr_kwh_used;
                $totalCharge = $genCharge + $franBenCharge + $transCharge + $transDemCharge + $syslossCharge + 
                $distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge;

                for($b=0;$b<count($llineCollection);$b++)
                {
                    $discountMin[$b] = $llineCollection[$b]->ll_min_kwh;
                    $discountMax[$b] = $llineCollection[$b]->ll_max_kwh;
                    if($item->mr_kwh_used >= $discountMin[$b] && $item->mr_kwh_used <= $discountMax[$b])
                    {
                        $discount[$b] = ($totalCharge * $llineCollection[$b]->ll_discount) * -1;
                    }
                }
                return $discount;
                    
                })->flatten();

                $discountSumAll[$i] = round($consDiscount[$i]->sum(),2);
            }

            $llPerArea = collect([
                'Range_From'=>$rangeFrom,
                'Range_To'=>$rangeTo,
                'Count'=>$count,
                'Kwh_Used'=>$sumKwh,
                'Lifeline_Amount'=>$discountSumAll,
                'Bill_Amount'=>$sumBillAmount,
            ]);

            return $final = collect([
                'Area'=>'0'.$check->ac_id.' '.$check->ac_name,
                'Lifeline_Per_Area'=>$llPerArea,
                'Total_Count'=>array_sum($llPerArea['Count']),
                'Total_Kwh_Used'=>array_sum($llPerArea['Kwh_Used']),
                'Total_Lifeline_Amount'=>round(array_sum($llPerArea['Lifeline_Amount']),2),
                'Total_Bill_Amount'=>round(array_sum($llPerArea['Bill_Amount']),2)
            ]);
        }

        
    }
}