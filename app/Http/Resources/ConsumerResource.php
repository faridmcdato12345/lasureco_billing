<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ConsumerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'cons_master_id' => $this -> cm_id,
            'route_code' => RouteCodeResource::collection(
                DB::table('route_code')
                ->where('rc_id',$this -> rc_id)
                ->whereNull('deleted_at')
                ->get()
            ),
            'consumer_type' => ConsumerTypeResource::collection(
                DB::table('cons_type')
                ->where('ct_id',$this->ct_id)
                ->whereNull('deleted_at')
                ->get()
            ),
            //'meter_master' => 
            'cons_master_first_name' => $this -> cm_first_name,
            'cons_master_middle_name' => $this -> cm_middle_name,
            'cons_master_last_name' => $this -> cm_last_name,
            'cons_master_full_name' => $this -> cm_full_name,
            'cons_master_address' => $this -> cm_address,
            'cons_master_birthdate' => $this -> cm_birthdate,
            'cons_master_status' => $this -> cm_con_status,
            'cons_master_deceased' => $this -> cm_deceased,
            'cons_master_marital_status' => $this -> cm_marital_status,
            'cons_master_image_url' => $this -> cm_image_url,
            'cons_master_createdAt_date' => $this -> cm_date_createdat,
            'cons_master_remarks' => $this -> cm_remarks
        ];
    }
}
