<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubstationCodeResource extends JsonResource
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
            'sub_id' => $this -> sc_id,
            'sub_desc' => $this -> sc_desc,
            'sub_address' => $this -> sc_address,
            'sub_rating' => $this -> sc_rating,
            'sub_voltprim' => $this -> sc_voltprim,
            'sub_voltsecond' => $this -> sc_voltsecond,
            'sub_xr_ratio' => $this -> sc_xr_ratio,
            'sub_exciting_curr' => $this -> sc_exciting_curr,
            'sub_impedence' => $this -> sc_impedence,
            'sub_coreloss' => $this -> sc_coreloss,
            'sub_copperloss' => $this -> sc_copperloss,
            'sub_noloadloss' => $this -> sc_noloadloss,
            'sub_con_type' => $this -> sc_con_type
        ];
    }
}
