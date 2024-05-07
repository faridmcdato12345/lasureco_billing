<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FeederCodeResource extends JsonResource
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
            'feeder_code_id' => $this->fc_id,
            'sub_code' => SubstationCodeResource::collection(
                DB::table('substation_code')
                ->where('sc_id',$this -> sc_id)
                ->whereNull('deleted_at')
                ->orderBy('sc_desc', 'asc')
                ->get()),
            'feeder_code' => $this->fc_code,
            'feeder_description' => $this->fc_desc
        ];
    }
}
