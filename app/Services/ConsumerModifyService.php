<?php

namespace App\Services;

use App\Models\ConsumerOtherMod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ConsumerModifyService {
    public $com_type;
    public $cm_id;
    public $com_old_info;
    public $com_new_info;
    public $user_id;

    function __construct($com_type,$cm_id,$com_old_info,$com_new_info,$user_id) {
        $this->com_type = $com_type;
        $this->cm_id = $cm_id;
        $this->com_old_info = $com_old_info;
        $this->com_new_info = $com_new_info;
        $this->user_id = $user_id;
    }

    public function modify()
    {
        $update = new ConsumerOtherMod;
        $update->com_type = $this->com_type;
        $update->cm_id = $this->cm_id;
        $update->com_old_info = $this->com_old_info;
        $update->com_new_info = $this->com_new_info;
        $update->com_date = Carbon::now()->toDateTimeString();
        $update->user_id = $this->user_id;
        $update->save();
    }

    


}