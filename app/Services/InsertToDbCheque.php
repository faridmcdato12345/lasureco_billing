<?php

namespace App\Services;

use App\Models\Cheque;
use Illuminate\Support\Facades\Crypt;

class InsertToDbCheque {

    public function __construct($collection){
        $data = new Cheque();
        $data->cheque_no = (empty(Crypt::decryptString($collection['cheque_no']))) ? null : intval(Crypt::decryptString($collection['cheque_no']));
        $data->s_or = (empty(Crypt::decryptString($collection['s_or']))) ? null : intval(Crypt::decryptString($collection['s_or']));
        $data->cheque_amount = Crypt::decryptString($collection['cheque_amount']);
        $data->cheque_bank_acc = (empty(Crypt::decryptString($collection['cheque_bank_acc']))) ? null : intval(Crypt::decryptString($collection['cheque_bank_acc']));
        $data->cheque_acc_name = (empty(Crypt::decryptString($collection['cheque_acc_name']))) ? null : intval(Crypt::decryptString($collection['cheque_acc_name']));
        $data->cheque_bank = (empty(Crypt::decryptString($collection['cheque_bank']))) ? null : intval(Crypt::decryptString($collection['cheque_bank']));;
        $data->cheque_bank_branch = (empty(Crypt::decryptString($collection['cheque_bank_branch']))) ? null : intval(Crypt::decryptString($collection['cheque_bank_branch']));
        $data->cheque_date = (empty(Crypt::decryptString($collection['cheque_date']))) ? null : Crypt::decryptString($collection['cheque_date']);
        $data->cheque_status = (empty(Crypt::decryptString($collection['cheque_status']))) ? null : Crypt::decryptString($collection['cheque_status']);
        $data->cheque_posted = (empty(Crypt::decryptString($collection['cheque_posted']))) ? null : Crypt::decryptString($collection['cheque_posted']);
        $data->deleted_at = (empty(Crypt::decryptString($collection['deleted_at']))) ? null : Crypt::decryptString($collection['deleted_at']);
        $data->created_at = (empty(Crypt::decryptString($collection['created_at']))) ? null : Crypt::decryptString($collection['created_at']);
        $data->updated_at = (empty(Crypt::decryptString($collection['updated_at']))) ? null : intval(Crypt::decryptString($collection['updated_at']));
        $data->teller_user_id = (empty(Crypt::decryptString($collection['teller_user_id']))) ? null : Crypt::decryptString($collection['teller_user_id']);
        $data->temp_cheque_id = (empty(Crypt::decryptString($collection['temp_cheque_id']))) ? null : Crypt::decryptString($collection['temp_cheque_id']);
        $data->save();
    }
}