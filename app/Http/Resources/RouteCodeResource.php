<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TownCodeResource;
use Illuminate\Support\Facades\DB;

class RouteCodeResource extends JsonResource
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
            'route_code_id' => $this -> rc_id,
            'town_code' => TownCodeResource::collection(
                DB::table('town_code')
                ->where('tc_id',$this->tc_id)
                ->whereNull('deleted_at')
                ->orderBy('tc_name', 'asc')
                ->get()),
            'route_code' => $this -> rc_code,
            'route_desc' => $this -> rc_desc
        ];
    }
}
