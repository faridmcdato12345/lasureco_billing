<?php

namespace App\Services;

use App\Models\Consumer;
use App\Models\EWALLET;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
class InsertToDbConsumer {

    public function __construct($collection){
    	$acctNumb = (empty(Crypt::decryptString($collection['cm_account_no']))) ? null : intval(Crypt::decryptString($collection['cm_account_no']));
    	$consExist = DB::table('cons_master')->where('cm_account_no',$acctNumb)->first();
    	if(!is_null($consExist)){
            $latestAccntNo  = Consumer::where('rc_id', $consExist->rc_id)->latest('cm_id')->first();
            $accountNo = (int)$latestAccntNo->cm_account_no + 1;
            $data = new Consumer();
	        $data->rc_id = (empty(Crypt::decryptString($collection['rc_id']))) ? null : intval(Crypt::decryptString($collection['rc_id']));
	        $data->ct_id = (empty(Crypt::decryptString($collection['ct_id']))) ? null : intval(Crypt::decryptString($collection['ct_id']));
	        $data->mm_id = (empty(Crypt::decryptString($collection['mm_id']))) ? null : intval(Crypt::decryptString($collection['mm_id']));
	        $data->cm_account_no = $accountNo;
	        $data->mm_master = (empty(Crypt::decryptString($collection['mm_master']))) ? null : intval(Crypt::decryptString($collection['mm_master']));
	        $data->cm_seq_no = (empty(Crypt::decryptString($collection['cm_seq_no']))) ? null : intval(Crypt::decryptString($collection['cm_seq_no']));;
	        $data->deleted_at = (empty(Crypt::decryptString($collection['deleted_at']))) ? null : intval(Crypt::decryptString($collection['deleted_at']));
	        $data->cm_first_name = (empty(Crypt::decryptString($collection['cm_first_name']))) ? null : Crypt::decryptString($collection['cm_first_name']);
	        $data->cm_middle_name = (empty(Crypt::decryptString($collection['cm_middle_name']))) ? null : Crypt::decryptString($collection['cm_middle_name']);
	        $data->cm_last_name = (empty(Crypt::decryptString($collection['cm_last_name']))) ? null : Crypt::decryptString($collection['cm_last_name']);
	        $data->cm_full_name = (empty(Crypt::decryptString($collection['cm_full_name']))) ? null : Crypt::decryptString($collection['cm_full_name']);
	        $data->cm_address = (empty(Crypt::decryptString($collection['cm_address']))) ? null : Crypt::decryptString($collection['cm_address']);
	        $data->cm_voting_address = (empty(Crypt::decryptString($collection['cm_voting_address']))) ? null : intval(Crypt::decryptString($collection['cm_voting_address']));
	        $data->cm_birthdate = (empty(Crypt::decryptString($collection['cm_birthdate']))) ? null : Crypt::decryptString($collection['cm_birthdate']);
	        $data->cm_con_status = (empty(Crypt::decryptString($collection['cm_con_status']))) ? null : Crypt::decryptString($collection['cm_con_status']);
	        $data->cm_deceased = (empty(Crypt::decryptString($collection['cm_deceased']))) ? null : Crypt::decryptString($collection['cm_deceased']);
	        $data->cm_kwh_mult = (empty(Crypt::decryptString($collection['cm_kwh_mult']))) ? null : Crypt::decryptString($collection['cm_kwh_mult']);
	        $data->cm_lgu2 = (empty(Crypt::decryptString($collection['cm_lgu2']))) ? null : Crypt::decryptString($collection['cm_lgu2']);
	        $data->cm_lgu5 = (empty(Crypt::decryptString($collection['cm_lgu5']))) ? null : Crypt::decryptString($collection['cm_lgu5']);
	        $data->cm_discount_name = (empty(Crypt::decryptString($collection['cm_discount_name']))) ? null : Crypt::decryptString($collection['cm_discount_name']);
	        $data->cm_discount_percent = (empty(Crypt::decryptString($collection['cm_discount_percent']))) ? null : Crypt::decryptString($collection['cm_discount_percent']);
	        $data->cm_marital_status = (empty(Crypt::decryptString($collection['cm_marital_status']))) ? null : Crypt::decryptString($collection['cm_marital_status']);
	        $data->cm_image_url = (empty(Crypt::decryptString($collection['cm_image_url']))) ? null : Crypt::decryptString($collection['cm_image_url']);
	        $data->cm_date_createdat = (empty(Crypt::decryptString($collection['cm_date_createdat']))) ? null : Crypt::decryptString($collection['cm_date_createdat']);
	        $data->cm_remarks = (empty(Crypt::decryptString($collection['cm_remarks']))) ? null : Crypt::decryptString($collection['cm_remarks']);
	        $data->employee = (empty(Crypt::decryptString($collection['employee']))) ? 0 : Crypt::decryptString($collection['employee']);
	        $data->temp_connect = (empty(Crypt::decryptString($collection['temp_connect']))) ? 0 : Crypt::decryptString($collection['temp_connect']);
	        $data->senior_citizen = (empty(Crypt::decryptString($collection['senior_citizen']))) ? 0 : Crypt::decryptString($collection['senior_citizen']);
	        $data->institutional = (empty(Crypt::decryptString($collection['institutional']))) ? 0 : Crypt::decryptString($collection['institutional']);
	        $data->metered = (empty(Crypt::decryptString($collection['metered']))) ? 0 : Crypt::decryptString($collection['metered']);
	        $data->govt = (empty(Crypt::decryptString($collection['govt']))) ? 0 : Crypt::decryptString($collection['govt']);
	        $data->main_accnt = (empty(Crypt::decryptString($collection['main_accnt']))) ? 0 : Crypt::decryptString($collection['main_accnt']);
	        $data->large_load = (empty(Crypt::decryptString($collection['large_load']))) ? 0 : Crypt::decryptString($collection['large_load']);
	        $data->nearest_cons = (empty(Crypt::decryptString($collection['nearest_cons']))) ? 0 : Crypt::decryptString($collection['nearest_cons']);
	        $data->occupant = (empty(Crypt::decryptString($collection['cm_con_status']))) ? null : Crypt::decryptString($collection['cm_con_status']);
	        $data->board_res = (empty(Crypt::decryptString($collection['board_res']))) ? null : Crypt::decryptString($collection['board_res']);
	        $data->tin = (empty(Crypt::decryptString($collection['tin']))) ? null : Crypt::decryptString($collection['tin']);
	        $data->temp_area_code = (empty(Crypt::decryptString($collection['temp_area_code']))) ? null : Crypt::decryptString($collection['temp_area_code']);
	        $data->temp_town_code = (empty(Crypt::decryptString($collection['temp_town_code']))) ? null : Crypt::decryptString($collection['temp_town_code']);
	        $data->temp_route_code = (empty(Crypt::decryptString($collection['temp_route_code']))) ? null : Crypt::decryptString($collection['temp_route_code']);
	        $data->temp_cons_type = (empty(Crypt::decryptString($collection['temp_cons_type']))) ? null : Crypt::decryptString($collection['temp_cons_type']);
	        $data->cm_address = (empty(Crypt::decryptString($collection['cm_address']))) ? null : Crypt::decryptString($collection['cm_address']);
	        $data->cm_voting_address = (empty(Crypt::decryptString($collection['cm_voting_address']))) ? null : Crypt::decryptString($collection['cm_voting_address']);
	        $data->block_no = (empty(Crypt::decryptString($collection['block_no']))) ? null : Crypt::decryptString($collection['block_no']);
	        $data->purok_no = (empty(Crypt::decryptString($collection['purok_no']))) ? null : Crypt::decryptString($collection['purok_no']);
	        $data->lot_no = (empty(Crypt::decryptString($collection['lot_no']))) ? null : Crypt::decryptString($collection['lot_no']);
	        $data->extension_name = (empty(Crypt::decryptString($collection['extension_name']))) ? null : Crypt::decryptString($collection['extension_name']);
	        $data->special_account_type = (empty(Crypt::decryptString($collection['special_account_type']))) ? null : Crypt::decryptString($collection['special_account_type']);
	        $data->pending = (empty(Crypt::decryptString($collection['pending']))) ? null : Crypt::decryptString($collection['pending']);
	        $data->sitio = (empty(Crypt::decryptString($collection['sitio']))) ? null : Crypt::decryptString($collection['sitio']);
	        $data->cm_birthdate = (empty(Crypt::decryptString($collection['cm_birthdate']))) ? null : Crypt::decryptString($collection['cm_birthdate']);
	        $data->teller_user_id = (empty(Crypt::decryptString($collection['teller_user_id']))) ? null : Crypt::decryptString($collection['teller_user_id']);
	        $data->temp_cons_id = (empty(Crypt::decryptString($collection['temp_cons_id']))) ? null : Crypt::decryptString($collection['temp_cons_id']);
	        $data->save();
			$this->addToEwallet($data->cm_id);
        }else{
        	$data = new Consumer();
	        $data->rc_id = (empty(Crypt::decryptString($collection['rc_id']))) ? null : intval(Crypt::decryptString($collection['rc_id']));
	        $data->ct_id = (empty(Crypt::decryptString($collection['ct_id']))) ? null : intval(Crypt::decryptString($collection['ct_id']));
	        $data->mm_id = (empty(Crypt::decryptString($collection['mm_id']))) ? null : intval(Crypt::decryptString($collection['mm_id']));
	        $data->cm_account_no = (empty(Crypt::decryptString($collection['cm_account_no']))) ? null : intval(Crypt::decryptString($collection['cm_account_no']));
	        $data->mm_master = (empty(Crypt::decryptString($collection['mm_master']))) ? null : intval(Crypt::decryptString($collection['mm_master']));
	        $data->cm_seq_no = (empty(Crypt::decryptString($collection['cm_seq_no']))) ? null : intval(Crypt::decryptString($collection['cm_seq_no']));;
	        $data->deleted_at = (empty(Crypt::decryptString($collection['deleted_at']))) ? null : intval(Crypt::decryptString($collection['deleted_at']));
	        $data->cm_first_name = (empty(Crypt::decryptString($collection['cm_first_name']))) ? null : Crypt::decryptString($collection['cm_first_name']);
	        $data->cm_middle_name = (empty(Crypt::decryptString($collection['cm_middle_name']))) ? null : Crypt::decryptString($collection['cm_middle_name']);
	        $data->cm_last_name = (empty(Crypt::decryptString($collection['cm_last_name']))) ? null : Crypt::decryptString($collection['cm_last_name']);
	        $data->cm_full_name = (empty(Crypt::decryptString($collection['cm_full_name']))) ? null : Crypt::decryptString($collection['cm_full_name']);
	        $data->cm_address = (empty(Crypt::decryptString($collection['cm_address']))) ? null : Crypt::decryptString($collection['cm_address']);
	        $data->cm_voting_address = (empty(Crypt::decryptString($collection['cm_voting_address']))) ? null : intval(Crypt::decryptString($collection['cm_voting_address']));
	        $data->cm_birthdate = (empty(Crypt::decryptString($collection['cm_birthdate']))) ? null : Crypt::decryptString($collection['cm_birthdate']);
	        $data->cm_con_status = (empty(Crypt::decryptString($collection['cm_con_status']))) ? null : Crypt::decryptString($collection['cm_con_status']);
	        $data->cm_deceased = (empty(Crypt::decryptString($collection['cm_deceased']))) ? null : Crypt::decryptString($collection['cm_deceased']);
	        $data->cm_kwh_mult = (empty(Crypt::decryptString($collection['cm_kwh_mult']))) ? null : Crypt::decryptString($collection['cm_kwh_mult']);
	        $data->cm_lgu2 = (empty(Crypt::decryptString($collection['cm_lgu2']))) ? null : Crypt::decryptString($collection['cm_lgu2']);
	        $data->cm_lgu5 = (empty(Crypt::decryptString($collection['cm_lgu5']))) ? null : Crypt::decryptString($collection['cm_lgu5']);
	        $data->cm_discount_name = (empty(Crypt::decryptString($collection['cm_discount_name']))) ? null : Crypt::decryptString($collection['cm_discount_name']);
	        $data->cm_discount_percent = (empty(Crypt::decryptString($collection['cm_discount_percent']))) ? null : Crypt::decryptString($collection['cm_discount_percent']);
	        $data->cm_marital_status = (empty(Crypt::decryptString($collection['cm_marital_status']))) ? null : Crypt::decryptString($collection['cm_marital_status']);
	        $data->cm_image_url = (empty(Crypt::decryptString($collection['cm_image_url']))) ? null : Crypt::decryptString($collection['cm_image_url']);
	        $data->cm_date_createdat = (empty(Crypt::decryptString($collection['cm_date_createdat']))) ? null : Crypt::decryptString($collection['cm_date_createdat']);
	        $data->cm_remarks = (empty(Crypt::decryptString($collection['cm_remarks']))) ? null : Crypt::decryptString($collection['cm_remarks']);
	        $data->employee = (empty(Crypt::decryptString($collection['employee']))) ? 0 : Crypt::decryptString($collection['employee']);
	        $data->temp_connect = (empty(Crypt::decryptString($collection['temp_connect']))) ? 0 : Crypt::decryptString($collection['temp_connect']);
	        $data->senior_citizen = (empty(Crypt::decryptString($collection['senior_citizen']))) ? 0 : Crypt::decryptString($collection['senior_citizen']);
	        $data->institutional = (empty(Crypt::decryptString($collection['institutional']))) ? 0 : Crypt::decryptString($collection['institutional']);
	        $data->metered = (empty(Crypt::decryptString($collection['metered']))) ? 0 : Crypt::decryptString($collection['metered']);
	        $data->govt = (empty(Crypt::decryptString($collection['govt']))) ? 0 : Crypt::decryptString($collection['govt']);
	        $data->main_accnt = (empty(Crypt::decryptString($collection['main_accnt']))) ? 0 : Crypt::decryptString($collection['main_accnt']);
	        $data->large_load = (empty(Crypt::decryptString($collection['large_load']))) ? 0 : Crypt::decryptString($collection['large_load']);
	        $data->nearest_cons = (empty(Crypt::decryptString($collection['nearest_cons']))) ? 0 : Crypt::decryptString($collection['nearest_cons']);
	        $data->occupant = (empty(Crypt::decryptString($collection['cm_con_status']))) ? null : Crypt::decryptString($collection['cm_con_status']);
	        $data->board_res = (empty(Crypt::decryptString($collection['board_res']))) ? null : Crypt::decryptString($collection['board_res']);
	        $data->tin = (empty(Crypt::decryptString($collection['tin']))) ? null : Crypt::decryptString($collection['tin']);
	        $data->temp_area_code = (empty(Crypt::decryptString($collection['temp_area_code']))) ? null : Crypt::decryptString($collection['temp_area_code']);
	        $data->temp_town_code = (empty(Crypt::decryptString($collection['temp_town_code']))) ? null : Crypt::decryptString($collection['temp_town_code']);
	        $data->temp_route_code = (empty(Crypt::decryptString($collection['temp_route_code']))) ? null : Crypt::decryptString($collection['temp_route_code']);
	        $data->temp_cons_type = (empty(Crypt::decryptString($collection['temp_cons_type']))) ? null : Crypt::decryptString($collection['temp_cons_type']);
	        $data->cm_address = (empty(Crypt::decryptString($collection['cm_address']))) ? null : Crypt::decryptString($collection['cm_address']);
	        $data->cm_voting_address = (empty(Crypt::decryptString($collection['cm_voting_address']))) ? null : Crypt::decryptString($collection['cm_voting_address']);
	        $data->block_no = (empty(Crypt::decryptString($collection['block_no']))) ? null : Crypt::decryptString($collection['block_no']);
	        $data->purok_no = (empty(Crypt::decryptString($collection['purok_no']))) ? null : Crypt::decryptString($collection['purok_no']);
	        $data->lot_no = (empty(Crypt::decryptString($collection['lot_no']))) ? null : Crypt::decryptString($collection['lot_no']);
	        $data->extension_name = (empty(Crypt::decryptString($collection['extension_name']))) ? null : Crypt::decryptString($collection['extension_name']);
	        $data->special_account_type = (empty(Crypt::decryptString($collection['special_account_type']))) ? null : Crypt::decryptString($collection['special_account_type']);
	        $data->pending = (empty(Crypt::decryptString($collection['pending']))) ? null : Crypt::decryptString($collection['pending']);
	        $data->sitio = (empty(Crypt::decryptString($collection['sitio']))) ? null : Crypt::decryptString($collection['sitio']);
	        $data->cm_birthdate = (empty(Crypt::decryptString($collection['cm_birthdate']))) ? null : Crypt::decryptString($collection['cm_birthdate']);
	        $data->teller_user_id = (empty(Crypt::decryptString($collection['teller_user_id']))) ? null : Crypt::decryptString($collection['teller_user_id']);
	        $data->temp_cons_id = (empty(Crypt::decryptString($collection['temp_cons_id']))) ? null : Crypt::decryptString($collection['temp_cons_id']);
	        $data->save();
			$this->addToEwallet($data->cm_id);
        }
    }

	private function addToEwallet($cm_id){
		EWALLET::firstOrCreate([
			'cm_id' => $cm_id,
			'temp_accountno' => null,
			'ew_total_amount' => 0
		]);
	}
}