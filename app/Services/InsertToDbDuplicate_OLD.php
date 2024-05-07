<?php

namespace App\Services;

use App\Models\Sales;
use App\Models\EWALLET;
use App\Models\EWALLET_LOG;
use App\Models\Consumer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InsertToDbDuplicate {

    public function __construct($collection){
        //dd(gettype(intval(Crypt::decryptString($collection['cm_id']))));
        $data = new Sales();
        $data->cm_id = intval(Crypt::decryptString($collection['cm_id']));
        $data->mr_id = (empty(Crypt::decryptString($collection['mr_id']))) ? null : intval(Crypt::decryptString($collection['mr_id']));
        $meter_reg = DB::table('meter_reg')->where('mr_id',$data->mr_id)->first();
        if(is_null($meter_reg)){
            $data->mr_id = null;
        }
        $data->f_id = (empty(Crypt::decryptString($collection['f_id']))) ? null : intval(Crypt::decryptString($collection['f_id']));
        $data->ct_id = Crypt::decryptString($collection['ct_id']);
        $data->cons_accountno = (empty(Crypt::decryptString($collection['cons_accountno']))) ? null : intval(Crypt::decryptString($collection['cons_accountno']));
        $data->s_or_num = (empty(Crypt::decryptString($collection['s_or_num']))) ? null : intval(Crypt::decryptString($collection['s_or_num']));
        $data->s_or_amount = null;
        $data->v_id = (empty(Crypt::decryptString($collection['v_id']))) ? null : intval(Crypt::decryptString($collection['v_id']));
        $data->s_bill_no = (empty(Crypt::decryptString($collection['s_bill_no']))) ? null : Crypt::decryptString($collection['s_bill_no']);
        $data->s_bill_amount = (empty(Crypt::decryptString($collection['s_bill_amount']))) ? null : Crypt::decryptString($collection['s_bill_amount']);
        $data->s_bill_date = (empty(Crypt::decryptString($collection['s_bill_date']))) ? null : Crypt::decryptString($collection['s_bill_date']);
        $data->s_status = (empty(Crypt::decryptString($collection['s_status']))) ? null : Crypt::decryptString($collection['s_status']);
        $data->s_mode_payment = (empty(Crypt::decryptString($collection['s_mode_payment']))) ? null : Crypt::decryptString($collection['s_mode_payment']);
        $data->cheque_id = (empty(Crypt::decryptString($collection['cheque_id']))) ? null : intval(Crypt::decryptString($collection['cheque_id']));
        $data->s_ref_no = (empty(Crypt::decryptString($collection['s_ref_no']))) ? null : Crypt::decryptString($collection['s_ref_no']);
        $data->ref_date = (empty(Crypt::decryptString($collection['ref_date']))) ? null : Crypt::decryptString($collection['ref_date']);
        $data->teller_user_id = (empty(Crypt::decryptString($collection['teller_user_id']))) ? null : intval(Crypt::decryptString($collection['teller_user_id']));
        $data->s_ack_receipt= (empty(Crypt::decryptString($collection['s_ack_receipt']))) ? null : Crypt::decryptString($collection['s_ack_receipt']);
        $data->ackn_date= (empty(Crypt::decryptString($collection['ackn_date']))) ? null : Crypt::decryptString($collection['ackn_date']);
        $data->ackn_user_id= (empty(Crypt::decryptString($collection['ackn_user_id']))) ? null : intval(Crypt::decryptString($collection['ackn_user_id']));
        $data->mr_arrear= (empty(Crypt::decryptString($collection['mr_arrear']))) ? null : Crypt::decryptString($collection['mr_arrear']);
        $data->e_wallet_applied= (empty(Crypt::decryptString($collection['e_wallet_applied']))) ? floatval('0') : floatval(Crypt::decryptString($collection['e_wallet_applied']));
        $data->e_wallet_added= floatval(Crypt::decryptString($collection['e_wallet_added'])) + floatval(Crypt::decryptString($collection['s_or_amount']));
        $data->ap_status= (empty(Crypt::decryptString($collection['ap_status']))) ? null : Crypt::decryptString($collection['ap_status']);
		$data->s_cutoff= (empty(Crypt::decryptString($collection['s_cutoff']))) ? null : Crypt::decryptString($collection['s_cutoff']);
        $data->server_added= 1;
        $data->save();
        if($data){
        	 $consId = Consumer::select('cm_id','temp_cons_id')->where('temp_cons_id',$data->cm_id)->first();
            if(!is_null($consId)){
                Sales::where('cm_id',$data->cm_id)->update(['cm_id',$consId->cm_id]);
            }
            $meter_reg = DB::table('meter_reg')->where('mr_id',intval(Crypt::decryptString($collection['mr_id'])))->first();
            if(is_null($meter_reg)){
                $orNumber = intval(Crypt::decryptString($collection['s_or_num']));
                $ewalletApplied = floatval(Crypt::decryptString($collection['e_wallet_applied']));
                $ewalletAdded = floatval(Crypt::decryptString($collection['e_wallet_added'])) + floatval(Crypt::decryptString($collection['s_or_amount']));
                if(floatval(Crypt::decryptString($collection['e_wallet_applied'])) != floatval('0')){
                    $query = EWALLET::where('cm_id',intval(Crypt::decryptString($collection['cm_id'])))->first();
                    $i = $query->ew_total_amount - $ewalletApplied;
                    $w = $i + $ewalletAdded;
                    $query->ew_total_amount = $w;
                    $query->save();
                    if($query){
                        $this->eWalletLogApplied($query->ew_id,$ewalletApplied,$orNumber);
                        $this->ewalletLogAdded($query->ew_id,$ewalletAdded,$orNumber);
                    }
                }else{
                    $totalEwallet = floatval(Crypt::decryptString($collection['e_wallet_added'])) + floatval(Crypt::decryptString($collection['s_or_amount']));
                    $query = EWALLET::where('cm_id',intval(Crypt::decryptString($collection['cm_id'])))->first();
                    $query->ew_total_amount += $totalEwallet;
                    $query->save();
                    if($query){
                        $this->ewalletLogAdded($query->ew_id,$ewalletAdded,$orNumber);
                    }
                }
            }else{
                $orNumber = intval(Crypt::decryptString($collection['s_or_num']));
                $ewalletApplied = floatval(Crypt::decryptString($collection['e_wallet_applied']));
                $ewalletAdded = floatval(Crypt::decryptString($collection['e_wallet_added'])) + floatval(Crypt::decryptString($collection['s_or_amount']));
                if(floatval(Crypt::decryptString($collection['e_wallet_applied'])) != floatval('0')){
                    $query = EWALLET::where('cm_id',intval(Crypt::decryptString($collection['cm_id'])))->first();
                    $i = $query->ew_total_amount - $ewalletApplied;
                    $w = $i + $ewalletAdded;
                    $query->ew_total_amount = $w;
                    $query->save();
                    if($query){
                        $this->eWalletLogApplied($query->ew_id,$ewalletApplied,$orNumber);
                        $this->ewalletLogAdded($query->ew_id,$ewalletAdded,$orNumber);
                    }
                }else{
                    $totalEwallet = floatval(Crypt::decryptString($collection['e_wallet_added'])) + floatval(Crypt::decryptString($collection['s_or_amount']));
                    $query = EWALLET::where('cm_id',intval(Crypt::decryptString($collection['cm_id'])))->first();
                    $query->ew_total_amount += $totalEwallet;
                    $query->save();
                    if($query){
                        $this->ewalletLogAdded($query->ew_id,$ewalletAdded,$orNumber);
                    }
                }
            }
            $this->updateMrStatus(intval(Crypt::decryptString($collection['mr_id'])));
            return true;
        }else{
            return false;
        }
    }
    private function eWalletLogApplied($id,$ewalletApplied,$orNumber){
        $ewalletLog = new EWALLET_LOG();
        $ewalletLog->ew_id = $id;
        $ewalletLog->ewl_amount = $ewalletApplied;
        $ewalletLog->ewl_remark = null;
        $ewalletLog->ewl_status = 'A';
        $ewalletLog->ewl_or = $orNumber;
        $ewalletLog->ewl_or_date = null;
        $ewalletLog->ewl_ap_billing = null;
        $ewalletLog->ewl_date = null;
        $ewalletLog->ewl_ap_billing_user_id = null;
        $ewalletLog->deleted_at = null;
        $ewalletLog->save();
    }
    private function ewalletLogAdded($id,$ewalletAdded,$orNumber){
        $ewalletLog = new EWALLET_LOG();
        $ewalletLog->ew_id = $id;
        $ewalletLog->ewl_amount = $ewalletAdded;
        $ewalletLog->ewl_remark = null;
        $ewalletLog->ewl_status = 'U';
        $ewalletLog->ewl_or = $orNumber;
        $ewalletLog->ewl_or_date = null;
        $ewalletLog->ewl_ap_billing = null;
        $ewalletLog->ewl_date = null;
        $ewalletLog->ewl_ap_billing_user_id = null;
        $ewalletLog->deleted_at = null;
        $ewalletLog->save();
    }
    private function updateMrStatus($mr_id){
        $query = DB::table('meter_reg')->where('mr_id',$mr_id)->update(['mr_status' => 1]);
    }
}