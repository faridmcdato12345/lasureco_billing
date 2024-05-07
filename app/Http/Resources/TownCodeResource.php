<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AreaCodeResource;
use Illuminate\Support\Facades\DB;

class TownCodeResource extends JsonResource
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
            'town_code_id' => $this -> tc_id,
            'town_code' => $this -> tc_code,
            'area_code' => AreaCodeResource::collection(
                DB::table('area_code')
                ->where('ac_id',$this -> ac_id)
                ->whereNull('deleted_at')
                ->orderBy('ac_name', 'asc')
                ->get()),
            'town_code_name' => $this -> tc_name
        ];
    }
}
