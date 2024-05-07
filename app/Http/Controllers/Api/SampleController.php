<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccountingCode;
use App\Models\Fund;
use App\Models\Signatory;
use App\Models\Voucher;

class SampleController extends Controller
{
    public function sample()
    {
        // $query = Voucher::select('particular','id')
        //     ->with('accountingCodeVoucher:id,name,code')->get();

        $query = AccountingCode::where('is_last',1)->whereNotNull('parent_code')->get();
        $query->groupBy('code');
        return response(['Message'=> $query],200);
    }
}
