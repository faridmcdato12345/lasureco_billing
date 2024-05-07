<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AuditTrailService {

    public function auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$cm_id){
        
        $data = new AuditTrail();
        $data->at_old_value = $at_old_value;
        $data->at_new_value = $at_new_value;
        $data->at_action = $at_action;
        $data->at_table = $at_table;
        $data->at_auditable = $at_auditable;
        $data->user_id = $user_id;
        $data->cm_id = $cm_id;
        $data->save();
    }
}