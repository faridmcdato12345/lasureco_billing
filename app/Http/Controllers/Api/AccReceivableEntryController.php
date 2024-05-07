<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AccountableReceivableEntry;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccReceivableEntryController extends Controller
{
    public function addAccRecEntry(Request $request)
    {
        $checkAccountID = DB::table('cons_master')
            ->select('cm_id')
            ->where('cm_id',$request->cm_id)
            ->first();
        if(!$checkAccountID)
        {
            return response(['Message'=>'Consumer Account Doesnt Exist'],422);
        }
        //For Audit Trail
        $at_old_value = '';
        $at_new_value = '';
        $at_action = 'Add';
        $at_table = 'acc_rec_entry';
        $at_auditable = 'Account Receivable';
        $user_id = $request->user_id;
        $id = null;
        $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);

        $current_date_time = Carbon::now()->toDateTimeString();
        $addEntry = AccountableReceivableEntry::firstOrCreate([
            'cm_id'=>$request->cm_id,
            'are_amount'=>$request->rec_amount,
            'are_date'=>$current_date_time
        ]);

        return response([
            'Message'=>'Succesful Entry of Accountable Amount'],200);
    }
}
